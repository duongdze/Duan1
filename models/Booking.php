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
                    BC.full_name AS customer_name
                FROM bookings AS B 
                LEFT JOIN tours AS T ON B.tour_id = T.id
                LEFT JOIN booking_customers AS BC ON B.id = BC.booking_id";
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
                    BC.full_name AS customer_name,
                    BC.phone AS customer_phone
                FROM bookings AS B 
                LEFT JOIN tours AS T ON B.tour_id = T.id
                LEFT JOIN booking_customers AS BC ON B.id = BC.booking_id AND BC.passenger_type = 'adult'
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
}
