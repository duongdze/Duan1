<?php
require_once 'BaseModel.php';

class TourPolicyAssignment extends BaseModel
{
    protected $table = 'tour_policy_assignments';
    protected $columns = [
        'id',
        'tour_id',
        'policy_id',
        'created_at',
    ];

    public function getByTourId($tourId)
    {
        return $this->select('*', 'tour_id = :tour_id', ['tour_id' => $tourId], 'id ASC');
    }
}
