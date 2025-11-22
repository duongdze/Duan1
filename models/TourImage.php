<?php
class TourImage extends BaseModel {
    protected $table = 'tour_images';
    protected $columns = [
        'id',
        'tour_id',
        'image_path'
    ];
}
