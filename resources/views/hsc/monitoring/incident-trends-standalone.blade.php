<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Repeat Incident Patterns — Health Service Commission</title>
    
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Inter"', 'sans-serif']
                    },
                    colors: {
                        brand: {
                            DEFAULT: '#0d7a3d',
                            light: '#1bb74d',
                            dark: '#0a5f30',
                        },
                        navy: '#1e3a5f',
                        surface: '#f8fafc',
                        border: '#e8ecf1',
                    }
                }
            }
        }
    </script>
    
    <style>
        * { font-family: Inter, Tahoma, sans-serif; }
        body { background: #f8fafc; margin: 0; padding: 0; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="bg-surface">
    <div class="min-h-screen p-6">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-navy mb-1">Repeat Incident Patterns</h1>
                <p class="text-sm text-slate-500">Incident types that have occurred 2+ times in the last 180 days</p>
            </div>
            <a href="{{ route('dashboards.hsc_conditions') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        <!-- Content Container -->
        <div class="max-w-7xl">
            <div class="mb-6">
                <h2 class="text-[14px] font-bold text-navy flex items-center gap-2 mb-4">
                    <i class="fa-solid fa-repeat text-orange-600"></i> REPEAT INCIDENT PATTERNS
                </h2>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg border border-slate-200 p-4">
                    <p class="text-xs text-slate-600 font-semibold">Total Pattern Types</p>
                    <p class="text-2xl font-bold text-slate-900 mt-1">{{ $repeatPatterns->count() }}</p>
                    <p class="text-[10px] text-slate-500 mt-1">Unique incident types repeating</p>
                </div>
                <div class="bg-white rounded-lg border border-slate-200 p-4">
                    <p class="text-xs text-slate-600 font-semibold">Total Repeat Incidents</p>
                    <p class="text-2xl font-bold text-orange-600 mt-1">{{ $repeatPatterns->sum('repeat_count') }}</p>
                    <p class="text-[10px] text-slate-500 mt-1">Across all patterns</p>
                </div>
                <div class="bg-white rounded-lg border border-slate-200 p-4">
                    <p class="text-xs text-slate-600 font-semibold">Highest Repetition</p>
                    <p class="text-2xl font-bold text-red-600 mt-1">{{ $repeatPatterns->max('repeat_count') ?? 0 }}</p>
                    <p class="text-[10px] text-slate-500 mt-1">Most repeated incident type</p>
                </div>
                <div class="bg-white rounded-lg border border-slate-200 p-4">
                    <p class="text-xs text-slate-600 font-semibold">Time Period</p>
                    <p class="text-2xl font-bold text-blue-600 mt-1">180</p>
                    <p class="text-[10px] text-slate-500 mt-1">Days analysis window</p>
                </div>
            </div>

            <!-- Table -->
            @if($repeatPatterns->count() > 0)
            <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50">
                            <th class="text-center py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Rank</th>
                            <th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Incident Type</th>
                            <th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Description</th>
                            <th class="text-center py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Repeat Count</th>
                            <th class="text-center py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($repeatPatterns as $index => $pattern)
                        <tr class="border-b border-slate-200 hover:bg-slate-50">
                            <td class="py-3 px-4 text-center text-sm font-bold text-slate-500">
                                #{{ $index + 1 }}
                            </td>
                            <td class="py-3 px-4 text-xs font-semibold text-navy">
                                {{ $pattern->incidentType->name ?? 'Unknown Type' }}
                            </td>
                            <td class="py-3 px-4 text-xs text-slate-600">
                                {{ $pattern->incidentType->description ?? 'No description available' }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                @php
                                    $badgeClass = 'bg-blue-100 text-blue-700';
                                    if($pattern->repeat_count >= 5) {
                                        $badgeClass = 'bg-red-100 text-red-700';
                                    } elseif($pattern->repeat_count >= 3) {
                                        $badgeClass = 'bg-orange-100 text-orange-700';
                                    }
                                @endphp
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold {{ $badgeClass }}">
                                    {{ $pattern->repeat_count }}x
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <button 
                                    onclick="showPatternDetails({{ $pattern->incident_type_id }}, '{{ addslashes($pattern->incidentType->name ?? 'Unknown') }}', {{ $pattern->repeat_count }})"
                                    class="inline-flex items-center gap-1 px-3 py-1 bg-brand text-white rounded text-xs font-semibold hover:bg-brand-dark transition"
                                >
                                    <i class="fa-solid fa-eye"></i> View Details
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-12 text-center">
                <i class="fa-solid fa-check-circle text-6xl text-emerald-500 mb-4"></i>
                <h3 class="text-lg font-bold text-slate-900 mb-2">No Repeat Patterns Found</h3>
                <p class="text-sm text-slate-500">No incident types have occurred 2 or more times in the last 180 days</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Modal -->
    <div id="patternModal" class="hidden fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50" onclick="closeModal(event)">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-6xl max-h-[90vh] overflow-hidden m-4" onclick="event.stopPropagation()">
            <!-- Modal Header -->
            <div class="bg-slate-50 border-b border-slate-200 px-6 py-4 flex items-center justify-between">
                <h3 id="modalTitle" class="text-lg font-bold text-navy flex items-center gap-2">
                    <i class="fa-solid fa-clipboard-list text-brand"></i>
                    Pattern Details
                </h3>
                <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600 text-2xl leading-none">
                    &times;
                </button>
            </div>
            
            <!-- Modal Body -->
            <div id="modalBody" class="p-6 overflow-y-auto max-h-[calc(90vh-80px)]">
                <!-- Content loaded dynamically -->
            </div>
        </div>
    </div>

    <script>
    // Pattern details data from Laravel
    const patternDetails = @json($patternDetails);

    function showPatternDetails(patternKey, incidentType, repeatCount) {
        const modal = document.getElementById('patternModal');
        const modalTitle = document.getElementById('modalTitle');
        const modalBody = document.getElementById('modalBody');
        
        modalTitle.innerHTML = `
            <i class="fa-solid fa-clipboard-list text-brand"></i>
            ${incidentType} (${repeatCount} Incidents)
        `;
        
        const facilities = patternDetails[patternKey] || {};
        
        if (Object.keys(facilities).length === 0) {
            modalBody.innerHTML = `
                <div class="text-center py-12">
                    <i class="fa-solid fa-inbox text-5xl text-slate-300 mb-4"></i>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">No Details Available</h3>
                    <p class="text-sm text-slate-500">Could not load incident details for this pattern</p>
                </div>
            `;
        } else {
            let html = '';
            
            Object.values(facilities).forEach((facilityData) => {
                const facility = facilityData.facility;
                const incidents = facilityData.incidents;
                const count = facilityData.count;
                
                html += `
                    <div class="mb-6 pb-6 border-b border-slate-200 last:border-b-0 last:mb-0 last:pb-0">
                        <div class="flex items-center justify-between mb-4 p-4 bg-slate-50 rounded-lg">
                            <div class="flex items-center gap-2 font-bold text-slate-900">
                                <i class="fa-solid fa-hospital text-brand"></i>
                                ${facility?.name || 'Unknown Facility'}
                            </div>
                            <div class="bg-orange-100 text-orange-700 px-3 py-1 rounded-full text-xs font-bold">
                                ${count} incident${count > 1 ? 's' : ''}
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                `;
                
                incidents.forEach((incident) => {
                    html += `
                        <div class="border border-slate-200 rounded-lg p-4 hover:border-brand hover:shadow-md transition-all">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-xs font-bold text-brand">
                                    <i class="fa-solid fa-hashtag"></i> ${incident.reference_number}
                                </span>
                                <span class="text-xs text-slate-500">
                                    ${new Date(incident.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}
                                </span>
                            </div>
                            
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-xs">
                                <div>
                                    <div class="text-slate-500 font-semibold mb-1">Employee</div>
                                    <div class="text-slate-700">${incident.first_name} ${incident.surname}</div>
                                </div>
                                <div>
                                    <div class="text-slate-500 font-semibold mb-1">Description</div>
                                    <div class="text-slate-700">${incident.incident_description ? incident.incident_description.substring(0, 60) + '...' : 'No description'}</div>
                                </div>
                                <div>
                                    <div class="text-slate-500 font-semibold mb-1">Status</div>
                                    <div class="text-slate-700">${formatStatus(incident.status)}</div>
                                </div>
                                <div>
                                    <div class="text-slate-500 font-semibold mb-1">Action</div>
                                    <a href="/incidents/${incident.id}" target="_blank" class="text-brand font-semibold hover:underline">
                                        <i class="fa-solid fa-external-link-alt"></i> View Case
                                    </a>
                                </div>
                            </div>
                        </div>
                    `;
                });
                
                html += `
                        </div>
                    </div>
                `;
            });
            
            modalBody.innerHTML = html;
        }
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal(event) {
        const modal = document.getElementById('patternModal');
        if (!event || event.target === modal || event.type === 'click') {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    function formatStatus(status) {
        const statusMap = {
            'PENDING_SUPERVISOR_VERIFICATION': 'Pending Verification',
            'VERIFIED_SUPERVISOR': 'Supervisor Verified',
            'ESCALATED_TO_HR': 'Escalated to HR',
            'RESOLVED': 'Resolved',
            'CLOSED': 'Closed',
            'REJECTED': 'Rejected'
        };
        return statusMap[status] || status?.replace(/_/g, ' ') || 'Pending';
    }
    </script>
</body>
</html>
