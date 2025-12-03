<?php
require_once 'BaseModel.php';

class TourVersionPrice extends BaseModel
{
    protected $table = 'tour_version_prices';

    /**
     * Get price by version_id
     * 
     * @param int $versionId
     * @return array|null
     */
    public function getByVersionId($versionId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE version_id = :version_id LIMIT 1";
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute(['version_id' => $versionId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Upsert price (insert or update)
     * 
     * @param int $versionId
     * @param array $priceData ['price_adult', 'price_child', 'price_infant']
     * @return bool
     */
    public function upsertPrice($versionId, $priceData)
    {
        // Check if price exists
        $existing = $this->getByVersionId($versionId);

        $data = [
            'version_id' => $versionId,
            'price_adult' => $priceData['price_adult'] ?? 0,
            'price_child' => $priceData['price_child'] ?? 0,
            'price_infant' => $priceData['price_infant'] ?? 0,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($existing) {
            // Update existing price - use update() method from BaseModel
            unset($data['version_id']); // Don't update version_id
            return $this->update($data, 'id = :id', ['id' => $existing['id']]);
        } else {
            // Insert new price
            $data['created_at'] = date('Y-m-d H:i:s');
            return $this->insert($data);
        }
    }

    /**
     * Delete price by version_id
     * 
     * @param int $versionId
     * @return bool
     */
    public function deleteByVersionId($versionId)
    {
        return $this->delete('version_id = :version_id', ['version_id' => $versionId]);
    }

    /**
     * Get all prices with version info
     * 
     * @return array
     */
    public function getAllWithVersionInfo()
    {
        $sql = "SELECT tvp.*, tv.name as version_name, tv.status as version_status
                FROM {$this->table} tvp
                INNER JOIN tour_versions tv ON tvp.version_id = tv.id
                ORDER BY tv.name ASC";

        $stmt = self::$pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
