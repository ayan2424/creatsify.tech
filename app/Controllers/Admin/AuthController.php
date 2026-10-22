<?php

namespace App\Controllers\Admin;

use App\Models\User;
use Core\Request;
use Core\Response;

/**
 * Creatsify Studio - Admin Authentication Controller
 */
class AuthController
{
    public function showLogin(Request $request): Response
    {
        if (auth()) {
            return Response::redirect('/admin');
        }

        return view('admin/auth/login', [
            'meta_title' => 'Sign In | Creatsify Admin Desk'
        ]);
    }

    public function login(Request $request): Response
    {
        $email = trim((string)$request->input('email', ''));
        $password = (string)$request->input('password', '');

        $user = User::findByEmail($email);

        if (!$user || !User::verifyPassword($password, $user['password'])) {
            flash('error', 'Invalid email address or password.');
            return Response::redirect('/admin/login');
        }

        if ($user['status'] !== 'active') {
            flash('error', 'Your account has been deactivated. Please contact the administrator.');
            return Response::redirect('/admin/login');
        }

        // Set session
        session()->regenerate();
        session()->set('auth_user', [
            'id' => (int)$user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role'],
            'avatar' => $user['avatar'] ?: '/assets/brand/creatsify-icon.png'
        ]);

        audit_log('user_login', 'user', (int)$user['id'], 'User logged into admin desk.');

        flash('success', "Welcome back, {$user['name']}!");
        return Response::redirect('/admin');
    }

    /**
     * 1-Click Demo Login for marketplace buyers/evaluators
     */
    public function demoLogin(Request $request): Response
    {
        $superAdmin = User::findByEmail('ayan@creatsify.tech');
        if (!$superAdmin) {
            // Fallback to first user in system
            $users = User::all();
            $superAdmin = $users[0] ?? null;
        }

        if ($superAdmin) {
            session()->regenerate();
            session()->set('auth_user', [
                'id' => (int)$superAdmin['id'],
                'name' => $superAdmin['name'],
                'email' => $superAdmin['email'],
                'role' => $superAdmin['role'],
                'avatar' => $superAdmin['avatar'] ?: '/assets/brand/creatsify-icon.png'
            ]);

            audit_log('demo_login', 'user', (int)$superAdmin['id'], 'Logged in via 1-click demo access.');
            flash('success', 'Logged in successfully as Super Admin (Demo Mode)!');
        }

        return Response::redirect('/admin');
    }

    public function logout(Request $request): Response
    {
        if (auth_id()) {
            audit_log('user_logout', 'user', auth_id(), 'User logged out.');
        }

        session()->remove('auth_user');
        session()->destroy();

        flash('success', 'You have been logged out.');
        return Response::redirect('/admin/login');
    }
}
