<?php
require_once 'BaseModel.php';

class TourDynamicPricing extends BaseModel
{
    protected $table = 'tour_dynamic_pricing';
    protected $columns = [
        'id',
        'tour_id',
        'pricing_option_id',
        'start_date',
        'end_date',
        'price',
        'notes',
        'created_at',
    ];

    public function getByTourId($tourId)
    {
        return $this->select('*', 'tour_id = :tour_id', ['tour_id' => $tourId], 'id ASC');
    }
}
