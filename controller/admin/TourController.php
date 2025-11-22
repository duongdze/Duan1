<?php
require_once 'models/Tour.php';
require_once 'models/TourPricing.php';
require_once 'models/TourItinerary.php';
require_once 'models/TourPartner.php';
require_once 'models/TourImage.php';
require_once 'models/TourCategory.php';
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
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $perPage = isset($_GET['per_page']) ? max(5, min(50, (int)$_GET['per_page'])) : 12;

        // Build filters from GET parameters
        $filters = [];

        // Search keyword
        if (!empty($_GET['keyword'])) {
            $filters['keyword'] = trim($_GET['keyword']);
        }

        // Category filter
        if (!empty($_GET['category_id'])) {
            $filters['category_id'] = (int)$_GET['category_id'];
        }

        // Supplier filter
        if (!empty($_GET['supplier_id'])) {
            $filters['supplier_id'] = (int)$_GET['supplier_id'];
        }

        // Date range filters
        if (!empty($_GET['date_from'])) {
            $filters['date_from'] = $_GET['date_from'];
        }
        if (!empty($_GET['date_to'])) {
            $filters['date_to'] = $_GET['date_to'];
        }

        // Price range filters
        if (!empty($_GET['price_min'])) {
            $filters['price_min'] = (float)$_GET['price_min'];
        }
        if (!empty($_GET['price_max'])) {
            $filters['price_max'] = (float)$_GET['price_max'];
        }

        // Rating filter
        if (!empty($_GET['rating_min'])) {
            $filters['rating_min'] = (float)$_GET['rating_min'];
        }

        // Sorting
        if (!empty($_GET['sort_by'])) {
            $filters['sort_by'] = $_GET['sort_by'];
            $filters['sort_dir'] = $_GET['sort_dir'] ?? 'DESC';
        }

        $result = $this->model->getAllTours($page, $perPage, $filters);
        $tours = $result['data'];
        $pagination = [
            'total' => $result['total'],
            'page' => $result['page'],
            'per_page' => $result['per_page'],
            'total_pages' => $result['total_pages'],
        ];

        // Get filter options for dropdowns
        $categoryModel = new TourCategory();
        $categories = $categoryModel->select();

        $supplierModel = new Supplier();
        $suppliers = $supplierModel->select();

        // Get statistics for header
        $stats = $this->getTourStatistics();

        require_once PATH_VIEW_ADMIN . 'pages/tours/index.php';
    }

    private function getTourStatistics()
    {
        // Total tours
        $totalTours = $this->model->count();

        // Active tours (ongoing)
        $activeTours = $this->model->getOngoingTours();

        // Total bookings
        $bookingModel = new Booking();
        $totalBookings = $bookingModel->count();

        // Average rating
        $avgRatingSql = "SELECT ROUND(AVG(rating), 1) as avg_rating FROM tour_feedbacks";
        $stmt = BaseModel::getPdo()->prepare($avgRatingSql);
        $stmt->execute();
        $avgRating = $stmt->fetch()['avg_rating'] ?? 0;

        return [
            'total_tours' => $totalTours,
            'active_tours' => $activeTours,
            'total_bookings' => $totalBookings,
            'avg_rating' => $avgRating
        ];
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

    public function store()
    {
        // Validate required fields
        $requiredFields = ['name', 'category_id', 'supplier_id', 'base_price'];
        foreach ($requiredFields as $field) {
            if (empty($_POST[$field])) {
                $_SESSION['error'] = "Trường {$field} là bắt buộc.";
                header('Location: ' . BASE_URL_ADMIN . '&action=tours/create');
                return;
            }
        }

        try {
            // Prepare tour basic data
            $tourData = [
                'name' => trim($_POST['name']),
                'category_id' => (int)$_POST['category_id'],
                'supplier_id' => (int)$_POST['supplier_id'],
                'description' => $_POST['description'] ?? '',
                'base_price' => (float)$_POST['base_price'],
                'policy' => $_POST['policy'] ?? '',
            ];

            // Handle image uploads
            $uploadedImages = [];
            if (!empty($_FILES['image_url']['name'][0])) {
                $uploadDir = PATH_ROOT . 'assets/uploads/tours/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                foreach ($_FILES['image_url']['tmp_name'] as $index => $tmpName) {
                    if (!empty($tmpName)) {
                        $originalName = $_FILES['image_url']['name'][$index];
                        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
                        $newName = uniqid('tour_') . '.' . $extension;
                        $filePath = $uploadDir . $newName;

                        if (move_uploaded_file($tmpName, $filePath)) {
                            $uploadedImages[] = [
                                'path' => 'assets/uploads/tours/' . $newName,
                                'is_main' => ($index === 0) ? true : false
                            ];
                        }
                    }
                }
            }

            // Parse JSON data from form
            $pricingOptions = json_decode($_POST['tour_pricing_options'] ?? '[]', true);
            $itineraries = json_decode($_POST['tour_itinerary'] ?? '[]', true);
            $partners = json_decode($_POST['tour_partners'] ?? '[]', true);

            // Create tour with all related data
            $tourId = $this->model->createTour($tourData, $pricingOptions, $itineraries, $partners, $uploadedImages);

            $_SESSION['success'] = 'Tour đã được tạo thành công!';
            header('Location: ' . BASE_URL_ADMIN . '&action=tours');
            exit;
        } catch (Exception $e) {
            $_SESSION['error'] = 'Có lỗi xảy ra khi tạo tour: ' . $e->getMessage();
            header('Location: ' . BASE_URL_ADMIN . '&action=tours/create');
            exit;
        }
    }

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
