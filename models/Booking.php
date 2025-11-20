<?php
// require_once 'models/BaseModel.php';

class Booking extends BaseModel
{
    protected $table = 'bookings';
    protected $columns = [
        'id',
        'tour_id',
        'customer_id',
        'version_id',
        'booking_date',
        'total_price',
        'status',
        'notes'
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
                    BC.name AS customer_name
                FROM bookings AS B 
                LEFT JOIN tours AS T ON B.tour_id = T.id
                LEFT JOIN booking_customers AS BC ON B.customer_id = BC.id";
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMonthlyRevenue($month, $year)
    {
        $sql = "SELECT SUM(total_price) as revenue FROM {$this->table} WHERE MONTH(booking_date) = :month AND YEAR(booking_date) = :year AND status = 'completed'";
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
        $sql = "SELECT COUNT(DISTINCT customer_id) as count FROM {$this->table} WHERE MONTH(booking_date) = :month AND YEAR(booking_date) = :year";
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute(['month' => $month, 'year' => $year]);
        $data = $stmt->fetch();
        return $data['count'] ?? 0;
    }
}
