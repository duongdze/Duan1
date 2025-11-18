<?php
// require_once 'models/Tour.php';
// require_once 'models/Supplier.php';

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
        require_once PATH_VIEW_ADMIN . 'pages/tours/index.php';
    }

    public function create()
    {
        // Load suppliers for supplier dropdown
        $supplierModel = new Supplier();
        $suppliers = $supplierModel->select();

        require_once PATH_VIEW_ADMIN . 'pages/tours/create.php';
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
        $supplier_id = !empty($_POST['supplier_id']) ? intval($_POST['supplier_id']) : null;

        if (empty($name) || empty($type)) {
            $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin!';
            header('Location: ?action=tours/create');
            return;
        }

        try {
            // Handle image upload
            $imagePath = null;
            if (!empty($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = PATH_ROOT . 'assets/uploads/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

                $tmp = $_FILES['image']['tmp_name'];
                $origName = $_FILES['image']['name'];
                $ext = pathinfo($origName, PATHINFO_EXTENSION);
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                if (!in_array(strtolower($ext), $allowed)) {
                    throw new Exception('Định dạng ảnh không hợp lệ. Vui lòng tải lên jpg, png, gif hoặc webp.');
                }
                $newName = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
                $dest = $uploadDir . $newName;
                if (!move_uploaded_file($tmp, $dest)) {
                    throw new Exception('Không thể lưu ảnh. Vui lòng thử lại.');
                }
                $imagePath = BASE_ASSETS_UPLOADS . $newName;
            }

            $tourId = $this->model->create([
                'name' => $name,
                'type' => $type,
                'description' => $description,
                'base_price' => $base_price,
                'policy' => $policy,
                'supplier_id' => $supplier_id,
                'image' => $imagePath,
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

        // Load suppliers for supplier dropdown
        $supplierModel = new Supplier();
        $suppliers = $supplierModel->select();

        require_once PATH_VIEW_ADMIN . 'pages/tours/edit.php';
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
        $supplier_id = !empty($_POST['supplier_id']) ? intval($_POST['supplier_id']) : null;

        if (empty($name) || empty($type)) {
            $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin!';
            header('Location: ?action=tours/edit&id=' . $id);
            return;
        }

        try {
            // Handle image upload on update
            $imagePath = null;
            if (!empty($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = PATH_ROOT . 'assets/uploads/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

                $tmp = $_FILES['image']['tmp_name'];
                $origName = $_FILES['image']['name'];
                $ext = pathinfo($origName, PATHINFO_EXTENSION);
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                if (!in_array(strtolower($ext), $allowed)) {
                    throw new Exception('Định dạng ảnh không hợp lệ. Vui lòng tải lên jpg, png, gif hoặc webp.');
                }
                $newName = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
                $dest = $uploadDir . $newName;
                if (!move_uploaded_file($tmp, $dest)) {
                    throw new Exception('Không thể lưu ảnh. Vui lòng thử lại.');
                }
                $imagePath = BASE_ASSETS_UPLOADS . $newName;
            }

            $updateData = [
                'name' => $name,
                'type' => $type,
                'description' => $description,
                'base_price' => $base_price,
                'policy' => $policy,
                'supplier_id' => $supplier_id,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            if (!empty($imagePath)) $updateData['image'] = $imagePath;

            $result = $this->model->updateById($id, $updateData);

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
    public function detail() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: ?action=tours');
            return;
        }
        $tour = $this->model->findById($id);
        if(!$tour) {
            $_SESSION['error'] = 'Không tìm thấy tour!';
            header('Location: ?action=tours');
            return;
        }
        require_once PATH_VIEW_ADMIN .'pages/tours/detail.php';
    }
}
