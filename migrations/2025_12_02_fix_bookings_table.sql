-- =====================================================
-- Migration: Fix all booking table issues
-- Date: 2025-12-02
-- Description: Fix final_price default and created_by nullable
-- =====================================================

-- 1. Sửa final_price để có default value = 0
ALTER TABLE `bookings` 
MODIFY COLUMN `final_price` DECIMAL(15,2) DEFAULT 0 NOT NULL;

-- 2. Sửa created_by để cho phép NULL
ALTER TABLE `bookings` 
MODIFY COLUMN `created_by` INT NULL;

-- =====================================================
-- End of Migration
-- =====================================================
