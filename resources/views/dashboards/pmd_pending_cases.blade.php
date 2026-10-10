@extends('layouts.dashboard')

@section('page_title', 'Pending PMD Cases')

@section('header_title', 'Pending Cases')
@section('header_subtitle', 'All cases awaiting PMD approval')

@section('sidebar_content')
<div class="px-5 pt-3 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Navigation</div>
<a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent transition-all font-medium text-[13px]">
    <i class="fa-solid fa-chart-pie w-5 text-center text-slate-700 text-sm"></i>
    <span>Dashboard</span>
</a>
<a href="{{ route('dashboards.pmd') }}" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent transition-all font-medium text-[13px]">
    <i class="fa-solid fa-chart-line w-5 text-center text-slate-700 text-sm"></i>
    <span>PMD Dashboard</span>
</a>
<a href="{{ route('pmd.pending_cases') }}" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-brand-light transition-all font-medium text-[13px]">
    <i class="fa-solid fa-list-check w-5 text-center text-slate-700 text-sm"></i>
    <span>All Pending Cases</span>
</a>
<a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-red-600 hover:bg-red-50 border-l-[3px] border-transparent hover:border-red-300 transition-all font-medium text-[13px]">
    <i class="fa-solid fa-arrow-right-from-bracket w-5 text-center"></i>
    <span>Logout</span>
</a>
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
@endsection

