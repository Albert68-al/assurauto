<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ActivityLog;
use App\Models\Contrat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::with('roles');

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filtre par rôle
        if ($request->filled('role')) {
            $query->role($request->role);
        }

        // Filtre par statut
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->whereNotNull('email_verified_at');
            } else {
                $query->whereNull('email_verified_at');
            }
        }

        $users = $query->latest()->paginate(15);
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'exists:roles,name'],
            'address' => ['nullable', 'string', 'max:500'],
            'city' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:20'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'address' => $validated['address'] ?? null,
            'city' => $validated['city'] ?? null,
            'postal_code' => $validated['postal_code'] ?? null,
            'email_verified_at' => now(),
        ]);

        // Assigner le rôle
        $user->assignRole($validated['role']);

        // Journaliser l'activité
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'create_user',
            'description' => "Création de l'utilisateur {$user->name} ({$user->email})",
            'subject_type' => User::class,
            'subject_id' => $user->id,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load('roles', 'permissions');
        $activities = ActivityLog::where('subject_type', User::class)
            ->where('subject_id', $user->id)
            ->orWhere('user_id', $user->id)
            ->latest()
            ->paginate(20);

        return view('admin.users.show', compact('user', 'activities'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['required', 'string', 'max:20', 'unique:users,phone,' . $user->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'exists:roles,name'],
            'address' => ['nullable', 'string', 'max:500'],
            'city' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:20'],
        ]);

        $oldData = $user->toArray();

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'] ?? null,
            'city' => $validated['city'] ?? null,
            'postal_code' => $validated['postal_code'] ?? null,
        ]);

        // Mettre à jour le mot de passe si fourni
        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($validated['password'])]);
        }

        // Synchroniser les rôles
        $user->syncRoles([$validated['role']]);

        // Journaliser l'activité
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'update_user',
            'description' => "Modification de l'utilisateur {$user->name} ({$user->email})",
            'subject_type' => User::class,
            'subject_id' => $user->id,
            'ip_address' => $request->ip(),
            'properties' => json_encode([
                'old' => $oldData,
                'new' => $user->toArray(),
            ]),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Empêcher la suppression de son propre compte
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $userName = $user->name;
        $userEmail = $user->email;

        try {
            DB::beginTransaction();

            // Supprimer d'abord les contrats liés s'ils existent
            if (Schema::hasTable('contrats')) {
                $user->contrats()->delete();
            }

            // Supprimer les autres relations si nécessaire
            if ($user->vehicules()->exists()) {
                $user->vehicules()->delete();
            }

            if ($user->wallet) {
                $user->wallet()->delete();
            }

            // Supprimer l'utilisateur
            $user->delete();

            // Journaliser la suppression
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'delete_user',
                'description' => "Suppression de l'utilisateur {$userName} ({$userEmail})",
                'subject_type' => User::class,
                'subject_id' => $user->id,
                'ip_address' => request()->ip(),
            ]);

            DB::commit();

            return redirect()->route('admin.users.index')
                ->with('success', 'Utilisateur supprimé avec succès.');

        } catch (Exception $e) {
            DB::rollBack();
            \Log::error('Erreur lors de la suppression de l\'utilisateur: ' . $e->getMessage());
            
            return redirect()->route('admin.users.index')
                ->with('error', 'Une erreur est survenue lors de la suppression de l\'utilisateur.');
        }
    }

    /**
     * Toggle user status (activate/deactivate)
     */
    public function toggleStatus(User $user)
    {
        if ($user->email_verified_at) {
            $user->update(['email_verified_at' => null]);
            $status = 'désactivé';
        } else {
            $user->update(['email_verified_at' => now()]);
            $status = 'activé';
        }

        // Journaliser l'activité
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'toggle_user_status',
            'description' => "L'utilisateur {$user->name} a été {$status}",
            'subject_type' => User::class,
            'subject_id' => $user->id,
            'ip_address' => request()->ip(),
        ]);

        return redirect()->back()
            ->with('success', "Utilisateur {$status} avec succès.");
    }
}
