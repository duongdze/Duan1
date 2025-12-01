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
        // Lấy thông tin user hiện tại
        $userRole = $_SESSION['user']['role'] ?? 'customer';
        $userId = $_SESSION['user']['user_id'] ?? null;

        // Lọc bookings theo role
        if ($userRole === 'guide') {
            $guideModel = new Guide();
            $guide = $guideModel->getByUserId($userId);
            $guideId = $guide['id'] ?? null;
            $bookings = $this->model->getAllByRole('guide', $guideId);
        } else {
            $bookings = $this->model->getAllByRole('admin');
        }

        // Lấy thống kê
        $stats = $this->model->getStats();

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

        // Kiểm tra quyền
        $userRole = $_SESSION['user']['role'] ?? 'customer';
        $userId = $_SESSION['user']['user_id'] ?? null;

        if (!$this->model->canUserEditBooking($id, $userId, $userRole)) {
            $_SESSION['error'] = 'Bạn không có quyền sửa booking này';
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

        // Get drivers list
        $driverModel = new Driver();
        $drivers = $driverModel->getAvailableDrivers();

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

        // Kiểm tra quyền
        $userRole = $_SESSION['user']['role'] ?? 'customer';
        $userId = $_SESSION['user']['user_id'] ?? null;

        if (!$this->model->canUserEditBooking($id, $userId, $userRole)) {
            $_SESSION['error'] = 'Bạn không có quyền sửa booking này';
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
                'driver_id' => $_POST['driver_id'] ?? null,
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

        // Kiểm tra quyền - chỉ admin mới được xóa
        $userRole = $_SESSION['user']['role'] ?? 'customer';
        if ($userRole !== 'admin') {
            $_SESSION['error'] = 'Chỉ admin mới có quyền xóa booking';
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

    /**
     * AJAX endpoint để cập nhật trạng thái booking
     */
    public function updateStatus()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }

        $bookingId = $_POST['booking_id'] ?? null;
        $newStatus = $_POST['status'] ?? null;

        if (!$bookingId || !$newStatus) {
            echo json_encode(['success' => false, 'message' => 'Thiếu thông tin bắt buộc']);
            exit;
        }

        // Kiểm tra quyền
        $userRole = $_SESSION['user']['role'] ?? 'customer';
        $userId = $_SESSION['user']['user_id'] ?? null;

        if (!$this->model->canUserEditBooking($bookingId, $userId, $userRole)) {
            echo json_encode(['success' => false, 'message' => 'Bạn không có quyền cập nhật booking này']);
            exit;
        }

        // Validate status
        $validStatuses = ['cho_xac_nhan', 'da_coc', 'hoan_tat', 'da_huy'];
        if (!in_array($newStatus, $validStatuses)) {
            echo json_encode(['success' => false, 'message' => 'Trạng thái không hợp lệ']);
            exit;
        }

        // Cập nhật trạng thái
        $result = $this->model->updateStatus($bookingId, $newStatus);

        if ($result) {
            // Lấy tên trạng thái để hiển thị
            $statusNames = [
                'cho_xac_nhan' => 'Chờ xác nhận',
                'da_coc' => 'Đã cọc',
                'hoan_tat' => 'Hoàn tất',
                'da_huy' => 'Hủy'
            ];

            echo json_encode([
                'success' => true,
                'message' => 'Cập nhật trạng thái thành công',
                'status' => $newStatus,
                'status_text' => $statusNames[$newStatus] ?? $newStatus
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Không thể cập nhật trạng thái']);
        }
        exit;
    }
    public function addCompanion()
    {
        header('Content-Type: application/json');
        // Kiểm tra quyền
        $userRole = $_SESSION['user']['role'] ?? 'customer';
        $userId = $_SESSION['user']['user_id'] ?? null;
        $bookingId = $_POST['booking_id'] ?? null;
        if (!$bookingId) {
            echo json_encode(['success' => false, 'message' => 'Thiếu thông tin booking']);
            exit;
        }
        // Kiểm tra quyền sửa booking này
        if (!$this->model->canUserEditBooking($bookingId, $userId, $userRole)) {
            echo json_encode(['success' => false, 'message' => 'Bạn không có quyền thêm khách cho booking này']);
            exit;
        }
        // Lấy dữ liệu từ form
        $data = [
            'booking_id' => $bookingId,
            'name' => $_POST['name'] ?? '',
            'gender' => $_POST['gender'] ?? null,
            'birth_date' => $_POST['birth_date'] ?? null,
            'phone' => $_POST['phone'] ?? null,
            'id_card' => $_POST['id_card'] ?? null,
            'room_type' => $_POST['room_type'] ?? null,
            'special_request' => $_POST['special_request'] ?? null
        ];
        // Validate
        if (empty($data['name'])) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng nhập họ tên khách']);
            exit;
        }
        // Thêm vào database
        try {
            $companionModel = new BookingCustomer();
            $companionId = $companionModel->insert($data);

            echo json_encode([
                'success' => true,
                'message' => 'Thêm khách đi kèm thành công',
                'companion_id' => $companionId,
                'companion' => $data
            ]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
        }
        exit;
    }
    /**
     * Cập nhật thông tin khách đi kèm
     * AJAX endpoint
     */
    public function updateCompanion()
    {
        header('Content-Type: application/json');
        $companionId = $_POST['companion_id'] ?? null;
        $bookingId = $_POST['booking_id'] ?? null;
        if (!$companionId || !$bookingId) {
            echo json_encode(['success' => false, 'message' => 'Thiếu thông tin']);
            exit;
        }
        // Kiểm tra quyền
        $userRole = $_SESSION['user']['role'] ?? 'customer';
        $userId = $_SESSION['user']['user_id'] ?? null;
        if (!$this->model->canUserEditBooking($bookingId, $userId, $userRole)) {
            echo json_encode(['success' => false, 'message' => 'Bạn không có quyền sửa khách này']);
            exit;
        }
        // Lấy dữ liệu từ form
        $data = [
            'name' => $_POST['name'] ?? '',
            'gender' => $_POST['gender'] ?? null,
            'birth_date' => $_POST['birth_date'] ?? null,
            'phone' => $_POST['phone'] ?? null,
            'id_card' => $_POST['id_card'] ?? null,
            'room_type' => $_POST['room_type'] ?? null,
            'special_request' => $_POST['special_request'] ?? null
        ];

        // Validate
        if (empty($data['name'])) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng nhập họ tên khách']);
            exit;
        }
        // Cập nhật database
        try {
            $companionModel = new BookingCustomer();
            $companionModel->update($companionId, $data);

            echo json_encode([
                'success' => true,
                'message' => 'Cập nhật thông tin khách thành công',
                'companion' => $data
            ]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
        }
        exit;
    }
    /**
     * Xóa khách đi kèm
     * AJAX endpoint
     */
    public function deleteCompanion()
    {
        header('Content-Type: application/json');
        $companionId = $_POST['companion_id'] ?? null;
        $bookingId = $_POST['booking_id'] ?? null;
        if (!$companionId || !$bookingId) {
            echo json_encode(['success' => false, 'message' => 'Thiếu thông tin']);
            exit;
        }
        // Kiểm tra quyền
        $userRole = $_SESSION['user']['role'] ?? 'customer';
        $userId = $_SESSION['user']['user_id'] ?? null;
        if (!$this->model->canUserEditBooking($bookingId, $userId, $userRole)) {
            echo json_encode(['success' => false, 'message' => 'Bạn không có quyền xóa khách này']);
            exit;
        }
        // Xóa khỏi database
        try {
            $companionModel = new BookingCustomer();
            $companionModel->delete($companionId);

            echo json_encode([
                'success' => true,
                'message' => 'Xóa khách đi kèm thành công'
            ]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
        }
        exit;
    }

    /**
     * Hiển thị trang check-in
     */
    public function checkin()
    {
        $bookingId = $_GET['id'] ?? null;

        if (!$bookingId) {
            $_SESSION['error'] = 'Không tìm thấy booking';
            header('Location: ' . BASE_URL_ADMIN . '&action=bookings');
            return;
        }

        // Lấy thông tin booking
        $booking = $this->model->getById($bookingId);
        if (!$booking) {
            $_SESSION['error'] = 'Booking không tồn tại';
            header('Location: ' . BASE_URL_ADMIN . '&action=bookings');
            return;
        }

        // Lấy thông tin tour
        $tourModel = new Tour();
        $tour = $tourModel->find('*', 'id = :id', ['id' => $booking['tour_id']]);

        // Lấy danh sách khách
        $customerModel = new BookingCustomer();
        $customers = $customerModel->getCustomersWithCheckinStatus($bookingId);
        $stats = $customerModel->getCheckinStats($bookingId);

        require_once PATH_VIEW_ADMIN . 'pages/bookings/checkin.php';
    }

    /**
     * Cập nhật trạng thái check-in (AJAX)
     */
    public function updateCheckin()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid method']);
            exit;
        }

        $customerId = $_POST['customer_id'] ?? null;
        $status = $_POST['status'] ?? null;
        $notes = $_POST['notes'] ?? null;

        if (!$customerId || !$status) {
            echo json_encode(['success' => false, 'message' => 'Missing required fields']);
            exit;
        }

        // Validate status
        if (!in_array($status, ['not_arrived', 'checked_in', 'absent'])) {
            echo json_encode(['success' => false, 'message' => 'Invalid status']);
            exit;
        }

        try {
            $userId = $_SESSION['user']['user_id'] ?? null;
            $customerModel = new BookingCustomer();

            $result = $customerModel->updateCheckinStatus(
                $customerId,
                $status,
                $userId,
                $notes
            );

            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Cập nhật thành công',
                    'timestamp' => date('H:i d/m/Y')
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Cập nhật thất bại']);
            }
        } catch (Exception $e) {
            error_log('Check-in error: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
        }
        exit;
    }

    /**
     * Check-in hàng loạt
     */
    public function bulkCheckin()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid method']);
            return;
        }

        $customerIds = $_POST['customer_ids'] ?? [];
        $status = $_POST['status'] ?? 'checked_in';

        if (empty($customerIds)) {
            echo json_encode(['success' => false, 'message' => 'Chưa chọn khách']);
            return;
        }

        try {
            $userId = $_SESSION['user']['user_id'] ?? null;
            $customerModel = new BookingCustomer();
            $count = 0;

            foreach ($customerIds as $customerId) {
                if ($customerModel->updateCheckinStatus($customerId, $status, $userId)) {
                    $count++;
                }
            }

            echo json_encode([
                'success' => true,
                'message' => "Đã check-in {$count} khách",
                'count' => $count
            ]);
        } catch (Exception $e) {
            error_log('Bulk check-in error: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống']);
        }
    }

    /**
     * In danh sách đoàn
     */
    public function printGroupList()
    {
        $bookingId = $_GET['id'] ?? null;

        if (!$bookingId) {
            $_SESSION['error'] = 'Không tìm thấy booking';
            header('Location: ' . BASE_URL_ADMIN . '&action=bookings');
            return;
        }

        // Lấy thông tin booking
        $booking = $this->model->getById($bookingId);
        if (!$booking) {
            $_SESSION['error'] = 'Booking không tồn tại';
            header('Location: ' . BASE_URL_ADMIN . '&action=bookings');
            return;
        }

        // Lấy thông tin tour
        $tourModel = new Tour();
        $tour = $tourModel->find('*', 'id = :id', ['id' => $booking['tour_id']]);

        // Lấy danh sách khách
        $customerModel = new BookingCustomer();
        $customers = $customerModel->getByBooking($bookingId);

        // Thống kê theo loại khách
        $stats = [
            'adults' => 0,
            'children' => 0,
            'infants' => 0,
            'total' => count($customers)
        ];

        foreach ($customers as $customer) {
            $type = $customer['passenger_type'] ?? 'adult';
            if ($type === 'adult') $stats['adults']++;
            elseif ($type === 'child') $stats['children']++;
            elseif ($type === 'infant') $stats['infants']++;
        }

        require_once PATH_VIEW_ADMIN . 'pages/bookings/print-group-list.php';
    }
}