@section('dashboard_content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-2">
    <div>
        <h1 class="text-[15px] font-bold text-navy tracking-wide">PENDING PMD APPROVAL CASES</h1>
        <p class="text-[12px] text-slate-500 mt-1">Complete list of all cases awaiting your review and approval</p>
    </div>
</div>
<div class="h-px w-full bg-border mb-4"></div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-3 md:gap-4 mb-6">
    <div class="bg-white rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-all overflow-hidden">
        <div class="h-0.5 bg-brand"></div>
        <div class="p-2.5 flex items-start justify-between gap-2">
            <div class="flex-1">
                <span class="text-xs font-semibold text-slate-600 uppercase tracking-wide">Total Pending</span>
                <p class="text-xl font-bold text-slate-900 mt-0.5">{{ $statistics['total_pending'] }}</p>
                <p class="text-xs text-slate-500 mt-0.5">Awaiting Review</p>
            </div>
            <div class="flex-shrink-0 w-9 h-9 bg-gradient-to-br from-amber-50 to-amber-100 rounded-lg flex items-center justify-center">
                <x-lucide-inbox class="w-4 h-4 text-amber-600" />
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-all overflow-hidden">
        <div class="h-0.5 bg-brand"></div>
        <div class="p-2.5 flex items-start justify-between gap-2">
            <div class="flex-1">
                <span class="text-xs font-semibold text-slate-600 uppercase tracking-wide">Escalated to PMD</span>
                <p class="text-xl font-bold text-slate-900 mt-0.5">{{ $statistics['escalated_to_pmd'] }}</p>
                <p class="text-xs text-slate-500 mt-0.5">Ready for Review</p>
            </div>
            <div class="flex-shrink-0 w-9 h-9 bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg flex items-center justify-center">
                <x-lucide-arrow-right-circle class="w-4 h-4 text-blue-600" />
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-all overflow-hidden">
        <div class="h-0.5 bg-brand"></div>
        <div class="p-2.5 flex items-start justify-between gap-2">
            <div class="flex-1">
                <span class="text-xs font-semibold text-slate-600 uppercase tracking-wide">Pending Review</span>
                <p class="text-xl font-bold text-slate-900 mt-0.5">{{ $statistics['pending_pmd_review'] }}</p>
                <p class="text-xs text-slate-500 mt-0.5">In Review</p>
            </div>
            <div class="flex-shrink-0 w-9 h-9 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-lg flex items-center justify-center">
                <x-lucide-check-circle-2 class="w-4 h-4 text-emerald-600" />
            </div>
        </div>
    </div>
</div>

<!-- Cases Table -->
<div class="bg-white rounded-xl border border-border shadow-sm p-4 md:p-6 mt-6 md:mt-8">
    <h3 class="text-sm md:text-base lg:text-lg font-semibold text-navy mb-4 md:mb-6">All Pending Cases</h3>
    @if($pendingCases->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-xs md:text-sm">
                <thead>
                    <tr class="border-b border-border text-slate-500">
                        <th class="text-left py-3 px-2 md:px-4 font-semibold">Reference</th>
                        <th class="text-left py-3 px-2 md:px-4 font-semibold hidden sm:table-cell">Employee</th>
                        <th class="text-left py-3 px-2 md:px-4 font-semibold hidden md:table-cell">Facility</th>
                        <th class="text-left py-3 px-2 md:px-4 font-semibold hidden lg:table-cell">District</th>
                        <th class="text-left py-3 px-2 md:px-4 font-semibold hidden xl:table-cell">Incident Type</th>
                        <th class="text-left py-3 px-2 md:px-4 font-semibold">Date Received</th>
                        <th class="text-left py-3 px-2 md:px-4 font-semibold hidden sm:table-cell">Status</th>
                        <th class="text-center py-3 px-2 md:px-4 font-semibold">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingCases as $incident)
                        <tr class="border-b border-border hover:bg-slate-50 transition-colors">
                            <td class="py-3 px-2 md:px-4">
                                <span class="font-medium text-brand text-xs md:text-sm">{{ $incident->reference_number }}</span>
                            </td>
                            <td class="py-3 px-2 md:px-4 hidden sm:table-cell">
                                <span class="text-slate-700 text-xs md:text-sm">{{ $incident->first_name }} {{ $incident->surname }}</span>
                            </td>
                            <td class="py-3 px-2 md:px-4 hidden md:table-cell">
                                <span class="text-slate-600 text-xs md:text-sm">{{ $incident->facility->name ?? 'N/A' }}</span>
                            </td>
                            <td class="py-3 px-2 md:px-4 hidden lg:table-cell">
                                <span class="text-slate-600 text-xs md:text-sm">{{ $incident->facility->district->name ?? 'N/A' }}</span>
                            </td>
                            <td class="py-3 px-2 md:px-4 hidden xl:table-cell">
                                <span class="text-slate-600 text-xs md:text-sm">{{ $incident->incidentType->name ?? 'N/A' }}</span>
                            </td>
                            <td class="py-3 px-2 md:px-4">
                                <span class="text-slate-500 text-xs md:text-sm">{{ $incident->created_at->format('M d, Y') }}</span>
                            </td>
                            <td class="py-3 px-2 md:px-4 hidden sm:table-cell">
                                @if($incident->status === 'ESCALATED_TO_PMD')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs md:text-sm font-semibold bg-blue-100 text-blue-700">
                                        <i class="fa-solid fa-arrow-up text-xs md:text-sm mr-1"></i>
                                        Escalated
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs md:text-sm font-semibold bg-amber-100 text-amber-700">
                                        <i class="fa-solid fa-hourglass text-xs md:text-sm mr-1"></i>
                                        Pending
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-2 md:px-4 text-center">
                                <a href="{{ route('pmd.review_case', $incident) }}" class="inline-flex items-center gap-1 px-2 md:px-3 py-1 md:py-1.5 bg-brand text-white rounded text-xs md:text-sm font-medium hover:bg-brand-dark transition-colors">
                                    <i class="fa-solid fa-eye text-xs md:text-sm"></i>
                                    <span class="hidden sm:inline">Review</span><span class="sm:hidden">View</span>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($pendingCases->hasPages())
            <div class="mt-6 pt-4 border-t border-border">
                <div class="flex items-center justify-between">
                    <p class="text-xs md:text-sm text-slate-600">
                        Showing <span class="font-medium">{{ $pendingCases->firstItem() }}</span> to <span class="font-medium">{{ $pendingCases->lastItem() }}</span> of <span class="font-medium">{{ $pendingCases->total() }}</span> cases
                    </p>
                    <div class="flex gap-1">
                        @if($pendingCases->onFirstPage())
                            <button disabled class="px-2 md:px-3 py-1 md:py-1.5 rounded border border-slate-200 text-xs md:text-sm font-medium text-slate-400 cursor-not-allowed">
                                <i class="fa-solid fa-chevron-left"></i>
                            </button>
                        @else
                            <a href="{{ $pendingCases->previousPageUrl() }}" class="px-2 md:px-3 py-1 md:py-1.5 rounded border border-slate-200 text-xs md:text-sm font-medium text-slate-600 hover:bg-slate-50 transition-colors">
                                <i class="fa-solid fa-chevron-left"></i>
                            </a>
                        @endif

                        @foreach($pendingCases->getUrlRange(1, $pendingCases->lastPage()) as $page => $url)
                            @if($page == $pendingCases->currentPage())
                                <button disabled class="px-2 md:px-3 py-1 md:py-1.5 rounded bg-brand text-white text-xs md:text-sm font-medium">{{ $page }}</button>
                            @else
                                <a href="{{ $url }}" class="px-2 md:px-3 py-1 md:py-1.5 rounded border border-slate-200 text-xs md:text-sm font-medium text-slate-600 hover:bg-slate-50 transition-colors">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if($pendingCases->hasMorePages())
                            <a href="{{ $pendingCases->nextPageUrl() }}" class="px-2 md:px-3 py-1 md:py-1.5 rounded border border-slate-200 text-xs md:text-sm font-medium text-slate-600 hover:bg-slate-50 transition-colors">
                                <i class="fa-solid fa-chevron-right"></i>
                            </a>
                        @else
                            <button disabled class="px-2 md:px-3 py-1 md:py-1.5 rounded border border-slate-200 text-xs md:text-sm font-medium text-slate-400 cursor-not-allowed">
                                <i class="fa-solid fa-chevron-right"></i>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    @else
        <div class="text-center py-8 md:py-12 text-slate-400">
            <i class="fa-solid fa-inbox text-4xl md:text-5xl mb-3 md:mb-4"></i>
            <p class="text-sm md:text-base font-medium">No pending cases</p>
            <p class="text-xs md:text-sm mt-1">All cases have been reviewed. Great work!</p>
        </div>
    @endif
</div>
@endsection
