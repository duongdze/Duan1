<?php

class DashboardController
{
    public function index()
    {
        // Kiểm tra quyền: chỉ admin và hdv (qtv) được truy cập
        check_role(['admin', 'hdv']);


        $tourModel = new Tour();
        $bookingModel = new Booking();
        $guideModel = new Guide();

        // Summary stats
        $totalTours = (int)$tourModel->count();
        $totalBookings = (int)$bookingModel->count();

        $revenueRow = $bookingModel->select('IFNULL(SUM(total_price), 0) as total_revenue');
        $totalRevenue = isset($revenueRow[0]['total_revenue']) ? $revenueRow[0]['total_revenue'] : 0;

        $totalGuides = (int)$guideModel->count();

        // Recent bookings
        $recentBookings = $bookingModel->select('*', null, [], 'booking_date DESC', 5);

        require_once PATH_VIEW_ADMIN . 'default/header.php';
        require_once PATH_VIEW_ADMIN . 'default/sidebar.php';
        require_once PATH_VIEW_ADMIN . 'dashboard.php';
        require_once PATH_VIEW_ADMIN . 'default/footer.php';
    }
}
