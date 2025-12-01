<?php
// require_once 'models/BaseModel.php';

class Booking extends BaseModel
{
    protected $table = 'bookings';
    protected $columns = [
        'id',
        'tour_id',
        'departure_id',
        'version_id',
        'customer_id',
        'adults',
        'children',
        'infants',
        'foc_pax',
        'original_price',
        'final_price',
        'status',
        'source',
        'booking_date',
        'departure_date',
        'notes',
        'discount_note',
        'created_by',
        'created_at',
        'updated_at'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public function getAll()
    {
        $sql = "SELECT 
                    B.*, 
                    T.name AS tour_name, 
                    U.full_name AS customer_name
                FROM bookings AS B 
                LEFT JOIN tours AS T ON B.tour_id = T.id
                LEFT JOIN users AS U ON B.customer_id = U.user_id AND U.role = 'customer'
                ORDER BY B.booking_date DESC, B.id DESC";
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMonthlyRevenue($month, $year)
    {
        $sql = "SELECT SUM(final_price) as revenue FROM {$this->table} WHERE MONTH(booking_date) = :month AND YEAR(booking_date) = :year AND status = 'completed'";
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute(['month' => $month, 'year' => $year]);
        $data = $stmt->fetch();
        return $data['revenue'] ?? 0;
    }

    public function getNewBookingsThisMonth($month, $year)
    {
        $conditions = "MONTH(booking_date) = :month AND YEAR(booking_date) = :year";
        return $this->count($conditions, ['month' => $month, 'year' => $year]);
    }

    public function getNewCustomersThisMonth($month, $year)
    {
        $sql = "SELECT COUNT(DISTINCT BC.full_name) as count 
                FROM bookings B 
                LEFT JOIN booking_customers BC ON B.id = BC.booking_id 
                WHERE MONTH(B.booking_date) = :month AND YEAR(B.booking_date) = :year";
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute(['month' => $month, 'year' => $year]);
        $data = $stmt->fetch();
        return $data['count'] ?? 0;
    }

    /**
     * Lấy các booking đang chờ xác nhận gần đây
     * @param int $limit
     * @return array
     */
    public function getRecentPendingBookings($limit = 5)
    {
        $sql = "SELECT 
                    B.id,
                    B.booking_date,
                    B.status,
                    T.name AS tour_name, 
                    BC.full_name AS customer_name
                FROM {$this->table} AS B 
                LEFT JOIN tours AS T ON B.tour_id = T.id
                LEFT JOIN booking_customers AS BC ON B.id = BC.booking_id
                WHERE B.status = 'pending'
                ORDER BY B.booking_date DESC, B.id DESC
                LIMIT " . (int)$limit;
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy thông tin booking theo ID
     * @param int $id
     * @return array|false
     */
    public function getById($id)
    {
        return $this->find('*', 'id = :id', ['id' => $id]);
    }

    /**
     * Lấy tất cả bookings của một tour
     * @param int $tourId
     * @return array
     */
    public function getByTourId($tourId)
    {
        return $this->select('*', 'tour_id = :tour_id', ['tour_id' => $tourId], 'id ASC');
    }

    /**
     * Lấy thông tin booking chi tiết kèm tour và customer
     * @param int $id
     * @return array|false
     */
    public function getBookingWithDetails($id)
    {
        $sql = "SELECT 
                    B.*, 
                    T.name AS tour_name,
                    T.base_price AS tour_base_price,
                    U.full_name AS customer_name,
                    U.phone AS customer_phone
                FROM bookings AS B 
                LEFT JOIN tours AS T ON B.tour_id = T.id
                LEFT JOIN users AS U ON B.customer_id = U.user_id AND U.role = 'customer'
                WHERE B.id = :id";
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Xóa booking và các dữ liệu liên quan
     * @param int $id
     * @return bool
     */
    public function deleteBooking($id)
    {
        try {
            $this->beginTransaction();

            // Xóa booking customers trước
            $bookingCustomerModel = new BookingCustomer();
            $bookingCustomerModel->deleteByBooking($id);

            // Xóa booking
            $this->delete('id = :id', ['id' => $id]);

            $this->commit();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * Kiểm tra user có quyền sửa booking không
     * @param int $bookingId
     * @param int $userId
     * @param string $userRole - 'admin' hoặc 'guide'
     * @return bool
     */
    public function canUserEditBooking($bookingId, $userId, $userRole)
    {
        // Admin có toàn quyền
        if ($userRole === 'admin') {
            return true;
        }

        // HDV chỉ được sửa booking của tour mình phụ trách
        if ($userRole === 'guide') {
            // Lấy thông tin booking
            $booking = $this->getById($bookingId);
            if (!$booking) {
                return false;
            }

            // Lấy guide_id từ user_id
            $guideModel = new Guide();
            $guide = $guideModel->getByUserId($userId);
            if (!$guide) {
                return false;
            }

            // Kiểm tra HDV có phụ trách tour này không
            $assignmentModel = new TourAssignment();
            return $assignmentModel->isGuideAssignedToTour($guide['id'], $booking['tour_id']);
        }

        return false;
    }

    /**
     * Lấy danh sách bookings của tour mà HDV phụ trách
     * @param int $guideId
     * @return array
     */
    public function getBookingsForGuide($guideId)
    {
        $sql = "SELECT 
                    B.*, 
                    T.name AS tour_name, 
                    U.full_name AS customer_name
                FROM {$this->table} AS B 
                INNER JOIN tour_assignments AS TA ON B.tour_id = TA.tour_id
                LEFT JOIN tours AS T ON B.tour_id = T.id
                LEFT JOIN users AS U ON B.customer_id = U.user_id
                WHERE TA.guide_id = :guide_id
                ORDER BY B.booking_date DESC, B.id DESC";

        $stmt = self::$pdo->prepare($sql);
        $stmt->execute(['guide_id' => $guideId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getAllByRole($userRole, $guideId = null)
    {
        if ($userRole === 'admin') {
            // Admin xem tất cả
            return $this->getAll();
        } elseif ($userRole === 'guide' && $guideId) {
            // HDV chỉ xem bookings của tour mình phụ trách
            return $this->getBookingsForGuide($guideId);
        }

        return [];
    }
    /**
     * Cập nhật trạng thái booking
     * @param int $bookingId
     * @param string $newStatus
     * @return bool
     */
    public function updateStatus($bookingId, $newStatus)
    {
        try {
            // Validate status
            $validStatuses = ['cho_xac_nhan', 'da_coc', 'hoan_tat', 'da_huy'];
            if (!in_array($newStatus, $validStatuses)) {
                return false;
            }

            return $this->update(
                ['status' => $newStatus],
                'id = :id',
                ['id' => $bookingId]
            );
        } catch (Exception $e) {
            error_log('Error updating booking status: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy thống kê booking theo khoảng thời gian
     */
    public function getBookingStats($dateFrom = null, $dateTo = null, $tourId = null, $status = null, $source = null)
    {
        $dateFrom = $dateFrom ?? date('Y-m-01');
        $dateTo = $dateTo ?? date('Y-m-d');

        $whereConditions = ["B.booking_date BETWEEN :date_from AND :date_to"];
        $params = [':date_from' => $dateFrom, ':date_to' => $dateTo];

        if ($tourId) {
            $whereConditions[] = "B.tour_id = :tour_id";
            $params[':tour_id'] = $tourId;
        }

        if ($status) {
            $whereConditions[] = "B.status = :status";
            $params[':status'] = $status;
        }

        if ($source) {
            $whereConditions[] = "B.source = :source";
            $params[':source'] = $source;
        }

        $whereClause = "WHERE " . implode(' AND ', $whereConditions);

        // Tổng booking
        $sql = "SELECT 
                    COUNT(B.id) as total_bookings,
                    SUM(CASE WHEN B.status IN ('completed', 'paid') THEN 1 ELSE 0 END) as successful_bookings,
                    SUM(CASE WHEN B.status IN ('completed', 'paid') THEN B.final_price ELSE 0 END) as total_revenue,
                    SUM(B.adults + B.children + B.infants) as total_customers,
                    AVG(B.adults + B.children + B.infants) as avg_customers_per_booking
                FROM bookings B 
                $whereClause";

        $stmt = self::$pdo->prepare($sql);
        $stmt->execute($params);
        $stats = $stmt->fetch();

        // Tính tỷ lệ thành công và chuyển đổi
        $totalBookings = $stats['total_bookings'] ?? 0;
        $successfulBookings = $stats['successful_bookings'] ?? 0;
        $successRate = $totalBookings > 0 ? ($successfulBookings / $totalBookings) * 100 : 0;

        // Tỷ lệ chuyển đổi (ước tính từ pending -> successful)
        $pendingBookings = $this->count(
            "booking_date BETWEEN :date_from AND :date_to AND status = 'pending'",
            [':date_from' => $dateFrom, ':date_to' => $dateTo]
        );
        $conversionRate = $pendingBookings > 0 ? ($successfulBookings / ($pendingBookings + $successfulBookings)) * 100 : 0;

        // Lấy dữ liệu kỳ trước để tính growth
        $previousStats = $this->getPreviousPeriodStats($dateFrom, $dateTo, $tourId, $status, $source);
        $bookingGrowth = $this->calculateGrowth($totalBookings, $previousStats['total_bookings']);

        return [
            'total_bookings' => $totalBookings,
            'successful_bookings' => $successfulBookings,
            'success_rate' => $successRate,
            'conversion_rate' => $conversionRate,
            'total_revenue' => $stats['total_revenue'] ?? 0,
            'total_customers' => $stats['total_customers'] ?? 0,
            'avg_customers_per_booking' => $stats['avg_customers_per_booking'] ?? 0,
            'booking_growth' => $bookingGrowth
        ];
    }

    /**
     * Lấy báo cáo booking chi tiết
     */
    public function getBookingReport($dateFrom = null, $dateTo = null, $tourId = null, $status = null, $source = null)
    {
        $dateFrom = $dateFrom ?? date('Y-m-01');
        $dateTo = $dateTo ?? date('Y-m-d');

        $whereConditions = ["B.booking_date BETWEEN :date_from AND :date_to"];
        $params = [':date_from' => $dateFrom, ':date_to' => $dateTo];

        if ($tourId) {
            $whereConditions[] = "B.tour_id = :tour_id";
            $params[':tour_id'] = $tourId;
        }

        if ($status) {
            $whereConditions[] = "B.status = :status";
            $params[':status'] = $status;
        }

        if ($source) {
            $whereConditions[] = "B.source = :source";
            $params[':source'] = $source;
        }

        $whereClause = "WHERE " . implode(' AND ', $whereConditions);

        $sql = "SELECT 
                    B.*,
                    T.name AS tour_name,
                    TC.name AS category_name,
                    BC.full_name AS customer_name,
                    BC.phone AS customer_phone
                FROM bookings B
                LEFT JOIN tours T ON B.tour_id = T.id
                LEFT JOIN tour_categories TC ON T.category_id = TC.id
                LEFT JOIN booking_customers BC ON B.id = BC.booking_id AND BC.passenger_type = 'adult'
                $whereClause
                ORDER BY B.booking_date DESC, B.id DESC";

        $stmt = self::$pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy tất cả bookings với filter theo role
     * @param string $userRole - 'admin' hoặc 'guide'
     * @param int|null $guideId - Chỉ cần nếu role là 'guide'
     * @return array
     */
    public function getTopBookedTours($dateFrom = null, $dateTo = null, $limit = 10)
    {
        $dateFrom = $dateFrom ?? date('Y-m-01');
        $dateTo = $dateTo ?? date('Y-m-d');

        $sql = "SELECT 
                    T.id,
                    T.name AS tour_name,
                    COUNT(B.id) AS booking_count,
                    COALESCE(SUM(B.final_price), 0) AS revenue,
                    COALESCE(AVG(B.final_price / (B.adults + B.children + B.infants)), 0) AS avg_price
                FROM tours T
                LEFT JOIN bookings B ON T.id = B.tour_id
                WHERE B.booking_date BETWEEN :date_from AND :date_to
                AND B.status IN ('completed', 'paid')
                GROUP BY T.id, T.name
                ORDER BY booking_count DESC, revenue DESC
                LIMIT " . (int)$limit;

        $stmt = self::$pdo->prepare($sql);
        $stmt->execute([':date_from' => $dateFrom, ':date_to' => $dateTo]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Phân tích booking theo nguồn
     */
    public function getSourceAnalysis($dateFrom = null, $dateTo = null)
    {
        $dateFrom = $dateFrom ?? date('Y-m-01');
        $dateTo = $dateTo ?? date('Y-m-d');

        $sql = "SELECT 
                    B.source,
                    COUNT(B.id) AS booking_count,
                    SUM(CASE WHEN B.status IN ('completed', 'paid') THEN 1 ELSE 0 END) AS successful_bookings,
                    COALESCE(SUM(B.final_price), 0) AS revenue
                FROM bookings B
                WHERE B.booking_date BETWEEN :date_from AND :date_to
                AND B.source IS NOT NULL
                GROUP BY B.source
                ORDER BY booking_count DESC";

        $stmt = self::$pdo->prepare($sql);
        $stmt->execute([':date_from' => $dateFrom, ':date_to' => $dateTo]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Tính tỷ lệ chuyển đổi cho mỗi nguồn
        foreach ($results as &$result) {
            $totalBookings = $result['booking_count'];
            $successfulBookings = $result['successful_bookings'];
            $result['conversion_rate'] = $totalBookings > 0 ? ($successfulBookings / $totalBookings) * 100 : 0;
        }

        return $results;
    }

    /**
     * Lấy dữ liệu booking theo tháng cho biểu đồ
     */
    public function getMonthlyBookingData($year = null, $tourId = null)
    {
        $year = $year ?? date('Y');

        $whereConditions = ["YEAR(B.booking_date) = :year"];
        $params = [':year' => $year];

        if ($tourId) {
            $whereConditions[] = "B.tour_id = :tour_id";
            $params[':tour_id'] = $tourId;
        }

        $whereClause = "WHERE " . implode(' AND ', $whereConditions);

        $sql = "SELECT 
                    MONTH(B.booking_date) as month,
                    COUNT(B.id) as total_bookings,
                    SUM(CASE WHEN B.status IN ('completed', 'paid') THEN 1 ELSE 0 END) as successful_bookings,
                    SUM(CASE WHEN B.status IN ('completed', 'paid') THEN B.final_price ELSE 0 END) as revenue,
                    SUM(B.adults + B.children + B.infants) as total_customers
                FROM bookings B 
                $whereClause
                GROUP BY MONTH(B.booking_date)
                ORDER BY month";

        $stmt = self::$pdo->prepare($sql);
        $stmt->execute($params);
        $monthlyData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Đảm bảo có đủ 12 tháng
        $result = [];
        for ($month = 1; $month <= 12; $month++) {
            $monthData = array_filter($monthlyData, function ($data) use ($month) {
                return $data['month'] == $month;
            });

            if (!empty($monthData)) {
                $result[] = reset($monthData);
            } else {
                $result[] = [
                    'month' => $month,
                    'month_name' => "Tháng " . $month,
                    'total_bookings' => 0,
                    'successful_bookings' => 0,
                    'revenue' => 0,
                    'total_customers' => 0
                ];
            }
        }

        return $result;
    }

    /**
     * Lấy thống kê kỳ trước
     */
    private function getPreviousPeriodStats($dateFrom, $dateTo, $tourId = null, $status = null, $source = null)
    {
        // Tính khoảng thời gian kỳ trước
        $days = (strtotime($dateTo) - strtotime($dateFrom)) / (60 * 60 * 24) + 1;
        $prevDateTo = date('Y-m-d', strtotime($dateFrom . ' -1 day'));
        $prevDateFrom = date('Y-m-d', strtotime($prevDateTo . ' -' . ($days - 1) . ' days'));

        return $this->getBookingStats($prevDateFrom, $prevDateTo, $tourId, $status, $source);
    }

    /**
     * Tính tỷ lệ tăng trưởng
     */
    private function calculateGrowth($current, $previous)
    {
        if ($previous == 0) return $current > 0 ? 100 : 0;
        return (($current - $previous) / $previous) * 100;
    }
}
