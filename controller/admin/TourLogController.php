<?php
// controller/admin/TourLogController.php
require_once 'models/TourLog.php';

class TourLogController {
    protected $model;

    public function __construct() {
        $this->model = new TourLog();
    }

    public function index() {
        $logs = $this->model->all();
        include 'views/admin/tour_logs/tour_logs.php';
    }

    public function create() {
        include 'views/admin/tour_logs/create.php';
    }

    public function store() {
        $data = [
            'tour_id' => $_POST['tour_id'],
            'guide_id' => $_POST['guide_id'],
            'date' => $_POST['date'],
            'description' => $_POST['description'],
            'issue' => $_POST['issue'],
            'solution' => $_POST['solution'],
            'customer_feedback' => $_POST['customer_feedback'],
        ];
        $this->model->create($data);
        header('Location: ?action=tour_logs');
    }

    public function edit() {
        $id = $_GET['id'];
        $log = $this->model->findById($id);
        include 'views/admin/tour_logs/edit.php';
    }

    public function update() {
        $id = $_POST['id'];
        $data = [
            'tour_id' => $_POST['tour_id'],
            'guide_id' => $_POST['guide_id'],
            'date' => $_POST['date'],
            'description' => $_POST['description'],
            'issue' => $_POST['issue'],
            'solution' => $_POST['solution'],
            'customer_feedback' => $_POST['customer_feedback'],
        ];
        $this->model->updateLog($id, $data);
        header('Location: ?action=tour_logs');
    }

    public function delete() {
        $id = $_POST['id'];
        $this->model->deleteById($id);
        header('Location: ?action=tour_logs');
    }
}
