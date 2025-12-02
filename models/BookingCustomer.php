<?php

class BookingCustomer extends BaseModel
{
    protected $table = 'booking_customers';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Lấy danh sách khách hàng của một đơn đặt
     * 
     * @param int $booking_id
     * @return array
     */
    public function getByBooking($booking_id)
    {
        return $this->select('*', 'booking_id = :booking_id', ['booking_id' => $booking_id], 'id ASC');
    }

    /**
     * Xóa tất cả khách hàng của một đơn đặt
     * 
     * @param int $booking_id
     * @return int
     */
    public function deleteByBooking($booking_id)
    {
        return $this->delete('booking_id = :booking_id', ['booking_id' => $booking_id]);
    }

    /**
     * Cập nhật trạng thái check-in
     * 
     * @param int $customerId
     * @param string $status - 'not_arrived', 'checked_in', 'absent'
     * @param int $userId - ID của user thực hiện check-in
     * @param string|null $notes
     * @return bool
     */
    public function updateCheckinStatus($customerId, $status, $userId, $notes = null)
    {
        // Set timezone to Vietnam
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        $data = [
            'checkin_status' => $status,
            'checkin_time' => date('Y-m-d H:i:s'),
            'checked_by' => $userId
        ];

        if ($notes !== null) {
            $data['checkin_notes'] = $notes;
        }

        return $this->update($data, 'id = :id', ['id' => $customerId]);
    }

    /**
     * Lấy danh sách khách với thông tin check-in
     * 
     * @param int $bookingId
     * @return array
     */
    public function getCustomersWithCheckinStatus($bookingId)
    {
        $sql = "SELECT bc.*, 
                u.full_name as checked_by_name
                FROM {$this->table} bc
                LEFT JOIN users u ON bc.checked_by = u.user_id
                WHERE bc.booking_id = :booking_id
                ORDER BY bc.passenger_type DESC, bc.full_name ASC";

        $stmt = self::$pdo->prepare($sql);
        $stmt->execute(['booking_id' => $bookingId]);
        return $stmt->fetchAll();
    }

    /**
     * Thống kê check-in theo booking
     * 
     * @param int $bookingId
     * @return array
     */
    public function getCheckinStats($bookingId)
    {
        $sql = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN checkin_status = 'checked_in' THEN 1 ELSE 0 END) as checked_in,
                SUM(CASE WHEN checkin_status = 'not_arrived' THEN 1 ELSE 0 END) as not_arrived,
                SUM(CASE WHEN checkin_status = 'absent' THEN 1 ELSE 0 END) as absent
                FROM {$this->table}
                WHERE booking_id = :booking_id";

        $stmt = self::$pdo->prepare($sql);
        $stmt->execute(['booking_id' => $bookingId]);
        return $stmt->fetch();
    }

    /**
     * Lấy danh sách khách có yêu cầu đặc biệt theo tour
     * 
     * @param int $tourId
     * @return array
     */
    public function getSpecialRequestsByTour($tourId)
    {
        $sql = "SELECT bc.*, 
                b.departure_date,
                b.id as booking_id
                FROM {$this->table} bc
                INNER JOIN bookings b ON bc.booking_id = b.id
                WHERE b.tour_id = :tour_id
                AND bc.special_request IS NOT NULL
                AND bc.special_request != ''
                ORDER BY b.departure_date ASC, bc.full_name ASC";

        $stmt = self::$pdo->prepare($sql);
        $stmt->execute(['tour_id' => $tourId]);
        return $stmt->fetchAll();
    }

    /**
     * Đánh dấu yêu cầu đặc biệt đã được xử lý
     * 
     * @param int $customerId
     * @param int $handled (0 = chưa xử lý, 1 = đã xử lý)
     * @return bool
     */
    public function markRequestHandled($customerId, $handled = 1)
    {
        return $this->update(
            ['request_handled' => $handled],
            'id = :id',
            ['id' => $customerId]
        );
    }
}
