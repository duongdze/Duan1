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
            header('Location:' . BASE_URL_ADMIN . '&action=bookings/create');
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
                header('Location:' . BASE_URL_ADMIN . '&action=bookings/create');
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
            header('Location:' . BASE_URL_ADMIN . '&action=bookings');
            exit;
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            header('Location:' . BASE_URL_ADMIN . '&action=bookings/create');
            exit;
        }
    }

    public function detail()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            $_SESSION['error'] = 'Không tìm thấy booking';
            header('Location:' . BASE_URL_ADMIN . '&action=bookings');
            exit;
        }

        $booking = $this->model->getBookingWithDetails($id);

        if (!$booking) {
            $_SESSION['error'] = 'Booking không tồn tại';
            header('Location:' . BASE_URL_ADMIN . '&action=bookings');
            exit;
        }

        // Lấy danh sách khách đi kèm
        $bookingCustomerModel = new BookingCustomer();
        $companions = $bookingCustomerModel->getByBooking($id);

        require_once PATH_VIEW_ADMIN . 'pages/bookings/detail.php';
    }

    public function edit()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            $_SESSION['error'] = 'Không tìm thấy booking';
            header('Location:' . BASE_URL_ADMIN . '&action=bookings');
            exit;
        }

        $booking = $this->model->getBookingWithDetails($id);

        if (!$booking) {
            $_SESSION['error'] = 'Booking không tồn tại';
            header('Location:' . BASE_URL_ADMIN . '&action=bookings');
            exit;
        }

        // Lấy danh sách khách đi kèm
        $bookingCustomerModel = new BookingCustomer();
        $companions = $bookingCustomerModel->getByBooking($id);

        // Load customers and tours data for dropdown
        $customerModel = new UserModel();
        $tourModel = new Tour();

        $customers = $customerModel->select('*', "role = :role", ['role' => 'customer']);
        $tours = $tourModel->select('*', null, [], 'name ASC');

        require_once PATH_VIEW_ADMIN . 'pages/bookings/edit.php';
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location:' . BASE_URL_ADMIN . '&action=bookings');
            exit;
        }

        $id = $_POST['id'] ?? null;

        if (!$id) {
            $_SESSION['error'] = 'Không tìm thấy booking';
            header('Location:' . BASE_URL_ADMIN . '&action=bookings');
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
                header('Location:' . BASE_URL_ADMIN . '&action=bookings/edit&id=' . $id);
                exit;
            }

            // Update booking
            $this->model->update([
                'customer_id' => $customer_id,
                'tour_id' => $tour_id,
                'booking_date' => $booking_date,
                'total_price' => $total_price,
                'status' => $status,
                'notes' => $notes
            ], 'id = :id', ['id' => $id]);

            // Update booking customers (companions)
            $bookingCustomerModel = new BookingCustomer();

            // Xóa tất cả companions cũ
            $bookingCustomerModel->deleteByBooking($id);

            // Thêm companions mới
            if (!empty($_POST['companion_name'])) {
                foreach ($_POST['companion_name'] as $index => $name) {
                    if (!empty($name)) {
                        $bookingCustomerModel->insert([
                            'booking_id' => $id,
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

            $_SESSION['success'] = 'Cập nhật đơn đặt tour thành công';
            header('Location:' . BASE_URL_ADMIN . '&action=bookings/detail&id=' . $id);
            exit;
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            header('Location:' . BASE_URL_ADMIN . '&action=bookings/edit&id=' . $id);
            exit;
        }
    }

    public function delete()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            $_SESSION['error'] = 'Không tìm thấy booking';
            header('Location:' . BASE_URL_ADMIN . '&action=bookings');
            exit;
        }

        try {
            $result = $this->model->deleteBooking($id);

            if ($result) {
                $_SESSION['success'] = 'Xóa booking thành công';
            } else {
                $_SESSION['error'] = 'Không thể xóa booking';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
        }

        header('Location:' . BASE_URL_ADMIN . '&action=bookings');
        exit;
    }
}
