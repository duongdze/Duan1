-- =====================================================
-- Migration: Update existing bookings final_price
-- Date: 2025-12-02
-- Description: Copy total_price to final_price for existing bookings
-- =====================================================

-- Cập nhật final_price từ total_price cho các booking hiện có
UPDATE `bookings` 
SET `final_price` = `total_price`
WHERE `final_price` = 0 AND `total_price` IS NOT NULL AND `total_price` > 0;

-- Hoặc nếu muốn cập nhật từ original_price
-- UPDATE `bookings` 
-- SET `final_price` = `original_price`
-- WHERE `final_price` = 0 AND `original_price` IS NOT NULL AND `original_price` > 0;

-- =====================================================
-- End of Migration
-- =====================================================
