<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';
?>

<main class="wrapper">
    <div class="main-content">
        <div class="page-header">
            <h1 class="h2">
                <i class="fas fa-map-marked-alt"></i> Tour Khả Dụng
            </h1>
            <p class="text-muted">Danh sách tour chưa có HDV phụ trách - Bạn có thể nhận tour để quản lý</p>
        </div>

        <?php if (!empty($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_SESSION['success']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_SESSION['error']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (!empty($availableTours)): ?>
            <div class="row g-3">
                <?php foreach ($availableTours as $tour): ?>
                    <?php
                    $totalCustomers = $tour['total_customers'] ?? 0;
                    $isEligible = ($totalCustomers >= 15 && $totalCustomers <= 30);

                    // Xác định badge trạng thái
                    if ($totalCustomers < 15) {
                        $statusBadge = '<span class="badge bg-warning"><i class="fas fa-exclamation-triangle me-1"></i>Chưa đủ người</span>';
                    } elseif ($totalCustomers > 30) {
                        $statusBadge = '<span class="badge bg-danger"><i class="fas fa-users-slash me-1"></i>Quá đông</span>';
                    } else {
                        $statusBadge = '<span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Đủ điều kiện</span>';
                    }
                    ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title text-primary" style="word-break: break-word; white-space: normal;">
                                    <i class="fas fa-route"></i>
                                    <?= htmlspecialchars($tour['name']) ?>
                                </h5>

                                <p class="card-text text-muted small mb-3">
                                    <?= htmlspecialchars(substr($tour['description'] ?? 'Không có mô tả', 0, 100)) ?>
                                    <?php if (strlen($tour['description'] ?? '') > 100): ?>...<?php endif; ?>
                                </p>

                                <div class="mb-2">
                                    <small class="text-muted d-block">
                                        <i class="fas fa-calendar-alt text-info"></i>
                                        <strong>Booking gần nhất:</strong>
                                        <?php if ($tour['nearest_booking_date']): ?>
                                            <?= date('d/m/Y', strtotime($tour['nearest_booking_date'])) ?>
                                        <?php else: ?>
                                            <span class="text-warning">Chưa có booking</span>
                                        <?php endif; ?>
                                    </small>
                                </div>

                                <div class="mb-3">
                                    <?= $statusBadge ?>
                                </div>

                                <div class="mb-3 d-flex gap-2 flex-wrap">
                                    <span class="badge bg-primary">
                                        <i class="fas fa-users"></i>
                                        <?= $totalCustomers ?> người
                                    </span>
                                    <span class="badge bg-info">
                                        <i class="fas fa-calendar-check"></i>
                                        <?= $tour['booking_count'] ?> booking
                                    </span>
                                    <span class="badge bg-success">
                                        <i class="fas fa-money-bill-wave"></i>
                                        <?= number_format($tour['total_booking_price'] ?? 0, 0, ',', '.') ?> ₫
                                    </span>
                                    <?php if ($tour['duration'] ?? null): ?>
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-clock"></i>
                                            <?= $tour['duration'] ?> ngày
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <!-- Version Breakdown -->
                                <?php if (!empty($tour['version_breakdown']) && count($tour['version_breakdown']) > 0): ?>
                                    <div class="version-breakdown mt-3">
                                        <button class="btn btn-sm btn-outline-info w-100"
                                            type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#version-detail-<?= $tour['id'] ?>"
                                            aria-expanded="false"
                                            aria-controls="version-detail-<?= $tour['id'] ?>">
                                            <i class="fas fa-chevron-down me-1"></i>
                                            Chi tiết theo version
                                        </button>

                                        <div class="collapse mt-2" id="version-detail-<?= $tour['id'] ?>">
                                            <div class="card card-body bg-light p-2">
                                                <?php foreach ($tour['version_breakdown'] as $index => $version): ?>
                                                    <div class="d-flex justify-content-between align-items-center <?= $index > 0 ? 'mt-2 pt-2 border-top' : '' ?>">
                                                        <div class="d-flex align-items-center">
                                                            <?php if ($version['version_id']): ?>
                                                                <i class="fas fa-tag text-info me-2"></i>
                                                                <small><strong><?= htmlspecialchars($version['version_name']) ?></strong></small>
                                                            <?php else: ?>
                                                                <i class="fas fa-box text-primary me-2"></i>
                                                                <small><strong>Mặc định</strong></small>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div>
                                                            <span class="badge bg-info me-1">
                                                                <i class="fas fa-users"></i> <?= $version['customer_count'] ?>
                                                            </span>
                                                            <span class="badge bg-secondary">
                                                                <i class="fas fa-calendar-check"></i> <?= $version['booking_count'] ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="mt-3">
                                    <?php
                                    $userRole = $_SESSION['user']['role'] ?? 'customer';
                                    if ($userRole === 'admin' && !empty($guides)):
                                    ?>
                                        <!-- Admin: Departure Date Selection -->
                                        <div class="mb-3">
                                            <label class="form-label small text-muted">
                                                <i class="fas fa-calendar-alt me-1"></i>
                                                Chọn ngày khởi hành
                                            </label>
                                            <?php if (!empty($tour['departure_dates'])): ?>
                                                <select class="form-select form-select-sm" id="departure-date-<?= $tour['id'] ?>">
                                                    <option value="">-- Chọn ngày --</option>
                                                    <?php foreach ($tour['departure_dates'] as $departure): ?>
                                                        <option value="<?= $departure['departure_date'] ?>">
                                                            <?= date('d/m/Y', strtotime($departure['departure_date'])) ?>
                                                            (Còn <?= $departure['available_seats'] ?> chỗ)
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            <?php else: ?>
                                                <select class="form-select form-select-sm" disabled>
                                                    <option>Chưa có lịch khởi hành</option>
                                                </select>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Confirm Date Button -->
                                        <?php if (!empty($tour['departure_dates'])): ?>
                                            <button class="btn btn-info w-100 mb-3 confirm-date-btn"
                                                    data-tour-id="<?= $tour['id'] ?>"
                                                    data-tour-name="<?= htmlspecialchars($tour['name']) ?>">
                                                <i class="fas fa-check-circle me-2"></i>
                                                Xác nhận ngày khởi hành
                                            </button>

                                            <!-- Guide Selection (Hidden until date confirmed) -->
                                            <div id="guide-section-<?= $tour['id'] ?>" style="display: none;">
                                                <div class="alert alert-success alert-sm mb-3 d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <i class="fas fa-calendar-check me-1"></i>
                                                        Ngày: <strong id="confirmed-date-<?= $tour['id'] ?>"></strong>
                                                    </div>
                                                    <button class="btn btn-sm btn-outline-secondary edit-date-btn" 
                                                            data-tour-id="<?= $tour['id'] ?>"
                                                            title="Chỉnh sửa ngày">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label small text-muted">
                                                        <i class="fas fa-user-tie me-1"></i>
                                                        Chọn HDV
                                                    </label>
                                                    <select class="form-select form-select-sm" id="guide-select-<?= $tour['id'] ?>">
                                                        <option value="">-- Chọn HDV --</option>
                                                        <?php foreach ($guides as $guide): ?>
                                                            <option value="<?= $guide['id'] ?>">
                                                                <?= htmlspecialchars($guide['full_name']) ?>
                                                                <?php if (!empty($guide['languages'])): ?>
                                                                    (<?= htmlspecialchars($guide['languages']) ?>)
                                                                <?php endif; ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>

                                                <button class="btn btn-success w-100 admin-assign-guide-btn"
                                                        data-tour-id="<?= $tour['id'] ?>"
                                                        data-tour-name="<?= htmlspecialchars($tour['name']) ?>">
                                                    <i class="fas fa-user-check me-2"></i>
                                                    Phân công HDV
                                                </button>
                                            </div>
                                        <?php else: ?>
                                            <div class="alert alert-warning small mb-0">
                                                <i class="fas fa-exclamation-triangle me-1"></i>
                                                Tour chưa có lịch trình
                                            </div>
                                        <?php endif; ?>
                                    <?php elseif ($isEligible): ?>
                                        <!-- HDV: Claim Tour Button -->
                                        <button
                                            class="btn btn-primary w-100 claim-tour-btn"
                                            data-tour-id="<?= $tour['id'] ?>"
                                            data-tour-name="<?= htmlspecialchars($tour['name']) ?>"
                                            data-total-customers="<?= $totalCustomers ?>">
                                            <i class="fas fa-hand-paper me-2"></i>
                                            Nhận Tour
                                        </button>
                                    <?php else: ?>
                                        <button class="btn btn-secondary w-100" disabled>
                                            <i class="fas fa-ban me-2"></i>
                                            Không đủ điều kiện
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info d-flex align-items-center">
                <i class="fas fa-info-circle fa-2x me-3"></i>
                <div>
                    <h5 class="mb-1">Không có tour khả dụng</h5>
                    <p class="mb-0">Hiện tại tất cả tour đã có HDV phụ trách. Vui lòng quay lại sau.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle claim tour buttons (for HDV)
        document.querySelectorAll('.claim-tour-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const tourId = this.dataset.tourId;
                const tourName = this.dataset.tourName;
                const totalCustomers = this.dataset.totalCustomers;

                if (confirm(`Bạn có chắc muốn nhận tour "${tourName}"?\nTổng số khách: ${totalCustomers} người`)) {
                    // Disable button và hiển thị loading
                    this.disabled = true;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang xử lý...';

                    // Send AJAX request
                    fetch('<?= BASE_URL_ADMIN ?>&action=available-tours/claim-tour', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: `tour_id=${tourId}`
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('✅ ' + data.message);
                                window.location.reload();
                            } else {
                                alert('❌ ' + data.message);
                                // Re-enable button
                                this.disabled = false;
                                this.innerHTML = '<i class="fas fa-hand-paper me-2"></i>Nhận Tour';
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Có lỗi xảy ra! Vui lòng thử lại.');
                            // Re-enable button
                            this.disabled = false;
                            this.innerHTML = '<i class="fas fa-hand-paper me-2"></i>Nhận Tour';
                        });
                }
            });
        });

        // Handle confirm date buttons
        document.querySelectorAll('.confirm-date-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const tourId = this.dataset.tourId;
                const dateSelect = document.getElementById(`departure-date-${tourId}`);
                const selectedDate = dateSelect.value;

                if (!selectedDate) {
                    alert('⚠️ Vui lòng chọn ngày khởi hành!');
                    dateSelect.focus();
                    return;
                }

                // Format date for display
                const formattedDate = new Date(selectedDate).toLocaleDateString('vi-VN');
                
                // Show confirmed date
                document.getElementById(`confirmed-date-${tourId}`).textContent = formattedDate;
                
                // Hide confirm button and date select
                this.style.display = 'none';
                dateSelect.closest('.mb-3').style.display = 'none';
                
                // Show guide selection section
                document.getElementById(`guide-section-${tourId}`).style.display = 'block';
            });
        });

        // Handle edit date buttons
        document.querySelectorAll('.edit-date-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const tourId = this.dataset.tourId;
                
                // Hide guide section
                document.getElementById(`guide-section-${tourId}`).style.display = 'none';
                
                // Show date select and confirm button again
                const dateSelectDiv = document.querySelector(`#departure-date-${tourId}`).closest('.mb-3');
                const confirmBtn = document.querySelector(`.confirm-date-btn[data-tour-id="${tourId}"]`);
                
                dateSelectDiv.style.display = 'block';
                confirmBtn.style.display = 'block';
                
                // Reset guide selection
                document.getElementById(`guide-select-${tourId}`).value = '';
            });
        });

        // Handle admin assign guide buttons
        document.querySelectorAll('.admin-assign-guide-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const tourId = this.dataset.tourId;
                const tourName = this.dataset.tourName;
                const guideSelect = document.getElementById(`guide-select-${tourId}`);
                const guideId = guideSelect.value;
                const departureDateInput = document.getElementById(`departure-date-${tourId}`);
                const departureDate = departureDateInput ? departureDateInput.value : '';

                if (!guideId) {
                    alert('⚠️ Vui lòng chọn HDV trước khi phân công!');
                    guideSelect.focus();
                    return;
                }

                if (!departureDate) {
                    alert('⚠️ Vui lòng chọn ngày khởi hành!');
                    departureDateInput.focus();
                    return;
                }

                const guideName = guideSelect.options[guideSelect.selectedIndex].text;
                const formattedDate = new Date(departureDate).toLocaleDateString('vi-VN');

                if (confirm(`Phân công HDV "${guideName}" cho tour "${tourName}"?\nNgày khởi hành: ${formattedDate}`)) {
                    // Disable button và hiển thị loading
                    this.disabled = true;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang phân công...';

                    // Send AJAX request
                    fetch('<?= BASE_URL_ADMIN ?>&action=available-tours/assign-guide', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: `tour_id=${tourId}&guide_id=${guideId}&departure_date=${departureDate}`
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('✅ ' + data.message);
                                window.location.reload();
                            } else {
                                alert('❌ ' + data.message);
                                this.disabled = false;
                                this.innerHTML = '<i class="fas fa-user-check me-2"></i>Phân công HDV';
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('❌ Có lỗi xảy ra khi phân công HDV!');
                            this.disabled = false;
                            this.innerHTML = '<i class="fas fa-user-check me-2"></i>Phân công HDV';
                        });
                }
            });
        });
    });
</script>

<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>