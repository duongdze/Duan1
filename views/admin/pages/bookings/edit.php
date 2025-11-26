<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';
?>

<main class="wrapper">
    <div class="main-content">
        <div class="page-header">
            <h1 class="h2">Chỉnh sửa Booking #<?= $booking['id'] ?></h1>
            <p class="text-muted">Cập nhật thông tin đơn đặt tour</p>
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

        <form method="POST" action="<?= BASE_URL_ADMIN ?>&action=bookings/update" class="booking-form">
            <input type="hidden" name="id" value="<?= $booking['id'] ?>">

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
                                            <option value="<?= htmlspecialchars($c['user_id']) ?>"
                                                <?= $c['user_id'] == $booking['customer_id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($c['full_name']) ?> (<?= htmlspecialchars($c['email']) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
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
                                            <option value="<?= htmlspecialchars($t['id']) ?>"
                                                data-price="<?= htmlspecialchars($t['base_price']) ?>"
                                                <?= $t['id'] == $booking['tour_id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($t['name']) ?> - <?= number_format($t['base_price'], 0, ',', '.') ?> ₫
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="booking-booking-date" class="form-label fw-bold">Ngày đặt tour</label>
                                <input type="date" class="form-control" id="booking-booking-date" name="booking_date"
                                    value="<?= htmlspecialchars($booking['booking_date']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="booking-total-price" class="form-label fw-bold">Tổng giá tiền</label>
                                <input type="number" class="form-control" id="booking-total-price" name="total_price"
                                    value="<?= htmlspecialchars($booking['total_price']) ?>"
                                    placeholder="0" min="0" step="50000" required>
                            </div>

                            <div class="mb-3">
                                <label for="booking-status" class="form-label fw-bold">Trạng thái đơn</label>
                                <select class="form-select" id="booking-status" name="status" required>
                                    <option value="">-- Chọn trạng thái --</option>
                                    <option value="cho_xac_nhan" <?= $booking['status'] == 'cho_xac_nhan' ? 'selected' : '' ?>>Chờ xác nhận</option>
                                    <option value="da_coc" <?= $booking['status'] == 'da_coc' ? 'selected' : '' ?>>Đã cọc</option>
                                    <option value="hoan_tat" <?= $booking['status'] == 'hoan_tat' ? 'selected' : '' ?>>Hoàn tất</option>
                                    <option value="da_huy" <?= $booking['status'] == 'da_huy' ? 'selected' : '' ?>>Đã hủy</option>
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
                            <textarea class="form-control" id="booking-notes" name="notes" rows="4"
                                placeholder="Ghi chú thêm về đơn đặt (yêu cầu đặc biệt, thông tin khách hàng,...)"><?= htmlspecialchars($booking['notes'] ?? '') ?></textarea>
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
                            <div id="booking-companion-list" class="d-flex flex-column gap-3"
                                data-initial='<?= json_encode($companions) ?>'></div>
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
                    <i class="fas fa-save"></i> Lưu thay đổi
                </button>
                <a href="?mode=admin&action=bookings/detail&id=<?= $booking['id'] ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Hủy
                </a>
            </div>
        </form>
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tourSelect = document.getElementById('booking-tour-id');
        const customerSelect = document.getElementById('booking-customer-id');
        const totalPriceInput = document.getElementById('booking-total-price');
        const companionList = document.getElementById('booking-companion-list');
        const addCompanionBtn = document.getElementById('booking-add-companion-btn');
        const companionTemplate = document.getElementById('companion-template');

        // Update total price when tour changes
        tourSelect.addEventListener('change', function() {
            if (this.value) {
                const price = this.options[this.selectedIndex].dataset.price || 0;
                totalPriceInput.value = parseInt(price) || 0;
                document.getElementById('booking-summary-tour').textContent = this.options[this.selectedIndex].text;
            } else {
                document.getElementById('booking-summary-tour').textContent = '--';
            }
            updateSummary();
        });

        // Update customer name in summary
        customerSelect.addEventListener('change', function() {
            if (this.value) {
                document.getElementById('booking-summary-customer').textContent = this.options[this.selectedIndex].text.split('(')[0].trim();
            } else {
                document.getElementById('booking-summary-customer').textContent = '--';
            }
            updateSummary();
        });

        // Add companion button
        addCompanionBtn.addEventListener('click', function(e) {
            e.preventDefault();
            addCompanionItem();
        });

        function addCompanionItem(data = null) {
            const clone = companionTemplate.content.cloneNode(true);

            if (data) {
                clone.querySelector('[data-field="name"]').value = data.name || '';
                clone.querySelector('[data-field="gender"]').value = data.gender || '';
                clone.querySelector('[data-field="birth_date"]').value = data.birth_date || '';
                clone.querySelector('[data-field="phone"]').value = data.phone || '';
                clone.querySelector('[data-field="id_card"]').value = data.id_card || '';
                clone.querySelector('[data-field="room_type"]').value = data.room_type || '';
                clone.querySelector('[data-field="special_request"]').value = data.special_request || '';
            }

            const removeBtn = clone.querySelector('.remove-companion');
            removeBtn.addEventListener('click', function(e) {
                e.preventDefault();
                this.closest('.companion-item').remove();
                updateSummary();
            });

            companionList.appendChild(clone);
            updateSummary();
        }

        function updateSummary() {
            const companionCount = document.querySelectorAll('.companion-item').length;
            document.getElementById('booking-summary-companion-count').textContent = companionCount;
            document.getElementById('booking-summary-price').textContent =
                (parseInt(totalPriceInput.value) || 0).toLocaleString('vi-VN');
        }

        // Load initial companions
        const initialData = JSON.parse(companionList.dataset.initial || '[]');
        if (initialData.length > 0) {
            initialData.forEach(companion => addCompanionItem(companion));
        }

        // Initialize summary with current values
        if (tourSelect.value) {
            document.getElementById('booking-summary-tour').textContent = tourSelect.options[tourSelect.selectedIndex].text;
        }
        if (customerSelect.value) {
            document.getElementById('booking-summary-customer').textContent = customerSelect.options[customerSelect.selectedIndex].text.split('(')[0].trim();
        }
        updateSummary();
    });
</script>

<?php
include_once PATH_VIEW_ADMIN . 'default/footer.php';
?>