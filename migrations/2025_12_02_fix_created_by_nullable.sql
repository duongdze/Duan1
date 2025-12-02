-- =====================================================
-- Migration: Fix created_by to allow NULL
-- Date: 2025-12-02
-- =====================================================

-- Sửa cột created_by để cho phép NULL
ALTER TABLE `bookings` 
MODIFY COLUMN `created_by` INT NULL;

-- =====================================================
-- End of Migration
-- =====================================================
