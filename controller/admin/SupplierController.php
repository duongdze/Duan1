<?php
require_once 'models/admin/Supplier.php';

class SupplierController {
    protected $model;
    
    public function __construct() {
        $this->model = new Supplier();
    }
    
    public function index() {
        $suppliers = $this->model->select();
        require_once 'views/admin/suppliers/index.php';
    }
    
    public function create() {
        require_once 'views/admin/suppliers/create.php';
    }
    
    public function store() {
        // Implementation
    }
}