<?php
require_once 'models/BaseModel.php';

class TourCategory extends BaseModel
{
    protected $table = 'tour_categories';
    protected $columns = [
        'id',
        'name',
        'slug',
        'description',
        'icon',
        'created_at',
        'updated_at'
    ];
}
