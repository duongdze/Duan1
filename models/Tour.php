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
        'pricing_options',
        'itinerary_schedule',
        'partner_services',
        'gallery_images',
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
                LEFT JOIN `suppliers` AS s ON t.supplier_id = s.id";
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFilteredTours(array $filters = [], int $page = 1, int $perPage = 10, string $sortBy = 'created_at', string $sortOrder = 'DESC'): array
    {
        $page = max(1, (int)$page);
        $perPage = max(5, min(50, (int)$perPage));
        $offset = ($page - 1) * $perPage;

        // Validate sort column
        $allowedSortColumns = ['name', 'type', 'base_price', 'created_at', 'updated_at'];
        $sortBy = in_array($sortBy, $allowedSortColumns) ? $sortBy : 'created_at';
        $sortOrder = strtoupper($sortOrder) === 'ASC' ? 'ASC' : 'DESC';

        $conditions = [];
        $params = [];

        if (!empty($filters['keyword'])) {
            $conditions[] = 't.name LIKE :keyword';
            $params['keyword'] = '%' . $filters['keyword'] . '%';
        }

        if (!empty($filters['type'])) {
            $conditions[] = 't.type = :type';
            $params['type'] = $filters['type'];
        }

        if (!empty($filters['supplier_id'])) {
            $conditions[] = 't.supplier_id = :supplier_id';
            $params['supplier_id'] = (int)$filters['supplier_id'];
        }

        if (!empty($filters['date_from'])) {
            $conditions[] = 'DATE(t.created_at) >= :date_from';
            $params['date_from'] = $filters['date_from'];
        }

        if (!empty($filters['date_to'])) {
            $conditions[] = 'DATE(t.created_at) <= :date_to';
            $params['date_to'] = $filters['date_to'];
        }

        $whereSql = '';
        if (!empty($conditions)) {
            $whereSql = 'WHERE ' . implode(' AND ', $conditions);
        }

        $countSql = "SELECT COUNT(*) FROM {$this->table} AS t {$whereSql}";
        $countStmt = self::$pdo->prepare($countSql);
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();

        $sql = "SELECT t.*, s.name AS supplier_name
                FROM {$this->table} AS t
                LEFT JOIN suppliers AS s ON t.supplier_id = s.id
                {$whereSql}
                ORDER BY t.{$sortBy} {$sortOrder}
                LIMIT :limit OFFSET :offset";

        $stmt = self::$pdo->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'data' => $data,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => (int)ceil($total / $perPage),
            'sort_by' => $sortBy,
            'sort_order' => $sortOrder,
        ];
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
