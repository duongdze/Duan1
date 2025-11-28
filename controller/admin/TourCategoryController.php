<?php
require_once 'models/TourCategory.php';

class TourCategoryController
{
    protected $model;
    protected $tourModel;

    public function __construct()
    {
        $this->model = new TourCategory();
        require_once 'models/Tour.php';
        $this->tourModel = new Tour();
    }

    /**
     * Validate category data
     */
    protected function validateCategoryData($data, $isUpdate = false)
    {
        $errors = [];

        if (empty(trim($data['name'] ?? ''))) {
            $errors['name'] = 'Tên danh mục không được để trống';
        } elseif (strlen(trim($data['name'])) > 255) {
            $errors['name'] = 'Tên danh mục không được vượt quá 255 ký tự';
        }

        if (!empty($data['parent_id'])) {
            $parent = $this->model->findById($data['parent_id']);
            if (!$parent) {
                $errors['parent_id'] = 'Danh mục cha không tồn tại';
            } elseif ($isUpdate && $data['parent_id'] == $data['id']) {
                $errors['parent_id'] = 'Danh mục không thể là con của chính nó';
            }
        }

        // Check for duplicate name
        if (!$isUpdate || ($isUpdate && $data['name'] !== $data['current_name'])) {
            $where = 'name = :name';
            $params = ['name' => $data['name']];

            if ($isUpdate) {
                $where .= ' AND id != :id';
                $params['id'] = $data['id'];
            }

            $existing = $this->model->select('id', $where, $params);
            if (!empty($existing)) {
                $errors['name'] = 'Tên danh mục đã tồn tại';
            }
        }

        return $errors;
    }

    /**
     * List all categories
     */
    public function index()
    {
        $type = $_GET['type'] ?? 'all';
        $categories = [];

        if ($type === 'all') {
            $categories = $this->model->getCategoryTree();
        } else {
            $categories = $this->model->getByType($type);
        }

        // Get tour counts for each category
        foreach ($categories as &$category) {
            $category['tour_count'] = $this->tourModel->select('COUNT(*) as count', 'category_id = :category_id', ['category_id' => $category['id']])[0]['count'] ?? 0;

            if (isset($category['children'])) {
                foreach ($category['children'] as &$child) {
                    $child['tour_count'] = $this->tourModel->select('COUNT(*) as count', 'category_id = :category_id', ['category_id' => $child['id']])[0]['count'] ?? 0;
                }
            }
        }

        $title = 'Quản lý Danh mục Tour';
        require_once PATH_VIEW_ADMIN . 'pages/tours_categories/index.php';
    }

    /**
     * Show create form
     */
    public function create()
    {
        $parentCategories = $this->model->getParentCategories();
        $title = 'Thêm Danh mục Mới';
        require_once PATH_VIEW_ADMIN . 'pages/tours_categories/form.php';
    }

    /**
     * Store new category
     */
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?action=tours_categories');
            return;
        }

        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'icon' => trim($_POST['icon'] ?? ''),
            'type' => $_POST['type'] ?? 'domestic',
            'parent_id' => !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : null,
            'sort_order' => (int)($_POST['sort_order'] ?? 0),
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];

        // Validate data
        $errors = $this->validateCategoryData($data);

        if (!empty($errors)) {
            $_SESSION['form_errors'] = $errors;
            $_SESSION['old_input'] = $data;
            header('Location: ?action=tours_categories/create');
            return;
        }

        try {
            $this->model->insertCategory($data);
            $_SESSION['success'] = 'Thêm danh mục thành công';
            header('Location: ?action=tours_categories');
        } catch (Exception $e) {
            error_log('Error creating tour category: ' . $e->getMessage());
            $_SESSION['error'] = 'Có lỗi xảy ra khi thêm danh mục. Vui lòng thử lại.';
            $_SESSION['old_input'] = $data;
            header('Location: ?action=tours_categories/create');
        }
    }

    /**
     * Show edit form
     */
    public function edit()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'Thiếu thông tin danh mục';
            header('Location: ?action=tours_categories');
            return;
        }

        $category = $this->model->findById($id);
        if (!$category) {
            $_SESSION['error'] = 'Không tìm thấy danh mục';
            header('Location: ?action=tours_categories');
            return;
        }

        $parentCategories = $this->model->getParentCategories();
        $title = 'Chỉnh sửa Danh mục';
        require_once PATH_VIEW_ADMIN . 'pages/tours_categories/form.php';
    }

    /**
     * Update category
     */
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?action=tours_categories');
            return;
        }

        $id = $_POST['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'Thiếu thông tin danh mục';
            header('Location: ?action=tours_categories');
            return;
        }

        $category = $this->model->findById($id);
        if (!$category) {
            $_SESSION['error'] = 'Không tìm thấy danh mục';
            header('Location: ?action=tours_categories');
            return;
        }

        $data = [
            'id' => $id,
            'current_name' => $category['name'],
            'name' => trim($_POST['name'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'icon' => trim($_POST['icon'] ?? ''),
            'type' => $_POST['type'] ?? 'domestic',
            'parent_id' => !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : null,
            'sort_order' => (int)($_POST['sort_order'] ?? 0),
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];

        // Validate data
        $errors = $this->validateCategoryData($data, true);

        if (!empty($errors)) {
            $_SESSION['form_errors'] = $errors;
            $_SESSION['old_input'] = $data;
            header('Location: ?action=tours_categories/edit&id=' . $id);
            return;
        }

        try {
            unset($data['current_name']);
            unset($data['id']);

            $this->model->updateById($id, $data);
            $_SESSION['success'] = 'Cập nhật danh mục thành công';
            header('Location: ?action=tours_categories');
        } catch (Exception $e) {
            error_log('Error updating tour category: ' . $e->getMessage());
            $_SESSION['error'] = 'Có lỗi xảy ra khi cập nhật danh mục. Vui lòng thử lại.';
            $_SESSION['old_input'] = $data;
            header('Location: ?action=tours_categories/edit&id=' . $id);
        }
    }

    /**
     * Delete category
     */
    public function delete()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'Thiếu thông tin danh mục';
            header('Location: ?action=tours_categories');
            return;
        }

        try {
            $this->model->deleteCategory($id);
            $_SESSION['success'] = 'Xóa danh mục thành công';
        } catch (Exception $e) {
            error_log('Error deleting tour category: ' . $e->getMessage());
            $_SESSION['error'] = $e->getMessage();
        }

        header('Location: ?action=tours_categories');
    }

    /**
     * Toggle category status
     */
    public function toggleStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['_method']) || $_POST['_method'] !== 'PATCH') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        $id = $_POST['id'] ?? null;

        if (!$id) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            return;
        }

        try {
            $result = $this->model->toggleStatus($id);

            if ($result) {
                echo json_encode(['success' => true]);
            } else {
                throw new Exception('Failed to toggle status');
            }
        } catch (Exception $e) {
            error_log('Error toggling category status: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Internal server error']);
        }
    }

    /**
     * Get categories for AJAX (for dropdowns, etc.)
     */
    public function getCategoriesAjax()
    {
        $type = $_GET['type'] ?? null;
        $categories = $this->model->getActiveCategories($type);

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'data' => $categories]);
    }
}
