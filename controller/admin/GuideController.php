<?php
require_once 'models/admin/Guide.php';

class GuideController {
    protected $model;
    
    public function __construct() {
        $this->model = new Guide();
    }
    
    public function index() {
        $guides = $this->model->select();
        require_once 'views/admin/guides/index.php';
    }
    
    public function create() {
        require_once 'views/admin/guides/create.php';
    }
    
    public function store() {
        // Implementation
    }
}