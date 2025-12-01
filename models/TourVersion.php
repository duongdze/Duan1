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
     * Get all versions ordered by creation date
     * @return array
     */
    public function getAllVersions()
    {
        return $this->select('*', '1=1 ORDER BY created_at DESC');
    }

    /**
     * Get versions by status
     * @param string $status
     * @return array
     */
    public function getByStatus($status)
    {
        return $this->select('*', 'status = :status ORDER BY created_at DESC', ['status' => $status]);
    }

    /**
     * Get versions with pagination
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getWithPagination($limit = 20, $offset = 0)
    {
        return $this->select('*', '1=1 ORDER BY created_at DESC LIMIT :limit OFFSET :offset', ['limit' => $limit, 'offset' => $offset]);
    }

    /**
     * Count total versions
     * @return int
     */
    public function countTotal()
    {
        $result = $this->select('COUNT(*) as total', '1=1');
        return $result[0]['total'] ?? 0;
    }

    /**
     * Count versions by status
     * @param string $status
     * @return int
     */
    public function countByStatus($status)
    {
        $result = $this->select('COUNT(*) as total', 'status = :status', ['status' => $status]);
        return $result[0]['total'] ?? 0;
    }
}
