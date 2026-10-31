<?php

namespace App\Http\Controllers\Admin;

use App\Enums\SubmissionStatus;
use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Submission;
use App\Models\SubmissionDocument;
use App\Services\SubmissionWorkflow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SubmissionController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['status', 'service', 'q']);

        $submissions = Submission::with('service')
            ->filter($filters)
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $services = Service::orderBy('sort_order')->get();
        $statuses = SubmissionStatus::cases();

        return view('admin.submissions.index', compact('submissions', 'services', 'statuses', 'filters'));
    }

    public function show(Submission $submission)
    {
        $submission->load(['service.requirements', 'documents', 'histories.user', 'completedBy', 'notifications']);

        return view('admin.submissions.show', compact('submission'));
    }

    public function verify(Request $request, Submission $submission, SubmissionWorkflow $workflow)
    {
        $validated = $request->validate([
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $workflow->verify($submission, $request->user(), $validated['note'] ?? null);

        return back()->with('success', 'Pengajuan berhasil diverifikasi.');
    }

    public function requestRevision(Request $request, Submission $submission, SubmissionWorkflow $workflow)
    {
        $validated = $request->validate([
            'note' => ['required', 'string', 'min:10', 'max:1000'],
        ], [
            'note.required' => 'Tuliskan catatan perbaikan yang jelas untuk pemohon.',
            'note.min' => 'Catatan perbaikan minimal 10 karakter.',
        ]);

        $workflow->requestRevision($submission, $request->user(), $validated['note']);

        return back()->with('success', 'Permintaan perbaikan dikirim ke pemohon.');
    }

    public function complete(Request $request, Submission $submission, SubmissionWorkflow $workflow)
    {
        $validated = $request->validate([
            'result_file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:4096'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $workflow->complete(
            $submission,
            $request->user(),
            $request->file('result_file'),
            $validated['note'] ?? null
        );

        return back()->with('success', 'Pengajuan ditandai selesai dan notifikasi disiapkan.');
    }

    public function downloadDocument(Submission $submission, SubmissionDocument $document)
    {
        $this->ensureDocumentBelongsToSubmission($document, $submission);
        $this->ensureFileExists($document->path);

        return Storage::disk('local')->download($document->path, $document->original_name);
    }

    public function previewDocument(Submission $submission, SubmissionDocument $document)
    {
        $this->ensureDocumentBelongsToSubmission($document, $submission);
        $this->ensureFileExists($document->path);

        return response()->file(Storage::disk('local')->path($document->path), [
            'Content-Type' => $document->mime_type ?: 'application/octet-stream',
            'Content-Disposition' => 'inline; filename="'.$document->original_name.'"',
        ]);
    }

    public function downloadResult(Submission $submission)
    {
        abort_unless($submission->result_file, 404);
        $this->ensureFileExists($submission->result_file);

        return Storage::disk('local')->download($submission->result_file, 'Hasil-'.$submission->tracking_code.'.'.pathinfo($submission->result_file, PATHINFO_EXTENSION));
    }

    private function ensureDocumentBelongsToSubmission(SubmissionDocument $document, Submission $submission): void
    {
        abort_unless($document->submission_id === $submission->id, 404);
    }

    private function ensureFileExists(string $path): void
    {
        abort_unless(Storage::disk('local')->exists($path), 404, 'Berkas tidak ditemukan di penyimpanan.');
    }
}
