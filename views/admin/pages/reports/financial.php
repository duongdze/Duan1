<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';
include_once PATH_VIEW_ADMIN . 'components/advanced_filters.php';
?>

<main class="wrapper">
    <div class="main-content">
        <div class="page-header">
            <h1 class="h2">Báo cáo Tài chính</h1>
            <p class="text-muted">Theo dõi doanh thu, chi phí và lợi nhuận</p>
        </div>

        <!-- Advanced Filters -->
        <?php
        renderAdvancedFilters($filters ?? [], $filterOptions ?? [], 'financial');
        ?>

        <!-- Bộ lọc thời gian -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <input type="hidden" name="action" value="reports/financial">

                    <div class="col-md-3">
                        <label for="date_from" class="form-label">Từ ngày</label>
                        <input type="date" class="form-control" id="date_from" name="date_from"
                            value="<?= $_GET['date_from'] ?? date('Y-m-01') ?>">
                    </div>

                    <div class="col-md-3">
                        <label for="date_to" class="form-label">Đến ngày</label>
                        <input type="date" class="form-control" id="date_to" name="date_to"
                            value="<?= $_GET['date_to'] ?? date('Y-m-d') ?>">
                    </div>

                    <div class="col-md-3">
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

                    <div class="col-md-3">
                        <label for="report_type" class="form-label">Loại báo cáo</label>
                        <select class="form-select" id="report_type" name="report_type">
                            <option value="summary" <?= (($_GET['report_type'] ?? 'summary') == 'summary') ? 'selected' : '' ?>>
                                Tổng quan
                            </option>
                            <option value="detailed" <?= (($_GET['report_type'] ?? '') == 'detailed') ? 'selected' : '' ?>>
                                Chi tiết
                            </option>
                            <option value="profit" <?= (($_GET['report_type'] ?? '') == 'profit') ? 'selected' : '' ?>>
                                Lãi/Lỗ
                            </option>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter me-2"></i>Lọc báo cáo
                        </button>
                        <a href="<?= BASE_URL_ADMIN ?>&action=reports/financial" class="btn btn-secondary">
                            <i class="fas fa-redo me-2"></i>Reset
                        </a>
                        <button type="button" class="btn btn-success float-end" onclick="exportReport()">
                            <i class="fas fa-download me-2"></i>Xuất Excel
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Thẻ KPI Tài chính -->
        <div class="row mb-4">
            <div class="col-md-6 col-xl-3 mb-3">
                <div class="card kpi-card">
                    <div class="card-body">
                        <div class="kpi-icon" style="background-color: #dcfce7;">
                            <i class="fas fa-arrow-trend-up" style="color: #22c55e;"></i>
                        </div>
                        <div>
                            <div class="kpi-label">Tổng Doanh thu</div>
                            <div class="kpi-value"><?= number_format($financialData['total_revenue'] ?? 0, 0, ',', '.') ?> VNĐ</div>
                            <small class="text-success">
                                <i class="fas fa-arrow-up"></i>
                                +<?= number_format($financialData['revenue_growth'] ?? 0, 1) ?>% so với kỳ trước
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3 mb-3">
                <div class="card kpi-card">
                    <div class="card-body">
                        <div class="kpi-icon" style="background-color: #fee2e2;">
                            <i class="fas fa-arrow-trend-down" style="color: #ef4444;"></i>
                        </div>
                        <div>
                            <div class="kpi-label">Tổng Chi phí</div>
                            <div class="kpi-value"><?= number_format($financialData['total_expense'] ?? 0, 0, ',', '.') ?> VNĐ</div>
                            <small class="text-danger">
                                <i class="fas fa-arrow-up"></i>
                                +<?= number_format($financialData['expense_growth'] ?? 0, 1) ?>% so với kỳ trước
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3 mb-3">
                <div class="card kpi-card">
                    <div class="card-body">
                        <div class="kpi-icon" style="background-color: #e0f2fe;">
                            <i class="fas fa-chart-line" style="color: #0ea5e9;"></i>
                        </div>
                        <div>
                            <div class="kpi-label">Lợi nhuận</div>
                            <div class="kpi-value <?= ($financialData['profit'] ?? 0) >= 0 ? 'text-success' : 'text-danger' ?>">
                                <?= number_format($financialData['profit'] ?? 0, 0, ',', '.') ?> VNĐ
                            </div>
                            <small class="<?= ($financialData['profit_growth'] ?? 0) >= 0 ? 'text-success' : 'text-danger' ?>">
                                <i class="fas fa-arrow-<?= ($financialData['profit_growth'] ?? 0) >= 0 ? 'up' : 'down' ?>"></i>
                                <?= number_format($financialData['profit_growth'] ?? 0, 1) ?>%
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
                            <div class="kpi-label">Tỷ suất lợi nhuận</div>
                            <div class="kpi-value"><?= number_format($financialData['profit_margin'] ?? 0, 1) ?>%</div>
                            <small class="text-muted">
                                <?= ($financialData['profit_margin'] ?? 0) >= 20 ? 'Tốt' : (($financialData['profit_margin'] ?? 0) >= 10 ? 'Trung bình' : 'Cần cải thiện') ?>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Biểu đồ và Bảng chi tiết -->
        <div class="row">
            <!-- Biểu đồ Doanh thu - Chi phí -->
            <div class="col-lg-8 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Biểu đồ Doanh thu - Chi phí</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="financialChart" height="100"></canvas>
                    </div>
                </div>
            </div>

            <!-- Biểu đồ tròn Lợi nhuận theo Tour -->
            <div class="col-lg-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Lợi nhuận theo Tour</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="profitByTourChart" height="150"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bảng chi tiết Lãi/Lỗ theo Tour -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Chi tiết Lãi/Lỗ theo Tour</h5>
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-primary active" onclick="showTable('all')">Tất cả</button>
                    <button class="btn btn-outline-success" onclick="showTable('profit')">Lãi</button>
                    <button class="btn btn-outline-danger" onclick="showTable('loss')">Lỗ</button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="financialTable">
                        <thead>
                            <tr>
                                <th>Tour</th>
                                <th>Số Booking</th>
                                <th>Doanh thu</th>
                                <th>Chi phí</th>
                                <th>Lợi nhuận</th>
                                <th>Tỷ suất LN</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($tourFinancials)): ?>
                                <?php foreach ($tourFinancials as $tour): ?>
                                    <tr data-status="<?= ($tour['profit'] ?? 0) >= 0 ? 'profit' : 'loss' ?>">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-light rounded-circle me-2">
                                                    <i class="fas fa-map-marked-alt text-primary"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-medium"><?= htmlspecialchars($tour['tour_name']) ?></div>
                                                    <small class="text-muted"><?= htmlspecialchars($tour['category_name'] ?? '') ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info"><?= $tour['booking_count'] ?? 0 ?></span>
                                        </td>
                                        <td>
                                            <span class="text-success fw-medium">
                                                <?= number_format($tour['revenue'] ?? 0, 0, ',', '.') ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-danger fw-medium">
                                                <?= number_format($tour['expense'] ?? 0, 0, ',', '.') ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="fw-medium <?= ($tour['profit'] ?? 0) >= 0 ? 'text-success' : 'text-danger' ?>">
                                                <?= number_format($tour['profit'] ?? 0, 0, ',', '.') ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar <?= ($tour['profit_margin'] ?? 0) >= 20 ? 'bg-success' : (($tour['profit_margin'] ?? 0) >= 10 ? 'bg-warning' : 'bg-danger') ?>"
                                                    style="width: <?= min(100, max(0, $tour['profit_margin'] ?? 0)) ?>%">
                                                </div>
                                            </div>
                                            <small><?= number_format($tour['profit_margin'] ?? 0, 1) ?>%</small>
                                        </td>
                                        <td>
                                            <?php if (($tour['profit'] ?? 0) >= 0): ?>
                                                <span class="badge bg-success">Lãi</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Lỗ</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" onclick="viewTourDetail(<?= $tour['tour_id'] ?? 0 ?>)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-success" onclick="downloadTourReport(<?= $tour['tour_id'] ?? 0 ?>)">
                                                <i class="fas fa-download"></i>
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
    </div>
