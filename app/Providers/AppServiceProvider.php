<?php

namespace App\Providers;

use App\Models\Artikel;
use App\Models\Aspirasi;
use App\Models\Berita;
use App\Models\ContentComment;
use App\Models\SuratPengajuan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer(['layouts.admin-dashboard', 'layouts.dashboard'], function ($view): void {
            $user = Auth::user();
            $notifications = [];
            $sidebarNotifications = [];

            if (!$user) {
                $view->with(compact('notifications', 'sidebarNotifications'));
                return;
            }

            if (in_array($user->role, ['admin', 'petugas'], true)) {
                $notificationVersion = function ($query): string {
                    return (clone $query)->count() . ':' . ((clone $query)->max('id') ?? 0);
                };

                $suratNotificationQuery = SuratPengajuan::whereIn('status', ['menunggu', 'diajukan']);
                $usersNotificationQuery = User::where('role', 'warga')->where('approval_status', 'pending');
                $aspirasiNotificationQuery = Aspirasi::where('created_at', '>=', now()->subDays(7));
                $commentsNotificationQuery = ContentComment::where('created_at', '>=', now()->subDays(7));
                $beritaNotificationQuery = Berita::where('created_at', '>=', now()->subDays(7));
                $artikelNotificationQuery = Artikel::where('created_at', '>=', now()->subDays(7));

                $sidebarNotifications = [
                    'users' => (clone $usersNotificationQuery)->count(),
                    'surat' => (clone $suratNotificationQuery)->count(),
                    'aspirasi' => (clone $aspirasiNotificationQuery)->count(),
                    'comments' => (clone $commentsNotificationQuery)->count(),
                    'berita' => (clone $beritaNotificationQuery)->count(),
                    'artikel' => (clone $artikelNotificationQuery)->count(),
                ];

                $sidebarNotificationVersions = [
                    'users' => $notificationVersion($usersNotificationQuery),
                    'surat' => $notificationVersion($suratNotificationQuery),
                    'aspirasi' => $notificationVersion($aspirasiNotificationQuery),
                    'comments' => $notificationVersion($commentsNotificationQuery),
                    'berita' => $notificationVersion($beritaNotificationQuery),
                    'artikel' => $notificationVersion($artikelNotificationQuery),
                ];

                $notifications = collect([
                    ['key' => 'surat', 'count' => $sidebarNotifications['surat'], 'version' => $sidebarNotificationVersions['surat'], 'label' => 'Pengajuan surat menunggu diproses', 'url' => route($user->role === 'petugas' ? 'petugas.surat-pengajuan.index' : 'admin.surat-pengajuan.index'), 'icon' => 'fa-file-signature'],
                    ['key' => 'users', 'count' => $sidebarNotifications['users'], 'version' => $sidebarNotificationVersions['users'], 'label' => 'Akun warga menunggu approval', 'url' => route($user->role === 'petugas' ? 'petugas.users.index' : 'admin.users.index', ['status' => 'pending']), 'icon' => 'fa-users'],
                    ['key' => 'aspirasi', 'count' => $sidebarNotifications['aspirasi'], 'version' => $sidebarNotificationVersions['aspirasi'], 'label' => 'Aspirasi baru 7 hari terakhir', 'url' => route($user->role === 'petugas' ? 'petugas.aspirasi.index' : 'admin.aspirasi.index'), 'icon' => 'fa-comments'],
                    ['key' => 'comments', 'count' => $sidebarNotifications['comments'], 'version' => $sidebarNotificationVersions['comments'], 'label' => 'Komentar baru 7 hari terakhir', 'url' => route($user->role === 'petugas' ? 'petugas.comments.index' : 'admin.comments.index'), 'icon' => 'fa-comment-dots'],
                    ['key' => 'berita', 'count' => $sidebarNotifications['berita'], 'version' => $sidebarNotificationVersions['berita'], 'label' => 'Berita baru 7 hari terakhir', 'url' => route($user->role === 'petugas' ? 'petugas.berita.index' : 'admin.berita.index'), 'icon' => 'fa-newspaper'],
                    ['key' => 'artikel', 'count' => $sidebarNotifications['artikel'], 'version' => $sidebarNotificationVersions['artikel'], 'label' => 'Artikel baru 7 hari terakhir', 'url' => route($user->role === 'petugas' ? 'petugas.artikel.index' : 'admin.artikel.index'), 'icon' => 'fa-book-open'],
                ])->filter(fn (array $notification) => $notification['count'] > 0)->values()->all();
            } else {
                $recentStatus = SuratPengajuan::where('user_id', $user->id)
                    ->whereIn('status', ['disetujui', 'ditolak'])
                    ->where('updated_at', '>=', now()->subDays(7))
                    ->latest('updated_at')
                    ->take(5)
                    ->get();

                $sidebarNotifications = [
                    'status' => $recentStatus->count(),
                    'dokumen' => $recentStatus->where('status', 'disetujui')->count(),
                    'chatbot' => 0,
                ];

                $notifications = $recentStatus->map(fn (SuratPengajuan $surat) => [
                    'key' => 'surat-' . $surat->getKey(),
                    'count' => 1,
                    'version' => $surat->updated_at?->format('YmdHis.u') ?? (string) $surat->getKey(),
                    'label' => 'Pengajuan ' . $surat->jenisLabel() . ' ' . ($surat->status === 'disetujui' ? 'disetujui' : 'ditolak'),
                    'url' => route('dashboard.status'),
                    'icon' => $surat->status === 'disetujui' ? 'fa-circle-check' : 'fa-circle-xmark',
                ])->all();
            }

            $view->with(compact('notifications', 'sidebarNotifications'));
        });
    }
}
