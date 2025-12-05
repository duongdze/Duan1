<?php
require_once 'models/BaseModel.php';

class SupplierContract extends BaseModel
{
    protected $table = 'supplier_contracts';
    protected $columns = [
        'id',
        'supplier_id',
        'contract_name',
        'start_date',
        'end_date',
        'price_info',
        'status',
        'notes'
    ];

    /**
     * Lấy hợp đồng theo supplier_id
     */
    public function getBySupplier($supplierId)
    {
        return $this->select('*', 'supplier_id = :supplier_id', ['supplier_id' => $supplierId], 'start_date DESC');
    }

    /**
     * Lấy hợp đồng đang hoạt động
     */
    public function getActiveContracts($supplierId)
    {
        return $this->select('*', "supplier_id = :supplier_id AND status = 'active'", ['supplier_id' => $supplierId], 'start_date DESC');
    }

    /**
     * Kiểm tra hợp đồng có còn hiệu lực không
     */
    public function isValid($contractId)
    {
        $contract = $this->find('*', 'id = :id', ['id' => $contractId]);
        
        if (!$contract) {
            return false;
        }

        $today = date('Y-m-d');
        return $contract['status'] === 'active' 
            && $today >= $contract['start_date'] 
            && $today <= $contract['end_date'];
    }
}
