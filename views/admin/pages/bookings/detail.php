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
                                        ?>
                                        <span class="badge bg-<?= $statusClass ?>"><?= $statusText ?></span>
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
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-users"></i> Danh sách khách đi kèm (<?= count($companions) ?>)
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($companions)): ?>
                            <div class="d-flex flex-column gap-3">
                                <?php foreach ($companions as $index => $companion): ?>
                                    <div class="border rounded p-3 bg-light">
                                        <h6 class="mb-2">Khách #<?= $index + 1 ?></h6>
                                        <table class="table table-sm table-borderless mb-0">
                                            <tbody>
                                                <tr>
                                                    <td class="fw-bold" style="width: 40%;">Họ tên:</td>
                                                    <td><?= htmlspecialchars($companion['name']) ?></td>
                                                </tr>
                                                <?php if (!empty($companion['gender'])): ?>
                                                    <tr>
                                                        <td class="fw-bold">Giới tính:</td>
                                                        <td><?= htmlspecialchars($companion['gender']) ?></td>
                                                    </tr>
                                                <?php endif; ?>
                                                <?php if (!empty($companion['birth_date'])): ?>
                                                    <tr>
                                                        <td class="fw-bold">Ngày sinh:</td>
                                                        <td><?= htmlspecialchars($companion['birth_date']) ?></td>
                                                    </tr>
                                                <?php endif; ?>
                                                <?php if (!empty($companion['phone'])): ?>
                                                    <tr>
                                                        <td class="fw-bold">Điện thoại:</td>
                                                        <td><?= htmlspecialchars($companion['phone']) ?></td>
                                                    </tr>
                                                <?php endif; ?>
                                                <?php if (!empty($companion['id_card'])): ?>
                                                    <tr>
                                                        <td class="fw-bold">CMND/Hộ chiếu:</td>
                                                        <td><?= htmlspecialchars($companion['id_card']) ?></td>
                                                    </tr>
                                                <?php endif; ?>
                                                <?php if (!empty($companion['room_type'])): ?>
                                                    <tr>
                                                        <td class="fw-bold">Loại phòng:</td>
                                                        <td><?= htmlspecialchars($companion['room_type']) ?></td>
                                                    </tr>
                                                <?php endif; ?>
                                                <?php if (!empty($companion['special_request'])): ?>
                                                    <tr>
                                                        <td class="fw-bold">Yêu cầu đặc biệt:</td>
                                                        <td><?= htmlspecialchars($companion['special_request']) ?></td>
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
</main>

<?php
include_once PATH_VIEW_ADMIN . 'default/footer.php';
?>