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
     * Return all tours with pagination, filtering and advanced data
     * @param int $page
     * @param int $perPage
     * @param array $filters
     * @return array
     */
    public function getAllTours($page = 1, $perPage = 10, $filters = [])
    {
        $page = max(1, (int)$page);
        $perPage = max(5, min(50, (int)$perPage));
        $offset = ($page - 1) * $perPage;

        // Build WHERE conditions
        $whereConditions = [];
        $params = [];

        // Keyword search
        if (!empty($filters['keyword'])) {
            $whereConditions[] = "(t.name LIKE :keyword OR t.description LIKE :keyword OR s.name LIKE :keyword)";
            $params[':keyword'] = '%' . $filters['keyword'] . '%';
        }

        // Category filter
        if (!empty($filters['category_id'])) {
            $whereConditions[] = "t.category_id = :category_id";
            $params[':category_id'] = $filters['category_id'];
        }

        // Supplier filter
        if (!empty($filters['supplier_id'])) {
            $whereConditions[] = "t.supplier_id = :supplier_id";
            $params[':supplier_id'] = $filters['supplier_id'];
        }

        // Date range filter
        if (!empty($filters['date_from'])) {
            $whereConditions[] = "DATE(t.created_at) >= :date_from";
            $params[':date_from'] = $filters['date_from'];
        }
        if (!empty($filters['date_to'])) {
            $whereConditions[] = "DATE(t.created_at) <= :date_to";
            $params[':date_to'] = $filters['date_to'];
        }

        // Price range filter
        if (!empty($filters['price_min'])) {
            $whereConditions[] = "t.base_price >= :price_min";
            $params[':price_min'] = $filters['price_min'];
        }
        if (!empty($filters['price_max'])) {
            $whereConditions[] = "t.base_price <= :price_max";
            $params[':price_max'] = $filters['price_max'];
        }

        // Rating filter
        if (!empty($filters['rating_min'])) {
            $whereConditions[] = "COALESCE(avg_rating, 0) >= :rating_min";
            $params[':rating_min'] = $filters['rating_min'];
        }

        $whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';

        // Build ORDER BY
        $orderBy = 't.created_at DESC';
        if (!empty($filters['sort_by'])) {
            $sortBy = $filters['sort_by'];
            $sortDir = strtoupper($filters['sort_dir'] ?? 'DESC');

            switch ($sortBy) {
                case 'name':
                    $orderBy = "t.name $sortDir";
                    break;
                case 'price':
                    $orderBy = "t.base_price $sortDir";
                    break;
                case 'rating':
                    $orderBy = "COALESCE(avg_rating, 0) $sortDir";
                    break;
                case 'created_at':
                default:
                    $orderBy = "t.created_at $sortDir";
                    break;
            }
        }

        // Count query
        $countSql = "SELECT COUNT(DISTINCT t.id) FROM {$this->table} AS t
                     LEFT JOIN `suppliers` AS s ON t.supplier_id = s.id
                     LEFT JOIN `tour_categories` AS tc ON t.category_id = tc.id
                     LEFT JOIN (
                         SELECT tour_id, AVG(rating) as avg_rating
                         FROM tour_feedbacks
                         GROUP BY tour_id
                     ) tf ON t.id = tf.tour_id
                     $whereClause";

        $countStmt = self::$pdo->prepare($countSql);
        foreach ($params as $key => $value) {
            $countStmt->bindValue($key, $value);
        }
        $countStmt->execute();
        $total = (int)$countStmt->fetchColumn();

        // Main query with complex joins
        $sql = "SELECT
                    t.*,
                    s.id AS supplier_id,
                    s.name AS supplier_name,
                    tc.name as category_name,
                    COALESCE(tf.avg_rating, 0) as avg_rating,
                    COALESCE(tb.booking_count, 0) as booking_count,
                    MAX(CASE WHEN tgi.main_img = 1 THEN tgi.image_url ELSE NULL END) AS main_image,
                    GROUP_CONCAT(tgi.image_url ORDER BY tgi.sort_order SEPARATOR ',') as gallery_images,
                    0 as availability_percentage
                FROM {$this->table} AS t
                LEFT JOIN `suppliers` AS s ON t.supplier_id = s.id
                LEFT JOIN `tour_categories` AS tc ON t.category_id = tc.id
                LEFT JOIN (
                    SELECT tour_id, AVG(rating) as avg_rating
                    FROM tour_feedbacks
                    GROUP BY tour_id
                ) tf ON t.id = tf.tour_id
                LEFT JOIN (
                    SELECT tour_id, COUNT(*) as booking_count
                    FROM bookings
                    GROUP BY tour_id
                ) tb ON t.id = tb.tour_id
                LEFT JOIN `tour_gallery_images` tgi ON t.id = tgi.tour_id
                $whereClause
                GROUP BY t.id, s.id, s.name, tc.name, tf.avg_rating, tb.booking_count
                ORDER BY $orderBy
                LIMIT :limit OFFSET :offset";

        $stmt = self::$pdo->prepare($sql);
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'data' => $data,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => (int)ceil($total / $perPage),
            'filters' => $filters,
        ];
    }



    public function findById($id)
    {
        $tour = $this->find('*', 'id = :id', ['id' => $id]);
        if (!$tour) {
            return null;
        }

        return $tour;
    }
    public function getRelatedData(string $tableName, int $tourId): array
    {
        $sql = "SELECT * FROM {$tableName} WHERE tour_id = :tour_id";
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute(['tour_id' => $tourId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createTour($tourData, $pricingOptions = [], $itineraries = [], $partners = [], $uploadedImages = [])
    {
        self::$pdo->beginTransaction(); // BẮT ĐẦU TRANSACTION
        try {
            // 1. INSERT TOUR CƠ BẢN
            $tourId = $this->insert($tourData);

            // 2. INSERT PRICING OPTIONS
            if (!empty($pricingOptions)) {
                $pricingModel = new TourPricing();
                foreach ($pricingOptions as $option) {
                    $pricingModel->insert([
                        'tour_id' => $tourId,
                        'label' => $option['label'] ?? '',
                        'price' => $option['price'] ?? 0,
                        'description' => $option['description'] ?? '',
                    ]);
                }
            }

            // 3. INSERT ITINERARIES
            if (!empty($itineraries)) {
                $itineraryModel = new TourItinerary();
                foreach ($itineraries as $index => $item) {
                    // Tính day_number từ day_label (VD: "Ngày 1" → 1)
                    $dayNumber = $index + 1;
                    if (preg_match('/Ngày\s+(\d+)/i', $item['day'], $matches)) {
                        $dayNumber = (int)$matches[1];
                    }

                    $itineraryModel->insert([
                        'tour_id' => $tourId,
                        'day_label' => $item['day'] ?? '',
                        'day_number' => $dayNumber,
                        'time_start' => $item['time_start'] ?? null,
                        'time_end' => $item['time_end'] ?? null,
                        'title' => $item['title'] ?? '',
                        'description' => $item['description'] ?? '',
                        'activities' => '', // Có thể mở rộng sau
                        'image_url' => '', // Có thể mở rộng sau
                    ]);
                }
            }

            // 4. INSERT IMAGES
            if (!empty($uploadedImages)) {
                $imageModel = new TourImage();
                foreach ($uploadedImages as $index => $image) {
                    $imageModel->insert([
                        'tour_id' => $tourId,
                        'main_img' => $image['is_main'] ? 1 : 0,
                        'image_url' => $image['path'],
                        'caption' => '',
                        'sort_order' => $index + 1,
                    ]);
                }
            }

            // 5. INSERT PARTNERS
            if (!empty($partners)) {
                $partnerModel = new TourPartner();
                foreach ($partners as $partner) {
                    $partnerModel->insert([
                        'tour_id' => $tourId,
                        'service_type' => $partner['service_type'] ?? 'other',
                        'partner_name' => $partner['name'] ?? '',
                        'contact' => $partner['contact'] ?? '',
                        'notes' => $partner['notes'] ?? '',
                    ]);
                }
            }

            self::$pdo->commit(); // COMMIT TRANSACTION
            return $tourId;
        } catch (Exception $e) {
            self::$pdo->rollBack(); // ROLLBACK NẾU CÓ LỖI
            throw $e;
        }
    }


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
