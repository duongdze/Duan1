<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';
?>

<main class="wrapper">
    <div class="main-content">
        <div class="page-header">
            <h1 class="h2">Tạo Đơn Đặt Tour Mới</h1>
            <p class="text-muted">Thêm đơn đặt tour cho khách hàng</p>
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

        <form method="POST" action="<?= BASE_URL_ADMIN ?>&action=bookings/store" class="booking-form">
            <div class="row g-3">
                <!-- Left Column -->
                <div class="col-lg-6">
                    <!-- Thông tin khách hàng -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fas fa-user"></i> Thông tin khách hàng
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="booking-customer-id" class="form-label fw-bold">Chọn khách hàng</label>
                                <select class="form-select" id="booking-customer-id" name="customer_id" required>
                                    <option value="">-- Chọn khách hàng --</option>
                                    <?php if (!empty($customers)): ?>
                                        <?php foreach ($customers as $c): ?>
                                            <option value="<?= htmlspecialchars($c['user_id']) ?>">
                                                <?= htmlspecialchars($c['full_name']) ?> (<?= htmlspecialchars($c['email']) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <small class=" d-block mt-2">
                                    <a href="?mode=admin&action=customers/create" target="_blank">
                                        <i class="fas fa-plus"></i> Tạo khách hàng mới
                                    </a>
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Thông tin đặt tour -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fas fa-shopping-cart"></i> Thông tin đặt tour
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="booking-tour-id" class="form-label fw-bold">Chọn tour</label>
                                <select class="form-select" id="booking-tour-id" name="tour_id" required>
                                    <option value="">-- Chọn tour --</option>
                                    <?php if (!empty($tours)): ?>
                                        <?php foreach ($tours as $t): ?>
                                            <option value="<?= htmlspecialchars($t['id']) ?>" data-price="<?= htmlspecialchars($t['base_price']) ?>">
                                                <?= htmlspecialchars($t['name']) ?> - <?= number_format($t['base_price'], 0, ',', '.') ?> ₫
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <small class="text-muted">Chọn tour để cập nhật giá cơ bản</small>
                            </div>

                            <div class="mb-3">
                                <label for="booking-booking-date" class="form-label fw-bold">Ngày đặt tour</label>
                                <input type="date" class="form-control" id="booking-booking-date" name="booking_date" required>
                            </div>

                            <div class="mb-3">
                                <label for="booking-total-price" class="form-label fw-bold">Tổng giá tiền</label>
                                <input type="number" class="form-control" id="booking-total-price" name="total_price" placeholder="0" min="0" step="50000" required>
                                <small class="text-muted">Sẽ được tự động cập nhật từ giá tour nếu không nhập</small>
                            </div>

                            <div class="mb-3">
                                <label for="booking-status" class="form-label fw-bold">Trạng thái đơn</label>
                                <select class="form-select" id="booking-status" name="status" required>
                                    <option value="">-- Chọn trạng thái --</option>
                                    <option value="cho_xac_nhan">Chờ xác nhận</option>
                                    <option value="da_coc">Đã cọc</option>
                                    <option value="hoan_tat">Hoàn tất</option>
                                    <option value="da_huy">Đã hủy</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Ghi chú -->
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fas fa-comment"></i> Ghi chú
                            </h5>
                        </div>
                        <div class="card-body">
                            <textarea class="form-control" id="booking-notes" name="notes" rows="4" placeholder="Ghi chú thêm về đơn đặt (yêu cầu đặc biệt, thông tin khách hàng,...)"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-lg-6">
                    <!-- Thông tin khách đi kèm -->
                    <div class="card mb-3">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-users"></i> Danh sách khách đi kèm
                            </h5>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="booking-add-companion-btn">
                                <i class="fas fa-plus"></i> Thêm khách
                            </button>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small mb-3">Thêm thông tin chi tiết của các khách đi kèm (họ tên, giới tính, ngày sinh, liên hệ,...)</p>
                            <div id="booking-companion-list" class="d-flex flex-column gap-3" data-initial="[]"></div>
                            <template id="companion-template">
                                <div class="companion-item border rounded p-3 bg-light-subtle position-relative">
                                    <button type="button" class="btn-close position-absolute top-0 end-0 m-2 text-danger remove-companion" aria-label="Xóa"></button>
                                    <div class="row g-2">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Họ tên</label>
                                            <input type="text" class="form-control" name="companion_name[]" data-field="name" placeholder="Nguyễn Văn A" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Giới tính</label>
                                            <select class="form-select" name="companion_gender[]" data-field="gender">
                                                <option value="">Chọn</option>
                                                <option value="Nam">Nam</option>
                                                <option value="Nữ">Nữ</option>
                                                <option value="Khác">Khác</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Ngày sinh</label>
                                            <input type="date" class="form-control" name="companion_birth_date[]" data-field="birth_date">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Điện thoại</label>
                                            <input type="tel" class="form-control" name="companion_phone[]" data-field="phone" placeholder="09xxxxxxxx">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">CMND/Hộ chiếu</label>
                                            <input type="text" class="form-control" name="companion_id_card[]" data-field="id_card" placeholder="012345678">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Loại phòng</label>
                                            <select class="form-select" name="companion_room_type[]" data-field="room_type">
                                                <option value="">Chọn loại phòng</option>
                                                <option value="đơn">Phòng đơn</option>
                                                <option value="đôi">Phòng đôi</option>
                                                <option value="ghép">Ghép phòng</option>
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label fw-bold">Yêu cầu đặc biệt</label>
                                            <textarea class="form-control" rows="2" name="companion_special_request[]" data-field="special_request" placeholder="Dị ứng thực phẩm, yêu cầu đặc biệt,..."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Tóm tắt đơn -->
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fas fa-receipt"></i> Tóm tắt đơn đặt
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-6">
                                    <strong>Tour:</strong>
                                </div>
                                <div class="col-6" id="booking-summary-tour">--</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6">
                                    <strong>Khách hàng:</strong>
                                </div>
                                <div class="col-6" id="booking-summary-customer">--</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6">
                                    <strong>Số lượng khách:</strong>
                                </div>
                                <div class="col-6" id="booking-summary-companion-count">0</div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-6">
                                    <strong>Tổng giá:</strong>
                                </div>
                                <div class="col-6 fw-bold text-danger">
                                    <span id="booking-summary-price">0</span> ₫
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-3 d-flex gap-2 mb-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-check"></i> Tạo đơn đặt
                </button>
                <a href="?mode=admin&action=bookings" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Hủy
                </a>
            </div>
        </form>
</main>
<?php
include_once PATH_VIEW_ADMIN . 'default/footer.php';
?>