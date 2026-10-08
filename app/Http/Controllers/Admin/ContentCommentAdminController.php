<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use App\Models\Berita;
use App\Models\ContentComment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContentCommentAdminController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));

        $comments = ContentComment::query()
            ->with('commentable')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhere('comment', 'like', '%' . $search . '%')
                        ->orWhere('commentable_type', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => ContentComment::count(),
            'today' => ContentComment::whereDate('created_at', today())->count(),
            'berita' => ContentComment::where('commentable_type', Berita::class)->count(),
            'artikel' => ContentComment::where('commentable_type', Artikel::class)->count(),
        ];

        return view('admin.comments.index', [
            'comments' => $comments,
            'stats' => $stats,
            'search' => $search,
        ]);
    }

    public function destroy(ContentComment $comment): RedirectResponse
    {
        $comment->delete();

        return back()->with('status', 'Komentar berhasil dihapus.');
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['required', 'integer', 'distinct', 'exists:content_comments,id'],
        ]);

        $comments = ContentComment::whereKey($data['ids'])->get();
        foreach ($comments as $comment) {
            $comment->delete();
        }

        return back()->with('status', $comments->count() . ' komentar berhasil dihapus.');
    }
}
