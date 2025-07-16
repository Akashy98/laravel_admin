<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Laratables\AppointmentLaratables;
use App\Models\Appointment;
use App\Models\AppointmentSetting;
use App\Models\Astrologer;
use Illuminate\Http\Request;
use Freshbitsweb\Laratables\Laratables;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    /**
     * Display appointments listing with status filtering
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        $statuses = [
            'all' => 'All Appointments',
            'pending' => 'Awaiting Response',
            'accepted' => 'Confirmed Bookings',
            'in_progress' => 'Active Sessions',
            'completed' => 'Completed Sessions',
            'cancelled' => 'Cancelled Bookings',
            'expired' => 'Expired Requests',
            'no_astrologer' => 'No Astrologer Available'
        ];

        // Get counts for each status
        $counts = [];
        foreach (array_keys($statuses) as $statusKey) {
            if ($statusKey === 'all') {
                $counts[$statusKey] = Appointment::count();
            } else {
                $counts[$statusKey] = Appointment::where('status', $statusKey)->count();
            }
        }

        return view('admin.appointments.index', compact('status', 'statuses', 'counts'));
    }

    /**
     * Get appointments data for DataTables using Laratables
     */
    public function list(Request $request)
    {
        return Laratables::recordsOf(Appointment::class, AppointmentLaratables::class);
    }

    /**
     * Show appointment details
     */
    public function show($id)
    {
        $appointment = Appointment::with([
            'user',
            'astrologer.user',
            'originalAstrologer.user',
            'astrologer.skills.category',
            'originalAstrologer.skills.category'
        ])->findOrFail($id);

        return view('admin.appointments.show', compact('appointment'));
    }

    /**
     * Update appointment status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,accepted,in_progress,completed,cancelled,expired,no_astrologer',
            'notes' => 'nullable|string|max:1000'
        ]);

        $appointment = Appointment::findOrFail($id);
        $oldStatus = $appointment->status;
        $newStatus = $request->status;

        // Update status and add notes if provided
        $appointment->update([
            'status' => $newStatus,
            'astrologer_notes' => $request->notes ?: $appointment->astrologer_notes
        ]);

        // Handle specific status changes
        switch ($newStatus) {
            case 'accepted':
                if (!$appointment->accepted_at) {
                    $appointment->update(['accepted_at' => now()]);
                }
                break;
            case 'in_progress':
                if (!$appointment->started_at) {
                    $appointment->update(['started_at' => now()]);
                }
                break;
            case 'completed':
                if (!$appointment->ended_at) {
                    $appointment->update(['ended_at' => now()]);
                }
                break;
            case 'cancelled':
                $appointment->update([
                    'cancelled_by' => 'admin',
                    'cancellation_reason' => $request->notes ?: 'Cancelled by admin'
                ]);
                break;
        }

        // Return JSON response for AJAX requests
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Appointment status updated successfully',
                'appointment' => $appointment->fresh()
            ]);
        }

        return redirect()->back()->with('success', 'Appointment status updated successfully');
    }

    /**
     * Assign astrologer to appointment
     */
    public function assignAstrologer(Request $request, $id)
    {
        $request->validate([
            'astrologer_id' => 'required|exists:astrologers,id'
        ]);

        $appointment = Appointment::findOrFail($id);

        // Check if astrologer is available
        $astrologer = Astrologer::find($request->astrologer_id);

        if (!$astrologer || $astrologer->status !== 'approved') {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Selected astrologer is not available'
                ], 400);
            }
            return redirect()->back()->with('error', 'Selected astrologer is not available');
        }

        // Check for conflicts if it's a scheduled appointment
        if ($appointment->isScheduled()) {
            $conflict = Appointment::where('astrologer_id', $request->astrologer_id)
                ->whereIn('status', ['pending', 'accepted', 'in_progress'])
                ->where('id', '!=', $appointment->id)
                ->where(function ($query) use ($appointment) {
                    $query->whereBetween('scheduled_at', [
                        $appointment->scheduled_at,
                        $appointment->scheduled_at->copy()->addMinutes($appointment->duration_minutes)
                    ])
                    ->orWhereBetween(DB::raw('DATE_ADD(scheduled_at, INTERVAL duration_minutes MINUTE)'), [
                        $appointment->scheduled_at,
                        $appointment->scheduled_at->copy()->addMinutes($appointment->duration_minutes)
                    ]);
                })
                ->exists();

            if ($conflict) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Astrologer has a conflicting appointment at this time'
                    ], 400);
                }
                return redirect()->back()->with('error', 'Astrologer has a conflicting appointment at this time');
            }
        }

        // Assign astrologer
        $appointment->update([
            'astrologer_id' => $request->astrologer_id,
            'status' => 'accepted',
            'accepted_at' => now()
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Astrologer assigned successfully',
                'appointment' => $appointment->fresh()
            ]);
        }

        return redirect()->back()->with('success', 'Astrologer assigned successfully');
    }

    /**
     * Get appointment statistics
     */
    public function statistics()
    {
        $stats = [
            'total' => Appointment::count(),
            'pending' => Appointment::where('status', 'pending')->count(),
            'accepted' => Appointment::where('status', 'accepted')->count(),
            'in_progress' => Appointment::where('status', 'in_progress')->count(),
            'completed' => Appointment::where('status', 'completed')->count(),
            'cancelled' => Appointment::where('status', 'cancelled')->count(),
            'expired' => Appointment::where('status', 'expired')->count(),
            'no_astrologer' => Appointment::where('status', 'no_astrologer')->count(),
        ];

        // Revenue statistics
        $revenue = [
            'total_revenue' => Appointment::where('payment_status', 'paid')->sum('amount_paid'),
            'today_revenue' => Appointment::where('payment_status', 'paid')
                ->whereDate('created_at', today())
                ->sum('amount_paid'),
            'this_month_revenue' => Appointment::where('payment_status', 'paid')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('amount_paid'),
        ];

        // Service type statistics
        $serviceStats = Appointment::selectRaw('service_type, COUNT(*) as count')
            ->groupBy('service_type')
            ->get()
            ->keyBy('service_type');

        // Booking type statistics
        $bookingStats = Appointment::selectRaw('booking_type, COUNT(*) as count')
            ->groupBy('booking_type')
            ->get()
            ->keyBy('booking_type');

        return view('admin.appointments.statistics', compact('stats', 'revenue', 'serviceStats', 'bookingStats'));
    }

    /**
     * Export appointments
     */
    public function export(Request $request)
    {
        $status = $request->get('status', 'all');

        $query = Appointment::with(['user', 'astrologer.user', 'originalAstrologer.user']);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $appointments = $query->get();

        $filename = 'appointments_' . $status . '_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($appointments) {
            $file = fopen('php://output', 'w');

            // CSV headers
            fputcsv($file, [
                'ID', 'User', 'Astrologer', 'Service Type', 'Booking Type',
                'Status', 'Scheduled At', 'Duration', 'Base Amount', 'Final Amount',
                'Amount Paid', 'Payment Status', 'Rating', 'Created At'
            ]);

            foreach ($appointments as $appointment) {
                fputcsv($file, [
                    $appointment->id,
                    $appointment->user ? $appointment->user->name : 'N/A',
                    $appointment->astrologer ? $appointment->astrologer->user->name : 'N/A',
                    $appointment->service_type,
                    $appointment->booking_type,
                    $appointment->status,
                    $appointment->scheduled_at ? $appointment->scheduled_at->format('Y-m-d H:i:s') : 'N/A',
                    $appointment->duration_minutes . ' min',
                    $appointment->base_amount,
                    $appointment->final_amount,
                    $appointment->amount_paid,
                    $appointment->payment_status,
                    $appointment->rating ?: 'N/A',
                    $appointment->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Remove the specified appointment
     */
    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);

        try {
            $appointment->delete();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Appointment deleted successfully'
                ]);
            }

            return redirect()->route('admin.appointments.index')->with('success', 'Appointment deleted successfully');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting appointment: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Error deleting appointment: ' . $e->getMessage());
        }
    }
}
