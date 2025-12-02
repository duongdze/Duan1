<?php
require_once 'models/BaseModel.php';

/**
 * Model quản lý tài xế
 */
class Driver extends BaseModel
{
    protected $table = 'drivers';
    protected $columns = [
        'id',
        'full_name',
        'phone',
        'email',
        'license_number',
        'license_type',
        'vehicle_type',
        'vehicle_plate',
        'vehicle_brand',
        'status',
        'rating',
        'notes',
        'created_at',
        'updated_at'
    ];

    /**
     * Lấy danh sách tài xế đang hoạt động
     */
    public function getActiveDrivers()
    {
        return $this->select('*', "status = 'active'", [], 'full_name ASC');
    }

    /**
     * Lấy tài xế theo ID
     */
    public function getById($id)
    {
        return $this->find('*', 'id = :id', ['id' => $id]);
    }

    /**
     * Lấy tài xế đang rảnh (không busy)
     */
    public function getAvailableDrivers()
    {
        return $this->select('*', "status IN ('active')", [], 'full_name ASC');
    }

    /**
     * Cập nhật trạng thái tài xế
     */
    public function updateStatus($id, $status)
    {
        return $this->update(
            ['status' => $status],
            'id = :id',
            ['id' => $id]
        );
    }

    /**
     * Kiểm tra số điện thoại đã tồn tại chưa
     */
    public function phoneExists($phone, $excludeId = null)
    {
        $conditions = 'phone = :phone';
        $params = ['phone' => $phone];

        if ($excludeId) {
            $conditions .= ' AND id != :id';
            $params['id'] = $excludeId;
        }

        $result = $this->find('id', $conditions, $params);
        return !empty($result);
    }

    /**
     * Kiểm tra số bằng lái đã tồn tại chưa
     */
    public function licenseExists($licenseNumber, $excludeId = null)
    {
        $conditions = 'license_number = :license';
        $params = ['license' => $licenseNumber];

        if ($excludeId) {
            $conditions .= ' AND id != :id';
            $params['id'] = $excludeId;
        }

        $result = $this->find('id', $conditions, $params);
        return !empty($result);
    }

    /**
     * Lấy thống kê tài xế
     */
    public function getStats()
    {
        $sql = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active,
                    SUM(CASE WHEN status = 'busy' THEN 1 ELSE 0 END) as busy,
                    SUM(CASE WHEN status = 'inactive' THEN 1 ELSE 0 END) as inactive,
                    AVG(rating) as avg_rating
                FROM {$this->table}";

        $stmt = self::$pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Tìm kiếm tài xế
     */
    public function search($keyword)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE full_name LIKE :keyword 
                OR phone LIKE :keyword 
                OR vehicle_plate LIKE :keyword
                ORDER BY full_name ASC";

        $stmt = self::$pdo->prepare($sql);
        $stmt->execute(['keyword' => "%{$keyword}%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
