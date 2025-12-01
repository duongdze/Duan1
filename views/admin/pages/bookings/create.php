<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';
?>

<main class="dashboard booking-create-page">
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
                        <span class="breadcrumb-current">Tạo Booking Mới</span>
                    </div>
                    <div class="page-title-section">
                        <h1 class="page-title">
                            <i class="fas fa-plus-circle title-icon"></i>
                            Tạo Booking Mới
                        </h1>
                        <p class="page-subtitle">Điền thông tin chi tiết để tạo đơn đặt tour mới</p>
                    </div>
                </div>
                <div class="header-right">
                    <a href="<?= BASE_URL_ADMIN ?>&action=bookings" class="btn btn-modern btn-secondary">
                        <i class="fas fa-times me-2"></i>
                        Hủy bỏ
                    </a>
                    <button type="submit" form="booking-form" class="btn btn-modern btn-primary">
                        <i class="fas fa-save me-2"></i>
                        Lưu Booking
                    </button>
                </div>
            </div>
        </header>

        <!-- Alert Messages -->
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

        <!-- Progress Steps -->
        <div class="progress-steps-wrapper mb-4">
            <div class="progress-steps">
                <div class="step active" data-step="1">
                    <div class="step-number">1</div>
                    <div class="step-label">Thông tin booking</div>
                </div>
                <div class="step" data-step="2">
                    <div class="step-number">2</div>
                    <div class="step-label">Xác nhận</div>
                </div>
            </div>
        </div>

        <!-- Booking Form -->
        <form method="POST" action="<?= BASE_URL_ADMIN ?>&action=bookings/store" id="booking-form">
            <div class="row">
                <!-- Main Content -->
                <div class="col-lg-8">
                    <!-- Step 1: Booking Information -->
                    <div class="form-step active" id="step-1">
                        <!-- Customer Information -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-user text-primary me-2"></i>
                                    Thông tin khách hàng
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <select class="form-select" id="customer_id" name="customer_id" required>
                                                <option value="">-- Chọn khách hàng --</option>
                                                <?php if (!empty($customers)): ?>
                                                    <?php foreach ($customers as $c): ?>
                                                        <option value="<?= htmlspecialchars($c['user_id']) ?>">
                                                            <?= htmlspecialchars($c['full_name']) ?> (<?= htmlspecialchars($c['email']) ?>)
                                                        </option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                            <label for="customer_id">Khách hàng <span class="text-danger">*</span></label>
                                        </div>
                                        <small class="text-muted d-block mt-2">
                                            <a href="<?= BASE_URL_ADMIN ?>&action=customers/create" target="_blank">
                                                <i class="fas fa-plus me-1"></i>Tạo khách hàng mới
                                            </a>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tour Information -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-route text-success me-2"></i>
                                    Thông tin tour
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <select class="form-select" id="tour_id" name="tour_id" required>
                                                <option value="">-- Chọn tour --</option>
                                                <?php if (!empty($tours)): ?>
                                                    <?php foreach ($tours as $t): ?>
                                                        <option value="<?= htmlspecialchars($t['id']) ?>" data-price="<?= htmlspecialchars($t['base_price']) ?>">
                                                            <?= htmlspecialchars($t['name']) ?> - <?= number_format($t['base_price'], 0, ',', '.') ?> ₫
                                                        </option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                            <label for="tour_id">Tour <span class="text-danger">*</span></label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="date" class="form-control" id="booking_date" name="booking_date" required placeholder=" ">
                                            <label for="booking_date">Ngày đặt tour <span class="text-danger">*</span></label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="number" class="form-control" id="total_price" name="total_price" min="0" step="50000" placeholder=" " required>
                                            <label for="total_price">Tổng giá tiền (VNĐ) <span class="text-danger">*</span></label>
                                        </div>
                                        <small class="text-muted d-block mt-2">Sẽ tự động cập nhật từ giá tour</small>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <select class="form-select" id="status" name="status" required>
                                                <option value="">-- Chọn trạng thái --</option>
                                                <option value="cho_xac_nhan">Chờ xác nhận</option>
                                                <option value="da_coc">Đã cọc</option>
                                                <option value="hoan_tat">Hoàn tất</option>
                                                <option value="da_huy">Đã hủy</option>
                                            </select>
                                            <label for="status">Trạng thái <span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-comment text-warning me-2"></i>
                                    Ghi chú
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="form-floating">
                                    <textarea class="form-control" id="notes" name="notes" style="height: 120px" placeholder=" "></textarea>
                                    <label for="notes">Ghi chú thêm về đơn đặt</label>
                                </div>
                                <small class="text-muted d-block mt-2">Yêu cầu đặc biệt, thông tin khách hàng...</small>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Confirmation -->
                    <div class="form-step" id="step-2">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Xác nhận thông tin
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Vui lòng kiểm tra lại thông tin trước khi tạo booking
                                </div>

                                <div class="booking-summary">
                                    <div class="row mb-3">
                                        <div class="col-4"><strong>Khách hàng:</strong></div>
                                        <div class="col-8" id="summary-customer">--</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-4"><strong>Tour:</strong></div>
                                        <div class="col-8" id="summary-tour">--</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-4"><strong>Ngày đặt:</strong></div>
                                        <div class="col-8" id="summary-date">--</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-4"><strong>Trạng thái:</strong></div>
                                        <div class="col-8" id="summary-status">--</div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-4"><strong>Tổng giá:</strong></div>
                                        <div class="col-8">
                                            <span class="text-danger fw-bold fs-5" id="summary-price">0 ₫</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Form Actions -->
                    <div class="card mb-4 sticky-top" style="top: 20px;">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Thao tác</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button type="submit" form="booking-form" class="btn btn-primary">
                                    <i class="fas fa-check me-2"></i>
                                    Tạo Booking
                                </button>
                                <a href="<?= BASE_URL_ADMIN ?>&action=bookings" class="btn btn-secondary">
                                    <i class="fas fa-times me-2"></i>
                                    Hủy bỏ
                                </a>
                            </div>

                            <hr class="my-3">

                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="previousStep()" id="prev-btn" style="display: none;">
                                    <i class="fas fa-chevron-left me-1"></i>
                                    Quay lại
                                </button>
                                <button type="button" class="btn btn-sm btn-primary" onclick="nextStep()" id="next-btn">
                                    Tiếp theo
                                    <i class="fas fa-chevron-right ms-1"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Summary -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-receipt me-2"></i>
                                Tóm tắt
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Tour:</span>
                                <span id="quick-tour">--</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Khách hàng:</span>
                                <span id="quick-customer">--</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <strong>Tổng:</strong>
                                <strong class="text-danger" id="quick-price">0 ₫</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>

