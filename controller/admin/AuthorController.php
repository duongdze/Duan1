<?php
class AuthorController
{
    public function login()
    {
        require_once PATH_VIEW_ADMIN . 'auth/login.php';
    }

    public function loginProcess()
    {
        header('Location: ' . BASE_URL_ADMIN);
    }

    public function logout()
    {
        // Destroy session and redirect to login
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }
        session_destroy();
        header('Location: ?action=login');
        exit;
    }
}
