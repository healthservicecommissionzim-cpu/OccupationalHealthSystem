@extends('layouts.app')

@section('title', 'Bulk Import Staff Rosters')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-800">
                    <i class="fas fa-file-upload text-blue-600 mr-3"></i>
                    Bulk Import Staff Rosters
                </h1>
                <p class="text-slate-600 mt-2">Import multiple roster records from CSV file</p>
            </div>
            <a href="{{ route('system.roster.manage-rosters.index') }}" class="text-slate-600 hover:text-slate-700 font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Back to Rosters
            </a>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
        </div>
    @endif

    <!-- Import Validation Errors -->
    @if(session('import_errors'))
        <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-6">
            <h3 class="text-lg font-semibold text-red-800 mb-4">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                Import Validation Errors
            </h3>
            <p class="text-red-700 mb-4">
                The following errors were found in your CSV file. Please correct them and try again.
            </p>
            <div class="bg-white rounded border border-red-200 max-h-96 overflow-y-auto">
                <table class="w-full text-sm">
                    <thead class="bg-red-100 sticky top-0">
                        <tr>
                            <th class="px-4 py-2 text-left font-semibold text-red-800">Row</th>
                            <th class="px-4 py-2 text-left font-semibold text-red-800">Field</th>
                            <th class="px-4 py-2 text-left font-semibold text-red-800">Error Message</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-red-100">
                        @foreach(session('import_errors') as $error)
                            <tr class="hover:bg-red-50">
                                <td class="px-4 py-2 text-red-700 font-medium">{{ $error['row'] }}</td>
                                <td class="px-4 py-2 text-red-700">{{ $error['field'] }}</td>
                                <td class="px-4 py-2 text-red-700">{{ $error['message'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- CSV Format Requirements -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-blue-800 mb-4">
            <i class="fas fa-info-circle mr-2"></i>
            CSV Format Requirements
        </h3>
        <div class="text-blue-700 space-y-3">
            <p class="font-medium">Your CSV file must include the following columns (in this exact order):</p>
            <ul class="list-disc list-inside space-y-2 ml-4">
                <li><strong>user_email</strong> - Email address of the staff member (must match an existing user)</li>
                <li><strong>staff_type</strong> - One of: permanent, locum, student, rotational</li>
                <li><strong>primary_facility_name</strong> - Name of the primary facility (must match exactly)</li>
                <li><strong>start_date</strong> - Start date in YYYY-MM-DD format (cannot be more than 2 years in the future)</li>
                <li><strong>end_date</strong> - End date in YYYY-MM-DD format (optional, leave blank if ongoing)</li>
            </ul>
            <div class="mt-4 p-4 bg-white rounded border border-blue-200">
                <p class="font-semibold text-blue-800 mb-2">Example CSV format:</p>
                <pre class="text-xs text-slate-700 overflow-x-auto">user_email,staff_type,primary_facility_name,start_date,end_date
john.doe@example.com,rotational,Harare Central Hospital,2024-01-01,2024-12-31
jane.smith@example.com,locum,Parirenyatwa Hospital,2024-06-01,
student@med.edu,student,Sally Mugabe Hospital,2024-09-01,2025-05-31</pre>
            </div>
            <div class="mt-4 pt-4 border-t border-blue-200">
                <p class="flex items-center gap-2">
                    <i class="fas fa-download text-blue-600"></i>
                    <a href="{{ route('system.roster.import.template') }}" 
                        class="text-blue-600 hover:text-blue-700 font-medium underline">
                        Download CSV Template with Example Data
                    </a>
                </p>
            </div>
        </div>
    </div>

    <!-- Important Notes -->
    <div class="bg-amber-50 border border-amber-200 rounded-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-amber-800 mb-3">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            Important Notes
        </h3>
        <ul class="list-disc list-inside space-y-2 text-amber-700">
            <li><strong>All records are validated before import</strong> - if any record has an error, no records will be imported</li>
            <li><strong>Backdated assignments are allowed</strong> - you can import historical roster data</li>
            <li><strong>Backdated assignments do not modify existing incidents</strong> - cost attribution is not retroactively updated</li>
            <li><strong>Facility names must match exactly</strong> - check spelling and capitalization</li>
            <li><strong>Users must already exist in the system</strong> - create user accounts before importing rosters</li>
        </ul>
    </div>

    <!-- Upload Form -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-slate-800 mb-6">
            <i class="fas fa-cloud-upload-alt text-blue-600 mr-2"></i>
            Upload CSV File
        </h2>
        
        <form method="POST" action="{{ route('system.roster.import.process') }}" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-700 mb-2">
                    Select CSV File
                    <span class="text-red-600">*</span>
                </label>
                <div class="flex items-center gap-4">
                    <input type="file" 
                        name="csv_file" 
                        accept=".csv,.txt"
                        required
                        class="block w-full text-sm text-slate-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-lg file:border-0
                            file:text-sm file:font-semibold
                            file:bg-blue-50 file:text-blue-700
                            hover:file:bg-blue-100
                            cursor-pointer">
                    <div class="text-sm text-slate-500">
                        Max file size: 10MB
                    </div>
                </div>
                @error('csv_file')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                    <i class="fas fa-upload mr-2"></i>Import Rosters
                </button>
                <a href="{{ route('system.roster.manage-rosters.index') }}" 
                    class="px-6 py-2 border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <!-- Tips Section -->
    <div class="mt-6 bg-slate-50 border border-slate-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-slate-800 mb-3">
            <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
            Tips for Successful Import
        </h3>
        <ul class="list-disc list-inside space-y-2 text-slate-700">
            <li>Download the template file to ensure correct format</li>
            <li>Save your file as CSV (Comma Separated Values) format from Excel or Google Sheets</li>
            <li>Ensure there are no extra blank rows at the end of your file</li>
            <li>Verify all user email addresses exist in the system before importing</li>
            <li>Double-check facility names match exactly (case-sensitive)</li>
            <li>Use YYYY-MM-DD format for all dates (e.g., 2024-01-15)</li>
            <li>Leave end_date column empty (not "N/A" or "null") for ongoing assignments</li>
            <li>Test with a small batch first (5-10 records) before importing large datasets</li>
        </ul>
    </div>
</div>
@endsection
