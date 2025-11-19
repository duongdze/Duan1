<?php
class BookingController
{
    protected $model;

    public function __construct()
    {
        $this->model = new Booking();
    }

    public function index()
    {
        $bookings = $this->model->getAll();
        require_once PATH_VIEW_ADMIN . 'pages/bookings/index.php';
    }

    public function create()
    {
        require_once 'views/admin/bookings/create.php';
    }

    public function store()
    {
        // Implementation
    }

    public function edit()
    {
        // Implementation
    }

    public function update()
    {
        // Implementation
    }
}
