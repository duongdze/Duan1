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
        $data = [
            'checkin_status' => $status,
            'checkin_time' => date('Y-m-d H:i:s'),
            'checked_by' => $userId
        ];

        if ($notes !== null) {
            $data['checkin_notes'] = $notes;
        }

        // Sync với is_present cũ để backward compatibility
        $data['is_present'] = ($status === 'checked_in') ? 1 : 0;

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
}
