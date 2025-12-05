<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';

// Helper for price formatting
function formatPrice($price)
{
    if ($price >= 1000000000) {
        return round($price / 1000000000, ($price / 1000000000) >= 10 ? 0 : 1) . ' tỷ';
    } elseif ($price >= 1000000) {
        return round($price / 1000000, 1) . ' tr';
    } else {
        return number_format($price, 0, ',', '.') . 'đ';
    }
}

// Status mapping
$statusMap = [
    'cho_xac_nhan' => ['text' => 'Chờ xác nhận', 'class' => 'warning', 'icon' => 'clock'],
    'da_coc' => ['text' => 'Đã cọc', 'class' => 'info', 'icon' => 'credit-card'],
    'hoan_tat' => ['text' => 'Hoàn tất', 'class' => 'success', 'icon' => 'check-circle'],
    'da_huy' => ['text' => 'Đã hủy', 'class' => 'danger', 'icon' => 'times-circle']
];

$currentStatus = $statusMap[$booking['status']] ?? ['text' => 'Unknown', 'class' => 'secondary', 'icon' => 'question'];

// Check edit permission
$userRole = $_SESSION['user']['role'] ?? 'customer';
$userId = $_SESSION['user']['user_id'] ?? null;
$bookingModel = new Booking();
$canEdit = $bookingModel->canUserEditBooking($booking['id'], $userId, $userRole);
?>

