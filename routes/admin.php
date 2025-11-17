<?php
// Include controllers
// require_once 'controller/admin/AuthorController.php';
// require_once 'controller/admin/DashboardController.php';
// require_once 'controller/admin/TourController.php';
// require_once 'controller/admin/TourVersionController.php';
// require_once 'controller/admin/BookingController.php';
// require_once 'controller/admin/GuideController.php';
// require_once 'controller/admin/SupplierController.php';
// require_once 'controller/admin/ReportController.php';

$action = $_GET['action'] ?? '/';

match ($action){
    // Dashboard
    '/'                     => (new DashboardController)->index(),
    
    // Auth
    'login'                 => (new AuthorController)->login(),
    'loginProcess'          => (new AuthorController)->loginProcess(),
    'logout'                => (new AuthorController)->logout(),
    
    // Tours Management 
    'tours'                 => (new TourController)->index(),
    'tours/create'          => (new TourController)->create(),
    'tours/store'           => (new TourController)->store(),
    'tours/edit'            => (new TourController)->edit(),
    'tours/update'          => (new TourController)->update(),
    'tours/delete'          => (new TourController)->delete(),
    'tours/versions'        => (new TourVersionController)->index(),
    
    // Bookings
    'bookings'             => (new BookingController)->index(),
    'bookings/create'      => (new BookingController)->create(),
    'bookings/store'       => (new BookingController)->store(),
    'bookings/edit'        => (new BookingController)->edit(),
    'bookings/update'      => (new BookingController)->update(),
    
    // Guides
    'guides'               => (new GuideController)->index(),
    'guides/create'        => (new GuideController)->create(), 
    'guides/store'         => (new GuideController)->store(),
    
    // Suppliers
    'suppliers'            => (new SupplierController)->index(),
    'suppliers/create'     => (new SupplierController)->create(),
    'suppliers/store'      => (new SupplierController)->store(),
    
    // Reports
    'reports/financial'    => (new ReportController)->financial(),
    'reports/bookings'     => (new ReportController)->bookings(),
    'reports/feedback'     => (new ReportController)->feedback(),
};