<?php
require_once 'models/TourVersion.php';

class TourVersionController
{
    protected $model;

    public function __construct()
    {
        $this->model = new TourVersion();
    }

    public function index()
    {
        $tour_id = $_GET['tour_id'] ?? null;
        if (!$tour_id) {
            $_SESSION['error'] = 'Thiếu tour_id';
            header('Location: ?action=tours');
            return;
        }

        // Get tour details
        require_once 'models/Tour.php';
        $tourModel = new Tour();
        $tour = $tourModel->findById($tour_id);
        
        if (!$tour) {
            $_SESSION['error'] = 'Không tìm thấy tour';
            header('Location: ?action=tours');
            return;
        }

        $versions = $this->model->select('*', 'tour_id = :tour_id ORDER BY start_date DESC', ['tour_id' => $tour_id]);
        $title = 'Phiên bản Tour - ' . $tour['name'];
        require_once PATH_VIEW_ADMIN . 'pages/tours_versions/index.php';
    }

    public function create()
    {
        $tour_id = $_GET['tour_id'] ?? null;
        if (!$tour_id) {
            $_SESSION['error'] = 'Thiếu tour_id';
            header('Location: ?action=tours');
            return;
        }

        // Get tour details
        require_once 'models/Tour.php';
        $tourModel = new Tour();
        $tour = $tourModel->findById($tour_id);
        
        if (!$tour) {
            $_SESSION['error'] = 'Không tìm thấy tour';
            header('Location: ?action=tours');
            return;
        }

        $title = 'Thêm phiên bản - ' . $tour['name'];
        require_once PATH_VIEW_ADMIN . 'pages/tours_versions/create.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?action=tours');
            return;
        }

        $tour_id = $_POST['tour_id'] ?? null;
        $name = trim($_POST['name'] ?? '');
        $start_date = $_POST['start_date'] ?? null;
        $end_date = $_POST['end_date'] ?? null;
        $price = $_POST['price'] ?? 0;
        $notes = trim($_POST['notes'] ?? '');

        // Validation
        if (!$tour_id || empty($name)) {
            $_SESSION['error'] = 'Vui lòng nhập tên phiên bản';
            header('Location: ?action=tours_versions/create&tour_id=' . $tour_id);
            return;
        }

        // Validate dates
        if ($start_date && $end_date && strtotime($end_date) < strtotime($start_date)) {
            $_SESSION['error'] = 'Ngày kết thúc phải sau ngày bắt đầu';
            header('Location: ?action=tours_versions/create&tour_id=' . $tour_id);
            return;
        }

        try {
            $this->model->insert([
                'tour_id' => $tour_id,
                'name' => $name,
                'start_date' => $start_date ?: null,
                'end_date' => $end_date ?: null,
                'price' => (float)$price,
                'notes' => $notes,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            $_SESSION['success'] = 'Thêm phiên bản thành công';
            header('Location: ?action=tours_versions&tour_id=' . $tour_id);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            header('Location: ?action=tours_versions/create&tour_id=' . $tour_id);
        }
    }

    public function edit()
    {
        $id = $_GET['id'] ?? null;
        $tour_id = $_GET['tour_id'] ?? null;
        
        if (!$id || !$tour_id) {
            $_SESSION['error'] = 'Thiếu thông tin';
            header('Location: ?action=tours');
            return;
        }

        // Get tour details
        require_once 'models/Tour.php';
        $tourModel = new Tour();
        $tour = $tourModel->findById($tour_id);
        
        if (!$tour) {
            $_SESSION['error'] = 'Không tìm thấy tour';
            header('Location: ?action=tours');
            return;
        }

        // Get version details
        $version = $this->model->findById($id);
        
        if (!$version || $version['tour_id'] != $tour_id) {
            $_SESSION['error'] = 'Không tìm thấy phiên bản';
            header('Location: ?action=tours_versions&tour_id=' . $tour_id);
            return;
        }

        $title = 'Chỉnh sửa phiên bản - ' . $tour['name'];
        require_once PATH_VIEW_ADMIN . 'pages/tours_versions/edit.php';
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?action=tours');
            return;
        }

        $id = $_POST['id'] ?? null;
        $tour_id = $_POST['tour_id'] ?? null;
        $name = trim($_POST['name'] ?? '');
        $start_date = $_POST['start_date'] ?? null;
        $end_date = $_POST['end_date'] ?? null;
        $price = $_POST['price'] ?? 0;
        $notes = trim($_POST['notes'] ?? '');

        // Validation
        if (!$id || !$tour_id || empty($name)) {
            $_SESSION['error'] = 'Vui lòng nhập tên phiên bản';
            header('Location: ?action=tours_versions/edit&id=' . $id . '&tour_id=' . $tour_id);
            return;
        }

        // Validate dates
        if ($start_date && $end_date && strtotime($end_date) < strtotime($start_date)) {
            $_SESSION['error'] = 'Ngày kết thúc phải sau ngày bắt đầu';
            header('Location: ?action=tours_versions/edit&id=' . $id . '&tour_id=' . $tour_id);
            return;
        }

        try {
            $this->model->updateById($id, [
                'name' => $name,
                'start_date' => $start_date ?: null,
                'end_date' => $end_date ?: null,
                'price' => (float)$price,
                'notes' => $notes,
            ]);

            $_SESSION['success'] = 'Cập nhật phiên bản thành công';
            header('Location: ?action=tours_versions&tour_id=' . $tour_id);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            header('Location: ?action=tours_versions/edit&id=' . $id . '&tour_id=' . $tour_id);
        }
    }

    public function delete()
    {
        $id = $_GET['id'] ?? null;
        $tour_id = $_GET['tour_id'] ?? null;
        if (!$id) {
            header('Location: ?action=tours');
            return;
        }

        try {
            $this->model->delete('id = :id', ['id' => $id]);
            $_SESSION['success'] = 'Xóa thành công';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
        }

        header('Location: ?action=tours_versions&tour_id=' . $tour_id);
    }
}