<main class="dashboard booking-detail-page">
    <div class="dashboard-container">
        <!-- Modern Page Header -->
        <header class="dashboard-header">
            <div class="header-content">
                <div class="header-left">
                    <div class="breadcrumb-modern">
                        <a href="<?= BASE_URL_ADMIN ?>&action=/" class="breadcrumb-link">
                            <i class="fas fa-home"></i>
                            <span>Dashboard</span>
                        </a>
                        <span class="breadcrumb-separator">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                        <a href="<?= BASE_URL_ADMIN ?>&action=bookings" class="breadcrumb-link">
                            <i class="fas fa-calendar-check"></i>
                            <span>Quản lý Booking</span>
                        </a>
                        <span class="breadcrumb-separator">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                        <span class="breadcrumb-current">Chi tiết #<?= $booking['id'] ?></span>
                    </div>
                    <div class="page-title-section">
                        <h1 class="page-title">
                            <i class="fas fa-file-invoice title-icon"></i>
                            Booking #<?= $booking['id'] ?>
                        </h1>
                        <p class="page-subtitle"><?= htmlspecialchars($booking['tour_name'] ?? 'Tour') ?></p>
                    </div>
                </div>
                <div class="header-right">
                    <?php if ($canEdit): ?>
                        <a href="<?= BASE_URL_ADMIN ?>&action=bookings/edit&id=<?= $booking['id'] ?>" class="btn btn-modern btn-secondary">
                            <i class="fas fa-edit me-2"></i>
                            Chỉnh sửa
                        </a>
                    <?php endif; ?>
                    <a href="<?= BASE_URL_ADMIN ?>&action=bookings" class="btn btn-modern btn-primary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Quay lại
                    </a>
                </div>
            </div>
        </header>

        <!-- Alert Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert-modern alert-success alert-dismissible fade show" role="alert">
                <div class="alert-content">
                    <i class="fas fa-check-circle alert-icon"></i>
                    <span><?= $_SESSION['success'] ?></span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert-modern alert-danger alert-dismissible fade show" role="alert">
                <div class="alert-content">
                    <i class="fas fa-exclamation-circle alert-icon"></i>
                    <span><?= $_SESSION['error'] ?></span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Statistics Cards -->
        <section class="stats-section">
            <div class="stats-grid">
                <div class="stat-card stat-primary">
                    <div class="stat-icon-wrapper">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value"><?= formatPrice($booking['total_price'] ?? 0) ?></div>
                        <div class="stat-label">Tổng tiền</div>
                    </div>
                </div>

                <div class="stat-card stat-<?= $currentStatus['class'] ?>">
                    <div class="stat-icon-wrapper">
                        <i class="fas fa-<?= $currentStatus['icon'] ?>"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value"><?= $currentStatus['text'] ?></div>
                        <div class="stat-label">Trạng thái</div>
                    </div>
                </div>

                <div class="stat-card stat-info">
                    <div class="stat-icon-wrapper">
                        <i class="fas fa-calendar"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value"><?= date('d/m/Y', strtotime($booking['booking_date'])) ?></div>
                        <div class="stat-label">Ngày đặt</div>
                    </div>
                </div>

                <div class="stat-card stat-success">
                    <div class="stat-icon-wrapper">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value"><?= count($companions) + 1 ?></div>
                        <div class="stat-label">Số khách</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Content Grid -->
        <div class="row">
            <!-- Main Column (Left) -->
            <div class="col-lg-8">
                <!-- Booking Information Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-info-circle text-primary me-2"></i>
                            Thông tin đơn đặt
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="info-label">Mã Booking</label>
                                    <div class="info-value">#<?= htmlspecialchars($booking['id']) ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="info-label">Ngày đặt</label>
                                    <div class="info-value"><?= date('d/m/Y H:i', strtotime($booking['booking_date'])) ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="info-label">Tổng giá</label>
                                    <div class="info-value text-danger fw-bold"><?= number_format($booking['total_price'], 0, ',', '.') ?> ₫</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="info-label">Trạng thái</label>
                                    <div class="info-value">
                                        <span id="status-badge" class="badge bg-<?= $currentStatus['class'] ?>" data-status="<?= $booking['status'] ?>">
                                            <i class="fas fa-<?= $currentStatus['icon'] ?> me-1"></i>
                                            <?= $currentStatus['text'] ?>
                                        </span>
                                        <?php if ($canEdit): ?>
                                            <div class="dropdown d-inline-block ms-2">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item status-change-btn" href="#" data-status="cho_xac_nhan" data-booking-id="<?= $booking['id'] ?>">Chờ xác nhận</a></li>
                                                    <li><a class="dropdown-item status-change-btn" href="#" data-status="da_coc" data-booking-id="<?= $booking['id'] ?>">Đã cọc</a></li>
                                                    <li><a class="dropdown-item status-change-btn" href="#" data-status="hoan_tat" data-booking-id="<?= $booking['id'] ?>">Hoàn tất</a></li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li><a class="dropdown-item status-change-btn text-danger" href="#" data-status="da_huy" data-booking-id="<?= $booking['id'] ?>">Hủy</a></li>
                                                </ul>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Guests List Card -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-users text-success me-2"></i>
                            Danh sách khách (<?= count($companions) + 1 ?>)
                        </h5>
                        <?php if ($canEdit): ?>
                            <button type="button" class="btn btn-sm btn-primary" id="add-companion-btn" data-booking-id="<?= $booking['id'] ?>">
                                <i class="fas fa-plus me-1"></i>
                                Thêm khách
                            </button>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <!-- Main Customer -->
                        <div class="guest-item mb-3">
                            <div class="guest-header">
                                <span class="badge bg-primary">Khách chính</span>
                                <h6 class="mb-0 ms-2"><?= htmlspecialchars($booking['customer_name'] ?? 'N/A') ?></h6>
                            </div>
                            <div class="guest-details mt-2">
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <small class="text-muted">Email:</small>
                                        <div><?= htmlspecialchars($booking['customer_email'] ?? 'N/A') ?></div>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted">Điện thoại:</small>
                                        <div><?= htmlspecialchars($booking['customer_phone'] ?? 'N/A') ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Companions -->
                        <?php if (!empty($companions)): ?>
                            <hr>
                            <div class="companions-list">
                                <?php foreach ($companions as $index => $companion): ?>
                                    <div class="guest-item mb-3">
                                        <div class="guest-header">
                                            <span class="badge bg-secondary">Khách #<?= $index + 1 ?></span>
                                            <h6 class="mb-0 ms-2"><?= htmlspecialchars($companion['full_name']) ?></h6>
                                            <?php if ($canEdit): ?>
                                                <div class="ms-auto">
                                                    <button class="btn btn-sm btn-outline-primary edit-companion-btn me-1" data-companion-id="<?= $companion['id'] ?>">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger delete-companion-btn" data-companion-id="<?= $companion['id'] ?>" data-booking-id="<?= $booking['id'] ?>">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="guest-details mt-2">
                                            <div class="row g-2">
                                                <?php if (!empty($companion['gender'])): ?>
                                                    <div class="col-md-4">
                                                        <small class="text-muted">Giới tính:</small>
                                                        <div><?= htmlspecialchars($companion['gender']) ?></div>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if (!empty($companion['birth_date'])): ?>
                                                    <div class="col-md-4">
                                                        <small class="text-muted">Ngày sinh:</small>
                                                        <div><?= htmlspecialchars($companion['birth_date']) ?></div>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if (!empty($companion['phone'])): ?>
                                                    <div class="col-md-4">
                                                        <small class="text-muted">Điện thoại:</small>
                                                        <div><?= htmlspecialchars($companion['phone']) ?></div>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if (!empty($companion['id_card'])): ?>
                                                    <div class="col-md-6">
                                                        <small class="text-muted">CMND/Hộ chiếu:</small>
                                                        <div><?= htmlspecialchars($companion['id_card']) ?></div>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if (!empty($companion['room_type'])): ?>
                                                    <div class="col-md-6">
                                                        <small class="text-muted">Loại phòng:</small>
                                                        <div><?= htmlspecialchars($companion['room_type']) ?></div>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if (!empty($companion['special_request']) || $canEdit): ?>
                                                    <div class="col-12">
                                                        <small class="text-muted">Yêu cầu đặc biệt:</small>
                                                        <div class="d-flex align-items-start">
                                                            <div class="flex-grow-1" id="special-request-<?= $companion['id'] ?>">
                                                                <?= !empty($companion['special_request']) ? htmlspecialchars($companion['special_request']) : '<span class="text-muted">Chưa có</span>' ?>
                                                            </div>
                                                            <?php if ($canEdit): ?>
                                                                <button class="btn btn-sm btn-outline-primary ms-2 edit-special-request-btn"
                                                                    data-companion-id="<?= $companion['id'] ?>"
                                                                    data-booking-id="<?= $booking['id'] ?>"
                                                                    data-current-request="<?= htmlspecialchars($companion['special_request'] ?? '') ?>"
                                                                    title="Sửa yêu cầu đặc biệt">
                                                                    <i class="fas fa-edit"></i>
                                                                </button>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>

                            </div>
                        <?php else: ?>
                            <div class="text-center text-muted py-3">
                                <i class="fas fa-user-plus fa-2x mb-2"></i>
                                <p class="mb-0">Chưa có khách đi kèm</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Notes Card -->
                <?php if (!empty($booking['notes'])): ?>
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-comment text-warning me-2"></i>
                                Ghi chú
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-0"><?= nl2br(htmlspecialchars($booking['notes'])) ?></p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar (Right) -->
            <div class="col-lg-4">
                <!-- Customer Info Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-user text-primary me-2"></i>
                            Thông tin khách hàng
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="customer-info">
                            <h6 class="mb-3"><?= htmlspecialchars($booking['customer_name'] ?? 'N/A') ?></h6>
                            <div class="info-row mb-2">
                                <i class="fas fa-envelope text-muted me-2"></i>
                                <span><?= htmlspecialchars($booking['customer_email'] ?? 'N/A') ?></span>
                            </div>
                            <div class="info-row">
                                <i class="fas fa-phone text-muted me-2"></i>
                                <span><?= htmlspecialchars($booking['customer_phone'] ?? 'N/A') ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tour Info Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-route text-success me-2"></i>
                            Thông tin tour
                        </h5>
                    </div>
                    <div class="card-body">
                        <h6 class="mb-3"><?= htmlspecialchars($booking['tour_name'] ?? 'N/A') ?></h6>
                        <div class="info-row mb-2">
                            <span class="text-muted">Giá cơ bản:</span>
                            <span class="fw-bold"><?= number_format($booking['tour_base_price'] ?? 0, 0, ',', '.') ?> ₫</span>
                        </div>
                        <a href="<?= BASE_URL_ADMIN ?>&action=tours/detail&id=<?= $booking['tour_id'] ?>" class="btn btn-sm btn-outline-primary w-100 mt-2">
                            <i class="fas fa-eye me-1"></i>
                            Xem chi tiết tour
                        </a>
                    </div>
                </div>

                <!-- Staff Assignment Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-users-cog text-info me-2"></i>
                            Phân công nhân sự
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="staff-item mb-3">
                            <label class="text-muted small">Hướng dẫn viên</label>
                            <div class="fw-medium">
                                <?php if (!empty($booking['guide_name'])): ?>
                                    <i class="fas fa-user-tie text-info me-1"></i>
                                    <?= htmlspecialchars($booking['guide_name']) ?>
                                <?php else: ?>
                                    <span class="text-muted">Chưa phân công</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="staff-item">
                            <label class="text-muted small">Tài xế</label>
                            <div class="fw-medium">
                                <?php if (!empty($booking['driver_name'])): ?>
                                    <i class="fas fa-car text-secondary me-1"></i>
                                    <?= htmlspecialchars($booking['driver_name']) ?>
                                <?php else: ?>
                                    <span class="text-muted">Chưa phân công</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Companion Modal -->
    <div class="modal fade" id="companionModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="companionModalTitle">Thêm Khách Đi Kèm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="companionForm">
                        <input type="hidden" id="companion-id" name="companion_id">
                        <input type="hidden" id="companion-booking-id" name="booking_id" value="<?= $booking['id'] ?>">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Họ tên <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="companion-name" name="name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Giới tính</label>
                                <select class="form-select" id="companion-gender" name="gender">
                                    <option value="">Chọn</option>
                                    <option value="Nam">Nam</option>
                                    <option value="Nữ">Nữ</option>
                                    <option value="Khác">Khác</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Ngày sinh</label>
                                <input type="date" class="form-control" id="companion-birth-date" name="birth_date">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Điện thoại</label>
                                <input type="tel" class="form-control" id="companion-phone" name="phone">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">CMND/Hộ chiếu</label>
                                <input type="text" class="form-control" id="companion-id-card" name="id_card">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Loại phòng</label>
                                <select class="form-select" id="companion-room-type" name="room_type">
                                    <option value="">Chọn loại phòng</option>
                                    <option value="đơn">Phòng đơn</option>
                                    <option value="đôi">Phòng đôi</option>
                                    <option value="ghép">Ghép phòng</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Yêu cầu đặc biệt</label>
                                <textarea class="form-control" id="companion-special-request" name="special_request" rows="3"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary" id="saveCompanionBtn">Lưu</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="specialRequestModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cập Nhật Yêu Cầu Đặc Biệt</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="specialRequestForm">
                        <input type="hidden" id="sr-companion-id">
                        <input type="hidden" id="sr-booking-id">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Yêu cầu đặc biệt</label>
                            <textarea class="form-control" id="sr-special-request" rows="4"
                                placeholder="Ví dụ: Ăn chay, dị ứng hải sản, cần xe lăn..."></textarea>
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Ghi chú các yêu cầu đặc biệt của khách để phục vụ tốt hơn
                            </small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary" id="saveSpecialRequestBtn">
                        <i class="fas fa-save me-1"></i>Lưu
                    </button>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    // Special Request Update
    document.addEventListener('DOMContentLoaded', function() {
        const specialRequestModal = new bootstrap.Modal(document.getElementById('specialRequestModal'));

        // Open modal
        document.querySelectorAll('.edit-special-request-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const companionId = this.dataset.companionId;
                const bookingId = this.dataset.bookingId;
                const currentRequest = this.dataset.currentRequest;

                document.getElementById('sr-companion-id').value = companionId;
                document.getElementById('sr-booking-id').value = bookingId;
                document.getElementById('sr-special-request').value = currentRequest;

                specialRequestModal.show();
            });
        });

        // Save special request
        document.getElementById('saveSpecialRequestBtn').addEventListener('click', function() {
            const companionId = document.getElementById('sr-companion-id').value;
            const bookingId = document.getElementById('sr-booking-id').value;
            const specialRequest = document.getElementById('sr-special-request').value;

            // AJAX call
            fetch('<?= BASE_URL_ADMIN ?>&action=bookings/updateSpecialRequest', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        companion_id: companionId,
                        booking_id: bookingId,
                        special_request: specialRequest
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update UI
                        const displayElement = document.getElementById('special-request-' + companionId);
                        if (displayElement) {
                            displayElement.innerHTML = specialRequest || '<span class="text-muted">Chưa có</span>';
                        }

                        // Update button data
                        const btn = document.querySelector(`[data-companion-id="${companionId}"]`);
                        if (btn) {
                            btn.dataset.currentRequest = specialRequest;
                        }

                        // Show success message
                        alert(data.message);
                        specialRequestModal.hide();
                    } else {
                        alert('Lỗi: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi cập nhật');
                });
        });
    });
</script>

<style>
    .info-item {
        padding: 12px;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .info-label {
        display: block;
        font-size: 0.875rem;
        color: #6c757d;
        margin-bottom: 4px;
    }

    .info-value {
        font-size: 1rem;
        font-weight: 500;
        color: #212529;
    }

    .guest-item {
        padding: 16px;
        background: #f8f9fa;
        border-radius: 8px;
        border-left: 3px solid #0d6efd;
    }

    .guest-header {
        display: flex;
        align-items: center;
    }

    .guest-details {
        font-size: 0.875rem;
    }

    .info-row {
        display: flex;
        align-items: center;
        font-size: 0.875rem;
    }

    .staff-item {
        padding: 12px;
        background: #f8f9fa;
        border-radius: 6px;
    }
</style>

<?php
include_once PATH_VIEW_ADMIN . 'default/footer.php';
?>