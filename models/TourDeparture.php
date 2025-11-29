<?php
require_once 'models/BaseModel.php';

class TourDeparture extends BaseModel
{
    protected $table = 'tour_departures';
    protected $columns = [
        'id',
        'tour_id',
        'departure_date',
        'max_seats',
        'price_adult',
        'price_child',
        'status',
        'created_at'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get departures by tour ID
     */
    public function getByTourId($tourId)
    {
        return $this->select('*', 'tour_id = :tid', ['tid' => $tourId], 'departure_date ASC');
    }
}
