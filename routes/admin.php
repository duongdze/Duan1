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

match ($action) {
    // Dashboard
    '/'                     => (new DashboardController)->index(),

    // Auth
    'login'                 => (new AuthorController)->login(),
    'loginProcess'          => (new AuthorController)->loginProcess(),
    'logout'                => (new AuthorController)->logout(),
    'account'               => (new AuthorController)->accountInfo(),

    // Tours Management 
    'tours'                 => (new TourController)->index(),
    'tours/create'          => (new TourController)->create(),
    'tours/store'           => (new TourController)->store(),
    'tours/edit'            => (new TourController)->edit(),
    'tours/update'          => (new TourController)->update(),
    'tours/delete'          => (new TourController)->delete(),
    'tours/detail'          => (new TourController)->detail(),
    'tours/toggle-status'   => (new TourController)->toggleStatus(),
    'tours/toggle-featured' => (new TourController)->toggleFeatured(),
    'tours/bulk-update-status' => (new TourController)->bulkUpdateStatus(),
    'tours/bulk-delete'     => (new TourController)->bulkDelete(),
    'tours/search'          => (new TourController)->search(),
    'tours/by-status'       => (new TourController)->getByStatus(),
    'tours_categories'      => (new TourCategoryController)->index(),
    'tours_categories/create' => (new TourCategoryController)->create(),
    'tours_categories/store'  => (new TourCategoryController)->store(),
    'tours_categories/edit'   => (new TourCategoryController)->edit(),
    'tours_categories/update' => (new TourCategoryController)->update(),
    'tours_categories/delete' => (new TourCategoryController)->delete(),
    'tours_categories/toggle-status' => (new TourCategoryController)->toggleStatus(),
    'tours_categories/ajax' => (new TourCategoryController)->getCategoriesAjax(),

    // Tour Versions
    'tours_versions'        => (new TourVersionController)->index(),
    'tours_versions/create' => (new TourVersionController)->create(),
    'tours_versions/store'  => (new TourVersionController)->store(),
    'tours_versions/edit'   => (new TourVersionController)->edit(),
    'tours_versions/update' => (new TourVersionController)->update(),
    'tours_versions/delete' => (new TourVersionController)->delete(),
    'tours_versions/toggle-status' => (new TourVersionController)->toggleStatus(),


    'tours/itineraries'     => (new ItineraryController)->index(),
    'tours/itineraries/create' => (new ItineraryController)->create(),
    'tours/itineraries/store'  => (new ItineraryController)->store(),
    'tours/itineraries/delete' => (new ItineraryController)->delete(),

    // Tour Logs
    'tours_logs'        => (new TourLogController)->index(),
    'tours_logs/create' => (new TourLogController)->create(),
    'tours_logs/store'  => (new TourLogController)->store(),
    'tours_logs/edit'   => (new TourLogController)->edit(),
    'tours_logs/update' => (new TourLogController)->update(),
    'tours_logs/delete' => (new TourLogController)->delete(),

    // Bookings
    'bookings'             => (new BookingController)->index(),
    'bookings/create'      => (new BookingController)->create(),
    'bookings/store'       => (new BookingController)->store(),
    'bookings/edit'        => (new BookingController)->edit(),
    'bookings/update'      => (new BookingController)->update(),
    'bookings/delete'      => (new BookingController)->delete(),
    'bookings/detail'      => (new BookingController)->detail(),

    // Guides
    'guides'               => (new GuideController)->index(),
    'guides/create'        => (new GuideController)->create(),
    'guides/store'         => (new GuideController)->store(),
    'guides/detail'        => (new GuideController)->detail(),
    'guides/edit'          => (new GuideController)->edit(),
    'guides/update'        => (new GuideController)->update(),
    'guides/delete'        => (new GuideController)->delete(),

    // Guides Work
    'guide/schedule'   => (new GuideWorkController)->schedule(),
    'guide/tourDetail' => (new GuideWorkController)->tourDetail(),

    // Suppliers
    'suppliers'            => (new SupplierController)->index(),
    'suppliers/create'     => (new SupplierController)->create(),
    'suppliers/store'      => (new SupplierController)->store(),
    // Reports
    'reports'              => (new ReportController)->index(),
    'reports/financial'    => (new ReportController)->financial(),
    'reports/bookings'     => (new ReportController)->bookings(),
    'reports/feedback'     => (new ReportController)->feedback(),
};
