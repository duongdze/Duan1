<?php
class TourPartner extends BaseModel {
    protected $table = 'tour_partners';
    protected $columns = [
        'id',
        'tour_id',
        'name',
        'contact'
    ];
}
