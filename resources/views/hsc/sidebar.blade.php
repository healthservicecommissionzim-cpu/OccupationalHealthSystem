<div class="px-5 pt-3 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Navigation</div>

<!-- Dashboard -->
<a href="{{ route('dashboards.hsc_conditions') }}" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] {{ request()->routeIs('dashboards.hsc_conditions') ? 'border-brand-light' : 'border-transparent' }} hover:border-brand transition-all font-medium text-[13px]">
    <i class="fa-solid fa-chart-pie w-5 text-center text-slate-700 text-sm"></i>
    <span>Dashboard</span>
</a>

<!-- Incident Mapping -->
<a href="{{ route('hsc.cases.incident-mapping') }}" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] {{ request()->routeIs('hsc.cases.incident-mapping') ? 'border-brand-light' : 'border-transparent' }} hover:border-brand transition-all font-medium text-[13px]">
    <i class="fa-solid fa-map-location-dot w-5 text-center text-blue-600 text-sm"></i>
    <span>Incident Mapping</span>
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
            <span>Injury on Duty Cases</span>
        </a>
        <a href="{{ route('hsc.cases.death-on-duty') }}" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] {{ request()->routeIs('hsc.cases.death-on-duty') ? 'border-brand' : 'border-transparent' }} hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-heart-crack w-4 text-center text-red-600"></i>
            <span>Death on Duty Cases</span>
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

<!-- Insights and Reports Section -->
<div x-data="{ open: {{ request()->routeIs('hsc.insights.*') ? 'true' : 'false' }} }" class="space-y-1">
    <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2.5 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] {{ request()->routeIs('hsc.insights.*') ? 'border-brand-light' : 'border-transparent' }} hover:border-[#1bb74d] transition-all font-medium text-[13px]">
        <span class="flex items-center gap-3">
            <i class="fa-solid fa-lightbulb w-5 text-center text-slate-700 text-sm"></i>
            Insights and Reports
        </span>
        <i class="fa-solid fa-chevron-down text-xs transition-transform" :class="{ 'rotate-180': open }"></i>
    </button>
    <div x-show="open" class="space-y-1 pl-2">
        <a href="{{ route('hsc.cases.by-facility') }}" class="flex items-center gap-3 px-4 py-2 mx-2 rounded-lg text-slate-600 hover:bg-slate-50 border-l-[3px] {{ request()->routeIs('hsc.cases.by-facility') ? 'border-brand' : 'border-transparent' }} hover:border-slate-300 transition-all text-[12px]">
            <i class="fa-solid fa-hospital w-4 text-center text-slate-700"></i>
            <span>Injury Cases By Facility</span>
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
