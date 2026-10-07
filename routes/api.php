<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\SuperAdminTicketController;
use App\Http\Controllers\LeaveInformationController;
use App\Http\Controllers\MedicalBoardController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group.
|
*/

// ─── Authenticated API Routes ───────────────────────────────────────────────
Route::middleware(['auth:web'])->group(function () {
    // ─── Ticket Management Routes ────────────────────────────────────────
    Route::post('/tickets', [TicketController::class, 'store'])->name('api.tickets.store');
    
    // Non-parameterized routes must come before parameterized routes
    Route::get('/tickets/my', [TicketController::class, 'myTickets'])->name('api.tickets.my');
    
    // Parameterized routes
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('api.tickets.show');
    Route::get('/tickets/{ticket}/replies', [TicketController::class, 'getReplies'])->name('api.tickets.get_replies');
    Route::post('/tickets/{ticket}/replies', [TicketController::class, 'storeReply'])->name('api.tickets.store_reply');
    Route::put('/tickets/{ticket}/status', [TicketController::class, 'updateStatus'])->name('api.tickets.update_status');

    // Super admin only routes
    Route::middleware('role:super_admin')->group(function () {
        Route::get('/tickets', [SuperAdminTicketController::class, 'index'])->name('api.tickets.index');
        Route::get('/tickets/pending-count', [SuperAdminTicketController::class, 'getPendingCount'])->name('api.tickets.pending_count');
    });

    // ─── Leave Information Routes ───────────────────────────────────────
    Route::get('/incidents/{incident}/leave-information', [LeaveInformationController::class, 'show'])->name('api.incidents.leave_information.show');
    Route::post('/incidents/{incident}/leave-information', [LeaveInformationController::class, 'store'])->name('api.incidents.leave_information.store');

    // ─── Medical Board Routes ───────────────────────────────────────────
    Route::middleware(['role:medical_board,pmd,cmo', 'permission:manage_incidents'])->group(function () {
        Route::post('/medical-board/incidents/{incident}/decision', [MedicalBoardController::class, 'submitDecision'])->name('api.medical_board.submit_decision');
    });
});
