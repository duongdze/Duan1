<?php
require_once 'models/TourLog.php';
require_once 'models/Tour.php';
require_once 'models/Guide.php';

class TourLogController
{
    protected $model;

    public function __construct()
    {
        $this->model = new TourLog();
    }

    public function index()
    {
        $tours = $this->model->getToursWithLogStats();
        require_once PATH_VIEW_ADMIN . 'pages/tours_logs/index.php';
    }

    public function create()
    {
        $tourModel = new Tour();
        // use generic select() to fetch all tours (getAll() not defined in Tour model)
        $tours = $tourModel->select();

        $guideModel = new Guide();
        $guides = $guideModel->getAllWithName();

        require_once PATH_VIEW_ADMIN . 'pages/tours_logs/create.php';
    }

    public function store()
    {
        $data = [
            'tour_id'           => $_POST['tour_id'] ?? null,
            'guide_id'          => $_POST['guide_id'] ?? null,
            'date'              => $_POST['date'] ?? date('Y-m-d'),
            'description'       => $_POST['description'] ?? '',
            'issue'             => $_POST['issue'] ?? '',
            'solution'          => $_POST['solution'] ?? '',
            'customer_feedback' => $_POST['customer_feedback'] ?? '',
            'weather'           => $_POST['weather'] ?? '',
            'incident'          => $_POST['incident'] ?? '',
            'health_status'     => $_POST['health_status'] ?? '',
            'special_activity'  => $_POST['special_activity'] ?? '',
            'handling_notes'    => $_POST['handling_notes'] ?? '',
            'guide_rating'      => $_POST['guide_rating'] ?? null,
        ];

        // enforce guide_id from session to prevent spoofing
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        $data['guide_id'] = $_SESSION['guide_id'] ?? $data['guide_id'];

        $this->model->create($data);
        // Redirect back to tour detail if tour_id is present
        if ($data['tour_id']) {
            header('Location:' . BASE_URL_ADMIN . '&action=tours_logs/tour_detail&id=' . $data['tour_id']);
        } else {
            header('Location:' . BASE_URL_ADMIN . '&action=tours_logs');
        }
        exit;
    }

    public function edit()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            die('Thiếu ID');
        }

        $log = $this->model->findById($id);
        if (!$log) {
            die('Không tìm thấy nhật ký');
        }

        $tourModel = new Tour();
        $tours = $tourModel->select();

        $guideModel = new Guide();
        $guides = $guideModel->getAllWithName();

        require_once PATH_VIEW_ADMIN . 'pages/tours_logs/edit.php';
    }

    public function update()
    {
        $id = $_POST['id'] ?? null;
        if (!$id) {
            die('Thiếu ID');
        }

        $data = [
            'tour_id'           => $_POST['tour_id'] ?? null,
            'guide_id'          => $_POST['guide_id'] ?? null,
            'date'              => $_POST['date'] ?? date('Y-m-d'),
            'description'       => $_POST['description'] ?? '',
            'issue'             => $_POST['issue'] ?? '',
            'solution'          => $_POST['solution'] ?? '',
            'customer_feedback' => $_POST['customer_feedback'] ?? '',
            'weather'           => $_POST['weather'] ?? '',
            'incident'          => $_POST['incident'] ?? '',
            'health_status'     => $_POST['health_status'] ?? '',
            'special_activity'  => $_POST['special_activity'] ?? '',
            'handling_notes'    => $_POST['handling_notes'] ?? '',
            'guide_rating'      => $_POST['guide_rating'] ?? null,
        ];

        // enforce guide_id from session
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        $data['guide_id'] = $_SESSION['guide_id'] ?? $data['guide_id'];

        $this->model->updateLog($id, $data);

        // Redirect back to tour detail if tour_id is present
        if ($data['tour_id']) {
            header('Location:' . BASE_URL_ADMIN . '&action=tours_logs/tour_detail&id=' . $data['tour_id']);
        } else {
            header('Location:' . BASE_URL_ADMIN . '&action=tours_logs');
        }
        exit;
    }

    public function detail()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            die('Thiếu ID');
        }

        $log = $this->model->findById($id);
        if (!$log) {
            die('Không tìm thấy nhật ký');
        }

        require_once PATH_VIEW_ADMIN . 'pages/tours_logs/detail.php';
    }

    public function tourDetail()
    {
        $tourId = $_GET['id'] ?? null;
        if (!$tourId) {
            die('Thiếu Tour ID');
        }

        $tourModel = new Tour();
        $tour = $tourModel->findById($tourId);

        if (!$tour) {
            die('Không tìm thấy Tour');
        }

        $logs = $this->model->getLogsByTourId($tourId);

        // Lấy danh sách khách có yêu cầu đặc biệt
        $bookingCustomerModel = new BookingCustomer();
        $specialRequests = $bookingCustomerModel->getSpecialRequestsByTour($tourId);

        require_once PATH_VIEW_ADMIN . 'pages/tours_logs/tour_detail.php';
    }

    public function delete()
    {
        $id = $_POST['id'] ?? null;
        $tourId = $_POST['tour_id'] ?? null; // Pass tour_id to redirect back correctly

        if ($id) {
            // Get log to find tour_id if not passed
            if (!$tourId) {
                $log = $this->model->findById($id);
                $tourId = $log['tour_id'] ?? null;
            }
            $this->model->deleteById($id);
        }

        if ($tourId) {
            header('Location:' . BASE_URL_ADMIN . '&action=tours_logs/tour_detail&id=' . $tourId);
        } else {
            header('Location:' . BASE_URL_ADMIN . '&action=tours_logs');
        }
        exit;
    }

    /**
     * AJAX endpoint: Đánh dấu yêu cầu đặc biệt đã xử lý
     */
    public function markRequestHandled()
    {
        header('Content-Type: application/json');

        $customerId = $_POST['customer_id'] ?? null;
        $handled = $_POST['handled'] ?? 1;

        if (!$customerId) {
            echo json_encode(['success' => false, 'message' => 'Thiếu customer ID']);
            exit;
        }

        $bookingCustomerModel = new BookingCustomer();
        $result = $bookingCustomerModel->markRequestHandled($customerId, $handled);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Đã cập nhật trạng thái']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Cập nhật thất bại']);
        }
        exit;
    }
}
