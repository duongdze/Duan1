<?php
require_once 'models/BaseModel.php';

class TourPricing extends BaseModel {
    protected $table = 'tour_pricing';
    protected $columns = [
        'id',
        'tour_id',
        'tier_name',
        'price',
        'description'
    ];
}
