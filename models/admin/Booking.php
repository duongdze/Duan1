<?php
require_once 'models/BaseModel.php';

class Booking extends BaseModel
{
    protected $table = 'bookings';
    protected $columns = [
        'id',
        'tour_id',
        'customer_id',
        'version_id',
        'booking_date',
        'total_price',
        'status',
        'notes'
    ];
}
