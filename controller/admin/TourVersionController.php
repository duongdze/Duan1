<?php
require_once 'models/TourVersion.php';

class TourVersionController
{
    protected $model;
    protected $tourModel;

    public function __construct()
    {
        $this->model = new TourVersion();
        require_once 'models/Tour.php';
        $this->tourModel = new Tour();
    }

    /**
     * Validate tour version data
     */
    protected function validateVersionData($data, $isUpdate = false)
    {
        $errors = [];

        if (empty(trim($data['name'] ?? ''))) {
            $errors['name'] = 'Tên phiên bản không được để trống';
        } elseif (strlen(trim($data['name'])) > 255) {
            $errors['name'] = 'Tên phiên bản không được vượt quá 255 ký tự';
        }

        // Check for duplicate version name for the same tour
        if (!$isUpdate || ($isUpdate && $data['name'] !== $data['current_name'])) {
            $existing = $this->model->select(
                'id',
                'name = :name AND id != :id',
                [
                    'name' => $data['name'],
                    'id' => $data['id'] ?? 0
                ]
            );

            if (!empty($existing)) {
                $errors['name'] = 'Tên phiên bản đã tồn tại';
            }
        }

        return $errors;
    }

    /**
     * Get tour ID from request
     */
    protected function getTourIdFromRequest()
    {
        return $_GET['tour_id'] ?? $_POST['tour_id'] ?? null;
    }

    /**
     * List all versions for a tour
     */
    public function index()
    {
        $tour_id = $this->getTourIdFromRequest();
        if (!$tour_id) {
            $_SESSION['error'] = 'Thiếu thông tin tour';
            header('Location: ?action=tours');
            return;
        }

        $tour = $this->tourModel->findById($tour_id);
        if (!$tour) {
            $_SESSION['error'] = 'Không tìm thấy tour';
            header('Location: ?action=tours');
            return;
        }

        $versions = $this->model->getByTourId($tour_id);

        $title = 'Quản lý phiên bản - ' . $tour['name'];
        require_once PATH_VIEW_ADMIN . 'pages/tours_versions/index.php';
    }

    /**
     * Show create form
     */
    public function create()
    {
        $tour_id = $this->getTourIdFromRequest();
        if (!$tour_id) {
            $_SESSION['error'] = 'Thiếu thông tin tour';
            header('Location: ?action=tours');
            return;
        }

        $tour = $this->tourModel->findById($tour_id);
        if (!$tour) {
            $_SESSION['error'] = 'Không tìm thấy tour';
            header('Location: ?action=tours');
            return;
        }

        $title = 'Thêm phiên bản mới - ' . $tour['name'];
        require_once PATH_VIEW_ADMIN . 'pages/tours_versions/form.php';
    }

