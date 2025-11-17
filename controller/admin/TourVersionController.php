<?php
require_once 'models/admin/TourVersion.php';

class TourVersionController {
    protected $model;
    
    public function __construct() {
        $this->model = new TourVersion();
    }
    
    public function index() {
        $versions = $this->model->select();
        require_once 'views/admin/tours/versions/index.php';
    }
}