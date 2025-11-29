<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';
?>

<main class="wrapper">
    <div class="main-content">
        <div class="page-header d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h2">Chi tiết Booking #<?= $booking['id'] ?></h1>
                <p class="text-muted">Thông tin chi tiết về đơn đặt tour</p>
            </div>
            <div class="d-flex gap-2">
                <a href="<?= BASE_URL_ADMIN . '&action=bookings/edit&id=' . $booking['id'] ?>" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Chỉnh sửa
                </a>
                <a href="<?= BASE_URL_ADMIN . '&action=bookings' ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_SESSION['error']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_SESSION['success']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <div class="row g-3">
            <!-- Left Column -->
            <div class="col-lg-6">
                <!-- Thông tin booking -->
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle"></i> Thông tin đơn đặt
                        </h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <td class="fw-bold" style="width: 40%;">Mã Booking:</td>
                                    <td>#<?= htmlspecialchars($booking['id']) ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Ngày đặt:</td>
                                    <td><?= htmlspecialchars($booking['booking_date']) ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Tổng giá:</td>
                                    <td class="text-danger fw-bold"><?= number_format($booking['total_price'], 0, ',', '.') ?> ₫</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Trạng thái:</td>
                                    <td>
                                        <?php
                                        $statusClass = 'warning';
                                        $statusText = 'Chờ Xác Nhận';
                                        if ($booking['status'] === 'hoan_tat') {
                                            $statusClass = 'success';
                                            $statusText = 'Hoàn Tất';
                                        } elseif ($booking['status'] === 'da_coc') {
                                            $statusClass = 'info';
                                            $statusText = 'Đã Cọc';
                                        } elseif ($booking['status'] === 'da_huy') {
                                            $statusClass = 'danger';
                                            $statusText = 'Đã Hủy';
                                        }

                                        // Kiểm tra quyền sửa
                                        $userRole = $_SESSION['user']['role'] ?? 'customer';
                                        $userId = $_SESSION['user']['user_id'] ?? null;
                                        $bookingModel = new Booking();
                                        $canEdit = $bookingModel->canUserEditBooking($booking['id'], $userId, $userRole);
                                        ?>
                                        <div class="d-flex align-items-center gap-2">
                                            <span id="status-badge" class="badge bg-<?= $statusClass ?>" data-status="<?= $booking['status'] ?>"><?= $statusText ?></span>

                                            <?php if ($canEdit): ?>
                                                <div class="dropdown">
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
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Thông tin khách hàng -->
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-user"></i> Thông tin khách hàng
                        </h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <td class="fw-bold" style="width: 40%;">Họ tên:</td>
                                    <td><?= htmlspecialchars($booking['customer_name'] ?? 'N/A') ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Email:</td>
                                    <td><?= htmlspecialchars($booking['customer_email'] ?? 'N/A') ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Điện thoại:</td>
                                    <td><?= htmlspecialchars($booking['customer_phone'] ?? 'N/A') ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Thông tin tour -->
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-map-marked-alt"></i> Thông tin tour
                        </h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <td class="fw-bold" style="width: 40%;">Tên tour:</td>
                                    <td><?= htmlspecialchars($booking['tour_name'] ?? 'N/A') ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Giá cơ bản:</td>
                                    <td><?= number_format($booking['tour_base_price'] ?? 0, 0, ',', '.') ?> ₫</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-lg-6">
                <!-- Danh sách khách đi kèm -->
                <div class="card mb-3">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-users"></i> Danh sách khách đi kèm (<?= count($companions) ?>)
                        </h5>
                        <?php if ($canEdit): ?>
                            <button type="button" class="btn btn-sm btn-primary" id="add-companion-btn" data-booking-id="<?= $booking['id'] ?>">
                                <i class="fas fa-plus"></i> Thêm khách
                            </button>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($companions)): ?>
                            <div class="d-flex flex-column gap-3">
                                <?php foreach ($companions as $index => $companion): ?>
                                    <div class="border rounded p-3 bg-light position-relative">
                                        <?php if ($canEdit): ?>
                                            <div class="position-absolute top-0 end-0 m-2">
                                                <button class="btn btn-sm btn-outline-primary edit-companion-btn me-1" data-companion-id="<?= $companion['id'] ?>">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger delete-companion-btn" data-companion-id="<?= $companion['id'] ?>" data-booking-id="<?= $booking['id'] ?>">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                        <h6 class="mb-2">Khách #<?= $index + 1 ?></h6>
                                        <table class="table table-sm table-borderless mb-0">
                                            <tbody>
                                                <tr>
                                                    <td class="fw-bold" style="width: 40%;">Họ tên:</td>
                                                    <td data-field="name"><?= htmlspecialchars($companion['full_name']) ?></td>
                                                </tr>
                                                <?php if (!empty($companion['gender'])): ?>
                                                    <tr>
                                                        <td class="fw-bold">Giới tính:</td>
                                                        <td data-field="gender"><?= htmlspecialchars($companion['gender']) ?></td>
                                                    </tr>
                                                <?php endif; ?>
                                                <?php if (!empty($companion['birth_date'])): ?>
                                                    <tr>
                                                        <td class="fw-bold">Ngày sinh:</td>
                                                        <td data-field="birth_date"><?= htmlspecialchars($companion['birth_date']) ?></td>
                                                    </tr>
                                                <?php endif; ?>
                                                <?php if (!empty($companion['phone'])): ?>
                                                    <tr>
                                                        <td class="fw-bold">Điện thoại:</td>
                                                        <td data-field="phone"><?= htmlspecialchars($companion['phone']) ?></td>
                                                    </tr>
                                                <?php endif; ?>
                                                <?php if (!empty($companion['id_card'])): ?>
                                                    <tr>
                                                        <td class="fw-bold">CMND/Hộ chiếu:</td>
                                                        <td data-field="id_card"><?= htmlspecialchars($companion['id_card']) ?></td>
                                                    </tr>
                                                <?php endif; ?>
                                                <?php if (!empty($companion['room_type'])): ?>
                                                    <tr>
                                                        <td class="fw-bold">Loại phòng:</td>
                                                        <td data-field="room_type"><?= htmlspecialchars($companion['room_type']) ?></td>
                                                    </tr>
                                                <?php endif; ?>
                                                <?php if (!empty($companion['special_request'])): ?>
                                                    <tr>
                                                        <td class="fw-bold">Yêu cầu đặc biệt:</td>
                                                        <td data-field="special_request"><?= htmlspecialchars($companion['special_request']) ?></td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted mb-0">Không có khách đi kèm</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Ghi chú -->
                <?php if (!empty($booking['notes'])): ?>
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fas fa-comment"></i> Ghi chú
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-0"><?= nl2br(htmlspecialchars($booking['notes'])) ?></p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
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
                                <textarea class="form-control" id="companion-special-request" name="special_request"
                                    rows="3"></textarea>
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
</main>

<?php
include_once PATH_VIEW_ADMIN . 'default/footer.php';
?>