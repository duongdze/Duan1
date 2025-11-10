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
}