<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';
?>

<main class="wrapper">
    <div class="main-content">
        <div class="page-header">
            <h1 class="h2">Báo cáo Booking</h1>
            <p class="text-muted">Thống kê và phân tích dữ liệu đặt tour</p>
        </div>

        <!-- Bộ lọc -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <input type="hidden" name="action" value="reports/bookings">

                    <div class="col-md-2">
                        <label for="date_from" class="form-label">Từ ngày</label>
                        <input type="date" class="form-control" id="date_from" name="date_from"
                            value="<?= $_GET['date_from'] ?? date('Y-m-01') ?>">
                    </div>

                    <div class="col-md-2">
                        <label for="date_to" class="form-label">Đến ngày</label>
                        <input type="date" class="form-control" id="date_to" name="date_to"
                            value="<?= $_GET['date_to'] ?? date('Y-m-d') ?>">
                    </div>

                    <div class="col-md-2">
                        <label for="tour_id" class="form-label">Tour</label>
                        <select class="form-select" id="tour_id" name="tour_id">
                            <option value="">Tất cả tours</option>
                            <?php if (isset($tours)): ?>
                                <?php foreach ($tours as $tour): ?>
                                    <option value="<?= $tour['id'] ?>"
                                        <?= (isset($_GET['tour_id']) && $_GET['tour_id'] == $tour['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($tour['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label for="status" class="form-label">Trạng thái</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Tất cả</option>
                            <option value="pending" <?= (($_GET['status'] ?? '') == 'pending') ? 'selected' : '' ?>>Chờ xác nhận</option>
                            <option value="confirmed" <?= (($_GET['status'] ?? '') == 'confirmed') ? 'selected' : '' ?>>Đã xác nhận</option>
                            <option value="deposited" <?= (($_GET['status'] ?? '') == 'deposited') ? 'selected' : '' ?>>Đã cọc</option>
                            <option value="paid" <?= (($_GET['status'] ?? '') == 'paid') ? 'selected' : '' ?>>Đã thanh toán</option>
                            <option value="completed" <?= (($_GET['status'] ?? '') == 'completed') ? 'selected' : '' ?>>Hoàn thành</option>
                            <option value="cancelled" <?= (($_GET['status'] ?? '') == 'cancelled') ? 'selected' : '' ?>>Đã hủy</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label for="source" class="form-label">Nguồn</label>
                        <select class="form-select" id="source" name="source">
                            <option value="">Tất cả</option>
                            <option value="website" <?= (($_GET['source'] ?? '') == 'website') ? 'selected' : '' ?>>Website</option>
                            <option value="zalo" <?= (($_GET['source'] ?? '') == 'zalo') ? 'selected' : '' ?>>Zalo</option>
                            <option value="phone" <?= (($_GET['source'] ?? '') == 'phone') ? 'selected' : '' ?>>Điện thoại</option>
                            <option value="walk_in" <?= (($_GET['source'] ?? '') == 'walk_in') ? 'selected' : '' ?>>Walk-in</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label for="report_type" class="form-label">Loại báo cáo</label>
                        <select class="form-select" id="report_type" name="report_type">
                            <option value="summary" <?= (($_GET['report_type'] ?? 'summary') == 'summary') ? 'selected' : '' ?>>Tổng quan</option>
                            <option value="conversion" <?= (($_GET['report_type'] ?? '') == 'conversion') ? 'selected' : '' ?>>Tỷ lệ chuyển đổi</option>
                            <option value="revenue" <?= (($_GET['report_type'] ?? '') == 'revenue') ? 'selected' : '' ?>>Doanh thu</option>
                            <option value="demographics" <?= (($_GET['report_type'] ?? '') == 'demographics') ? 'selected' : '' ?>>Nhân khẩu học</option>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter me-2"></i>Lọc báo cáo
                        </button>
                        <a href="<?= BASE_URL_ADMIN ?>&action=reports/bookings" class="btn btn-secondary">
                            <i class="fas fa-redo me-2"></i>Reset
                        </a>
                        <button type="button" class="btn btn-success float-end" onclick="exportBookingReport()">
                            <i class="fas fa-download me-2"></i>Xuất Excel
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Thẻ KPI Booking -->
        <div class="row mb-4">
            <div class="col-md-6 col-xl-3 mb-3">
                <div class="card kpi-card">
                    <div class="card-body">
                        <div class="kpi-icon" style="background-color: #dbeafe;">
                            <i class="fas fa-calendar-check" style="color: #3b82f6;"></i>
                        </div>
                        <div>
                            <div class="kpi-label">Tổng Booking</div>
                            <div class="kpi-value"><?= number_format($bookingStats['total_bookings'] ?? 0) ?></div>
                            <small class="text-success">
                                <i class="fas fa-arrow-up"></i>
                                +<?= number_format($bookingStats['booking_growth'] ?? 0, 1) ?>% so với kỳ trước
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3 mb-3">
                <div class="card kpi-card">
                    <div class="card-body">
                        <div class="kpi-icon" style="background-color: #dcfce7;">
                            <i class="fas fa-check-circle" style="color: #22c55e;"></i>
                        </div>
                        <div>
                            <div class="kpi-label">Booking Thành công</div>
                            <div class="kpi-value"><?= number_format($bookingStats['successful_bookings'] ?? 0) ?></div>
                            <small class="text-muted">
                                Tỷ lệ: <?= number_format($bookingStats['success_rate'] ?? 0, 1) ?>%
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3 mb-3">
                <div class="card kpi-card">
                    <div class="card-body">
                        <div class="kpi-icon" style="background-color: #fef3c7;">
                            <i class="fas fa-percentage" style="color: #f59e0b;"></i>
                        </div>
                        <div>
                            <div class="kpi-label">Tỷ lệ Chuyển đổi</div>
                            <div class="kpi-value"><?= number_format($bookingStats['conversion_rate'] ?? 0, 1) ?>%</div>
                            <small class="text-muted">
                                <?= ($bookingStats['conversion_rate'] ?? 0) >= 30 ? 'Tốt' : (($bookingStats['conversion_rate'] ?? 0) >= 20 ? 'Trung bình' : 'Cần cải thiện') ?>
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3 mb-3">
                <div class="card kpi-card">
                    <div class="card-body">
                        <div class="kpi-icon" style="background-color: #e0f2fe;">
                            <i class="fas fa-users" style="color: #0ea5e9;"></i>
                        </div>
                        <div>
                            <div class="kpi-label">Tổng Khách hàng</div>
                            <div class="kpi-value"><?= number_format($bookingStats['total_customers'] ?? 0) ?></div>
                            <small class="text-muted">
                                Trung bình: <?= number_format($bookingStats['avg_customers_per_booking'] ?? 0, 1) ?>/booking
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Biểu đồ và Phân tích -->
        <div class="row">
            <!-- Biểu đồ Booking theo thời gian -->
            <div class="col-lg-8 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Biểu đồ Booking theo Thời gian</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="bookingChart" height="100"></canvas>
                    </div>
                </div>
            </div>

            <!-- Biểu đồ tròn Booking theo Nguồn -->
            <div class="col-lg-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Booking theo Nguồn</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="sourceChart" height="150"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bảng chi tiết Booking -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Chi tiết Booking</h5>
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-primary active" onclick="showBookingTable('all')">Tất cả</button>
                    <button class="btn btn-outline-success" onclick="showBookingTable('successful')">Thành công</button>
                    <button class="btn btn-outline-warning" onclick="showBookingTable('pending')">Chờ xử lý</button>
                    <button class="btn btn-outline-danger" onclick="showBookingTable('cancelled')">Đã hủy</button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="bookingTable">
                        <thead>
                            <tr>
                                <th>Mã Booking</th>
                                <th>Khách hàng</th>
                                <th>Tour</th>
                                <th>Ngày đi</th>
                                <th>Số khách</th>
                                <th>Giá trị</th>
                                <th>Trạng thái</th>
                                <th>Nguồn</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($bookings)): ?>
                                <?php foreach ($bookings as $booking): ?>
                                    <tr data-status="<?= $booking['status'] ?>">
                                        <td>
                                            <span class="badge bg-primary">#<?= str_pad($booking['id'], 6, '0', STR_PAD_LEFT) ?></span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-light rounded-circle me-2">
                                                    <i class="fas fa-user text-primary"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-medium"><?= htmlspecialchars($booking['customer_name'] ?? 'N/A') ?></div>
                                                    <small class="text-muted"><?= htmlspecialchars($booking['customer_phone'] ?? '') ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-medium"><?= htmlspecialchars($booking['tour_name']) ?></div>
                                            <small class="text-muted"><?= htmlspecialchars($booking['category_name'] ?? '') ?></small>
                                        </td>
                                        <td>
                                            <?= date('d/m/Y', strtotime($booking['departure_date'])) ?>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <span class="badge bg-info"><?= $booking['adults'] ?? 0 ?></span>
                                                <span class="badge bg-secondary"><?= $booking['children'] ?? 0 ?></span>
                                                <span class="badge bg-warning"><?= $booking['infants'] ?? 0 ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-success fw-medium">
                                                <?= number_format($booking['final_price'] ?? 0, 0, ',', '.') ?> VNĐ
                                            </span>
                                        </td>
                                        <td>
                                            <?php
                                            $statusColors = [
                                                'pending' => 'warning',
                                                'confirmed' => 'info',
                                                'deposited' => 'primary',
                                                'paid' => 'success',
                                                'completed' => 'success',
                                                'cancelled' => 'danger'
                                            ];
                                            $statusLabels = [
                                                'pending' => 'Chờ xác nhận',
                                                'confirmed' => 'Đã xác nhận',
                                                'deposited' => 'Đã cọc',
                                                'paid' => 'Đã thanh toán',
                                                'completed' => 'Hoàn thành',
                                                'cancelled' => 'Đã hủy'
                                            ];
                                            $color = $statusColors[$booking['status']] ?? 'secondary';
                                            $label = $statusLabels[$booking['status']] ?? $booking['status'];
                                            ?>
                                            <span class="badge bg-<?= $color ?>"><?= $label ?></span>
                                        </td>
                                        <td>
                                            <?php
                                            $sourceIcons = [
                                                'website' => 'fa-globe',
                                                'zalo' => 'fa-comment',
                                                'phone' => 'fa-phone',
                                                'walk_in' => 'fa-walking'
                                            ];
                                            $icon = $sourceIcons[$booking['source']] ?? 'fa-question';
                                            ?>
                                            <i class="fas <?= $icon ?> text-muted"></i>
                                            <small class="ms-1"><?= ucfirst($booking['source'] ?? '') ?></small>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" onclick="viewBookingDetail(<?= $booking['id'] ?>)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-success" onclick="downloadBookingInvoice(<?= $booking['id'] ?>)">
                                                <i class="fas fa-file-invoice"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Phân tích Chi tiết -->
        <div class="row mt-4">
            <!-- Top Tours được booking nhiều nhất -->
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Top Tours được Booking nhiều nhất</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <?php if (isset($topTours)): ?>
                                <?php foreach ($topTours as $index => $tour): ?>
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-medium"><?= $tour['tour_name'] ?></div>
                                            <small class="text-muted"><?= $tour['booking_count'] ?> booking</small>
                                        </div>
                                        <div class="text-end">
                                            <div class="text-success fw-medium"><?= number_format($tour['revenue'], 0, ',', '.') ?> VNĐ</div>
                                            <small class="text-muted"><?= number_format($tour['avg_price'], 0, ',', '.') ?>/khách</small>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Phân tích theo Nguồn -->
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Phân tích theo Nguồn Booking</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <?php if (isset($sourceAnalysis)): ?>
                                <?php foreach ($sourceAnalysis as $source): ?>
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <?php
                                            $sourceIcons = [
                                                'website' => 'fa-globe text-primary',
                                                'zalo' => 'fa-comment text-info',
                                                'phone' => 'fa-phone text-success',
                                                'walk_in' => 'fa-walking text-warning'
                                            ];
                                            $icon = $sourceIcons[$source['source']] ?? 'fa-question text-secondary';
                                            ?>
                                            <i class="fas <?= $icon ?> me-2"></i>
                                            <div>
                                                <div class="fw-medium"><?= ucfirst($source['source']) ?></div>
                                                <small class="text-muted"><?= $source['booking_count'] ?> booking</small>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <div class="fw-medium"><?= number_format($source['conversion_rate'], 1) ?>%</div>
                                            <small class="text-muted">tỷ lệ chuyển đổi</small>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Biểu đồ Booking theo thời gian
    const bookingCtx = document.getElementById('bookingChart');
    if (bookingCtx) {
        new Chart(bookingCtx, {
            type: 'line',
            data: {
                labels: <?= json_encode($monthlyLabels ?? ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6']) ?>,
                datasets: [{
                    label: 'Tổng Booking',
                    data: <?= json_encode($monthlyBookings ?? [45, 52, 38, 65, 48, 72]) ?>,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4
                }, {
                    label: 'Booking Thành công',
                    data: <?= json_encode($monthlySuccessfulBookings ?? [35, 42, 28, 52, 38, 58]) ?>,
                    borderColor: '#22c55e',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 10
                        }
                    }
                }
            }
        });
    }

    // Biểu đồ tròn Booking theo Nguồn
    const sourceCtx = document.getElementById('sourceChart');
    if (sourceCtx) {
        new Chart(sourceCtx, {
            type: 'doughnut',
            data: {
                labels: <?= json_encode($sourceNames ?? ['Website', 'Zalo', 'Điện thoại', 'Walk-in']) ?>,
                datasets: [{
                    data: <?= json_encode($sourceCounts ?? [120, 85, 65, 45]) ?>,
                    backgroundColor: [
                        '#3b82f6',
                        '#22c55e',
                        '#f59e0b',
                        '#ef4444'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
    }

    // Functions
    function showBookingTable(type) {
        const rows = document.querySelectorAll('#bookingTable tbody tr');
        rows.forEach(row => {
            if (type === 'all') {
                row.style.display = '';
            } else if (type === 'successful' && ['completed', 'paid'].includes(row.dataset.status)) {
                row.style.display = '';
            } else if (type === 'pending' && ['pending', 'confirmed', 'deposited'].includes(row.dataset.status)) {
                row.style.display = '';
            } else if (type === 'cancelled' && row.dataset.status === 'cancelled') {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });

        // Update button states
        document.querySelectorAll('.btn-group .btn').forEach(btn => {
            btn.classList.remove('active');
        });
        event.target.classList.add('active');
    }

    function viewBookingDetail(bookingId) {
        window.open(`<?= BASE_URL_ADMIN ?>&action=bookings/detail&id=${bookingId}`, '_blank');
    }

    function downloadBookingInvoice(bookingId) {
        const params = new URLSearchParams(window.location.search);
        params.set('export', 'invoice');
        params.set('booking_id', bookingId);
        window.open(window.location.pathname + '?' + params.toString(), '_blank');
    }

    function exportBookingReport() {
        const params = new URLSearchParams(window.location.search);
        params.set('export', 'excel');
        window.open(window.location.pathname + '?' + params.toString(), '_blank');
    }
</script>

<style>
    .kpi-card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transition: transform 0.2s ease-in-out;
    }

    .kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
    }

    .kpi-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        margin-bottom: 1rem;
    }

    .kpi-label {
        font-size: 0.875rem;
        color: #6b7280;
        margin-bottom: 0.25rem;
    }

    .kpi-value {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1f2937;
    }

    .avatar-sm {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }

    .table th {
        font-weight: 600;
        color: #374151;
        border-bottom: 2px solid #e5e7eb;
    }

    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }

    .list-group-item {
        border: none;
        border-bottom: 1px solid #e5e7eb;
    }

    .list-group-item:last-child {
        border-bottom: none;
    }
</style>