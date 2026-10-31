<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubmissionRequest;
use App\Models\Service;
use App\Models\Submission;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SubmissionController extends Controller
{
    public function create(Request $request)
    {
        $services = Service::active()->orderBy('sort_order')->get();
        $selectedService = $request->filled('layanan')
            ? $services->firstWhere('slug', $request->input('layanan'))
            : null;

        return view('submissions.create', compact('services', 'selectedService'));
    }

    public function store(StoreSubmissionRequest $request, NotificationService $notificationService)
    {
        $submission = DB::transaction(function () use ($request) {
            $submission = Submission::create([
                'tracking_code' => $this->generateTrackingCode(),
                'service_id' => $request->integer('service_id'),
                'applicant_name' => $request->string('applicant_name')->trim()->toString(),
                'nik' => $request->input('nik'),
                'kk_number' => $request->input('kk_number'),
                'phone' => $request->input('phone'),
                'email' => $request->input('email'),
                'address' => $request->string('address')->trim()->toString(),
                'rt' => $request->input('rt'),
                'rw' => $request->input('rw'),
                'notes' => $request->input('notes'),
                'status' => 'submitted',
                'notification_channel' => $request->input('notification_channel'),
            ]);

            foreach (['ktp', 'kk', 'pengantar'] as $type) {
                $this->storeDocument($submission, $request->file("documents.{$type}"), $type);
            }

            foreach ($request->file('documents.pendukung', []) as $file) {
                $this->storeDocument($submission, $file, 'pendukung');
            }

            $submission->histories()->create([
                'status' => 'submitted',
                'title' => 'Pengajuan diterima',
                'note' => 'Pengajuan berhasil dikirim dan menunggu pemeriksaan petugas.',
            ]);

            return $submission;
        });

        $notificationService->submissionCreated($submission);

        return redirect()
            ->route('submissions.success', $submission->tracking_code)
            ->with('success', 'Pengajuan berhasil dikirim.');
    }

    public function success(string $code)
    {
        $submission = Submission::with('service')
            ->where('tracking_code', strtoupper(trim($code)))
            ->firstOrFail();

        return view('submissions.success', compact('submission'));
    }

    private function storeDocument(Submission $submission, $file, string $type): void
    {
        $path = $file->store("submissions/{$submission->id}", 'local');

        $submission->documents()->create([
            'type' => $type,
            'original_name' => $file->getClientOriginalName(),
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
        ]);
    }

    private function generateTrackingCode(): string
    {
        do {
            $code = 'BS-'.now()->format('Ymd').'-'.strtoupper(Str::random(4));
        } while (Submission::where('tracking_code', $code)->exists());

        return $code;
    }
}