    /**
     * Store new version
     */
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?action=tours');
            return;
        }

        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'status' => isset($_POST['status']) && in_array($_POST['status'], ['active', 'inactive']) ?
                $_POST['status'] : 'inactive',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Validate data
        $errors = $this->validateVersionData($data);

        if (!empty($errors)) {
            $_SESSION['form_errors'] = $errors;
            $_SESSION['old_input'] = $data;
            header('Location: ?action=tours_versions/create&tour_id=' . $_POST['tour_id']);
            return;
        }

        try {
            $this->model->insert($data);
            $_SESSION['success'] = 'Thêm phiên bản thành công';
            header('Location: ?action=tours_versions&tour_id=' . $_POST['tour_id']);
        } catch (Exception $e) {
            error_log('Error creating tour version: ' . $e->getMessage());
            $_SESSION['error'] = 'Có lỗi xảy ra khi thêm phiên bản. Vui lòng thử lại.';
            $_SESSION['old_input'] = $data;
            header('Location: ?action=tours_versions/create&tour_id=' . $_POST['tour_id']);
        }
    }

    /**
     * Show edit form
     */
    public function edit()
    {
        $id = $_GET['id'] ?? null;
        $tour_id = $this->getTourIdFromRequest();

        if (!$id || !$tour_id) {
            $_SESSION['error'] = 'Thiếu thông tin';
            header('Location: ?action=tours');
            return;
        }

        $version = $this->model->findById($id);
        if (!$version) {
            $_SESSION['error'] = 'Không tìm thấy phiên bản';
            header('Location: ?action=tours_versions&tour_id=' . $tour_id);
            return;
        }

        $tour = $this->tourModel->findById($tour_id);
        if (!$tour) {
            $_SESSION['error'] = 'Không tìm thấy tour';
            header('Location: ?action=tours');
            return;
        }

        $title = 'Chỉnh sửa phiên bản - ' . $tour['name'];
        require_once PATH_VIEW_ADMIN . 'pages/tours_versions/form.php';
    }

    /**
     * Update version
     */
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?action=tours');
            return;
        }

        $id = $_POST['id'] ?? null;
        $tour_id = $_POST['tour_id'] ?? null;

        if (!$id || !$tour_id) {
            $_SESSION['error'] = 'Thiếu thông tin';
            header('Location: ?action=tours');
            return;
        }

        $version = $this->model->findById($id);
        if (!$version) {
            $_SESSION['error'] = 'Không tìm thấy phiên bản';
            header('Location: ?action=tours_versions&tour_id=' . $tour_id);
            return;
        }

        $data = [
            'id' => $id,
            'current_name' => $version['name'], // For duplicate check
            'name' => trim($_POST['name'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'status' => isset($_POST['status']) && in_array($_POST['status'], ['active', 'inactive']) ?
                $_POST['status'] : 'inactive',
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Validate data
        $errors = $this->validateVersionData($data, true);

        if (!empty($errors)) {
            $_SESSION['form_errors'] = $errors;
            $_SESSION['old_input'] = $data;
            header('Location: ?action=tours_versions/edit&id=' . $id . '&tour_id=' . $tour_id);
            return;
        }

        try {
            // Don't update created_at
            unset($data['current_name']);
            unset($data['id']);

            $this->model->updateById($id, $data);

            $_SESSION['success'] = 'Cập nhật phiên bản thành công';
            header('Location: ?action=tours_versions&tour_id=' . $tour_id);
        } catch (Exception $e) {
            error_log('Error updating tour version: ' . $e->getMessage());
            $_SESSION['error'] = 'Có lỗi xảy ra khi cập nhật phiên bản. Vui lòng thử lại.';
            $_SESSION['old_input'] = $data;
            header('Location: ?action=tours_versions/edit&id=' . $id . '&tour_id=' . $tour_id);
        }
    }

    /**
     * Delete version
     */
    public function delete()
    {
        $id = $_GET['id'] ?? null;
        $tour_id = $this->getTourIdFromRequest();

        if (!$id || !$tour_id) {
            $_SESSION['error'] = 'Thiếu thông tin';
            header('Location: ?action=tours');
            return;
        }

        $version = $this->model->findById($id);
        if (!$version) {
            $_SESSION['error'] = 'Không tìm thấy phiên bản';
            header('Location: ?action=tours_versions&tour_id=' . $tour_id);
            return;
        }

        try {
            $this->model->delete('id = :id', ['id' => $id]);
            $_SESSION['success'] = 'Xóa phiên bản thành công';
        } catch (Exception $e) {
            error_log('Error deleting tour version: ' . $e->getMessage());
            $_SESSION['error'] = 'Có lỗi xảy ra khi xóa phiên bản. Vui lòng thử lại.';
        }

        header('Location: ?action=tours_versions&tour_id=' . $tour_id);
    }

    /**
     * Toggle version status
     */
    public function toggleStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['_method']) || $_POST['_method'] !== 'PATCH') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        $id = $_POST['id'] ?? null;
        $status = $_POST['status'] ?? null;

        if (!$id || !in_array($status, ['active', 'inactive'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            return;
        }

        try {
            $version = $this->model->findById($id);
            if (!$version) {
                throw new Exception('Version not found');
            }

            $this->model->updateById($id, [
                'status' => $status,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            error_log('Error toggling version status: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Internal server error']);
        }
    }
}
