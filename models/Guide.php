<?php
require_once 'models/BaseModel.php';

class Guide extends BaseModel
{
    protected $table = 'guides';
    protected $columns = [
        'id',
        'user_id',
        'languages',
        'experience_years',
        'rating',
        'health_status',
        'notes'
    ];
}
