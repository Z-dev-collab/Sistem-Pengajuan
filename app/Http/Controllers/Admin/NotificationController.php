<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['status', 'channel', 'q']);

        $notifications = Notification::with(['submission.service'])
            ->when($filters['status'] ?? null, fn ($q, $status) => $q->where('status', $status))
            ->when($filters['channel'] ?? null, fn ($q, $channel) => $q->where('channel', $channel))
            ->when($filters['q'] ?? null, function ($q, $search) {
                $q->where(function ($inner) use ($search) {
                    $inner->where('recipient', 'like', "%{$search}%")
                        ->orWhere('message', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.notifications.index', compact('notifications', 'filters'));
    }

    public function resend(Notification $notification, NotificationService $notificationService)
    {
        if ($notification->status === 'sent') {
            return back()->with('info', 'Notifikasi ini sudah berstatus terkirim.');
        }

        $notificationService->attemptSend($notification);

        if ($notification->fresh()->status === 'sent') {
            return back()->with('success', 'Notifikasi berhasil dikirim ulang.');
        }

        return back()->with('warning', 'Gateway belum dikonfigurasi atau gagal. Gunakan tombol WhatsApp untuk mengirim manual.');
    }

    public function markSent(Notification $notification)
    {
        $notification->update([
            'status' => 'sent',
            'sent_at' => now(),
            'error' => null,
        ]);

        return back()->with('success', 'Notifikasi ditandai sudah terkirim.');
    }
}
