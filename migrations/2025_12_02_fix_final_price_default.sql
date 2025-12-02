-- =====================================================
-- Migration: Fix final_price default value
-- Date: 2025-12-02
-- =====================================================

-- Sửa cột final_price để có default value = 0
ALTER TABLE `bookings` 
MODIFY COLUMN `final_price` DECIMAL(15,2) DEFAULT 0 NOT NULL;

-- =====================================================
-- End of Migration
-- =====================================================
