<?php

namespace App\Controllers\Admin;

use App\Models\AuditLog;
use App\Models\Role;
use App\Models\User;
use Core\Request;
use Core\Response;

/**
 * Creatsify Studio - User, Role & Audit Log Controller (Cheezify Architecture)
 */
class UsersController
{
    public function index(Request $request): Response
    {
        $users = User::all();
        $roles = Role::all();

        return view('admin/users/index', [
            'users' => $users,
            'roles' => $roles,
            'meta_title' => 'User & Role Management | Creatsify Admin'
        ]);
    }

    public function create(Request $request): Response
    {
        $roles = Role::all();

        return view('admin/users/create', [
            'roles' => $roles,
            'meta_title' => 'New User | Creatsify Admin'
        ]);
    }

    public function store(Request $request): Response
    {
        $name = trim((string)$request->input('name', ''));
        $email = trim((string)$request->input('email', ''));
        $password = (string)$request->input('password', '');
        $role = trim((string)$request->input('role', 'admin'));
        $phone = trim((string)$request->input('phone', ''));

        if (empty($name) || empty($email) || empty($password)) {
            flash('error', 'Name, email, and password are required.');
            return Response::redirect('/admin/users/create');
        }

        if (User::findByEmail($email)) {
            flash('error', 'A user with that email already exists.');
            return Response::redirect('/admin/users/create');
        }

        $id = User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role' => $role,
            'phone' => $phone,
            'status' => 'active'
        ]);

        audit_log('create_user', 'user', $id, "Created user: {$name} ({$role})");
        flash('success', "User '{$name}' created successfully!");

        return Response::redirect('/admin/users');
    }

    public function edit(Request $request, string $id): Response
    {
        $user = User::findById((int)$id);
        if (!$user) {
            flash('error', 'User not found.');
            return Response::redirect('/admin/users');
        }

        $roles = Role::all();

        return view('admin/users/edit', [
            'user' => $user,
            'roles' => $roles,
            'meta_title' => "Edit {$user['name']} | Creatsify Admin"
        ]);
    }

    public function update(Request $request, string $id): Response
    {
        $user = User::findById((int)$id);
        if (!$user) {
            flash('error', 'User not found.');
            return Response::redirect('/admin/users');
        }

        $name = trim((string)$request->input('name', ''));
        $email = trim((string)$request->input('email', ''));
        $password = (string)$request->input('password', '');
        $role = trim((string)$request->input('role', $user['role']));
        $phone = trim((string)$request->input('phone', ''));
        $status = trim((string)$request->input('status', 'active'));

        $updateData = [
            'name' => $name,
            'email' => $email,
            'role' => $role,
            'phone' => $phone,
            'status' => $status
        ];

        if (!empty($password)) {
            $updateData['password'] = $password;
        }

        User::update((int)$id, $updateData);

        audit_log('update_user', 'user', (int)$id, "Updated user: {$name}");
        flash('success', "User '{$name}' updated successfully!");

        return Response::redirect('/admin/users');
    }

    public function delete(Request $request, string $id): Response
    {
        // Prevent deleting oneself
        if ((int)$id === auth_id()) {
            flash('error', 'You cannot delete your own account.');
            return Response::redirect('/admin/users');
        }

        User::delete((int)$id);
        audit_log('delete_user', 'user', (int)$id, "Deleted user #{$id}");
        flash('success', 'User deleted successfully.');

        return Response::redirect('/admin/users');
    }

    /**
     * Audit Logs Security Viewer
     */
    public function auditLogs(Request $request): Response
    {
        $page = max(1, (int)$request->get('page', 1));
        $limit = 50;
        $offset = ($page - 1) * $limit;

        $logs = AuditLog::paginate($limit, $offset);
        $total = AuditLog::count();

        return view('admin/users/audit_logs', [
            'logs' => $logs,
            'total' => $total,
            'page' => $page,
            'totalPages' => ceil($total / $limit),
            'meta_title' => 'Security Audit Logs | Creatsify Admin'
        ]);
    }
}
