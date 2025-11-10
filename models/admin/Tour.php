<?php
require_once 'models/BaseModel.php';

class Tour extends BaseModel
{
    protected $table = 'tours';
    protected $columns = [
        'id',
        'name',
        'type',
        'description',
        'base_price',
        'policy',
        'supplier_id',
        'created_at',
        'updated_at'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Return all tours
     * @return array
     */
    public function getAll()
    {
        return $this->select();
    }

    /**
     * Find one tour by id
     */
    public function findById($id)
    {
        return $this->find('*', 'id = :id', ['id' => $id]);
    }

    /**
     * Create a new tour record from associative array
     */
    public function create(array $data)
    {
        return $this->insert($data);
    }

    /**
     * Update tour by id
     */
    public function updateById($id, array $data)
    {
        return $this->update($data, 'id = :id', ['id' => $id]);
    }

    /**
     * Delete tour by id
     */
    public function deleteById($id)
    {
        return $this->delete('id = :id', ['id' => $id]);
    }
}
