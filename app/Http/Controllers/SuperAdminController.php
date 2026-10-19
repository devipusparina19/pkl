<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SuperAdminController extends Controller
{
    public function index()
    {
        $totalAdmin = User::where('role', 'admin')->count();
        $totalPimpinan = User::where('role', 'pimpinan')->count();

        $totalPengguna = User::whereIn('role', [
            'admin',
            'pimpinan',
        ])->count();

        return view('super-admin.dashboard', compact(
            'totalAdmin',
            'totalPimpinan',
            'totalPengguna'
        ));
    }

    public function users(Request $request)
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'role' => ['nullable', Rule::in(['admin', 'pimpinan'])],
            'status' => ['nullable', Rule::in(['active', 'inactive'])],
        ]);

        $query = User::query()
            ->whereIn('role', ['admin', 'pimpinan']);

        if (! empty($filters['role'])) {
            $query->where('role', $filters['role']);
        }

        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->where(
                'is_active',
                $filters['status'] === 'active'
            );
        }

        if (! empty($filters['search'])) {
            $search = $filters['search'];

            $query->where(function ($subQuery) use ($search) {
                $subQuery->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query
            ->orderBy('role')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('super-admin.users.index', compact('users'));
    }

    public function createUser()
    {
        return view('super-admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => [
                'required',
                'string',
                'max:100',
                'unique:users,username',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
            'role' => ['required', Rule::in(['admin', 'pimpinan'])],
        ]);

        User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'is_active' => true,
        ]);

        return redirect()
            ->route('super-admin.users.index')
            ->with('success', 'Akun berhasil ditambahkan.');
    }

    public function editUser(User $user)
    {
        $this->ensureManageableUser($user);

        return view('super-admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $this->ensureManageableUser($user);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => [
                'required',
                'string',
                'max:100',
                Rule::unique('users', 'username')->ignore($user->id),
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'role' => ['required', Rule::in(['admin', 'pimpinan'])],
            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);

        $user->name = $validated['name'];
        $user->username = $validated['username'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()
            ->route('super-admin.users.index')
            ->with('success', 'Akun berhasil diperbarui.');
    }

    /**
     * Mengaktifkan atau menonaktifkan akun Admin/Pimpinan.
     */
    public function toggleUserStatus(User $user)
    {
        $this->ensureManageableUser($user);

        if ((int) $user->id === (int) auth()->id()) {
            return back()->with(
                'error',
                'Anda tidak dapat mengubah status akun sendiri.'
            );
        }

        $user->is_active = ! $user->is_active;
        $user->save();

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()
            ->route('super-admin.users.index')
            ->with('success', "Akun {$user->name} berhasil {$status}.");
    }

    public function destroyUser(User $user)
    {
        $this->ensureManageableUser($user);

        if ((int) $user->id === (int) auth()->id()) {
            return back()->with(
                'error',
                'Anda tidak dapat menghapus akun sendiri.'
            );
        }

        $user->delete();

        return redirect()
            ->route('super-admin.users.index')
            ->with('success', 'Akun berhasil dihapus.');
    }

    private function ensureManageableUser(User $user): void
    {
        abort_unless(
            in_array($user->role, ['admin', 'pimpinan'], true),
            404
        );
    }
}