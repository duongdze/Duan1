<?php
require_once 'models/GuideWorkModel.php';

class GuideWorkController
{
    public function schedule()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $role = $_SESSION['role'] ?? null;
        $userId = $_SESSION['user_id'] ?? null;

        if ($role === 'guide' && $userId) {
            $guide = GuideWorkModel::getGuideByUserId($userId);
            if (!$guide) {
                die("Không tìm thấy hướng dẫn viên.");
            }

            $assignments = GuideWorkModel::getAssignmentsByGuideId($guide['id']) ?: [];
            require_once PATH_VIEW_ADMIN . 'pages/guide_works/schedule_guide.php';
        } else {
            $guides = GuideWorkModel::getAllGuides();
            $guideAssignments = [];

            foreach ($guides as $g) {
                $assignments = GuideWorkModel::getAssignmentsByGuideId($g['id']) ?: [];
                $guideAssignments[] = [
                    'guide' => $g,
                    'assignments' => $assignments
                ];
            }

            require_once PATH_VIEW_ADMIN . 'pages/guide_works/schedule_all.php';
        }
    }

    public function tourDetail()
    {
        $tourId = $_GET['id'] ?? null;
        $guideId = $_GET['guide_id'] ?? null;

        if (!$tourId || !$guideId) {
            die("Thiếu tour_id hoặc guide_id");
        }

        // Lấy thông tin tour
        $tour = GuideWorkModel::getTourById($tourId);
        $assignment = GuideWorkModel::getAssignment($tourId, $guideId);
        $itineraries = GuideWorkModel::getItinerariesByTourId($tourId) ?: [];

        // Lấy bookings của tour này
        $bookingModel = new Booking();
        $bookings = $bookingModel->getByTourId($tourId);

        // Lấy danh sách khách từ tất cả bookings
        $customerModel = new BookingCustomer();
        $allCustomers = [];
        $stats = [
            'total' => 0,
            'checked_in' => 0,
            'not_arrived' => 0,
            'absent' => 0
        ];

        foreach ($bookings as $booking) {
            $customers = $customerModel->getCustomersWithCheckinStatus($booking['id']);
            foreach ($customers as $customer) {
                $customer['booking_code'] = $booking['id'];
                $customer['booking_customer_name'] = $booking['customer_name'] ?? 'N/A';
                $allCustomers[] = $customer;

                // Tính stats
                $stats['total']++;
                $status = $customer['checkin_status'] ?? 'not_arrived';
                if (isset($stats[$status])) {
                    $stats[$status]++;
                }
            }
        }

        require_once PATH_VIEW_ADMIN . 'pages/guide_works/tour_detail.php';
    }

    /**
     * HDV hủy nhận tour (phải trước 3 ngày)
     */
    public function cancelAssignment()
    {
        header('Content-Type: application/json');

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $assignmentId = $_POST['assignment_id'] ?? null;
        $userId = $_SESSION['user_id'] ?? null;

        if (!$assignmentId) {
            echo json_encode(['success' => false, 'message' => 'Thiếu assignment ID']);
            exit;
        }

        // Lấy thông tin assignment
        $assignment = GuideWorkModel::getAssignmentById($assignmentId);

        if (!$assignment) {
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy phân công']);
            exit;
        }

        // Kiểm tra xem HDV có phải là người được phân công không
        $guide = GuideWorkModel::getGuideByUserId($userId);
        if (!$guide || $guide['id'] != $assignment['guide_id']) {
            echo json_encode(['success' => false, 'message' => 'Bạn không có quyền hủy phân công này']);
            exit;
        }

        // Kiểm tra điều kiện 3 ngày
        $startDate = new DateTime($assignment['start_date']);
        $today = new DateTime();
        $daysUntilStart = $today->diff($startDate)->days;

        if ($daysUntilStart < 3 || $today >= $startDate) {
            echo json_encode([
                'success' => false,
                'message' => 'Không thể hủy tour. Phải hủy trước ít nhất 3 ngày so với ngày bắt đầu.'
            ]);
            exit;
        }

        // Xóa assignment
        $result = GuideWorkModel::deleteAssignment($assignmentId);

        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'Đã hủy nhận tour thành công'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi hủy tour'
            ]);
        }
        exit;
    }
}
