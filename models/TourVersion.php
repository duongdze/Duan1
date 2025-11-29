<?php
require_once 'models/BaseModel.php';

class TourVersion extends BaseModel
{
    protected $table = 'tour_versions';
    protected $columns = [
        'id',
        'name',
        'status',
        'description',
        'created_at',
        'updated_at'
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

    /**
     * Get active version for a tour
     * @param int $tourId
     * @return array|null
     */
    public function getActiveVersion($tourId)
    {
        return $this->find('*', 'tour_id = :tour_id AND status = "active"', ['tour_id' => $tourId]);
    }

    /**
     * Deactivate all versions except one
     * @param int $tourId
     * @param int $exceptId
     * @return bool
     */
    public function deactivateOthers($tourId, $exceptId)
    {
        return $this->update(['status' => 'inactive'], 'tour_id = :tour_id AND id != :id', ['tour_id' => $tourId, 'id' => $exceptId]);
    }

    /**
     * Get versions by tour ID
     * @param int $tourId
     * @return array
     */
    public function getByTourId($tourId)
    {
        return $this->select('*', 'tour_id = :tour_id ORDER BY created_at DESC', ['tour_id' => $tourId]);
    }
}
