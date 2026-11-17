<div class="px-5 pt-3 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Navigation</div>

<!-- Dashboard -->
<a href="{{ route('dashboards.hsc_conditions') }}" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-brand-light transition-all font-medium text-[13px]">
    <i class="fa-solid fa-chart-pie w-5 text-center text-slate-700 text-sm"></i>
    <span>Dashboard</span>
</a>

<!-- Case Management Section -->
<div x-data="{ open: {{ request()->routeIs('hsc.cases.*') ? 'true' : 'false' }} }" class="space-y-1">
    <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] {{ request()->routeIs('hsc.cases.*') ? 'border-brand-light' : 'border-transparent' }} hover:border-[#1bb74d] transition-all font-medium text-[13px]">
        <span class="flex items-center gap-3">
            <i class="fa-solid fa-folder-open w-5 text-center text-slate-700 text-sm"></i>
            Case Management
        </span>
        <i class="fa-solid fa-chevron-down text-xs transition-transform" :class="{ 'rotate-180': open }"></i>
    </button>
    <div x-show="open" class="space-y-1 pl-2">
        <a href="{{ route('hsc.cases.all') }}" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] {{ request()->routeIs('hsc.cases.all') ? 'border-brand' : 'border-transparent' }} hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-list-check w-4 text-center text-slate-700"></i>
            <span>All Cases</span>
        </a>
        <a href="{{ route('hsc.cases.by-facility') }}" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] {{ request()->routeIs('hsc.cases.by-facility') ? 'border-brand' : 'border-transparent' }} hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-hospital w-4 text-center text-slate-700"></i>
            <span>Cases by Facility</span>
        </a>
    </div>
</div>

<!-- Monitoring & Analytics Section -->
<div x-data="{ open: {{ request()->routeIs('hsc.compliance.*', 'hsc.facility.*', 'hsc.incident.*', 'hsc.risk.*') ? 'true' : 'false' }} }" class="space-y-1">
    <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] {{ request()->routeIs('hsc.compliance.*', 'hsc.facility.*', 'hsc.incident.*', 'hsc.risk.*') ? 'border-brand-light' : 'border-transparent' }} hover:border-[#1bb74d] transition-all font-medium text-[13px]">
        <span class="flex items-center gap-3">
            <i class="fa-solid fa-chart-line w-5 text-center text-slate-700 text-sm"></i>
            Monitoring & Analytics
        </span>
        <i class="fa-solid fa-chevron-down text-xs transition-transform" :class="{ 'rotate-180': open }"></i>
    </button>
    <div x-show="open" class="space-y-1 pl-2">
        <a href="{{ route('hsc.compliance.dashboard') }}" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] {{ request()->routeIs('hsc.compliance.dashboard') ? 'border-brand' : 'border-transparent' }} hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-clipboard-check w-4 text-center text-slate-700"></i>
            <span>Compliance Dashboard</span>
        </a>
        <a href="{{ route('hsc.incident.trends') }}" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] {{ request()->routeIs('hsc.incident.trends') ? 'border-brand' : 'border-transparent' }} hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-chart-area w-4 text-center text-slate-700"></i>
            <span>Incident Trends</span>
        </a>
        <a href="{{ route('hsc.risk.assessment') }}" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] {{ request()->routeIs('hsc.risk.assessment') ? 'border-brand' : 'border-transparent' }} hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-exclamation-triangle w-4 text-center text-orange-600"></i>
            <span>Risk Assessment</span>
        </a>
    </div>
</div>

<!-- Reports & Evidence Section -->
<div x-data="{ open: {{ request()->routeIs('hsc.reports.*') ? 'true' : 'false' }} }" class="space-y-1">
    <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] {{ request()->routeIs('hsc.reports.*') ? 'border-brand-light' : 'border-transparent' }} hover:border-[#1bb74d] transition-all font-medium text-[13px]">
        <span class="flex items-center gap-3">
            <i class="fa-solid fa-file-pdf w-5 text-center text-slate-700 text-sm"></i>
            Reports & Evidence
        </span>
        <i class="fa-solid fa-chevron-down text-xs transition-transform" :class="{ 'rotate-180': open }"></i>
    </button>
    <div x-show="open" class="space-y-1 pl-2">
        <a href="{{ route('hsc.reports.generate') }}" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] {{ request()->routeIs('hsc.reports.generate') ? 'border-brand' : 'border-transparent' }} hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-file-export w-4 text-center text-slate-700"></i>
            <span>Generate Reports</span>
        </a>
        <a href="{{ route('hsc.reports.export') }}" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] {{ request()->routeIs('hsc.reports.export') ? 'border-brand' : 'border-transparent' }} hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-download w-4 text-center text-slate-700"></i>
            <span>Export Data</span>
        </a>
        <a href="{{ route('hsc.reports.statistics') }}" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] {{ request()->routeIs('hsc.reports.statistics') ? 'border-brand' : 'border-transparent' }} hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-chart-bar w-4 text-center text-slate-700"></i>
            <span>Statistical Summary</span>
        </a>
        <a href="{{ route('hsc.reports.comparative') }}" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] {{ request()->routeIs('hsc.reports.comparative') ? 'border-brand' : 'border-transparent' }} hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-code-branch w-4 text-center text-slate-700"></i>
            <span>Comparative Analysis</span>
        </a>
    </div>
</div>


<div class="my-3 border-t border-slate-200"></div>

<!-- Support Section -->
<a href="{{ route('support.create_ticket') }}" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] border-transparent hover:border-brand transition-all font-medium text-[13px]">
    <i class="fa-solid fa-headset w-5 text-center text-slate-700 text-sm"></i>
    <span>IT Support</span>
</a>

<div class="my-3 border-t border-slate-200"></div>

<!-- Logout -->
<a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-red-600 hover:bg-red-50 border-l-[3px] border-transparent hover:border-red-300 transition-all font-medium text-[13px]">
    <i class="fa-solid fa-arrow-right-from-bracket w-5 text-center"></i>
    <span>Logout</span>
</a>
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
