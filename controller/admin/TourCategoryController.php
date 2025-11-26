<?php
require_once 'models/TourCategory.php';

class TourCategoryController
{
    protected $model;

    public function __construct()
    {
        $this->model = new TourCategory();
    }

    public function index()
    {
        require_once PATH_VIEW_ADMIN . 'pages/tours_categories/index.php';
    }

    public function create()
    {
        require_once PATH_VIEW_ADMIN . 'pages/tours_categories/create.php';
    }

    public function store() {}

    public function edit()
    {
        require_once PATH_VIEW_ADMIN . 'pages/tours_categories/edit.php';
    }

    public function update() {}

    public function delete() {}
}
