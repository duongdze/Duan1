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
        // Load customers and tours data for dropdown
        $customerModel = new UserModel();
        $tourModel = new Tour();
        
        $customers = $customerModel->select('*', "role = :role", ['role' => 'customer']);
        $tours = $tourModel->select('*', null, [], 'name ASC');
        
        require_once PATH_VIEW_ADMIN . 'pages/bookings/create.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?mode=admin&action=bookings/create');
            exit;
        }

        try {
            // Validate inputs
            $customer_id = $_POST['customer_id'] ?? null;
            $tour_id = $_POST['tour_id'] ?? null;
            $booking_date = $_POST['booking_date'] ?? null;
            $total_price = $_POST['total_price'] ?? null;
            $status = $_POST['status'] ?? 'cho_xac_nhan';
            $notes = $_POST['notes'] ?? '';

            // Basic validation
            if (!$customer_id || !$tour_id || !$booking_date || !$total_price || !$status) {
                $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin bắt buộc';
                header('Location: ?mode=admin&action=bookings/create');
                exit;
            }

            // Insert booking
            $booking_id = $this->model->insert([
                'customer_id' => $customer_id,
                'tour_id' => $tour_id,
                'booking_date' => $booking_date,
                'total_price' => $total_price,
                'status' => $status,
                'notes' => $notes
            ]);

            // Insert booking customers (companions)
            if (!empty($_POST['companion_name'])) {
                $bookingCustomerModel = new BookingCustomer();
                
                foreach ($_POST['companion_name'] as $index => $name) {
                    if (!empty($name)) {
                        $bookingCustomerModel->insert([
                            'booking_id' => $booking_id,
                            'name' => $name,
                            'gender' => $_POST['companion_gender'][$index] ?? '',
                            'birth_date' => $_POST['companion_birth_date'][$index] ?? null,
                            'phone' => $_POST['companion_phone'][$index] ?? '',
                            'id_card' => $_POST['companion_id_card'][$index] ?? '',
                            'special_request' => $_POST['companion_special_request'][$index] ?? '',
                            'room_type' => $_POST['companion_room_type'][$index] ?? ''
                        ]);
                    }
                }
            }

            $_SESSION['success'] = 'Tạo đơn đặt tour thành công';
            header('Location: ?mode=admin&action=bookings');
            exit;
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            header('Location: ?mode=admin&action=bookings/create');
            exit;
        }
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
