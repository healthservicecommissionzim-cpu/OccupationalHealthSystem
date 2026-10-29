<?php

namespace App\Services\Roster;

use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ValidationService
{
    /**
     * Valid staff types
     */
    private const STAFF_TYPES = ['permanent', 'locum', 'student', 'rotational'];

    /**
     * Valid assignment types
     */
    private const ASSIGNMENT_TYPES = ['rotation', 'locum_contract', 'student_placement'];

    /**
     * Valid assignment statuses
     */
    private const ASSIGNMENT_STATUSES = ['active', 'completed', 'cancelled'];

    /**
     * Maximum future date limit in years
     */
    private const FUTURE_DATE_LIMIT_YEARS = 2;

    /**
     * Validate roster creation data
     * 
     * @param array $data
     * @return array (validated data)
     * @throws ValidationException
     */
    public function validateRosterData(array $data): array
    {
        $validator = Validator::make($data, [
            'user_id' => 'required|integer|exists:users,id',
            'staff_type' => 'required|string|in:' . implode(',', self::STAFF_TYPES),
            'primary_facility_id' => 'required|integer|exists:facilities,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'notes' => 'nullable|string|max:1000',
            'created_by_user_id' => 'required|integer|exists:users,id',
        ], [
            'user_id.required' => 'User is required',
            'user_id.exists' => 'User with ID :input does not exist',
            'staff_type.required' => 'Staff type is required',
            'staff_type.in' => 'Invalid staff type. Must be one of: ' . implode(', ', self::STAFF_TYPES),
            'primary_facility_id.required' => 'Primary facility is required',
            'primary_facility_id.exists' => 'Facility with ID :input does not exist',
            'start_date.required' => 'Start date is required',
            'start_date.date' => 'Start date must be a valid date',
            'end_date.date' => 'End date must be a valid date',
            'end_date.after' => 'End date must be after start date',
            'created_by_user_id.required' => 'Created by user is required',
            'created_by_user_id.exists' => 'Created by user with ID :input does not exist',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $validated = $validator->validated();

        // Additional validation: check start date is not more than 2 years in future
        if (isset($validated['start_date'])) {
            $this->validateStartDate(Carbon::parse($validated['start_date']));
        }

        // Additional validation: check date range if both dates provided
        if (isset($validated['start_date']) && isset($validated['end_date'])) {
            $this->validateDateRange(
                Carbon::parse($validated['start_date']),
                Carbon::parse($validated['end_date'])
            );
        }

        return $validated;
    }

    /**
     * Validate assignment creation data
     * 
     * @param array $data
     * @return array (validated data)
     * @throws ValidationException
     */
    public function validateAssignmentData(array $data): array
    {
        $validator = Validator::make($data, [
            'staff_roster_id' => 'required|integer|exists:staff_rosters,id',
            'facility_id' => 'required|integer|exists:facilities,id',
            'assignment_start_date' => 'required|date',
            'assignment_end_date' => 'required|date|after:assignment_start_date',
            'cost_center_facility_id' => 'required|integer|exists:facilities,id',
            'assignment_type' => 'required|string|in:' . implode(',', self::ASSIGNMENT_TYPES),
            'supervisor_id' => 'nullable|integer|exists:users,id',
            'status' => 'nullable|string|in:' . implode(',', self::ASSIGNMENT_STATUSES),
            'notes' => 'nullable|string|max:1000',
            'created_by_user_id' => 'required|integer|exists:users,id',
        ], [
            'staff_roster_id.required' => 'Staff roster is required',
            'staff_roster_id.exists' => 'Staff roster with ID :input does not exist',
            'facility_id.required' => 'Facility is required',
            'facility_id.exists' => 'Facility with ID :input does not exist',
            'assignment_start_date.required' => 'Assignment start date is required',
            'assignment_start_date.date' => 'Assignment start date must be a valid date',
            'assignment_end_date.required' => 'Assignment end date is required',
            'assignment_end_date.date' => 'Assignment end date must be a valid date',
            'assignment_end_date.after' => 'Assignment end date must be after assignment start date',
            'cost_center_facility_id.required' => 'Cost center facility is required',
            'cost_center_facility_id.exists' => 'Cost center facility with ID :input does not exist',
            'assignment_type.required' => 'Assignment type is required',
            'assignment_type.in' => 'Invalid assignment type. Must be one of: ' . implode(', ', self::ASSIGNMENT_TYPES),
            'supervisor_id.exists' => 'Supervisor with ID :input does not exist',
            'status.in' => 'Invalid status. Must be one of: ' . implode(', ', self::ASSIGNMENT_STATUSES),
            'created_by_user_id.required' => 'Created by user is required',
            'created_by_user_id.exists' => 'Created by user with ID :input does not exist',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $validated = $validator->validated();

        // Additional validation: check start date is not more than 2 years in future
        if (isset($validated['assignment_start_date'])) {
            $this->validateStartDate(Carbon::parse($validated['assignment_start_date']));
        }

        // Additional validation: check date range
        if (isset($validated['assignment_start_date']) && isset($validated['assignment_end_date'])) {
            $this->validateDateRange(
                Carbon::parse($validated['assignment_start_date']),
                Carbon::parse($validated['assignment_end_date'])
            );
        }

        return $validated;
    }

    /**
     * Validate that start date is not more than 2 years in future
     * 
     * @param Carbon $startDate
     * @return bool
     * @throws ValidationException
     */
    public function validateStartDate(Carbon $startDate): bool
    {
        $maxFutureDate = Carbon::now()->addYears(self::FUTURE_DATE_LIMIT_YEARS);

        if ($startDate->greaterThan($maxFutureDate)) {
            throw ValidationException::withMessages([
                'start_date' => ['Assignment start date cannot be more than ' . self::FUTURE_DATE_LIMIT_YEARS . ' years in the future']
            ]);
        }

        return true;
    }

    /**
     * Validate that end date is after start date
     * 
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return bool
     * @throws ValidationException
     */
    public function validateDateRange(Carbon $startDate, Carbon $endDate): bool
    {
        if ($endDate->lessThanOrEqualTo($startDate)) {
            throw ValidationException::withMessages([
                'end_date' => ['Assignment end date must be after start date']
            ]);
        }

        return true;
    }

    /**
     * Validate staff type enum
     * 
     * @param string $staffType
     * @return bool
     * @throws ValidationException
     */
    public function validateStaffType(string $staffType): bool
    {
        if (!in_array($staffType, self::STAFF_TYPES, true)) {
            throw ValidationException::withMessages([
                'staff_type' => ['Invalid staff type. Must be one of: ' . implode(', ', self::STAFF_TYPES)]
            ]);
        }

        return true;
    }

    /**
     * Validate assignment type enum
     * 
     * @param string $assignmentType
     * @return bool
     * @throws ValidationException
     */
    public function validateAssignmentType(string $assignmentType): bool
    {
        if (!in_array($assignmentType, self::ASSIGNMENT_TYPES, true)) {
            throw ValidationException::withMessages([
                'assignment_type' => ['Invalid assignment type. Must be one of: ' . implode(', ', self::ASSIGNMENT_TYPES)]
            ]);
        }

        return true;
    }
}
