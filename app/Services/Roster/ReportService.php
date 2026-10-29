<?php

namespace App\Services\Roster;

use App\Models\Incident;
use App\Models\StaffRoster;
use App\Models\RosterAssignment;
use App\Models\Facility;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * ReportService
 * 
 * Generates cost center reports and workforce analytics for the roster management system.
 * Provides insights into incident cost attribution, workforce distribution, and staff movements.
 */
class ReportService
{
    /**
     * Get incidents grouped by cost center facility.
     * 
     * @param Carbon|null $startDate
     * @param Carbon|null $endDate
     * @return Collection
     */
    public function getIncidentsByCostCenter(?Carbon $startDate = null, ?Carbon $endDate = null): Collection
    {
        $query = Incident::query()
            ->with([
                'workerPrimaryFacility',
                'facility',
                'costCenterFacility',
                'rosterAssignment.staffRoster.user',
                'rosterAssignment.facility',
                'rosterAssignment.costCenterFacility'
            ])
            ->whereNotNull('cost_center_facility_id');

        // Apply date range filters if provided
        if ($startDate) {
            $query->where('incident_date', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('incident_date', '<=', $endDate);
        }

        // Get all incidents
        $incidents = $query->get();

        // Group by cost_center_facility_id
        return $incidents->groupBy('cost_center_facility_id')->map(function ($groupedIncidents, $costCenterFacilityId) {
            return [
                'cost_center_facility_id' => $costCenterFacilityId,
                'cost_center_facility_name' => $groupedIncidents->first()->costCenterFacility->name ?? 'Unknown',
                'incidents' => $groupedIncidents->map(function ($incident) {
                    $data = [
                        'id' => $incident->id,
                        'reference_number' => $incident->reference_number,
                        'worker_name' => trim($incident->first_name . ' ' . $incident->surname),
                        'incident_date' => $incident->incident_date,
                        'worker_primary_facility_id' => $incident->worker_primary_facility_id,
                        'worker_primary_facility_name' => $incident->workerPrimaryFacility->name ?? 'Unknown',
                        'incident_facility_id' => $incident->facility_id,
                        'incident_facility_name' => $incident->facility->name ?? 'Unknown',
                        'cost_center_facility_id' => $incident->cost_center_facility_id,
                        'cost_center_facility_name' => $incident->costCenterFacility->name ?? 'Unknown',
                        'is_rotational_staff' => $incident->is_rotational_staff,
                    ];

                    // Include roster assignment context for rotational incidents
                    if ($incident->is_rotational_staff && $incident->rosterAssignment) {
                        $assignment = $incident->rosterAssignment;
                        $data['roster_context'] = [
                            'assignment_id' => $assignment->id,
                            'assignment_type' => $assignment->assignment_type,
                            'assignment_start_date' => $assignment->assignment_start_date,
                            'assignment_end_date' => $assignment->assignment_end_date,
                            'staff_name' => $assignment->staffRoster->user->name ?? 'Unknown',
                            'assigned_facility_name' => $assignment->facility->name ?? 'Unknown',
                            'cost_center_name' => $assignment->costCenterFacility->name ?? 'Unknown',
                        ];
                    }

                    return $data;
                }),
                'total_incidents' => $groupedIncidents->count(),
                'rotational_incidents' => $groupedIncidents->where('is_rotational_staff', true)->count(),
            ];
        })->values();
    }

    /**
     * Get cost center summary statistics.
     * 
     * @param Carbon|null $startDate
     * @param Carbon|null $endDate
     * @return array
     */
    public function getCostCenterSummaryStatistics(?Carbon $startDate = null, ?Carbon $endDate = null): array
    {
        $query = Incident::query()
            ->whereNotNull('cost_center_facility_id');

        // Apply date range filters if provided
        if ($startDate) {
            $query->where('incident_date', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('incident_date', '<=', $endDate);
        }

        // Calculate total incident count
        $totalIncidents = $query->count();

        // Calculate rotational incident count (is_rotational_staff=true)
        $rotationalIncidents = (clone $query)->where('is_rotational_staff', true)->count();

        // Group by cost_center_facility_id and count per facility
        $costCenterDistribution = (clone $query)
            ->select('cost_center_facility_id', DB::raw('COUNT(*) as incident_count'))
            ->with('costCenterFacility')
            ->groupBy('cost_center_facility_id')
            ->get()
            ->map(function ($item) {
                return [
                    'cost_center_facility_id' => $item->cost_center_facility_id,
                    'cost_center_facility_name' => $item->costCenterFacility->name ?? 'Unknown',
                    'incident_count' => $item->incident_count,
                ];
            })
            ->sortByDesc('incident_count')
            ->values()
            ->toArray();

        return [
            'total_incidents' => $totalIncidents,
            'rotational_incidents' => $rotationalIncidents,
            'non_rotational_incidents' => $totalIncidents - $rotationalIncidents,
            'rotational_percentage' => $totalIncidents > 0 
                ? round(($rotationalIncidents / $totalIncidents) * 100, 2) 
                : 0,
            'cost_center_distribution' => $costCenterDistribution,
        ];
    }

    /**
     * Get workforce distribution by facility for a specific date.
     * 
     * @param Carbon $date
     * @return Collection
     */
    public function getWorkforceDistribution(Carbon $date): Collection
    {
        // Get all facilities
        $facilities = Facility::with('facilityType', 'district', 'province')->get();

        $distribution = $facilities->map(function ($facility) use ($date) {
            // Count permanent staff (where primary_facility_id = facility)
            $permanentStaff = StaffRoster::where('primary_facility_id', $facility->id)
                ->where('active', true)
                ->where('start_date', '<=', $date)
                ->where(function ($query) use ($date) {
                    $query->whereNull('end_date')
                        ->orWhere('end_date', '>=', $date);
                })
                ->count();

            // Count rotational staff (active assignments at this facility on the date)
            $rotationalStaff = RosterAssignment::where('facility_id', $facility->id)
                ->where('assignment_start_date', '<=', $date)
                ->where('assignment_end_date', '>=', $date)
                ->where('status', '!=', 'cancelled')
                ->count();

            // Total staff at facility
            $totalStaff = $permanentStaff + $rotationalStaff;

            return [
                'facility_id' => $facility->id,
                'facility_name' => $facility->name,
                'facility_type' => $facility->facilityType->name ?? 'Unknown',
                'district' => $facility->district->name ?? 'Unknown',
                'province' => $facility->province->name ?? 'Unknown',
                'permanent_staff_count' => $permanentStaff,
                'rotational_staff_count' => $rotationalStaff,
                'total_staff_count' => $totalStaff,
            ];
        });

        // Filter out facilities with no staff and sort by total staff count
        return $distribution
            ->filter(fn($item) => $item['total_staff_count'] > 0)
            ->sortByDesc('total_staff_count')
            ->values();
    }

    /**
     * Get rotation movements (incoming and outgoing staff) for a facility.
     * 
     * @param int $facilityId
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return array
     */
    public function getRotationMovements(int $facilityId, Carbon $startDate, Carbon $endDate): array
    {
        // Query assignments starting within date range (incoming)
        $incoming = RosterAssignment::with([
            'staffRoster.user',
            'staffRoster.primaryFacility',
            'facility',
            'costCenterFacility'
        ])
            ->where('facility_id', $facilityId)
            ->whereBetween('assignment_start_date', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->orderBy('assignment_start_date')
            ->get()
            ->map(function ($assignment) {
                return [
                    'assignment_id' => $assignment->id,
                    'staff_name' => $assignment->staffRoster->user->name ?? 'Unknown',
                    'staff_type' => $assignment->staffRoster->staff_type ?? 'Unknown',
                    'primary_facility' => $assignment->staffRoster->primaryFacility->name ?? 'Unknown',
                    'assignment_type' => $assignment->assignment_type,
                    'assignment_start_date' => $assignment->assignment_start_date,
                    'assignment_end_date' => $assignment->assignment_end_date,
                    'cost_center_facility' => $assignment->costCenterFacility->name ?? 'Unknown',
                    'status' => $assignment->status,
                ];
            });

        // Query assignments ending within date range (outgoing)
        $outgoing = RosterAssignment::with([
            'staffRoster.user',
            'staffRoster.primaryFacility',
            'facility',
            'costCenterFacility'
        ])
            ->where('facility_id', $facilityId)
            ->whereBetween('assignment_end_date', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->orderBy('assignment_end_date')
            ->get()
            ->map(function ($assignment) {
                return [
                    'assignment_id' => $assignment->id,
                    'staff_name' => $assignment->staffRoster->user->name ?? 'Unknown',
                    'staff_type' => $assignment->staffRoster->staff_type ?? 'Unknown',
                    'primary_facility' => $assignment->staffRoster->primaryFacility->name ?? 'Unknown',
                    'assignment_type' => $assignment->assignment_type,
                    'assignment_start_date' => $assignment->assignment_start_date,
                    'assignment_end_date' => $assignment->assignment_end_date,
                    'cost_center_facility' => $assignment->costCenterFacility->name ?? 'Unknown',
                    'status' => $assignment->status,
                ];
            });

        return [
            'incoming' => $incoming,
            'outgoing' => $outgoing,
        ];
    }

    /**
     * Export roster data to CSV format.
     * 
     * @param array $filters
     * @return string
     */
    public function exportRosterData(array $filters): string
    {
        // Build query for rosters and assignments
        $query = RosterAssignment::query()
            ->with([
                'staffRoster.user',
                'staffRoster.primaryFacility.district',
                'facility.district',
                'costCenterFacility'
            ]);

        // Apply filters
        if (!empty($filters['start_date'])) {
            $startDate = Carbon::parse($filters['start_date']);
            $query->where('assignment_start_date', '>=', $startDate);
        }

        if (!empty($filters['end_date'])) {
            $endDate = Carbon::parse($filters['end_date']);
            $query->where('assignment_end_date', '<=', $endDate);
        }

        if (!empty($filters['facility_id'])) {
            $query->where('facility_id', $filters['facility_id']);
        }

        if (!empty($filters['staff_type'])) {
            $query->whereHas('staffRoster', function ($q) use ($filters) {
                $q->where('staff_type', $filters['staff_type']);
            });
        }

        if (!empty($filters['district_id'])) {
            $query->whereHas('facility.district', function ($q) use ($filters) {
                $q->where('id', $filters['district_id']);
            });
        }

        // Get assignments
        $assignments = $query->orderBy('assignment_start_date', 'desc')->get();

        // Build CSV content
        $csvData = [];
        
        // Add headers
        $csvData[] = [
            'Staff Name',
            'Staff Type',
            'Primary Facility',
            'Assigned Facility',
            'Assignment Start Date',
            'Assignment End Date',
            'Cost Center',
            'Assignment Type',
            'Status',
            'District',
        ];

        // Add data rows
        foreach ($assignments as $assignment) {
            $csvData[] = [
                $assignment->staffRoster->user->name ?? '',
                ucfirst(str_replace('_', ' ', $assignment->staffRoster->staff_type ?? '')),
                $assignment->staffRoster->primaryFacility->name ?? '',
                $assignment->facility->name ?? '',
                $assignment->assignment_start_date ? $assignment->assignment_start_date->format('Y-m-d') : '',
                $assignment->assignment_end_date ? $assignment->assignment_end_date->format('Y-m-d') : '',
                $assignment->costCenterFacility->name ?? '',
                ucfirst(str_replace('_', ' ', $assignment->assignment_type)),
                ucfirst($assignment->status),
                $assignment->facility->district->name ?? '',
            ];
        }

        // Convert to CSV string
        $output = fopen('php://temp', 'r+');
        foreach ($csvData as $row) {
            fputcsv($output, $row);
        }
        rewind($output);
        $csvContent = stream_get_contents($output);
        fclose($output);

        return $csvContent;
    }
}
