<?php
$action = $_GET['action'] ?? '/';

match ($action){
    '/'         => (new DashboardController)->index(),


    'login'     => (new AutherController)->login(),
};