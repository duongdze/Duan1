<?php
require_once 'models/BaseModel.php';

class Tour extends BaseModel
{
    protected $table = 'tours';
    protected $columns = [
        'id',
        'name',
        'category_id',
        'description',
        'base_price',
        'policy',
        'supplier_id',
        'picture',
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
        $sql = "SELECT t.*, s.id AS supplier_id, s.name AS supplier_name, tc.name as category_name
                FROM {$this->table} AS t
                LEFT JOIN `suppliers` AS s ON t.supplier_id = s.id
                LEFT JOIN `tour_categories` AS tc ON t.category_id = tc.id";
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
        $allowedSortColumns = ['name', 'category_name', 'base_price', 'created_at', 'updated_at'];
        $sortBy = in_array($sortBy, $allowedSortColumns) ? $sortBy : 'created_at';
        $sortOrder = strtoupper($sortOrder) === 'ASC' ? 'ASC' : 'DESC';

        $conditions = [];
        $params = [];
        $joins = "LEFT JOIN suppliers AS s ON t.supplier_id = s.id 
                  LEFT JOIN tour_categories AS tc ON t.category_id = tc.id";

        if (!empty($filters['keyword'])) {
            $conditions[] = 't.name LIKE :keyword';
            $params['keyword'] = '%' . $filters['keyword'] . '%';
        }

        if (!empty($filters['category_id'])) {
            $conditions[] = 't.category_id = :category_id';
            $params['category_id'] = $filters['category_id'];
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

        $countSql = "SELECT COUNT(*) FROM {$this->table} AS t {$joins} {$whereSql}";
        $countStmt = self::$pdo->prepare($countSql);
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();

        $orderBySql = "ORDER BY {$sortBy} {$sortOrder}";
        if ($sortBy === 'category_name') {
            $orderBySql = "ORDER BY tc.name {$sortOrder}";
        } else {
            $orderBySql = "ORDER BY t.{$sortBy} {$sortOrder}";
        }

        $sql = "SELECT t.*, s.name AS supplier_name, tc.name AS category_name
                FROM {$this->table} AS t
                {$joins}
                {$whereSql}
                {$orderBySql}
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

    public function findById($id)
    {
        $tour = $this->find('*', 'id = :id', ['id' => $id]);
        if (!$tour) {
            return null;
        }

        // Lấy dữ liệu từ các bảng liên quan
        $tour['pricing_options'] = $this->getRelatedData('tour_pricing_options', $id);
        $tour['gallery_images'] = $this->getRelatedData('tour_gallery_images', $id);
        $tour['itineraries'] = $this->getRelatedData('itineraries', $id);
        $tour['partner_services'] = $this->getRelatedData('tour_partner_services', $id);

        return $tour;
    }

    private function getRelatedData(string $tableName, int $tourId): array
    {
        $sql = "SELECT * FROM {$tableName} WHERE tour_id = :tour_id";
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute(['tour_id' => $tourId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Create a new tour record from associative array
     */
    public function create(array $data)
    {
        try {
            self::$pdo->beginTransaction();

            // Tách dữ liệu
            $tourData = [
                'name' => $data['name'],
                'category_id' => $data['category_id'],
                'description' => $data['description'],
                'base_price' => $data['base_price'],
                'policy' => $data['policy'],
                'supplier_id' => $data['supplier_id'],
                'picture' => $data['picture'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            // 1. Thêm vào bảng tours
            $tourId = $this->insert($tourData);

            // 2. Thêm vào bảng tour_pricing_options
            if (!empty($data['pricing_options'])) {
                $stmt = self::$pdo->prepare(
                    "INSERT INTO tour_pricing_options (tour_id, label, price, description, created_at) 
                     VALUES (:tour_id, :label, :price, :description, :created_at)"
                );
                foreach ($data['pricing_options'] as $option) {
                    $stmt->execute([
                        ':tour_id' => $tourId,
                        ':label' => $option['label'],
                        ':price' => $option['price'],
                        ':description' => $option['description'],
                        ':created_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            }

            // 3. Thêm vào bảng itineraries
            if (!empty($data['itineraries'])) {
                $stmt = self::$pdo->prepare(
                    "INSERT INTO itineraries (tour_id, day_label, time_start, time_end, title, description) 
                     VALUES (:tour_id, :day_label, :time_start, :time_end, :title, :description)"
                );
                foreach ($data['itineraries'] as $item) {
                    $stmt->execute([
                        ':tour_id' => $tourId,
                        ':day_label' => $item['day'],
                        ':time_start' => !empty($item['time_start']) ? $item['time_start'] : null,
                        ':time_end' => !empty($item['time_end']) ? $item['time_end'] : null,
                        ':title' => $item['title'],
                        ':description' => $item['description'],
                    ]);
                }
            }
            
            // 4. Thêm vào bảng tour_gallery_images
            if (!empty($data['gallery_images'])) {
                $stmt = self::$pdo->prepare(
                    "INSERT INTO tour_gallery_images (tour_id, image_url, created_at) 
                     VALUES (:tour_id, :image_url, :created_at)"
                );
                foreach ($data['gallery_images'] as $imageUrl) {
                    $stmt->execute([
                        ':tour_id' => $tourId,
                        ':image_url' => $imageUrl,
                        ':created_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            }

            // 5. Thêm vào bảng tour_partner_services
            if (!empty($data['partner_services'])) {
                $stmt = self::$pdo->prepare(
                    "INSERT INTO tour_partner_services (tour_id, service_type, partner_name, contact, notes, created_at) 
                     VALUES (:tour_id, :service_type, :partner_name, :contact, :notes, :created_at)"
                );
                foreach ($data['partner_services'] as $service) {
                    $stmt->execute([
                        ':tour_id' => $tourId,
                        ':service_type' => $service['service_type'],
                        ':partner_name' => $service['name'],
                        ':contact' => $service['contact'],
                        ':notes' => $service['notes'],
                        ':created_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            }

            self::$pdo->commit();
            return $tourId;
        } catch (Exception $e) {
            self::$pdo->rollBack();
            // Ghi log lỗi hoặc throw exception để controller bắt
            throw new Exception("Lỗi khi tạo tour: " . $e->getMessage());
        }
    }

    public function updateTour(int $tourId, array $data): bool
    {
        try {
            self::$pdo->beginTransaction();

            // 1. Update the main tour data
            $tourData = [
                'name' => $data['name'],
                'category_id' => $data['category_id'],
                'description' => $data['description'],
                'base_price' => $data['base_price'],
                'policy' => $data['policy'],
                'supplier_id' => $data['supplier_id'],
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            // Only update picture if a new one is provided
            if (!empty($data['picture'])) {
                $tourData['picture'] = $data['picture'];
            }
            $this->update($tourData, 'id = :id', ['id' => $tourId]);

            // 2. Clear and re-insert related data
            $relatedTables = ['tour_pricing_options', 'itineraries', 'tour_gallery_images', 'tour_partner_services'];
            foreach ($relatedTables as $table) {
                $stmt = self::$pdo->prepare("DELETE FROM {$table} WHERE tour_id = :tour_id");
                $stmt->execute([':tour_id' => $tourId]);
            }

            // 3. Insert new related data (reusing logic from create)
            // Insert pricing options
            if (!empty($data['pricing_options'])) {
                $stmt = self::$pdo->prepare("INSERT INTO tour_pricing_options (tour_id, label, price, description, created_at) VALUES (:tour_id, :label, :price, :description, :created_at)");
                foreach ($data['pricing_options'] as $option) {
                    $stmt->execute([':tour_id' => $tourId, ':label' => $option['label'], ':price' => $option['price'], ':description' => $option['description'], ':created_at' => date('Y-m-d H:i:s')]);
                }
            }

            // Insert itineraries
            if (!empty($data['itineraries'])) {
                $stmt = self::$pdo->prepare("INSERT INTO itineraries (tour_id, day_label, time_start, time_end, title, description) VALUES (:tour_id, :day_label, :time_start, :time_end, :title, :description)");
                foreach ($data['itineraries'] as $item) {
                    $stmt->execute([':tour_id' => $tourId, ':day_label' => $item['day'], ':time_start' => !empty($item['time_start']) ? $item['time_start'] : null, ':time_end' => !empty($item['time_end']) ? $item['time_end'] : null, ':title' => $item['title'], ':description' => $item['description']]);
                }
            }

            // Insert gallery images
            if (!empty($data['gallery_images'])) {
                $stmt = self::$pdo->prepare("INSERT INTO tour_gallery_images (tour_id, image_url, created_at) VALUES (:tour_id, :image_url, :created_at)");
                foreach ($data['gallery_images'] as $imageUrl) {
                    $stmt->execute([':tour_id' => $tourId, ':image_url' => $imageUrl, ':created_at' => date('Y-m-d H:i:s')]);
                }
            }

            // Insert partner services
            if (!empty($data['partner_services'])) {
                $stmt = self::$pdo->prepare("INSERT INTO tour_partner_services (tour_id, service_type, partner_name, contact, notes, created_at) VALUES (:tour_id, :service_type, :partner_name, :contact, :notes, :created_at)");
                foreach ($data['partner_services'] as $service) {
                    $stmt->execute([':tour_id' => $tourId, ':service_type' => $service['service_type'], ':partner_name' => $service['name'], ':contact' => $service['contact'], ':notes' => $service['notes'], ':created_at' => date('Y-m-d H:i:s')]);
                }
            }

            self::$pdo->commit();
            return true;
        } catch (Exception $e) {
            self::$pdo->rollBack();
            throw new Exception("Lỗi khi cập nhật tour: " . $e->getMessage());
        }
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
