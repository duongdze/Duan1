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
}
