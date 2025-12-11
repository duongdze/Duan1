<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';
?>

<main class="dashboard main-content">
    <div class="dashboard-container">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active">Quản lý xe</li>
                    </ol>
                </nav>
                <h2 class="h4 mb-0">
                    <i class="fas fa-bus text-primary me-2"></i>
                    Danh sách xe - Tour #<?= $assignment_id ?>
                </h2>
            </div>
            <div>
                <a href="<?= BASE_URL_ADMIN ?>&action=tour_vehicles/create&assignment_id=<?= $assignment_id ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Thêm xe
                </a>
                <a href="<?= BASE_URL_ADMIN ?>&action=available-tours" class="btn btn-secondary ms-2"> <!-- Tạm về available tours -->
                    <i class="fas fa-arrow-left me-2"></i>Quay lại
                </a>
            </div>
        </div>

        <!-- Alert -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i><?= $_SESSION['success'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i><?= $_SESSION['error'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Content -->
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Biển số</th>
                                <th>Nhà xe</th>
                                <th>Loại xe</th>
                                <th>Tài xế</th>
                                <th>Liên hệ</th>
                                <th>Trạng thái</th>
                                <th class="text-end pe-4">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($vehicles)): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="fas fa-bus fa-3x mb-3 text-secondary opacity-50"></i>
                                        <p class="mb-0">Chưa có xe nào được phân công cho chuyến này.</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($vehicles as $v): ?>
                                    <tr>
                                        <td class="ps-4 fw-bold text-primary"><?= htmlspecialchars($v['vehicle_plate']) ?></td>
                                        <td><?= htmlspecialchars($v['company_name'] ?? '---') ?></td>
                                        <td><?= htmlspecialchars($v['vehicle_type']) ?></td>
                                        <td>
                                            <?php if (!empty($v['driver_name'])): ?>
                                                <div class="fw-medium"><?= htmlspecialchars($v['driver_name']) ?></div>
                                                <?php if (!empty($v['driver_license'])): ?>
                                                    <small class="text-muted"><i class="far fa-id-card me-1"></i><?= htmlspecialchars($v['driver_license']) ?></small>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="text-muted">---</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (!empty($v['driver_phone'])): ?>
                                                <a href="tel:<?= htmlspecialchars($v['driver_phone']) ?>" class="text-decoration-none">
                                                    <i class="fas fa-phone-alt me-1 text-success"></i><?= htmlspecialchars($v['driver_phone']) ?>
                                                </a>
                                            <?php else: ?>
                                                ---
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php
                                            $statusMap = [
                                                'assigned' => ['badge' => 'bg-info', 'label' => 'Đã phân công'],
                                                'confirmed' => ['badge' => 'bg-primary', 'label' => 'Đã xác nhận'],
                                                'completed' => ['badge' => 'bg-success', 'label' => 'Hoàn thành'],
                                                'cancelled' => ['badge' => 'bg-danger', 'label' => 'Hủy']
                                            ];
                                            $s = $statusMap[$v['status']] ?? ['badge' => 'bg-secondary', 'label' => $v['status']];
                                            ?>
                                            <span class="badge <?= $s['badge'] ?> rounded-pill"><?= $s['label'] ?></span>
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="btn-group">
                                                <a href="<?= BASE_URL_ADMIN ?>&action=tour_vehicles/edit&id=<?= $v['id'] ?>" class="btn btn-sm btn-outline-primary" title="Sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="<?= BASE_URL_ADMIN ?>&action=tour_vehicles/delete&id=<?= $v['id'] ?>&assignment_id=<?= $assignment_id ?>"
                                                    class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Bạn có chắc chắn muốn xóa xe này?');" title="Xóa">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>