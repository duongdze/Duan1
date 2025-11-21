<?php
require_once 'models/Tour.php';
require_once 'models/TourPricing.php';
require_once 'models/TourItinerary.php';
require_once 'models/TourPartner.php';
require_once 'models/TourImage.php';

class TourController
{
    protected $model;

    public function __construct()
    {
        $this->model = new Tour();
    }

    public function index()
    {
        $filters = [
            'keyword' => trim($_GET['keyword'] ?? ''),
            'category_id' => $_GET['category_id'] ?? '',
            'supplier_id' => $_GET['supplier_id'] ?? '',
            'date_from' => $_GET['date_from'] ?? '',
            'date_to' => $_GET['date_to'] ?? '',
            'sort_by' => $_GET['sort_by'] ?? 'created_at',
            'sort_dir' => $_GET['sort_dir'] ?? 'desc',
        ];

        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $perPage = isset($_GET['per_page']) ? max(5, min(50, (int)$_GET['per_page'])) : 10;

        $sortBy = $filters['sort_by'];
        $sortOrder = $filters['sort_dir'];

        $result = $this->model->getFilteredTours($filters, $page, $perPage, $sortBy, $sortOrder);
        $tours = $result['data'];
        $pagination = [
            'total' => $result['total'],
            'page' => $result['page'],
            'per_page' => $result['per_page'],
            'total_pages' => $result['total_pages'],
        ];

        $supplierModel = new Supplier();
        $suppliers = $supplierModel->select();

        $categoryModel = new TourCategory();
        $categories = $categoryModel->select();

        $filters['supplier_id'] = $filters['supplier_id'] !== '' ? (int)$filters['supplier_id'] : '';
        $filters['category_id'] = $filters['category_id'] !== '' ? (int)$filters['category_id'] : '';

        require_once PATH_VIEW_ADMIN . 'pages/tours/index.php';
    }

    public function create()
    {

        
        // Load suppliers for supplier dropdown
        $supplierModel = new Supplier();
        $suppliers = $supplierModel->select();

        // Load categories for category dropdown
        $categoryModel = new TourCategory();
        $categories = $categoryModel->select();


        require_once PATH_VIEW_ADMIN . 'pages/tours/create.php';
    }

    public function store() {}

    public function edit()
    {

        require_once PATH_VIEW_ADMIN . 'pages/tours/edit.php';
    }

    public function update() {}

    public function delete()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location:' . BASE_URL_ADMIN . '&action=tours');
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

        header('Location:' . BASE_URL_ADMIN . '&action=tours');
    }
    public function detail()
    {
        require_once PATH_VIEW_ADMIN . 'pages/tours/detail.php';
    }
}
