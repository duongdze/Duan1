<?php
class ReportController
{
    public function index()
    {
        require_once 'views/admin/reports/index.php';
    }

    public function financial()
    {
        require_once 'views/admin/reports/financial.php';
    }

    public function bookings()
    {
        require_once 'views/admin/reports/bookings.php';
    }

    public function feedback()
    {
        require_once 'views/admin/reports/feedback.php';
    }
}
