<?php
class TourItinerary extends BaseModel {
    protected $table = 'tour_itinerary';
    protected $columns = [
        'id',
        'tour_id',
        'day_number',
        'description'
    ];
}
