<?php
require_once 'models/Driver.php';

class DriverController
{
    private $model;

    public function __construct()
    {
        $this->model = new Driver();
    }

    /**
     * Hiển thị danh sách tài xế
     */
    public function index()
    {
        $drivers = $this->model->select('*', null, [], 'full_name ASC');
        $stats = $this->model->getStats();

        require_once PATH_VIEW_ADMIN . 'pages/drivers/index.php';
    }

    /**
     * Hiển thị form tạo tài xế mới
     */
    public function create()
    {
        require_once PATH_VIEW_ADMIN . 'pages/drivers/create.php';
    }

    /**
     * Lưu tài xế mới
     */
    public function store()
    {
        try {
            // Validate input
            $errors = $this->validateDriver($_POST);

            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                $_SESSION['old'] = $_POST;
                header('Location: ' . BASE_URL_ADMIN . '&action=drivers/create');
                exit;
            }

            // Kiểm tra SĐT trùng
            if ($this->model->phoneExists($_POST['phone'])) {
                $_SESSION['error'] = 'Số điện thoại đã tồn tại!';
                $_SESSION['old'] = $_POST;
                header('Location: ' . BASE_URL_ADMIN . '&action=drivers/create');
                exit;
            }

            // Kiểm tra bằng lái trùng
            if ($this->model->licenseExists($_POST['license_number'])) {
                $_SESSION['error'] = 'Số bằng lái đã tồn tại!';
                $_SESSION['old'] = $_POST;
                header('Location: ' . BASE_URL_ADMIN . '&action=drivers/create');
                exit;
            }

            // Chuẩn bị dữ liệu
            $data = [
                'full_name' => trim($_POST['full_name']),
                'phone' => trim($_POST['phone']),
                'email' => !empty($_POST['email']) ? trim($_POST['email']) : null,
                'license_number' => trim($_POST['license_number']),
                'license_type' => !empty($_POST['license_type']) ? trim($_POST['license_type']) : null,
                'vehicle_type' => !empty($_POST['vehicle_type']) ? trim($_POST['vehicle_type']) : null,
                'vehicle_plate' => !empty($_POST['vehicle_plate']) ? trim($_POST['vehicle_plate']) : null,
                'vehicle_brand' => !empty($_POST['vehicle_brand']) ? trim($_POST['vehicle_brand']) : null,
                'status' => $_POST['status'] ?? 'active',
                'rating' => !empty($_POST['rating']) ? floatval($_POST['rating']) : 5.00,
                'notes' => !empty($_POST['notes']) ? trim($_POST['notes']) : null
            ];

            $id = $this->model->insert($data);

            if ($id) {
                $_SESSION['success'] = 'Thêm tài xế thành công!';
                header('Location: ' . BASE_URL_ADMIN . '&action=drivers');
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra khi thêm tài xế!';
                header('Location: ' . BASE_URL_ADMIN . '&action=drivers/create');
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            header('Location: ' . BASE_URL_ADMIN . '&action=drivers/create');
        }
        exit;
    }

