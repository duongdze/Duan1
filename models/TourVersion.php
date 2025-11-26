<?php
require_once 'models/BaseModel.php';

class TourVersion extends BaseModel
{
    protected $table = 'tour_versions';
    protected $columns = [
        'id', 'tour_id', 'name', 'start_date', 'end_date', 'price', 'notes', 'created_at'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Find a tour version by ID
     * @param int $id
     * @return array|null
     */
    public function findById($id)
    {
        return $this->find('*', 'id = :id', ['id' => $id]);
    }

    /**
     * Update a tour version by ID
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateById($id, $data)
    {
        return $this->update($data, 'id = :id', ['id' => $id]);
    }
}
