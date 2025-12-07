<?php
require_once 'models/BaseModel.php';

class TourDeparture extends BaseModel
{
    protected $table = 'tour_departures';
    protected $columns = [
        'id',
        'tour_id',
        'departure_date',
        'max_seats',
        'price_adult',
        'price_child',
        'status',
        'created_at'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get departures by tour ID
     */
    public function getByTourId($tourId)
    {
        return $this->select('*', 'tour_id = :tid', ['tid' => $tourId], 'departure_date ASC');
    }

    /**
     * Lấy lịch khởi hành gần nhất (>= hôm nay) cho tour
     * @param int $tourId
     * @return array|null
     */
    public function getNextDepartureByTourId($tourId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE tour_id = :tid AND departure_date >= CURDATE() AND (status = 'open' OR status = 'guaranteed') ORDER BY departure_date ASC LIMIT 1";
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute(['tid' => $tourId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
    public function findById($id)
    {
        $item = $this->find('*', 'id = :id', ['id' => $id]);
        return $item ?: null;
    }
}
