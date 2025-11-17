<?php
require_once 'models/BaseModel.php';

class Tour extends BaseModel
{
    protected $table = 'tours';
    protected $columns = [
        'id',
        'name',
        'type',
        'description',
        'base_price',
        'policy',
        'supplier_id',
        'created_at',
        'updated_at'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Return all tours
     * @return array
     */
    public function getAll()
    {
        $sql = "SELECT t.*, s.id AS supplier_id, s.name AS supplier_name
                FROM {$this->table} AS t
                INNER JOIN `suppliers` AS s ON t.supplier_id = s.id";
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Find one tour by id
     */
    public function findById($id)
    {
        return $this->find('*', 'id = :id', ['id' => $id]);
    }

    /**
     * Create a new tour record from associative array
     */
    public function create(array $data)
    {
        return $this->insert($data);
    }

    /**
     * Update tour by id
     */
    public function updateById($id, array $data)
    {
        return $this->update($data, 'id = :id', ['id' => $id]);
    }

    /**
     * Delete tour by id
     */
    public function deleteById($id)
    {
        return $this->delete('id = :id', ['id' => $id]);
    }

    public function getOngoingTours()
    {
        $today = date('Y-m-d');
        $sql = "SELECT COUNT(DISTINCT t.id) as count FROM {$this->table} t
                INNER JOIN tour_versions tv ON t.id = tv.tour_id
                WHERE tv.start_date <= :today AND tv.end_date >= :today";
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute(['today' => $today]);
        $data = $stmt->fetch();
        return $data['count'] ?? 0;
    }
}
