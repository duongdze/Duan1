<?php
require_once 'models/BaseModel.php';

/**
 * Model quản lý phân công tour cho hướng dẫn viên
 * Sử dụng bảng tour_assignments có sẵn
 */
class TourAssignment extends BaseModel
{
    protected $table = 'tour_assignments';
    protected $columns = [
        'id',
        'tour_id',
        'guide_id',
        'driver_name',
        'start_date',
        'end_date',
        'status'
    ];

    /**
     * Phân công tour cho HDV
     * @param int $guideId
     * @param int $tourId
     * @param string|null $startDate
     * @param string|null $endDate
     * @param string $status
     * @return int|false - ID của assignment hoặc false nếu thất bại
     */
    public function assignTourToGuide($guideId, $tourId, $startDate = null, $endDate = null, $status = 'active')
    {
        try {
            return $this->insert([
                'guide_id' => $guideId,
                'tour_id' => $tourId,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => $status,
                'driver_name' => null
            ]);
        } catch (Exception $e) {
            error_log('Error assigning tour to guide: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy danh sách tour của một HDV (đang active)
     * @param int $guideId
     * @return array
     */
    public function getToursByGuide($guideId)
    {
        $sql = "SELECT 
                    ta.*,
                    t.id as tour_id,
                    t.name as tour_name,
                    t.base_price,
                    t.description
                FROM {$this->table} AS ta
                LEFT JOIN tours AS t ON ta.tour_id = t.id
                WHERE ta.guide_id = :guide_id
                ORDER BY ta.start_date DESC";

        $stmt = self::$pdo->prepare($sql);
        $stmt->execute(['guide_id' => $guideId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy danh sách HDV của một tour
     * @param int $tourId
     * @return array
     */
    public function getGuidesByTour($tourId)
    {
        $sql = "SELECT 
                    ta.*,
                    g.id as guide_id,
                    u.full_name as guide_name,
                    u.email as guide_email,
                    u.phone as guide_phone,
                    g.languages,
                    g.experience_years
                FROM {$this->table} AS ta
                LEFT JOIN guides AS g ON ta.guide_id = g.id
                LEFT JOIN users AS u ON g.user_id = u.user_id
                WHERE ta.tour_id = :tour_id
                ORDER BY ta.start_date DESC";

        $stmt = self::$pdo->prepare($sql);
        $stmt->execute(['tour_id' => $tourId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Hủy phân công tour cho HDV
     * @param int $id - ID của tour_assignment
     * @return bool
     */
    public function removeAssignment($id)
    {
        try {
            return $this->delete('id = :id', ['id' => $id]);
        } catch (Exception $e) {
            error_log('Error removing tour assignment: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Kiểm tra HDV có phụ trách tour không (bất kỳ thời điểm nào)
     * @param int $guideId
     * @param int $tourId
     * @return bool
     */
    public function isGuideAssignedToTour($guideId, $tourId)
    {
        $result = $this->find('id', 'guide_id = :guide_id AND tour_id = :tour_id', [
            'guide_id' => $guideId,
            'tour_id' => $tourId
        ]);
        return !empty($result);
    }

    /**
     * Lấy tất cả phân công với thông tin chi tiết
     * @return array
     */
    public function getAllAssignments()
    {
        $sql = "SELECT 
                    ta.*,
                    g.id as guide_id,
                    u.full_name as guide_name,
                    u.email as guide_email,
                    t.id as tour_id,
                    t.name as tour_name
                FROM {$this->table} AS ta
                LEFT JOIN guides AS g ON ta.guide_id = g.id
                LEFT JOIN users AS u ON g.user_id = u.user_id
                LEFT JOIN tours AS t ON ta.tour_id = t.id
                ORDER BY ta.start_date DESC";

        $stmt = self::$pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Cập nhật trạng thái assignment
     * @param int $id
     * @param string $status
     * @return bool
     */
    public function updateStatus($id, $status)
    {
        try {
            return $this->update(
                ['status' => $status],
                'id = :id',
                ['id' => $id]
            );
        } catch (Exception $e) {
            error_log('Error updating assignment status: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy các tour assignments đang active của HDV
     * @param int $guideId
     * @return array
     */
    public function getActiveAssignmentsByGuide($guideId)
    {
        $sql = "SELECT 
                    ta.*,
                    t.id as tour_id,
                    t.name as tour_name,
                    t.base_price
                FROM {$this->table} AS ta
                LEFT JOIN tours AS t ON ta.tour_id = t.id
                WHERE ta.guide_id = :guide_id 
                AND (ta.status = 'active' OR ta.status IS NULL)
                AND (ta.end_date IS NULL OR ta.end_date >= CURDATE())
                ORDER BY ta.start_date DESC";

        $stmt = self::$pdo->prepare($sql);
        $stmt->execute(['guide_id' => $guideId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
