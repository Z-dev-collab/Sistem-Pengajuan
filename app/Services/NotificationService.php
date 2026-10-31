<?php

namespace App\Services;

use App\Enums\SubmissionStatus;
use App\Models\Notification;
use App\Models\Submission;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    public function submissionCreated(Submission $submission): Notification
    {
        $message = sprintf(
            "Halo %s, pengajuan %s Anda telah kami terima dengan nomor %s. Simpan nomornya dan tunjukkan saat mengambil dokumen. — Kelurahan Batu Selicin",
            $submission->applicant_name,
            $submission->service->name,
            $submission->tracking_code
        );

        return $this->queue($submission, $submission->notification_channel, $submission->phone, $message);
    }

    public function submissionStatusChanged(Submission $submission): Notification
    {
        $message = match ($submission->status) {
            SubmissionStatus::Verified => sprintf(
                "Halo %s, berkas pengajuan %s (%s) sudah diverifikasi dan sedang diproses. Kami akan mengabari Anda jika ada perubahan. — Kelurahan Batu Selicin",
                $submission->applicant_name,
                $submission->service->name,
                $submission->tracking_code
            ),
            SubmissionStatus::NeedsRevision => sprintf(
                "Halo %s, pengajuan %s (%s) memerlukan perbaikan. Catatan petugas: %s. Hubungi petugas untuk mengirim berkas perbaikan. — Kelurahan Batu Selicin",
                $submission->applicant_name,
                $submission->service->name,
                $submission->tracking_code,
                $submission->admin_notes ?: 'Silakan hubungi petugas kelurahan.'
            ),
            SubmissionStatus::Completed => sprintf(
                "Halo %s, pengajuan %s (%s) telah SELESAI. Silakan ambil dokumen di kantor Kelurahan Batu Selicin pada hari dan jam layanan dengan menunjukkan nomor pengajuan. — Kelurahan Batu Selicin",
                $submission->applicant_name,
                $submission->service->name,
                $submission->tracking_code
            ),
            default => sprintf(
                "Halo %s, status pengajuan %s (%s) saat ini: %s. Kami akan mengabari Anda jika ada perubahan. — Kelurahan Batu Selicin",
                $submission->applicant_name,
                $submission->service->name,
                $submission->tracking_code,
                $submission->status->label()
            ),
        };

        return $this->queue(
            $submission,
            $submission->notification_channel,
            $submission->phone,
            $message,
            $submission->status === SubmissionStatus::Completed ? 'completed' : null
        );
    }

    public function queue(?Submission $submission, string $channel, string $recipient, string $message, ?string $event = null): Notification
    {
        $notification = Notification::create([
            'submission_id' => $submission?->id,
            'channel' => $channel === 'sms' ? 'sms' : 'whatsapp',
            'recipient' => $recipient,
            'message' => $message,
            'status' => 'pending',
        ]);

        $this->attemptSend($notification);

        return $notification->refresh();
    }

    public function attemptSend(Notification $notification): bool
    {
        $notification->update(['error' => null]);

        try {
            if ($notification->channel === 'whatsapp') {
                return $this->sendWhatsapp($notification);
            }

            return $this->sendSms($notification);
        } catch (\Throwable $e) {
            Log::warning('Gagal mengirim notifikasi: '.$e->getMessage());
            $notification->update([
                'status' => 'failed',
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    private function sendWhatsapp(Notification $notification): bool
    {
        $token = config('services.whatsapp.token');
        $phoneNumberId = config('services.whatsapp.phone_number_id');

        if (! $token || ! $phoneNumberId) {
            return false;
        }

        $phone = preg_replace('/[^0-9]/', '', $notification->recipient);
        $phone = str_starts_with($phone, '0') ? '62'.substr($phone, 1) : $phone;

        $response = Http::timeout(15)
            ->withToken($token)
            ->post(rtrim(config('services.whatsapp.api_url'), '/').'/'.$phoneNumberId.'/messages', [
                'messaging_product' => 'whatsapp',
                'to' => $phone,
                'type' => 'text',
                'text' => ['body' => $notification->message],
            ]);

        if ($response->failed()) {
            $notification->update([
                'status' => 'failed',
                'error' => $response->json('error.message') ?? 'Gateway WhatsApp menolak permintaan.',
            ]);

            return false;
        }

        $notification->update([
            'status' => 'sent',
            'sent_at' => now(),
            'provider_reference' => $response->json('messages.0.id'),
        ]);

        return true;
    }

    private function sendSms(Notification $notification): bool
    {
        $endpoint = config('services.sms.endpoint');
        $token = config('services.sms.token');

        if (! $endpoint || ! $token) {
            return false;
        }

        $response = Http::timeout(15)
            ->withToken($token)
            ->post($endpoint, [
                'target' => $notification->recipient,
                'message' => $notification->message,
            ]);

        if ($response->failed()) {
            $notification->update([
                'status' => 'failed',
                'error' => 'Gateway SMS menolak permintaan.',
            ]);

            return false;
        }

        $notification->update([
            'status' => 'sent',
            'sent_at' => now(),
            'provider_reference' => $response->json('id') ?? $response->json('message_id'),
        ]);

        return true;
    }
}
