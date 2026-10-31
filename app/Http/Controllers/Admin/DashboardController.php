<?php

namespace App\Http\Controllers\Admin;

use App\Enums\SubmissionStatus;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Service;
use App\Models\Submission;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $statusCounts = Submission::query()
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $stats = [
            'new' => (int) ($statusCounts[SubmissionStatus::Submitted->value] ?? 0),
            'verified' => (int) ($statusCounts[SubmissionStatus::Verified->value] ?? 0),
            'revision' => (int) ($statusCounts[SubmissionStatus::NeedsRevision->value] ?? 0),
            'completed' => Submission::where('status', SubmissionStatus::Completed)
                ->whereBetween('completed_at', [now()->startOfMonth(), now()->endOfMonth()])
                ->count(),
        ];

        $recentSubmissions = Submission::with('service')->latest()->limit(6)->get();

        $serviceStats = Service::withCount([
            'submissions as total_submissions',
            'submissions as open_submissions' => fn ($q) => $q->whereIn('status', [
                SubmissionStatus::Submitted->value,
                SubmissionStatus::Verified->value,
                SubmissionStatus::NeedsRevision->value,
            ]),
        ])->orderBy('sort_order')->get();

        $pendingNotifications = Notification::where('status', 'pending')->count();

        return view('admin.dashboard', compact(
            'stats',
            'recentSubmissions',
            'serviceStats',
            'pendingNotifications',
            'statusCounts'
        ));
    }
}
