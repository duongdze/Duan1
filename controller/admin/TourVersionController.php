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

        $versions = $this->model->select('*', 'tour_id = :tour_id', ['tour_id' => $tour_id]);
        $title = 'Phiên bản Tour';
        require_once PATH_VIEW_ADMIN . 'pages/tours/versions.php';
    }

    public function create()
    {
        $tour_id = $_GET['tour_id'] ?? null;
        if (!$tour_id) {
            $_SESSION['error'] = 'Thiếu tour_id';
            header('Location: ?action=tours');
            return;
        }

        $title = 'Thêm phiên bản';
        require_once PATH_VIEW_ADMIN . 'pages/tours/versions_create.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $tour_id = $_POST['tour_id'] ?? null;
        $name = $_POST['name'] ?? '';
        $start_date = $_POST['start_date'] ?? null;
        $end_date = $_POST['end_date'] ?? null;
        $price = $_POST['price'] ?? 0;
        $notes = $_POST['notes'] ?? '';

        if (!$tour_id || !$name) {
            $_SESSION['error'] = 'Thiếu thông tin bắt buộc';
            header('Location: ?action=tours/versions&tour_id=' . $tour_id);
            return;
        }

        try {
            $this->model->insert([
                'tour_id' => $tour_id,
                'name' => $name,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'price' => $price,
                'notes' => $notes,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            $_SESSION['success'] = 'Thêm phiên bản thành công';
            header('Location: ?action=tours/versions&tour_id=' . $tour_id);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            header('Location: ?action=tours/versions&tour_id=' . $tour_id);
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

        header('Location: ?action=tours/versions&tour_id=' . $tour_id);
    }
}
