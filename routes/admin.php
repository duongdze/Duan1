<?php
$action = $_GET['action'] ?? '/';

match ($action){
    '/'                     => (new DashboardController)->index(),


    'login'                 => (new AuthorController)->login(),
    'loginProcess'          => (new AuthorController)->loginProcess(),
    'logout'                => (new AuthorController)->logout(),
};