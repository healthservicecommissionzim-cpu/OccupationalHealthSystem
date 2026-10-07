<?php

namespace App\Http\Controllers;

use App\Models\FirstAidRecord;
use App\Models\Incident;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class FirstAidController extends Controller
{
    /**
     * Show the token verification form.
     */
    public function tokenForm()
    {
        return view('first_aid.token_verification');
    }

    /**
     * Verify the first aid token and unlock the form.
     */
    public function verifyToken(Request $request)
    {
        $request->validate([
            'token' => 'required|string|size:4|regex:/^\d{4}$/',
        ], [
            'token.regex' => 'Token must be exactly 4 digits.',
            'token.size' => 'Token must be exactly 4 digits.',
        ]);

        $incident = Incident::verifyFirstAidToken($request->token);

        if (!$incident) {
            return back()->withErrors([
                'token' => 'Invalid or expired token. Please check the token provided to you.',
            ]);
        }

        // Token is valid, show the first aid form
        return redirect()->route('first_aid.create', ['incident_id' => $incident->id, 'token' => $request->token]);
    }

    /**
     * Show the form to create a first aid record.
     */
    public function create(Request $request)
    {
        $incident_id = $request->query('incident_id');
        $token = $request->query('token');
        
        if (!$incident_id) {
            return redirect()->route('first_aid.token_form')
                ->with('error', 'Invalid access. Please use the token verification form.');
        }

        $incident = Incident::findOrFail($incident_id);

        // Verify token matches
        if (!$token || $incident->first_aid_token !== $token) {
            return redirect()->route('first_aid.token_form')
                ->with('error', 'Invalid token. Please try again.');
        }

        // Check if token is still valid
        if (!$incident->isFirstAidTokenValid()) {
            return redirect()->route('first_aid.token_form')
                ->with('error', 'Token has expired or first aid has already been recorded for this case.');
        }

        return view('first_aid.create', compact('incident', 'token'));
    }

    /**
     * Store a new first aid record.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'incident_id' => 'required|exists:incidents,id',
            'token' => 'required|string|size:4',
            'nurse_name' => 'required|string|max:255',
            'nurse_license_number' => 'required|string|max:100',
            'injury_description' => 'required|string|min:10|max:1000',
            'first_aid_description' => 'required|string|min:20|max:2000',
            'time_administered' => 'required|date_format:Y-m-d\TH:i',
            'location' => 'required|string|max:255',
            'severity_level' => 'required|in:minor,moderate,severe',
            'notes' => 'nullable|string|max:1000',
        ]);

        $incident = Incident::findOrFail($validated['incident_id']);

        // Verify token is valid
        if ($incident->first_aid_token !== $validated['token'] || !$incident->isFirstAidTokenValid()) {
            return response()->json([
                'success' => false,
                'errors' => ['token' => ['Invalid or expired token. Your session may have timed out.']]
            ], 422);
        }

        // Create first aid record
        FirstAidRecord::create([
            'incident_id' => $incident->id,
            'nurse_name' => $validated['nurse_name'],
            'nurse_license_number' => $validated['nurse_license_number'],
            'injury_description' => $validated['injury_description'],
            'first_aid_description' => $validated['first_aid_description'],
            'time_administered' => $validated['time_administered'],
            'location' => $validated['location'],
            'severity_level' => $validated['severity_level'],
            'notes' => $validated['notes'],
        ]);

        // Mark first aid as recorded and expire token
        $incident->markFirstAidRecorded();

        return response()->json([
            'success' => true,
            'message' => 'First aid record submitted successfully.'
        ], 200);
    }

    /**
     * Show success page.
     */
    public function success()
    {
        return view('first_aid.success');
    }

    /**
     * Get a specific first aid record as JSON (for API).
     */
    public function getRecord(FirstAidRecord $record)
    {
        return response()->json([
            'success' => true,
            'record' => $record
        ]);
    }

    /**
     * Show all first aid records for an incident.
     */
    public function incident(Incident $incident)
    {
        $records = $incident->firstAidRecords()->orderBy('created_at', 'desc')->get();
        
        return view('first_aid.incident_records', compact('incident', 'records'));
    }

    /**
     * Approve a first aid record (for DMO/supervisor).
     */
    public function approve(FirstAidRecord $record, Request $request)
    {
        $this->authorize('update', $record);

        $request->validate([
            'comments' => 'nullable|string|max:500',
        ]);

        $record->approve(auth()->id(), $request->input('comments'));

        return back()->with('success', 'First aid record approved.');
    }

    /**
     * Reject a first aid record (for DMO/supervisor).
     */
    public function reject(FirstAidRecord $record, Request $request)
    {
        $this->authorize('update', $record);

        $validated = $request->validate([
            'comments' => 'required|string|max:500',
        ]);

        $record->reject(auth()->id(), $validated['comments']);

        return back()->with('success', 'First aid record rejected.');
    }

    /**
     * Download first aid record as PDF.
     */
    public function downloadPdf(FirstAidRecord $record)
    {
        $pdf = Pdf::loadView('first_aid.pdf', compact('record'));
        return $pdf->download('first-aid-record-' . $record->id . '.pdf');
    }
}
