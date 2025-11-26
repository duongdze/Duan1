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
        $logs = $this->model->all();
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
        header('Location:' . BASE_URL_ADMIN . '&action=tours_logs');
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
        header('Location:' . BASE_URL_ADMIN . '&action=tours_logs');
        exit;
    }

    public function delete()
    {
        $id = $_POST['id'] ?? null;
        if ($id) {
            $this->model->deleteById($id);
        }
        header('Location:' . BASE_URL_ADMIN . '&action=tours_logs');
        exit;
    }
}
