<?php

namespace App\Http\Controllers\CoS;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    /**
     * Display announcements and archive.
     */
    public function index(Request $request)
    {
        $this->authorize('broadcast', 'announcement');

        $activeAnnouncements = Announcement::active()
            ->orderBy('created_at', 'desc')
            ->get();

        $archivedAnnouncements = Announcement::where(function ($q) {
            $q->where('published_at', '<', now())->where('expires_at', '<', now());
        })
            ->orderBy('created_at', 'desc')
            ->paginate(25);

        return view('dashboards.cos.announcements.index', compact(
            'activeAnnouncements',
            'archivedAnnouncements'
        ));
    }

    /**
     * Show form to create new announcement.
     */
    public function create()
    {
        $this->authorize('broadcast', 'announcement');

        $audiences = [
            'supervisors' => 'Supervisors',
            'pmds' => 'PMDs (Medical Doctors)',
            'doctors' => 'All Doctors',
            'all_staff' => 'All Staff',
        ];

        return view('dashboards.cos.announcements.create', compact('audiences'));
    }

    /**
     * Store a new announcement.
     */
    public function store(Request $request)
    {
        $this->authorize('broadcast', 'announcement');

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:20|max:5000',
            'target_audience' => 'required|in:supervisors,pmds,doctors,all_staff',
            'scheduled_for' => 'nullable|date|after_or_equal:now',
            'expires_at' => 'nullable|date|after_or_equal:now',
        ]);

        try {
            $announcement = Announcement::create([
                'title' => $validated['title'],
                'content' => $validated['content'],
                'author_id' => Auth::id(),
                'target_audience' => $validated['target_audience'],
                'scheduled_for' => $validated['scheduled_for'] ?? null,
                'expires_at' => $validated['expires_at'] ?? null,
                'published_at' => empty($validated['scheduled_for']) ? now() : null,
            ]);

            // Log to audit trail
            app(\App\Services\AuditService::class)->logAction('announcement_sent', null, [
                'action' => 'Announcement created',
                'affected_entity' => 'Announcement',
                'new_values' => [
                    'announcement_id' => $announcement->id,
                    'title' => $announcement->title,
                    'audience' => $announcement->target_audience,
                ],
            ]);

            // Queue notification dispatch if published immediately
            if (!empty($validated['scheduled_for']) === false) {
                // In a real implementation, dispatch event to notify recipients
                // Event::dispatch(new AnnouncementPublished($announcement));
            }

            return redirect()->route('cos.announcements.index')
                ->with('success', 'Announcement created successfully');
        } catch (\Exception $e) {
            return back()->withError('Failed to create announcement: ' . $e->getMessage());
        }
    }

    /**
     * Show announcement detail.
     */
    public function show(Announcement $announcement)
    {
        $this->authorize('broadcast', 'announcement');

        return view('dashboards.cos.announcements.show', compact('announcement'));
    }

    /**
     * Show form to edit announcement.
     */
    public function edit(Announcement $announcement)
    {
        $this->authorize('broadcast', 'announcement');

        // Only allow editing if not yet published
        if ($announcement->published_at && $announcement->published_at < now()) {
            return back()->withError('Cannot edit published announcements');
        }

        $audiences = [
            'supervisors' => 'Supervisors',
            'pmds' => 'PMDs (Medical Doctors)',
            'doctors' => 'All Doctors',
            'all_staff' => 'All Staff',
        ];

        return view('dashboards.cos.announcements.edit', compact('announcement', 'audiences'));
    }

    /**
     * Update announcement.
     */
    public function update(Request $request, Announcement $announcement)
    {
        $this->authorize('broadcast', 'announcement');

        // Only allow editing if not yet published
        if ($announcement->published_at && $announcement->published_at < now()) {
            return back()->withError('Cannot edit published announcements');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:20|max:5000',
            'target_audience' => 'required|in:supervisors,pmds,doctors,all_staff',
            'scheduled_for' => 'nullable|date|after_or_equal:now',
            'expires_at' => 'nullable|date|after_or_equal:now',
        ]);

        try {
            $announcement->update($validated);

            return redirect()->route('cos.announcements.index')
                ->with('success', 'Announcement updated successfully');
        } catch (\Exception $e) {
            return back()->withError('Failed to update announcement: ' . $e->getMessage());
        }
    }

    /**
     * Delete announcement (only if not yet published).
     */
    public function destroy(Announcement $announcement)
    {
        $this->authorize('broadcast', 'announcement');

        if ($announcement->published_at && $announcement->published_at < now()) {
            return back()->withError('Cannot delete published announcements');
        }

        try {
            $announcement->delete();

            return back()->with('success', 'Announcement deleted successfully');
        } catch (\Exception $e) {
            return back()->withError('Failed to delete announcement: ' . $e->getMessage());
        }
    }

    /**
     * Get active announcements for dashboard banner.
     */
    public function getActiveBannerAnnouncements(Request $request)
    {
        // For dashboard display
        $announcements = Announcement::active()
            ->where(function ($q) {
                $q->where('target_audience', 'all_staff')
                    ->orWhere('target_audience', auth()->user()->role?->name);
            })
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        return response()->json($announcements);
    }
}
