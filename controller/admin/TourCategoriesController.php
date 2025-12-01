<?php

class TourCategoriesController
{
    protected $model;

    public function __construct()
    {
        $this->model = new TourCategory();
    }

    
    public function index()
    {
        try {
            // Get all categories
            $categories = $this->model->getAllCategories();

            // Enrich with tour counts
            $enrichedCategories = [];
            foreach ($categories as $category) {
                $catWithCount = $this->model->getCategoryWithTourCount($category['id']);
                $enrichedCategories[] = $catWithCount;
            }

            $categories = $enrichedCategories;

            require_once PATH_VIEW_ADMIN . 'pages/tours_categories/index.php';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi khi tải danh sách danh mục: ' . $e->getMessage();
            require_once PATH_VIEW_ADMIN . 'pages/tours_categories/index.php';
        }
    }

    
    public function create()
    {
        try {
            $category = null;
            require_once PATH_VIEW_ADMIN . 'pages/tours_categories/form.php';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            header('Location: ' . BASE_URL_ADMIN . '&action=tours_categories');
            exit;
        }
    }

    
    public function store()
    {
        try {
        
            if (empty($_POST['name'])) {
                $_SESSION['error'] = 'Tên danh mục không được để trống';
                header('Location: ' . BASE_URL_ADMIN . '&action=tours_categories/create');
                exit;
            }

            $data = [
                'name' => trim($_POST['name']),
                'slug' => !empty($_POST['slug']) ? trim($_POST['slug']) : null,
                'description' => !empty($_POST['description']) ? trim($_POST['description']) : null,
                'icon' => !empty($_POST['icon']) ? trim($_POST['icon']) : 'fas fa-folder',
            ];

            $id = $this->model->insertCategory($data);

            if ($id) {
                $_SESSION['success'] = 'Danh mục tour đã được tạo thành công!';
                header('Location: ' . BASE_URL_ADMIN . '&action=tours_categories');
                exit;
            } else {
                throw new Exception('Không thể tạo danh mục');
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi khi tạo danh mục: ' . $e->getMessage();
            $_SESSION['old_input'] = $_POST;
            header('Location: ' . BASE_URL_ADMIN . '&action=tours_categories/create');
            exit;
        }
    }

    
    public function edit()
    {
        try {
            $id = $_GET['id'] ?? null;

            if (!$id) {
                throw new Exception('ID danh mục không hợp lệ');
            }

            $category = $this->model->findById($id);

            if (!$category) {
                throw new Exception('Danh mục không tồn tại');
            }

            require_once PATH_VIEW_ADMIN . 'pages/tours_categories/form.php';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            header('Location: ' . BASE_URL_ADMIN . '&action=tours_categories');
            exit;
        }
    }

    
    public function update()
    {
        try {
            $id = $_POST['id'] ?? null;

            if (!$id) {
                throw new Exception('ID danh mục không hợp lệ');
            }

            if (empty($_POST['name'])) {
                $_SESSION['error'] = 'Tên danh mục không được để trống';
                header('Location: ' . BASE_URL_ADMIN . '&action=tours_categories/edit&id=' . $id);
                exit;
            }

            $category = $this->model->findById($id);
            if (!$category) {
                throw new Exception('Danh mục không tồn tại');
            }

            $data = [
                'name' => trim($_POST['name']),
                'slug' => !empty($_POST['slug']) ? trim($_POST['slug']) : null,
                'description' => !empty($_POST['description']) ? trim($_POST['description']) : null,
                'icon' => !empty($_POST['icon']) ? trim($_POST['icon']) : 'fas fa-folder',
            ];

            $result = $this->model->updateById($id, $data);

            if ($result) {
                $_SESSION['success'] = 'Danh mục tour đã được cập nhật thành công!';
                header('Location: ' . BASE_URL_ADMIN . '&action=tours_categories');
                exit;
            } else {
                throw new Exception('Không thể cập nhật danh mục');
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi khi cập nhật danh mục: ' . $e->getMessage();
            header('Location: ' . BASE_URL_ADMIN . '&action=tours_categories/edit&id=' . $_POST['id']);
            exit;
        }
    }

    /**
     * Delete category
     */
    public function delete()
    {
        try {
            $id = $_GET['id'] ?? null;

            if (!$id) {
                throw new Exception('ID danh mục không hợp lệ');
            }

            $category = $this->model->findById($id);
            if (!$category) {
                throw new Exception('Danh mục không tồn tại');
            }

            // Try to delete (will check if has tours)
            $result = $this->model->deleteCategory($id);

            if ($result) {
                $_SESSION['success'] = 'Danh mục tour đã được xóa thành công!';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        header('Location: ' . BASE_URL_ADMIN . '&action=tours_categories');
        exit;
    }
}
