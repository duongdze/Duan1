<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';
?>

<main class="dashboard driver-edit-page">
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
                        <a href="<?= BASE_URL_ADMIN ?>&action=drivers" class="breadcrumb-link">
                            <i class="fas fa-car"></i>
                            <span>Quản lý Tài xế</span>
                        </a>
                        <span class="breadcrumb-separator">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                        <span class="breadcrumb-current">Chỉnh sửa Tài xế</span>
                    </div>
                    <div class="page-title-section">
                        <h1 class="page-title">
                            <i class="fas fa-edit title-icon"></i>
                            Chỉnh sửa Tài Xế
                        </h1>
                        <p class="page-subtitle">Cập nhật thông tin tài xế</p>
                    </div>
                </div>
                <div class="header-right">
                    <a href="<?= BASE_URL_ADMIN ?>&action=drivers" class="btn btn-modern btn-secondary">
                        <i class="fas fa-times me-2"></i>
                        Hủy bỏ
                    </a>
                    <a href="<?= BASE_URL_ADMIN ?>&action=drivers/detail&id=<?= $driver['id'] ?>" class="btn btn-modern btn-info">
                        <i class="fas fa-eye me-2"></i>
                        Xem chi tiết
                    </a>
                    <button type="submit" form="driver-edit-form" class="btn btn-modern btn-primary">
                        <i class="fas fa-save me-2"></i>
                        Lưu thay đổi
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
                    <div class="step-label">Thông tin cá nhân</div>
                </div>
                <div class="step" data-step="2">
                    <div class="step-number">2</div>
                    <div class="step-label">Xe & Bằng lái</div>
                </div>
            </div>
        </div>

        <!-- Driver Form -->
        <form method="POST" action="<?= BASE_URL_ADMIN ?>&action=drivers/update" id="driver-edit-form">
            <input type="hidden" name="id" value="<?= $driver['id'] ?>">

            <div class="row">
                <!-- Main Content -->
                <div class="col-lg-8">
                    <!-- Step 1: Personal Information -->
                    <div class="form-step active" id="step-1">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-user text-primary me-2"></i>
                                    Thông tin cá nhân
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="full_name" name="full_name"
                                                value="<?= htmlspecialchars($driver['full_name'] ?? '') ?>" required placeholder=" ">
                                            <label for="full_name">Họ và tên <span class="text-danger">*</span></label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="email" class="form-control" id="email" name="email"
                                                value="<?= htmlspecialchars($driver['email'] ?? '') ?>" required placeholder=" ">
                                            <label for="email">Email <span class="text-danger">*</span></label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="tel" class="form-control" id="phone" name="phone"
                                                value="<?= htmlspecialchars($driver['phone'] ?? '') ?>" required placeholder=" ">
                                            <label for="phone">Số điện thoại <span class="text-danger">*</span></label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="address" name="address"
                                                value="<?= htmlspecialchars($driver['address'] ?? '') ?>" placeholder=" ">
                                            <label for="address">Địa chỉ</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Vehicle & License -->
                    <div class="form-step" id="step-2">
                        <!-- License Information -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-id-card text-warning me-2"></i>
                                    Thông tin bằng lái
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="license_number" name="license_number"
                                                value="<?= htmlspecialchars($driver['license_number'] ?? '') ?>" placeholder=" ">
                                            <label for="license_number">Số bằng lái</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <select class="form-select" id="license_type" name="license_type">
                                                <option value="">-- Chọn loại --</option>
                                                <option value="B1" <?= ($driver['license_type'] ?? '') == 'B1' ? 'selected' : '' ?>>B1 - Xe dưới 9 chỗ</option>
                                                <option value="B2" <?= ($driver['license_type'] ?? '') == 'B2' ? 'selected' : '' ?>>B2 - Xe từ 9 chỗ trở lên</option>
                                                <option value="C" <?= ($driver['license_type'] ?? '') == 'C' ? 'selected' : '' ?>>C - Xe tải</option>
                                                <option value="D" <?= ($driver['license_type'] ?? '') == 'D' ? 'selected' : '' ?>>D - Xe khách</option>
                                                <option value="E" <?= ($driver['license_type'] ?? '') == 'E' ? 'selected' : '' ?>>E - Xe container</option>
                                            </select>
                                            <label for="license_type">Loại bằng lái</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="date" class="form-control" id="license_expiry" name="license_expiry"
                                                value="<?= $driver['license_expiry'] ?? '' ?>" placeholder=" ">
                                            <label for="license_expiry">Ngày hết hạn</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Vehicle Information -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-car text-success me-2"></i>
                                    Thông tin phương tiện
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="vehicle_plate" name="vehicle_plate"
                                                value="<?= htmlspecialchars($driver['vehicle_plate'] ?? '') ?>" placeholder=" ">
                                            <label for="vehicle_plate">Biển số xe</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <select class="form-select" id="vehicle_type" name="vehicle_type">
                                                <option value="">-- Chọn loại xe --</option>
                                                <option value="4-seat" <?= ($driver['vehicle_type'] ?? '') == '4-seat' ? 'selected' : '' ?>>Xe 4 chỗ</option>
                                                <option value="7-seat" <?= ($driver['vehicle_type'] ?? '') == '7-seat' ? 'selected' : '' ?>>Xe 7 chỗ</option>
                                                <option value="16-seat" <?= ($driver['vehicle_type'] ?? '') == '16-seat' ? 'selected' : '' ?>>Xe 16 chỗ</option>
                                                <option value="29-seat" <?= ($driver['vehicle_type'] ?? '') == '29-seat' ? 'selected' : '' ?>>Xe 29 chỗ</option>
                                                <option value="45-seat" <?= ($driver['vehicle_type'] ?? '') == '45-seat' ? 'selected' : '' ?>>Xe 45 chỗ</option>
                                            </select>
                                            <label for="vehicle_type">Loại xe</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="vehicle_model" name="vehicle_model"
                                                value="<?= htmlspecialchars($driver['vehicle_model'] ?? '') ?>" placeholder=" ">
                                            <label for="vehicle_model">Hiệu xe</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="number" class="form-control" id="vehicle_year" name="vehicle_year"
                                                value="<?= $driver['vehicle_year'] ?? '' ?>" min="1990" max="2030" placeholder=" ">
                                            <label for="vehicle_year">Năm sản xuất</label>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-floating">
                                            <textarea class="form-control" id="notes" name="notes" style="height: 100px" placeholder=" "><?= htmlspecialchars($driver['notes'] ?? '') ?></textarea>
                                            <label for="notes">Ghi chú</label>
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
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Thao tác</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button type="submit" form="driver-edit-form" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>
                                    Lưu thay đổi
                                </button>
                                <a href="<?= BASE_URL_ADMIN ?>&action=drivers/detail&id=<?= $driver['id'] ?>" class="btn btn-info">
                                    <i class="fas fa-eye me-2"></i>
                                    Xem chi tiết
                                </a>
                                <a href="<?= BASE_URL_ADMIN ?>&action=drivers" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>
                                    Hủy
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
                </div>
            </div>
        </form>
    </div>
</main>

<script>
    let currentStep = 1;
    const totalSteps = 2;

    document.addEventListener('DOMContentLoaded', function() {
        initializeForm();
    });

    function initializeForm() {
        updateStepDisplay();
        updateNavigationButtons();
    }

    function nextStep() {
        if (validateCurrentStep()) {
            if (currentStep < totalSteps) {
                currentStep++;
                updateStepDisplay();
                updateNavigationButtons();
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
        document.querySelectorAll('.step').forEach(step => {
            step.classList.remove('active', 'completed');
            const stepNum = parseInt(step.dataset.step);
            if (stepNum === currentStep) {
                step.classList.add('active');
            } else if (stepNum < currentStep) {
                step.classList.add('completed');
            }
        });

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
</script>

<?php
include_once PATH_VIEW_ADMIN . 'default/footer.php';
?>