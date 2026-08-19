<?php

declare(strict_types=1);

final class AuthController
{
    public static function loginForm(): void
    {
        if (Auth::check()) {
            redirect('/');
        }
        render('login', ['title' => 'Log in']);
    }

    public static function login(): void
    {
        verify_csrf();
        $username = trim((string) ($_POST['username'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        if (Auth::attempt($username, $password)) {
            flash('success', 'Welcome back, ' . Auth::displayName() . '!');
            redirect('/');
        }
        flash('error', 'Hmm, that username or password did not match.');
        redirect('/login');
    }

    public static function signupForm(): void
    {
        if (Auth::check()) {
            redirect('/');
        }
        render('signup', ['title' => 'Sign up']);
    }

    public static function signup(): void
    {
        verify_csrf();
        $username = (string) ($_POST['username'] ?? '');
        $password = (string) ($_POST['password'] ?? '');
        $displayName = (string) ($_POST['display_name'] ?? '');
        $errors = Auth::register($username, $password, $displayName);
        if ($errors) {
            flash('error', $errors[0]);
            redirect('/signup');
        }
        flash('success', 'You are in! Time to earn some cards.');
        redirect('/');
    }

    public static function logout(): void
    {
        verify_csrf();
        Auth::logout();
        flash('success', 'See you next time.');
        redirect('/');
    }
}
