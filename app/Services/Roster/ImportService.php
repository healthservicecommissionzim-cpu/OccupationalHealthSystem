<?php

namespace App\Services\Roster;

use App\Models\User;
use App\Models\Facility;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class ImportService
{
    protected RosterService $rosterService;
    protected AssignmentService $assignmentService;

    /**
     * Constructor to inject dependencies.
     *
     * @param RosterService $rosterService
     * @param AssignmentService $assignmentService
     */
    public function __construct(
        RosterService $rosterService,
        AssignmentService $assignmentService
    ) {
        $this->rosterService = $rosterService;
        $this->assignmentService = $assignmentService;
    }

    /**
     * Import rosters from CSV file
     * 
     * Supports backdated assignments - allows dates in the past without
     * retroactively modifying existing incident attributions.
     * 
     * @param string $csvFilePath
     * @param int $createdByUserId
     * @return array ['success' => bool, 'imported' => int, 'errors' => array]
     */
    public function importRostersCsv(string $csvFilePath, int $createdByUserId): array
    {
        $errors = [];
        $validRecords = [];
        $rowNumber = 0;

        // Parse CSV file
        if (!file_exists($csvFilePath)) {
            return [
                'success' => false,
                'imported' => 0,
                'errors' => [
                    [
                        'row' => 0,
                        'field' => 'file',
                        'message' => 'CSV file not found'
                    ]
                ]
            ];
        }

        $file = fopen($csvFilePath, 'r');
        
        // Read header row
        $headers = fgetcsv($file);
        
        // Validate headers
        $expectedHeaders = ['user_email', 'staff_type', 'primary_facility_name', 'start_date', 'end_date'];
        if (!$headers || array_map('strtolower', array_map('trim', $headers)) !== $expectedHeaders) {
            fclose($file);
            return [
                'success' => false,
                'imported' => 0,
                'errors' => [
                    [
                        'row' => 0,
                        'field' => 'headers',
                        'message' => 'Invalid CSV headers. Expected: ' . implode(', ', $expectedHeaders)
                    ]
                ]
            ];
        }

        // Parse data rows
        while (($row = fgetcsv($file)) !== false) {
            $rowNumber++;
            
            // Skip empty rows
            if (empty(array_filter($row))) {
                continue;
            }

            // Parse row data
            $data = [
                'user_email' => trim($row[0] ?? ''),
                'staff_type' => trim($row[1] ?? ''),
                'primary_facility_name' => trim($row[2] ?? ''),
                'start_date' => trim($row[3] ?? ''),
                'end_date' => trim($row[4] ?? ''),
            ];

            // Validate and collect errors
            $rowErrors = $this->validateCsvRow($data, $rowNumber);
            
            if (!empty($rowErrors)) {
                $errors = array_merge($errors, $rowErrors);
            } else {
                // Lookup foreign keys and store validated record
                try {
                    $user = User::where('email', $data['user_email'])->first();
                    $facility = Facility::where('name', $data['primary_facility_name'])->first();

                    if (!$user) {
                        $errors[] = [
                            'row' => $rowNumber,
                            'field' => 'user_email',
                            'message' => "User with email '{$data['user_email']}' not found"
                        ];
                        continue;
                    }

                    if (!$facility) {
                        $errors[] = [
                            'row' => $rowNumber,
                            'field' => 'primary_facility_name',
                            'message' => "Facility with name '{$data['primary_facility_name']}' not found"
                        ];
                        continue;
                    }

                    $validRecords[] = [
                        'user_id' => $user->id,
                        'staff_type' => $data['staff_type'],
                        'primary_facility_id' => $facility->id,
                        'start_date' => Carbon::parse($data['start_date']),
                        'end_date' => !empty($data['end_date']) ? Carbon::parse($data['end_date']) : null,
                    ];
                } catch (\Exception $e) {
                    $errors[] = [
                        'row' => $rowNumber,
                        'field' => 'general',
                        'message' => 'Error processing row: ' . $e->getMessage()
                    ];
                }
            }
        }

        fclose($file);

        // If there are validation errors, return them without committing
        if (!empty($errors)) {
            return [
                'success' => false,
                'imported' => 0,
                'errors' => $errors
            ];
        }

        // All records valid - commit within transaction
        $importedCount = 0;
        
        try {
            DB::transaction(function () use ($validRecords, $createdByUserId, &$importedCount) {
                foreach ($validRecords as $record) {
                    $this->rosterService->createRoster(
                        $record['user_id'],
                        $record['staff_type'],
                        $record['primary_facility_id'],
                        $record['start_date'],
                        $record['end_date'],
                        $createdByUserId
                    );
                    $importedCount++;
                }
            });

            return [
                'success' => true,
                'imported' => $importedCount,
                'errors' => []
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'imported' => 0,
                'errors' => [
                    [
                        'row' => 0,
                        'field' => 'transaction',
                        'message' => 'Failed to import records: ' . $e->getMessage()
                    ]
                ]
            ];
        }
    }

    /**
     * Validate a single CSV row
     * 
     * @param array $data
     * @param int $rowNumber
     * @return array
     */
    protected function validateCsvRow(array $data, int $rowNumber): array
    {
        $errors = [];

        // Validate user_email
        if (empty($data['user_email'])) {
            $errors[] = [
                'row' => $rowNumber,
                'field' => 'user_email',
                'message' => 'User email is required'
            ];
        } elseif (!filter_var($data['user_email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = [
                'row' => $rowNumber,
                'field' => 'user_email',
                'message' => 'Invalid email format'
            ];
        }

        // Validate staff_type
        $validStaffTypes = ['permanent', 'locum', 'student', 'rotational'];
        if (empty($data['staff_type'])) {
            $errors[] = [
                'row' => $rowNumber,
                'field' => 'staff_type',
                'message' => 'Staff type is required'
            ];
        } elseif (!in_array(strtolower($data['staff_type']), $validStaffTypes)) {
            $errors[] = [
                'row' => $rowNumber,
                'field' => 'staff_type',
                'message' => 'Invalid staff type. Must be one of: ' . implode(', ', $validStaffTypes)
            ];
        }

        // Validate primary_facility_name
        if (empty($data['primary_facility_name'])) {
            $errors[] = [
                'row' => $rowNumber,
                'field' => 'primary_facility_name',
                'message' => 'Primary facility name is required'
            ];
        }

        // Validate start_date
        if (empty($data['start_date'])) {
            $errors[] = [
                'row' => $rowNumber,
                'field' => 'start_date',
                'message' => 'Start date is required'
            ];
        } else {
            try {
                $startDate = Carbon::parse($data['start_date']);
                
                // Check 2-year future limit
                if ($startDate->greaterThan(Carbon::now()->addYears(2))) {
                    $errors[] = [
                        'row' => $rowNumber,
                        'field' => 'start_date',
                        'message' => 'Start date cannot be more than 2 years in the future'
                    ];
                }
            } catch (\Exception $e) {
                $errors[] = [
                    'row' => $rowNumber,
                    'field' => 'start_date',
                    'message' => 'Invalid date format for start_date'
                ];
            }
        }

        // Validate end_date (optional)
        if (!empty($data['end_date'])) {
            try {
                $endDate = Carbon::parse($data['end_date']);
                
                if (!empty($data['start_date'])) {
                    try {
                        $startDate = Carbon::parse($data['start_date']);
                        if ($endDate->lessThanOrEqualTo($startDate)) {
                            $errors[] = [
                                'row' => $rowNumber,
                                'field' => 'end_date',
                                'message' => 'End date must be after start date'
                            ];
                        }
                    } catch (\Exception $e) {
                        // Start date error already captured above
                    }
                }
            } catch (\Exception $e) {
                $errors[] = [
                    'row' => $rowNumber,
                    'field' => 'end_date',
                    'message' => 'Invalid date format for end_date'
                ];
            }
        }

        return $errors;
    }
}
