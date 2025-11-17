<?php

class DashboardController
{
    public function index()
    {
        // Kiểm tra quyền: chỉ admin và hdv (qtv) được truy cập
        check_role(['admin', 'hdv']);

        // Load models for dashboard stats
        require_once 'models/Tour.php';
        require_once 'models/Booking.php';
        require_once 'models/Guide.php';

        $tourModel = new Tour();
        $bookingModel = new Booking();
        $guideModel = new Guide();

        // Tính doanh thu tháng hiện tại
        $currentMonth = date('m');
        $currentYear = date('Y');
        $monthlyRevenue = $bookingModel->getMonthlyRevenue($currentMonth, $currentYear);

        // Số booking mới (trong tháng)
        $newBookings = $bookingModel->getNewBookingsThisMonth($currentMonth, $currentYear);

        // Tour đang chạy (trạng thái active/ongoing)
        $ongoingTours = $tourModel->getOngoingTours();

        // Khách hàng mới (trong tháng)
        $newCustomers = $bookingModel->getNewCustomersThisMonth($currentMonth, $currentYear);

        // Truyền dữ liệu sang view
        $data = [
            'monthlyRevenue' => $monthlyRevenue,
            'newBookings' => $newBookings,
            'ongoingTours' => $ongoingTours,
            'newCustomers' => $newCustomers
        ];

        require_once PATH_VIEW_ADMIN . 'default/header.php';
        require_once PATH_VIEW_ADMIN . 'default/sidebar.php';
        require_once PATH_VIEW_ADMIN . 'dashboard.php';
        require_once PATH_VIEW_ADMIN . 'default/footer.php';
    }
}
