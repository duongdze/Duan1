<?php

class TourAssignmentController
{
    protected $model;

    public function __construct()
    {
        $this->model = new TourAssignment();
    }

    /**
     * Hiển thị trang quản lý phân công tour
     */
    public function index()
    {
        // Chỉ admin mới được truy cập
        $userRole = $_SESSION['user']['role'] ?? 'customer';
        if ($userRole !== 'admin') {
            $_SESSION['error'] = 'Bạn không có quyền truy cập trang này';
            header('Location:' . BASE_URL_ADMIN);
            exit;
        }

        $assignments = $this->model->getAllAssignments();

        // Load guides và tours cho dropdown
        $guideModel = new Guide();
        $tourModel = new Tour();

        $guides = $guideModel->getAll();
        $tours = $tourModel->select('*', null, [], 'name ASC');

        require_once PATH_VIEW_ADMIN . 'pages/guides/tour-assignments.php';
    }

    /**
     * Phân công tour cho HDV
     */
    public function assign()
    {
        // Chỉ admin mới được phân công
        $userRole = $_SESSION['user']['role'] ?? 'customer';
        if ($userRole !== 'admin') {
            $_SESSION['error'] = 'Bạn không có quyền thực hiện thao tác này';
            header('Location:' . BASE_URL_ADMIN);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location:' . BASE_URL_ADMIN . '&action=guides/tour-assignments');
            exit;
        }

        $guideId = $_POST['guide_id'] ?? null;
        $tourId = $_POST['tour_id'] ?? null;
        $startDate = $_POST['start_date'] ?? null;
        $endDate = $_POST['end_date'] ?? null;
        $status = $_POST['status'] ?? 'active';

        if (!$guideId || !$tourId) {
            $_SESSION['error'] = 'Vui lòng chọn HDV và Tour';
            header('Location:' . BASE_URL_ADMIN . '&action=guides/tour-assignments');
            exit;
        }

        try {
            $result = $this->model->assignTourToGuide($guideId, $tourId, $startDate, $endDate, $status);

            if ($result) {
                $_SESSION['success'] = 'Phân công tour thành công';
            } else {
                $_SESSION['error'] = 'Không thể phân công tour';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
        }

        header('Location:' . BASE_URL_ADMIN . '&action=guides/tour-assignments');
        exit;
    }

    /**
     * Hủy phân công
     */
    public function remove()
    {
        // Chỉ admin mới được hủy phân công
        $userRole = $_SESSION['user']['role'] ?? 'customer';
        if ($userRole !== 'admin') {
            $_SESSION['error'] = 'Bạn không có quyền thực hiện thao tác này';
            header('Location:' . BASE_URL_ADMIN);
            exit;
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $_SESSION['error'] = 'Không tìm thấy phân công';
            header('Location:' . BASE_URL_ADMIN . '&action=guides/tour-assignments');
            exit;
        }

        try {
            $result = $this->model->removeAssignment($id);

            if ($result) {
                $_SESSION['success'] = 'Hủy phân công thành công';
            } else {
                $_SESSION['error'] = 'Không thể hủy phân công';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
        }

        header('Location:' . BASE_URL_ADMIN . '&action=guides/tour-assignments');
        exit;
    }

    /**
     * AJAX: Lấy danh sách tour của một HDV
     */
    public function getGuideTours()
    {
        header('Content-Type: application/json');

        $guideId = $_GET['guide_id'] ?? null;

        if (!$guideId) {
            echo json_encode(['success' => false, 'message' => 'Thiếu guide_id']);
            exit;
        }

        $tours = $this->model->getToursByGuide($guideId);
        echo json_encode(['success' => true, 'tours' => $tours]);
        exit;
    }

    /**
     * AJAX: Lấy danh sách HDV của một tour
     */
    public function getTourGuides()
    {
        header('Content-Type: application/json');

        $tourId = $_GET['tour_id'] ?? null;

        if (!$tourId) {
            echo json_encode(['success' => false, 'message' => 'Thiếu tour_id']);
            exit;
        }

        $guides = $this->model->getGuidesByTour($tourId);
        echo json_encode(['success' => true, 'guides' => $guides]);
        exit;
    }
    /**
     * Hiển thị trang danh sách tour khả dụng (chỉ cho HDV)
     */
    public function availableTours()
    {
        // Chỉ HDV và Admin mới được xem
        $userRole = $_SESSION['user']['role'] ?? 'customer';
        if (!in_array($userRole, ['hdv', 'admin'])) {
            $_SESSION['error'] = 'Bạn không có quyền truy cập trang này';
            header('Location:' . BASE_URL_ADMIN);
            exit;
        }

        $tourAssignmentModel = new TourAssignment();
        $availableTours = $tourAssignmentModel->getAvailableTours();

        include_once PATH_VIEW_ADMIN . 'pages/guides/available-tours.php';
    }
    /**
     * HDV nhận tour (AJAX endpoint)
     */
    public function claimTour()
    {
        header('Content-Type: application/json');

        // Kiểm tra quyền
        $userRole = $_SESSION['user']['role'] ?? 'customer';
        $guideId = $_SESSION['user']['user_id'] ?? null;

        if (!in_array($userRole, ['hdv', 'admin'])) {
            echo json_encode(['success' => false, 'message' => 'Bạn không có quyền nhận tour']);
            exit;
        }

        $tourId = $_POST['tour_id'] ?? null;

        if (!$tourId) {
            echo json_encode(['success' => false, 'message' => 'Thiếu thông tin tour']);
            exit;
        }

        try {
            $tourAssignmentModel = new TourAssignment();

            // Kiểm tra tour đã có HDV chưa (race condition protection)
            if ($tourAssignmentModel->tourHasGuide($tourId)) {
                echo json_encode(['success' => false, 'message' => 'Tour này đã có HDV khác nhận rồi']);
                exit;
            }

            // Kiểm tra HDV đã nhận tour này chưa
            if ($tourAssignmentModel->isGuideAssignedToTour($guideId, $tourId)) {
                echo json_encode(['success' => false, 'message' => 'Bạn đã nhận tour này rồi']);
                exit;
            }

            // Assign tour cho HDV
            $result = $tourAssignmentModel->assignTourToGuide($guideId, $tourId);

            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Nhận tour thành công! Tour đã được thêm vào danh sách của bạn.'
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Không thể nhận tour']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
        }
        exit;
    }
}
