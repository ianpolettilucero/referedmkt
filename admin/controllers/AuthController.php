<?php
namespace Admin\Controllers;

use Admin\AdminView;
use Core\Auth;
use Core\Csrf;
use Core\Flash;
use Core\RateLimiter;

final class AuthController
{
    public function showLogin(): void
    {
        if (Auth::check()) {
            header('Location: /admin/dashboard', true, 302);
            return;
        }
        $view = new AdminView();
        echo $view->render('auth/login', [
            'csrf_token' => Csrf::token(),
            'flashes'    => Flash::consume(),
            'page_title' => 'Login',
        ]);
    }

    public function login(): void
    {
        Csrf::requireValid();
        $email = trim((string)($_POST['email'] ?? ''));
        $pass  = (string)($_POST['password'] ?? '');

        if ($email === '' || $pass === '') {
            Flash::error('Email y contraseña son requeridos.');
            header('Location: /admin/login', true, 302);
            return;
        }

        if (!RateLimiter::check($email)) {
            // Mensaje generico para no filtrar si el limite es por IP o por email.
            Flash::error('Demasiados intentos fallidos. Probá de nuevo en 15 minutos.');
            header('Location: /admin/login', true, 302);
            return;
        }

        $ok = Auth::attempt($email, $pass);
        RateLimiter::record($email, $ok);
        RateLimiter::maybeGC();

        if (!$ok) {
            // Delay fijo ante fallo (suma una barrera al brute force distribuido).
            usleep(400000);
            Flash::error('Credenciales invalidas.');
            header('Location: /admin/login', true, 302);
            return;
        }

        Flash::success('Bienvenido.');
        header('Location: /admin/dashboard', true, 302);
    }

    public function logout(): void
    {
        Csrf::requireValid();
        Auth::logout();
        header('Location: /admin/login', true, 302);
    }
}
