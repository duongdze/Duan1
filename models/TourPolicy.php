<?php
require_once 'models/BaseModel.php';

class TourPolicy extends BaseModel
{
    protected $table = 'tour_policies';
    protected $columns = [
        'id',
        'name',
        'slug',
        'description',
        'created_at',
        'updated_at'
    ];

    public function __construct()
    {
        parent::__construct();
    }
    public function findById($id)
    {
        return $this->find('*', 'id = :id', ['id' => $id]);
    }
}
