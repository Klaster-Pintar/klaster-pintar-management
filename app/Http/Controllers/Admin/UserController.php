<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of SUPERADMIN users only (Owner Management)
     */
    public function index(Request $request)
    {
        $query = User::query()
            ->where('role', 'SUPERADMIN') // Only SUPERADMIN
            ->whereNull('deleted_at');

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new SUPERADMIN (Owner)
     */
    public function create()
    {
        $roles = ['SUPERADMIN']; // Only SUPERADMIN
        $statuses = ['VERIFIED', 'PENDING', 'INACTIVE'];
        $genders = ['M' => 'Laki-laki', 'F' => 'Perempuan'];

        return view('admin.users.create', compact('roles', 'statuses', 'genders'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $data = $request->validated();

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        // Hash password
        $data['password'] = Hash::make($data['password']);

        // Set roles as JSON array
        $data['roles'] = [$data['role']]; // Single role converted to array

        // Set created_id and updated_id
        $data['created_id'] = Auth::id();
        $data['updated_id'] = Auth::id();

        // Default values
        $data['active_flag'] = $request->has('active_flag') ? 1 : 0;
        $data['suspend'] = 0;
        $data['blocked'] = 0;

        User::create($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        // Prevent showing SUPERADMIN
        if ($user->role === 'SUPERADMIN') {
            abort(403, 'Unauthorized access');
        }

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        // Prevent editing SUPERADMIN
        if ($user->role === 'SUPERADMIN') {
            abort(403, 'Unauthorized access');
        }

        $roles = ['ADMIN', 'USER', 'VIEWER'];
        $statuses = ['PENDING', 'VERIFIED', 'REJECTED'];
        $genders = ['M' => 'Male', 'F' => 'Female'];

        return view('admin.users.edit', compact('user', 'roles', 'statuses', 'genders'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user)
    {
        // Prevent updating SUPERADMIN
        if ($user->role === 'SUPERADMIN') {
            abort(403, 'Unauthorized access');
        }

        $data = $request->validated();

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        // Hash password only if provided
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        // Set roles as JSON array
        if (isset($data['role'])) {
            $data['roles'] = [$data['role']];
        }

        // Set updated_id
        $data['updated_id'] = Auth::id();

        // Handle checkboxes
        $data['active_flag'] = $request->has('active_flag') ? 1 : 0;
        $data['suspend'] = $request->has('suspend') ? 1 : 0;
        $data['blocked'] = $request->has('blocked') ? 1 : 0;

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Prevent deleting SUPERADMIN
        if ($user->role === 'SUPERADMIN') {
            abort(403, 'Unauthorized access');
        }

        // Soft delete - set deleted_id
        $user->update(['deleted_id' => Auth::id()]);
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus!');
    }

    /**
     * Toggle user status (activate/suspend/block)
     */
    public function toggleStatus(Request $request, User $user)
    {
        // Prevent toggling SUPERADMIN
        if ($user->role === 'SUPERADMIN') {
            abort(403, 'Unauthorized access');
        }

        $action = $request->input('action');

        switch ($action) {
            case 'activate':
                $user->update([
                    'active_flag' => 1,
                    'suspend' => 0,
                    'blocked' => 0,
                    'updated_id' => Auth::id()
                ]);
                $message = 'User berhasil diaktifkan!';
                break;

            case 'suspend':
                $user->update([
                    'suspend' => 1,
                    'suspend_expired_at' => now()->addDays(30),
                    'updated_id' => Auth::id()
                ]);
                $message = 'User berhasil disuspend!';
                break;

            case 'block':
                $user->update([
                    'blocked' => 1,
                    'blocked_id' => Auth::id(),
                    'updated_id' => Auth::id()
                ]);
                $message = 'User berhasil diblokir!';
                break;

            default:
                return back()->with('error', 'Invalid action');
        }

        return back()->with('success', $message);
    }
}
