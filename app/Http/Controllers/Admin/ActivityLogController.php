<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of activity logs.
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with('user');

        // Filtre par action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filtre par utilisateur
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filtre par date
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Recherche dans la description
        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        $logs = $query->latest()->paginate(20);

        // Liste des actions pour le filtre
        $actions = ActivityLog::select('action')->distinct()->pluck('action');

        return view('admin.activity-logs.index', compact('logs', 'actions'));
    }

    /**
     * Remove the specified activity log.
     */
    public function destroy(ActivityLog $activityLog)
    {
        $activityLog->delete();

        return redirect()->route('admin.activity-logs.index')
            ->with('success', 'Journal d\'activité supprimé avec succès.');
    }

    /**
     * Clear all activity logs.
     */
    public function clear(Request $request)
    {
        // Journaliser cette action avant de tout supprimer
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'clear_activity_logs',
            'description' => 'Suppression de tous les journaux d\'activité',
            'ip_address' => $request->ip(),
        ]);

        // Supprimer tous les logs sauf celui qu'on vient de créer
        ActivityLog::where('action', '!=', 'clear_activity_logs')
            ->orWhereNull('action')
            ->delete();

        return redirect()->route('admin.activity-logs.index')
            ->with('success', 'Tous les journaux d\'activité ont été supprimés.');
    }
}