</main>

<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Biểu đồ Doanh thu - Chi phí
    const financialCtx = document.getElementById('financialChart');
    if (financialCtx) {
        new Chart(financialCtx, {
            type: 'line',
            data: {
                labels: <?= json_encode($monthlyLabels ?? ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6']) ?>,
                datasets: [{
                    label: 'Doanh thu',
                    data: <?= json_encode($monthlyRevenue ?? [12000000, 19000000, 15000000, 25000000, 22000000, 30000000]) ?>,
                    borderColor: '#22c55e',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.4
                }, {
                    label: 'Chi phí',
                    data: <?= json_encode($monthlyExpense ?? [8000000, 12000000, 10000000, 18000000, 15000000, 20000000]) ?>,
                    borderColor: '#ef4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    tension: 0.4
                }, {
                    label: 'Lợi nhuận',
                    data: <?= json_encode($monthlyProfit ?? [4000000, 7000000, 5000000, 7000000, 7000000, 10000000]) ?>,
                    borderColor: '#0ea5e9',
                    backgroundColor: 'rgba(14, 165, 233, 0.1)',
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
                            callback: function(value) {
                                return new Intl.NumberFormat('vi-VN').format(value) + ' VNĐ';
                            }
                        }
                    }
                }
            }
        });
    }

    // Biểu đồ tròn Lợi nhuận theo Tour
    const profitByTourCtx = document.getElementById('profitByTourChart');
    if (profitByTourCtx) {
        new Chart(profitByTourCtx, {
            type: 'doughnut',
            data: {
                labels: <?= json_encode($tourNames ?? ['Tour Hà Nội', 'Tour Đà Nẵng', 'Tour TP.HCM']) ?>,
                datasets: [{
                    data: <?= json_encode($tourProfits ?? [4000000, 7000000, 5000000]) ?>,
                    backgroundColor: [
                        '#22c55e',
                        '#0ea5e9',
                        '#f59e0b',
                        '#ef4444',
                        '#8b5cf6'
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
    function showTable(type) {
        const rows = document.querySelectorAll('#financialTable tbody tr');
        rows.forEach(row => {
            if (type === 'all') {
                row.style.display = '';
            } else if (type === 'profit' && row.dataset.status === 'profit') {
                row.style.display = '';
            } else if (type === 'loss' && row.dataset.status === 'loss') {
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

    function viewTourDetail(tourId) {
        window.open(`<?= BASE_URL_ADMIN ?>&action=tours/detail&id=${tourId}`, '_blank');
    }

    function downloadTourReport(tourId) {
        const params = new URLSearchParams(window.location.search);
        params.set('export', 'tour');
        params.set('tour_id', tourId);
        window.open(window.location.pathname + '?' + params.toString(), '_blank');
    }

    function exportReport() {
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

    .progress {
        background-color: #e5e7eb;
    }

    .progress-bar {
        transition: width 0.3s ease;
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
</style>