    /**
     * Hiển thị form sửa tài xế
     */
    public function edit()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            $_SESSION['error'] = 'Không tìm thấy tài xế!';
            header('Location: ' . BASE_URL_ADMIN . '&action=drivers');
            exit;
        }

        $driver = $this->model->getById($id);

        if (!$driver) {
            $_SESSION['error'] = 'Không tìm thấy tài xế!';
            header('Location: ' . BASE_URL_ADMIN . '&action=drivers');
            exit;
        }

        require_once PATH_VIEW_ADMIN . 'pages/drivers/edit.php';
    }

    /**
     * Cập nhật tài xế
     */
    public function update()
    {
        try {
            $id = $_POST['id'] ?? null;

            if (!$id) {
                $_SESSION['error'] = 'Không tìm thấy tài xế!';
                header('Location: ' . BASE_URL_ADMIN . '&action=drivers');
                exit;
            }

            // Validate input
            $errors = $this->validateDriver($_POST, $id);

            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                $_SESSION['old'] = $_POST;
                header('Location: ' . BASE_URL_ADMIN . '&action=drivers/edit&id=' . $id);
                exit;
            }

            // Kiểm tra SĐT trùng (trừ bản ghi hiện tại)
            if ($this->model->phoneExists($_POST['phone'], $id)) {
                $_SESSION['error'] = 'Số điện thoại đã tồn tại!';
                $_SESSION['old'] = $_POST;
                header('Location: ' . BASE_URL_ADMIN . '&action=drivers/edit&id=' . $id);
                exit;
            }

            // Kiểm tra bằng lái trùng (trừ bản ghi hiện tại)
            if ($this->model->licenseExists($_POST['license_number'], $id)) {
                $_SESSION['error'] = 'Số bằng lái đã tồn tại!';
                $_SESSION['old'] = $_POST;
                header('Location: ' . BASE_URL_ADMIN . '&action=drivers/edit&id=' . $id);
                exit;
            }

            // Chuẩn bị dữ liệu
            $data = [
                'full_name' => trim($_POST['full_name']),
                'phone' => trim($_POST['phone']),
                'email' => !empty($_POST['email']) ? trim($_POST['email']) : null,
                'license_number' => trim($_POST['license_number']),
                'license_type' => !empty($_POST['license_type']) ? trim($_POST['license_type']) : null,
                'vehicle_type' => !empty($_POST['vehicle_type']) ? trim($_POST['vehicle_type']) : null,
                'vehicle_plate' => !empty($_POST['vehicle_plate']) ? trim($_POST['vehicle_plate']) : null,
                'vehicle_brand' => !empty($_POST['vehicle_brand']) ? trim($_POST['vehicle_brand']) : null,
                'status' => $_POST['status'] ?? 'active',
                'rating' => !empty($_POST['rating']) ? floatval($_POST['rating']) : 5.00,
                'notes' => !empty($_POST['notes']) ? trim($_POST['notes']) : null
            ];

            $result = $this->model->update($data, 'id = :id', ['id' => $id]);

            if ($result) {
                $_SESSION['success'] = 'Cập nhật tài xế thành công!';
            } else {
                $_SESSION['error'] = 'Không có thay đổi nào được thực hiện!';
            }

            header('Location: ' . BASE_URL_ADMIN . '&action=drivers');
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            header('Location: ' . BASE_URL_ADMIN . '&action=drivers/edit&id=' . $id);
        }
        exit;
    }

    /**
     * Xóa tài xế
     */
    public function delete()
    {
        try {
            $id = $_POST['id'] ?? null;

            if (!$id) {
                $_SESSION['error'] = 'Không tìm thấy tài xế!';
                header('Location: ' . BASE_URL_ADMIN . '&action=drivers');
                exit;
            }

            $result = $this->model->delete('id = :id', ['id' => $id]);

            if ($result) {
                $_SESSION['success'] = 'Xóa tài xế thành công!';
            } else {
                $_SESSION['error'] = 'Không thể xóa tài xế!';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
        }

        header('Location: ' . BASE_URL_ADMIN . '&action=drivers');
        exit;
    }

    /**
     * Hiển thị chi tiết tài xế
     */
    public function detail()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            $_SESSION['error'] = 'Không tìm thấy tài xế!';
            header('Location: ' . BASE_URL_ADMIN . '&action=drivers');
            exit;
        }

        $driver = $this->model->getById($id);

        if (!$driver) {
            $_SESSION['error'] = 'Không tìm thấy tài xế!';
            header('Location: ' . BASE_URL_ADMIN . '&action=drivers');
            exit;
        }

        // Lấy danh sách booking của tài xế này
        $bookingModel = new Booking();
        $bookings = $bookingModel->select(
            'id, tour_id, booking_date, departure_date, status, final_price',
            'driver_id = :driver_id',
            ['driver_id' => $id],
            'booking_date DESC'
        );

        require_once PATH_VIEW_ADMIN . 'pages/drivers/detail.php';
    }

    /**
     * Validate dữ liệu tài xế
     */
    private function validateDriver($data, $excludeId = null)
    {
        $errors = [];

        if (empty($data['full_name'])) {
            $errors['full_name'] = 'Vui lòng nhập họ tên tài xế!';
        }

        if (empty($data['phone'])) {
            $errors['phone'] = 'Vui lòng nhập số điện thoại!';
        } elseif (!preg_match('/^[0-9]{10,11}$/', $data['phone'])) {
            $errors['phone'] = 'Số điện thoại không hợp lệ!';
        }

        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email không hợp lệ!';
        }

        if (empty($data['license_number'])) {
            $errors['license_number'] = 'Vui lòng nhập số bằng lái!';
        }

        return $errors;
    }
}
