<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';

$isEdit = isset($vehicle);
$title = $isEdit ? 'Cập nhật thông tin xe' : 'Thêm xe vào chuyến đi';
$action = $isEdit ? 'update' : 'store';
$formAction = BASE_URL_ADMIN . '&action=tour_vehicles/' . $action;
?>

<main class="dashboard main-content">
    <div class="dashboard-container">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=tour_vehicles&assignment_id=<?= $assignment_id ?>">Quản lý xe</a></li>
                        <li class="breadcrumb-item active"><?= $title ?></li>
                    </ol>
                </nav>
                <h2 class="h4 mb-0"><?= $title ?></h2>
            </div>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle me-2"></i><?= $_SESSION['error'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form action="<?= $formAction ?>" method="POST" class="needs-validation" novalidate>
            <input type="hidden" name="tour_assignment_id" value="<?= $assignment_id ?>">
            <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?= $vehicle['id'] ?>">
            <?php endif; ?>

            <div class="row">
                <!-- Vehicle Info -->
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="card-title mb-0 text-primary"><i class="fas fa-truck-moving me-2"></i>Thông tin phương tiện</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Nhà xe</label>
                                    <select name="bus_company_id" id="bus_company_select" class="form-select">
                                        <option value="">-- Chọn nhà xe / Tự do --</option>
                                        <?php if (!empty($busCompanies)): ?>
                                            <?php foreach ($busCompanies as $company): ?>
                                                <option value="<?= $company['id'] ?>" <?= ($vehicle['bus_company_id'] ?? '') == $company['id'] ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($company['company_name']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>

                                    <!-- History Suggestion Container -->
                                    <div id="vehicle_history_container" class="mt-2 d-none">
                                        <label class="form-label text-muted small fst-italic"> Chọn từ lịch sử:</label>
                                        <select id="vehicle_history_select" class="form-select form-select-sm">
                                            <option value="">-- Chọn xe đã từng chạy --</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Biển số xe <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="vehicle_plate" id="vehicle_plate" required value="<?= htmlspecialchars($vehicle['vehicle_plate'] ?? '') ?>" placeholder="VD: 29B-123.45">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Loại xe</label>
                                    <input type="text" class="form-control" name="vehicle_type" id="vehicle_type" value="<?= htmlspecialchars($vehicle['vehicle_type'] ?? '') ?>" placeholder="VD: 45 chỗ, Limousine...">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Hãng xe / Hiệu xe</label>
                                    <input type="text" class="form-control" name="vehicle_brand" id="vehicle_brand" value="<?= htmlspecialchars($vehicle['vehicle_brand'] ?? '') ?>" placeholder="VD: Hyundai Universe">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white py-3">
                            <h5 class="card-title mb-0 text-success"><i class="fas fa-user-tie me-2"></i>Thông tin tài xế</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-medium">Tên tài xế</label>
                                    <input type="text" class="form-control" name="driver_name" id="driver_name" value="<?= htmlspecialchars($vehicle['driver_name'] ?? '') ?>" placeholder="Họ và tên">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-medium">Số điện thoại</label>
                                    <input type="tel" class="form-control" name="driver_phone" id="driver_phone" value="<?= htmlspecialchars($vehicle['driver_phone'] ?? '') ?>" placeholder="SĐT liên hệ">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-medium">Giấy phép lái xe</label>
                                    <input type="text" class="form-control" name="driver_license" id="driver_license" value="<?= htmlspecialchars($vehicle['driver_license'] ?? '') ?>" placeholder="Hạng E, FC...">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-medium">Ghi chú</label>
                                    <textarea class="form-control" name="notes" rows="3" placeholder="Ghi chú thêm về xe, điểm đón..."><?= htmlspecialchars($vehicle['notes'] ?? '') ?></textarea>
                                </div>
                                <?php if ($isEdit): ?>
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium">Trạng thái</label>
                                        <select name="status" class="form-select">
                                            <option value="assigned" <?= ($vehicle['status'] ?? '') == 'assigned' ? 'selected' : '' ?>>Đã phân công</option>
                                            <option value="confirmed" <?= ($vehicle['status'] ?? '') == 'confirmed' ? 'selected' : '' ?>>Đã xác nhận</option>
                                            <option value="completed" <?= ($vehicle['status'] ?? '') == 'completed' ? 'selected' : '' ?>>Hoàn thành</option>
                                            <option value="cancelled" <?= ($vehicle['status'] ?? '') == 'cancelled' ? 'selected' : '' ?>>Hủy</option>
                                        </select>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="col-lg-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary w-100 mb-2">
                                <i class="fas fa-save me-2"></i><?= $isEdit ? 'Cập nhật' : 'Lưu lại' ?>
                            </button>
                            <a href="<?= BASE_URL_ADMIN ?>&action=tour_vehicles&assignment_id=<?= $assignment_id ?>" class="btn btn-secondary w-100">
                                <i class="fas fa-times me-2"></i>Hủy bỏ
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const busCompanySelect = document.getElementById('bus_company_select');
        const historyContainer = document.getElementById('vehicle_history_container');
        const historySelect = document.getElementById('vehicle_history_select');

        // Fields to auto-fill
        const fields = {
            'vehicle_plate': 'vehicle_plate',
            'vehicle_type': 'vehicle_type',
            'vehicle_brand': 'vehicle_brand',
            'driver_name': 'driver_name',
            'driver_phone': 'driver_phone',
            'driver_license': 'driver_license'
        };

        busCompanySelect.addEventListener('change', function() {
            const companyId = this.value;

            // Reset and hide history if no company selected
            if (!companyId) {
                historyContainer.classList.add('d-none');
                return;
            }

            // Fetch history
            fetch(`<?= BASE_URL_ADMIN ?>&action=tour_vehicles/get-history&bus_company_id=${companyId}`)
                .then(response => response.json())
                .then(data => {
                    // Clear old options
                    historySelect.innerHTML = '<option value="">-- Chọn xe đã từng chạy --</option>';

                    if (data.length > 0) {
                        data.forEach((item, index) => {
                            const option = document.createElement('option');
                            option.value = index; // Store index to retrieve full object later
                            // Display: Plate - Driver (Type)
                            let text = item.vehicle_plate || 'Chưa rõ BKS';
                            if (item.driver_name) text += ` - TX: ${item.driver_name}`;
                            if (item.vehicle_type) text += ` (${item.vehicle_type})`;
                            option.text = text;

                            // Store full data in data attribute
                            option.dataset.vehicle = JSON.stringify(item);
                            historySelect.appendChild(option);
                        });
                        historyContainer.classList.remove('d-none');
                    } else {
                        historyContainer.classList.add('d-none');
                    }
                })
                .catch(error => console.error('Error fetching vehicle history:', error));
        });

        // Handle history selection
        historySelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (!selectedOption.value) return;

            const vehicleData = JSON.parse(selectedOption.dataset.vehicle);

            // Auto-fill fields
            for (const [inputId, dataKey] of Object.entries(fields)) {
                const input = document.getElementById(inputId);
                if (input && vehicleData[dataKey]) {
                    input.value = vehicleData[dataKey];
                }
            }
        });
    });
</script>

<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>