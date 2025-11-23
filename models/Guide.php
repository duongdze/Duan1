<?php
require_once 'models/BaseModel.php';

class Guide extends BaseModel
{
    protected $table = 'guides';
    protected $columns = [
        'id',
        'user_id',
        'languages',
        'experience_years',
        'rating',
        'health_status',
        'notes'
    ];

    public function getAllWithName()
    {
        $stmt = self::$pdo->query("SELECT g.id, u.full_name FROM guides g JOIN users u ON u.user_id = g.user_id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
