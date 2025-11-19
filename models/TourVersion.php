<?php
require_once 'models/BaseModel.php';

class TourVersion extends BaseModel
{
    protected $table = 'tour_versions';
    protected $columns = [
        'id', 'tour_id', 'name', 'start_date', 'end_date', 'price', 'notes', 'created_at'
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
