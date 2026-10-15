@extends('layouts.app')

@section('content')
<div class="cos-dashboard">
    <!-- Navigation -->
    <nav class="cos-navbar bg-gradient-to-r from-blue-900 to-blue-700 text-white shadow-lg">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <h1 class="text-2xl font-bold">HSC Conditions of Service</h1>
                    <span class="text-sm bg-blue-500 px-3 py-1 rounded-full">Governance Dashboard</span>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('cos.notifications.index') }}" class="relative hover:bg-blue-600 px-3 py-2 rounded">
                        <i class="fas fa-bell"></i>
                        @if($unreadNotificationCount ?? 0 > 0)
                            <span class="absolute top-0 right-0 bg-red-500 text-xs rounded-full w-5 h-5 flex items-center justify-center">
                                {{ $unreadNotificationCount }}
                            </span>
                        @endif
                    </a>
                    <button onclick="openProfileModal()" class="hover:bg-blue-600 px-3 py-2 rounded cursor-pointer">{{ auth()->user()->name }}</button>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="hover:bg-blue-600 px-3 py-2 rounded text-sm">Logout</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sub Navigation -->
        <div class="bg-blue-800 border-t border-blue-700">
            <div class="container mx-auto px-4 py-2">
                <div class="flex space-x-6 text-sm">
                    <a href="{{ route('cos.index') }}" class="hover:text-blue-200 {{ request()->routeIs('cos.index') ? 'text-yellow-300 border-b-2 border-yellow-300' : '' }}">
                        Dashboard
                    </a>
                    @can('run_compliance_checklist')
                        <a href="{{ route('cos.compliance.report') }}" class="hover:text-blue-200 {{ request()->routeIs('cos.compliance.*') ? 'text-yellow-300 border-b-2 border-yellow-300' : '' }}">
                            Compliance
                        </a>
                    @endcan
                    @can('view_audit_log')
                        <a href="#" class="hover:text-blue-200">Audit Logs</a>
                    @endcan
                    @can('broadcast_announcement')
                        <a href="{{ route('cos.announcements.index') }}" class="hover:text-blue-200 {{ request()->routeIs('cos.announcements.*') ? 'text-yellow-300 border-b-2 border-yellow-300' : '' }}">
                            Announcements
                        </a>
                    @endcan
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <h4 class="font-bold">Errors:</h4>
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 flex justify-between">
                <span>{{ session('success') }}</span>
                <button type="button" onclick="this.parentElement.style.display='none'" class="text-green-700 font-bold">×</button>
            </div>
        @endif

        @yield('cos-content')
    </div>
</div>

<style>
    .cos-dashboard {
        min-height: 100vh;
        background-color: #f9fafb;
    }

    .metric-card {
        background: white;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .metric-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .metric-value {
        font-size: 2rem;
        font-weight: bold;
        color: #1f2937;
    }

    .metric-label {
        font-size: 0.875rem;
        color: #6b7280;
        margin-top: 8px;
    }

    .breach-alert {
        background-color: #fef2f2;
        border-left: 4px solid #ef4444;
        padding: 12px;
        margin-bottom: 10px;
        border-radius: 4px;
    }

    .timeline-item {
        padding: 15px;
        border-left: 3px solid #3b82f6;
        margin-bottom: 15px;
        background: white;
        border-radius: 4px;
    }

    .timeline-item.current {
        border-left-color: #10b981;
        background-color: #f0fdf4;
    }

    .compliance-item {
        display: flex;
        align-items: center;
        padding: 12px;
        margin-bottom: 10px;
        background: white;
        border-radius: 4px;
        border: 1px solid #e5e7eb;
    }

    .compliance-item.compliant {
        border-left: 4px solid #10b981;
    }

    .compliance-item.non-compliant {
        border-left: 4px solid #ef4444;
    }
</style>
@endsection

<!-- Profile Modal Component -->
@include('components.profile-modal')