<script>
    // Booking Creation JavaScript
    let currentStep = 1;
    const totalSteps = 2;

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        initializeForm();
        setupEventListeners();
    });

    function initializeForm() {
        updateStepDisplay();
        updateNavigationButtons();
    }

    function setupEventListeners() {
        // Auto-update price when tour is selected
        document.getElementById('tour_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const price = selectedOption.getAttribute('data-price');
            if (price) {
                document.getElementById('total_price').value = price;
                updateSummary();
            }
        });

        // Update summary on field changes
        ['customer_id', 'tour_id', 'booking_date', 'status', 'total_price'].forEach(id => {
            const element = document.getElementById(id);
            if (element) {
                element.addEventListener('change', updateSummary);
            }
        });
    }

    // Step Navigation
    function nextStep() {
        if (validateCurrentStep()) {
            if (currentStep < totalSteps) {
                currentStep++;
                updateStepDisplay();
                updateNavigationButtons();
                updateSummary();
            }
        }
    }

    function previousStep() {
        if (currentStep > 1) {
            currentStep--;
            updateStepDisplay();
            updateNavigationButtons();
        }
    }

    function updateStepDisplay() {
        // Update progress steps
        document.querySelectorAll('.step').forEach(step => {
            step.classList.remove('active', 'completed');
            const stepNum = parseInt(step.dataset.step);
            if (stepNum === currentStep) {
                step.classList.add('active');
            } else if (stepNum < currentStep) {
                step.classList.add('completed');
            }
        });

        // Update form sections
        document.querySelectorAll('.form-step').forEach(step => {
            step.classList.remove('active');
        });
        document.getElementById(`step-${currentStep}`).classList.add('active');
    }

    function updateNavigationButtons() {
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');

        prevBtn.style.display = currentStep === 1 ? 'none' : 'block';
        nextBtn.style.display = currentStep === totalSteps ? 'none' : 'block';
    }

    // Validation
    function validateCurrentStep() {
        const currentStepElement = document.getElementById(`step-${currentStep}`);
        const requiredFields = currentStepElement.querySelectorAll('[required]');

        for (let field of requiredFields) {
            if (!field.value.trim()) {
                field.focus();
                alert('Vui lòng điền đầy đủ thông tin bắt buộc');
                return false;
            }
        }

        return true;
    }

    // Update Summary
    function updateSummary() {
        const customerSelect = document.getElementById('customer_id');
        const tourSelect = document.getElementById('tour_id');
        const dateInput = document.getElementById('booking_date');
        const statusSelect = document.getElementById('status');
        const priceInput = document.getElementById('total_price');

        // Update quick summary
        const customerText = customerSelect.options[customerSelect.selectedIndex]?.text || '--';
        const tourText = tourSelect.options[tourSelect.selectedIndex]?.text || '--';
        const price = priceInput.value || '0';

        document.getElementById('quick-customer').textContent = customerText.split('(')[0].trim();
        document.getElementById('quick-tour').textContent = tourText.split('-')[0].trim();
        document.getElementById('quick-price').textContent = new Intl.NumberFormat('vi-VN').format(price) + ' ₫';

        // Update confirmation summary
        document.getElementById('summary-customer').textContent = customerText;
        document.getElementById('summary-tour').textContent = tourText;
        document.getElementById('summary-date').textContent = dateInput.value || '--';
        document.getElementById('summary-status').textContent = statusSelect.options[statusSelect.selectedIndex]?.text || '--';
        document.getElementById('summary-price').textContent = new Intl.NumberFormat('vi-VN').format(price) + ' ₫';
    }
</script>

<?php
include_once PATH_VIEW_ADMIN . 'default/footer.php';
?>