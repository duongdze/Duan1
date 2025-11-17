<?php
require_once 'models/Tour.php';
require_once 'models/Supplier.php';

class TourController
{
    protected $model;

    public function __construct()
    {
        $this->model = new Tour();
    }

    public function index()
    {
        $tours = $this->model->getAll();
        require_once PATH_VIEW_ADMIN . 'tours/index.php';
    }

    public function create()
    {
        require_once 'views/admin/tours/create.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        // Validate input
        $name = $_POST['name'] ?? '';
        $type = $_POST['type'] ?? '';
        $description = $_POST['description'] ?? '';
        $base_price = $_POST['base_price'] ?? 0;
        $policy = $_POST['policy'] ?? '';

        if (empty($name) || empty($type)) {
            $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin!';
            header('Location: ?action=tours/create');
            return;
        }

        try {
            $tourId = $this->model->create([
                'name' => $name,
                'type' => $type,
                'description' => $description,
                'base_price' => $base_price,
                'policy' => $policy,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            if ($tourId) {
                $_SESSION['success'] = 'Thêm tour thành công!';
                header('Location: ?action=tours');
            } else {
                throw new Exception('Không thể thêm tour');
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Có lỗi xảy ra: ' . $e->getMessage();
            header('Location: ?action=tours/create');
        }
    }

    public function edit()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location:' . BASE_URL_ADMIN . '?action=tours');
            return;
        }

        $tour = $this->model->findById($id);
        if (!$tour) {
            $_SESSION['error'] = 'Không tìm thấy tour!';
            header('Location: ?action=tours');
            return;
        }

        require_once PATH_VIEW_ADMIN . 'tours/edit.php';
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $id = $_POST['id'] ?? null;
        if (!$id) {
            header('Location: ?action=tours');
            return;
        }

        // Validate input
        $name = $_POST['name'] ?? '';
        $type = $_POST['type'] ?? '';
        $description = $_POST['description'] ?? '';
        $base_price = $_POST['base_price'] ?? 0;
        $policy = $_POST['policy'] ?? '';

        if (empty($name) || empty($type)) {
            $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin!';
            header('Location: ?action=tours/edit&id=' . $id);
            return;
        }

        try {
            $result = $this->model->updateById($id, [
                'name' => $name,
                'type' => $type,
                'description' => $description,
                'base_price' => $base_price,
                'policy' => $policy,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            if ($result) {
                $_SESSION['success'] = 'Cập nhật tour thành công!';
                header('Location: ?action=tours');
            } else {
                throw new Exception('Không thể cập nhật tour');
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Có lỗi xảy ra: ' . $e->getMessage();
            header('Location: ?action=tours/edit&id=' . $id);
        }
    }

    public function delete()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: ?action=tours');
            return;
        }

        try {
            $result = $this->model->deleteById($id);
            if ($result) {
                $_SESSION['success'] = 'Xóa tour thành công!';
            } else {
                throw new Exception('Không thể xóa tour');
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Có lỗi xảy ra: ' . $e->getMessage();
        }

        header('Location: ?action=tours');
    }
}
