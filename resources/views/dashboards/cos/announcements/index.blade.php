@extends('layouts.cos-dashboard')

@section('title', 'Announcements')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Announcements</h1>
            <p class="text-gray-600 mt-1">Policy updates and procedural communications</p>
        </div>
        @can('broadcast_announcement')
        <a href="{{ route('cos.announcements.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            New Announcement
        </a>
        @endcan
    </div>

    <!-- Active Banner Announcement -->
    @if($activeBannerAnnouncement)
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <h3 class="font-semibold text-yellow-900">{{ $activeBannerAnnouncement->title }}</h3>
                <p class="text-yellow-800 text-sm mt-1">{{ $activeBannerAnnouncement->content }}</p>
                <p class="text-xs text-yellow-700 mt-2">
                    By: {{ $activeBannerAnnouncement->author->name }} • 
                    {{ $activeBannerAnnouncement->published_at->diffForHumans() }}
                </p>
            </div>
            <button onclick="this.parentElement.parentElement.parentElement.style.display='none'" class="text-yellow-600 hover:text-yellow-900">
                ✕
            </button>
        </div>
    </div>
    @endif

    <!-- Tabs -->
    <div class="flex gap-4 border-b border-gray-200">
        <a href="?tab=active" class="px-4 py-2 border-b-2 {{ request('tab', 'active') === 'active' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-600 hover:text-gray-900' }} font-medium">
            Active
        </a>
        <a href="?tab=archive" class="px-4 py-2 border-b-2 {{ request('tab') === 'archive' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-600 hover:text-gray-900' }} font-medium">
            Archive
        </a>
    </div>

    <!-- Active Announcements -->
    @if(request('tab', 'active') === 'active')
    <div class="space-y-4">
        @forelse($announcements as $announcement)
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $announcement->title }}</h3>
                    <p class="text-gray-600 mt-2">{{ $announcement->content }}</p>
                    <div class="flex items-center gap-4 mt-4 text-sm text-gray-500">
                        <span>Target: <strong>{{ str_replace('_', ' ', ucfirst($announcement->target_audience)) }}</strong></span>
                        <span>By: <strong>{{ $announcement->author->name }}</strong></span>
                        <span>Published: <strong>{{ $announcement->published_at?->format('M d, Y H:i') ?? 'Pending' }}</strong></span>
                    </div>
                </div>
                @can('broadcast_announcement')
                <div class="flex gap-2">
                    <a href="{{ route('cos.announcements.edit', $announcement->id) }}" class="px-3 py-1 text-sm text-blue-600 hover:bg-blue-50 rounded">
                        Edit
                    </a>
                    <form action="{{ route('cos.announcements.destroy', $announcement->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this announcement?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3 py-1 text-sm text-red-600 hover:bg-red-50 rounded">
                            Delete
                        </button>
                    </form>
                </div>
                @endcan
            </div>
        </div>
        @empty
        <div class="bg-white rounded-lg shadow p-8 text-center text-gray-500">
            <p>No active announcements.</p>
        </div>
        @endforelse
    </div>
    @else
    <!-- Archive -->
    <div class="space-y-4">
        @forelse($archivedAnnouncements as $announcement)
        <div class="bg-white rounded-lg shadow p-6 opacity-75">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $announcement->title }}</h3>
                    <p class="text-gray-600 mt-2">{{ $announcement->content }}</p>
                    <div class="flex items-center gap-4 mt-4 text-sm text-gray-500">
                        <span>Target: <strong>{{ str_replace('_', ' ', ucfirst($announcement->target_audience)) }}</strong></span>
                        <span>By: <strong>{{ $announcement->author->name }}</strong></span>
                        <span>Published: <strong>{{ $announcement->published_at?->format('M d, Y H:i') }}</strong></span>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-lg shadow p-8 text-center text-gray-500">
            <p>No archived announcements.</p>
        </div>
        @endforelse
    </div>
    @endif
</div>
@endsection
