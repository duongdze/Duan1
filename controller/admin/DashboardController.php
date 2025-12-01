<?php
class DashboardController
{
    protected $model;
    public function __construct()
    {
        $this->model = new Booking();
    }
    public function index()
    {
        // Kiểm tra quyền: chỉ admin và hdv (qtv) được truy cập
        check_role(['admin', 'guide']);


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

        // Booking chờ xác nhận
        $bookings = $this->model->getAll();
        $pendingBookings = $bookingModel->getRecentPendingBookings(5);

        // Truyền dữ liệu sang view
        $data = [
            'monthlyRevenue' => $monthlyRevenue,
            'newBookings' => $newBookings,
            'ongoingTours' => $ongoingTours,
            'newCustomers' => $newCustomers,
            'pendingBookings' => $pendingBookings
        ];

        require_once PATH_VIEW_ADMIN . 'default/header.php';
        require_once PATH_VIEW_ADMIN . 'default/sidebar.php';
        require_once PATH_VIEW_ADMIN . 'dashboard.php';
        require_once PATH_VIEW_ADMIN . 'default/footer.php';
    }
}
