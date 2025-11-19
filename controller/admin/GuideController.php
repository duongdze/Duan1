<?php
// require_once 'models/admin/Guide.php';

class GuideController
{
    protected $model;

    public function __construct()
    {
        $this->model = new Guide();
    }

    public function index()
    {
        $guides = $this->model->select();
        require_once PATH_VIEW_ADMIN . 'pages/guides/index.php';
    }

    public function create()
    {
        require_once PATH_VIEW_ADMIN . 'pages/guides/create.php';
    }

    public function store()
    {
        // Implementation
    }
}
