<?php

class DashboardController
{
    public function index() 
    {
        // Check if user is logged in
        if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
            header('Location: ' . BASE_URL_ADMIN . '&action=login');
            exit;
        }
        
        $view = 'dashboard';
        require_once PATH_VIEW_ADMIN . 'dashboard.php';
    }
}