<?php

namespace App\Services;

use App\Enums\SubmissionStatus;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SubmissionWorkflow
{
    public function __construct(private NotificationService $notificationService)
    {
    }

    public function verify(Submission $submission, User $user, ?string $note = null): Submission
    {
        return $this->transition(
            $submission,
            $user,
            SubmissionStatus::Verified,
            'Berkas diverifikasi',
            $note ?: 'Seluruh berkas dinyatakan lengkap. Pengajuan sedang diproses.',
            ['verified_at' => now()]
        );
    }

    public function requestRevision(Submission $submission, User $user, string $note): Submission
    {
        return $this->transition(
            $submission,
            $user,
            SubmissionStatus::NeedsRevision,
            'Berkas perlu diperbaiki',
            $note,
            ['admin_notes' => $note]
        );
    }

    public function complete(Submission $submission, User $user, ?UploadedFile $resultFile = null, ?string $note = null): Submission
    {
        $attributes = [
            'completed_at' => now(),
            'completed_by' => $user->id,
        ];

        if ($resultFile) {
            $attributes['result_file'] = $resultFile->store('results', 'local');
        }

        return $this->transition(
            $submission,
            $user,
            SubmissionStatus::Completed,
            'Pengajuan selesai',
            $note ?: 'Dokumen selesai dan siap diambil di kantor kelurahan.',
            $attributes
        );
    }

    public function documentsReplaced(Submission $submission, string $note = 'Berkas perbaikan telah diunggah pemohon.'): Submission
    {
        return DB::transaction(function () use ($submission, $note) {
            $submission->update([
                'status' => SubmissionStatus::Submitted,
                'admin_notes' => null,
            ]);

            $submission->histories()->create([
                'status' => SubmissionStatus::Submitted,
                'title' => 'Berkas perbaikan diterima',
                'note' => $note,
            ]);

            return $submission->refresh();
        });
    }

    private function transition(Submission $submission, User $user, SubmissionStatus $status, string $title, string $note, array $attributes = []): Submission
    {
        return DB::transaction(function () use ($submission, $user, $status, $title, $note, $attributes) {
            $submission->update($attributes + ['status' => $status]);

            $submission->histories()->create([
                'status' => $status,
                'title' => $title,
                'note' => $note,
                'changed_by' => $user->id,
            ]);

            $this->notificationService->submissionStatusChanged($submission->refresh());

            return $submission->refresh();
        });
    }
}
