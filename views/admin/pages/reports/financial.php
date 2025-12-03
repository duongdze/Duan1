<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';

// Get data from controller
$financialData = $data['financialData'] ?? [];
$tourFinancials = $data['tourFinancials'] ?? [];
$filters = $data['filters'] ?? [];
$filterOptions = $data['filterOptions'] ?? [];
?>

<main class="dashboard">
    <div class="dashboard-container">
        <!-- Header -->
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
                        <a href="<?= BASE_URL_ADMIN ?>&action=reports" class="breadcrumb-link">
                            <span>Báo Cáo</span>
                        </a>
                        <span class="breadcrumb-separator">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                        <span class="breadcrumb-current">Tài Chính</span>
                    </div>
                    <div class="page-title-section">
                        <h1 class="page-title">
                            <i class="fas fa-dollar-sign title-icon"></i>
                            Báo Cáo Tài Chính
                        </h1>
                        <p class="page-subtitle">Doanh thu, chi phí và lợi nhuận chi tiết theo tour</p>
                    </div>
                </div>
            </div>
        </header>

        <!-- Advanced Filters -->
        <section class="filters-section">
            <div class="filter-card">
                <div class="filter-header">
                    <h3 class="filter-title">
                        <i class="fas fa-filter"></i>
                        Bộ Lọc Nâng Cao
                    </h3>
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="resetFilters()">
                        <i class="fas fa-redo"></i> Reset
                    </button>
                </div>
                <form method="GET" action="<?= BASE_URL_ADMIN . '&action=reports/financial' ?>" class="filter-form">
                    <input type="hidden" name="action" value="reports/financial">
                    
                    <div class="filter-row">
                        <div class="filter-group">
                            <label class="filter-label">Từ ngày</label>
                            <input type="date" class="form-control" name="date_from" 
                                value="<?= htmlspecialchars($filters['date_from'] ?? date('Y-m-01')) ?>">
                        </div>

                        <div class="filter-group">
                            <label class="filter-label">Đến ngày</label>
                            <input type="date" class="form-control" name="date_to" 
                                value="<?= htmlspecialchars($filters['date_to'] ?? date('Y-m-d')) ?>">
                        </div>

                        <div class="filter-group">
                            <label class="filter-label">Tour</label>
                            <select class="form-select" name="tour_id">
                                <option value="">Tất cả</option>
                                <?php if (!empty($filterOptions['tours'])): ?>
                                    <?php foreach ($filterOptions['tours'] as $tour): ?>
                                        <option value="<?= $tour['id'] ?>" 
                                            <?= ($filters['tour_id'] ?? '') == $tour['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($tour['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label class="filter-label">Danh mục</label>
                            <select class="form-select" name="category_id">
                                <option value="">Tất cả</option>
                                <?php if (!empty($filterOptions['categories'])): ?>
                                    <?php foreach ($filterOptions['categories'] as $category): ?>
                                        <option value="<?= $category['id'] ?>" 
                                            <?= ($filters['category_id'] ?? '') == $category['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($category['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="filter-group filter-actions-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>
                                Lọc
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </section>

        <!-- Financial Summary Cards -->
        <section class="financial-summary-section">
            <div class="summary-grid">
                <!-- Total Revenue -->
                <div class="summary-card summary-success">
                    <div class="summary-icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="summary-content">
                        <div class="summary-label">Tổng Doanh Thu</div>
                        <div class="summary-value"><?= number_format($financialData['total_revenue'] ?? 0, 0, ',', '.') ?> ₫</div>
                        <div class="summary-detail">
                            Từ <?= $financialData['total_bookings'] ?? 0 ?> booking
                        </div>
                    </div>
                </div>

                <!-- Total Expense -->
                <div class="summary-card summary-danger">
                    <div class="summary-icon">
                        <i class="fas fa-receipt"></i>
                    </div>
                    <div class="summary-content">
                        <div class="summary-label">Tổng Chi Phí</div>
                        <div class="summary-value"><?= number_format($financialData['total_expense'] ?? 0, 0, ',', '.') ?> ₫</div>
                        <div class="summary-detail">
                            <?= $financialData['cost_count'] ?? 0 ?> khoản chi
                        </div>
                    </div>
                </div>

                <!-- Profit -->
                <div class="summary-card summary-primary">
                    <div class="summary-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="summary-content">
                        <div class="summary-label">Lợi Nhuận</div>
                        <div class="summary-value"><?= number_format($financialData['profit'] ?? 0, 0, ',', '.') ?> ₫</div>
                        <div class="summary-detail">
                            Margin: <?= number_format($financialData['profit_margin'] ?? 0, 1) ?>%
                        </div>
                    </div>
                </div>

                <!-- Average Booking Value -->
                <div class="summary-card summary-info">
                    <div class="summary-icon">
                        <i class="fas fa-calculator"></i>
                    </div>
                    <div class="summary-content">
                        <div class="summary-label">Giá Trị TB/Booking</div>
                        <div class="summary-value"><?= number_format($financialData['avg_booking_value'] ?? 0, 0, ',', '.') ?> ₫</div>
                        <div class="summary-detail">
                            Chi phí TB: <?= number_format($financialData['avg_cost'] ?? 0, 0, ',', '.') ?> ₫
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Charts Section -->
        <section class="charts-section">
            <div class="charts-row">
                <!-- Monthly Revenue vs Expense -->
                <div class="chart-card chart-card-large">
                    <div class="chart-header">
                        <h3 class="chart-title">
                            <i class="fas fa-chart-bar"></i>
                            Doanh Thu & Chi Phí Theo Tháng
                        </h3>
                    </div>
                    <div class="chart-body">
                        <canvas id="monthlyFinancialChart"></canvas>
                    </div>
                </div>

                <!-- Profit by Tour (Pie Chart) -->
                <div class="chart-card">
                    <div class="chart-header">
                        <h3 class="chart-title">
                            <i class="fas fa-chart-pie"></i>
                            Lợi Nhuận Top 5 Tours
                        </h3>
                    </div>
                    <div class="chart-body">
                        <canvas id="tourProfitChart"></canvas>
                    </div>
                </div>
            </div>
        </section>

        <!-- Tour Financials Table -->
        <section class="tours-section">
            <div class="tours-header">
                <div class="tours-info">
                    <h3 class="tours-title">
                        <i class="fas fa-table"></i>
                        Chi Tiết Tài Chính Theo Tour
                    </h3>
                </div>
                <div class="tours-count">
                    <span class="count-info"><?= count($tourFinancials) ?> tours</span>
                </div>
            </div>

            <div class="tours-container">
                <?php if (!empty($tourFinancials)): ?>
                    <div class="table-responsive">
                        <table class="table table-modern">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tên Tour</th>
                                    <th>Danh Mục</th>
                                    <th class="text-end">Booking</th>
                                    <th class="text-end">Doanh Thu</th>
                                    <th class="text-end">Chi Phí</th>
                                    <th class="text-end">Lợi Nhuận</th>
                                    <th class="text-end">Margin</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tourFinancials as $index => $tour): ?>
                                    <tr>
                                        <td><strong><?= $index + 1 ?></strong></td>
                                        <td>
                                            <div class="tour-name">
                                                <i class="fas fa-map-marked-alt text-info me-2"></i>
                                                <?= htmlspecialchars($tour['tour_name']) ?>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                <?= htmlspecialchars($tour['category_name'] ?? 'N/A') ?>
                                            </span>
                                        </td>
                                        <td class="text-end"><?= number_format($tour['booking_count'] ?? 0) ?></td>
                                        <td class="text-end">
                                            <strong class="text-success">
                                                <?= number_format($tour['revenue'] ?? 0, 0, ',', '.') ?> ₫
                                            </strong>
                                        </td>
                                        <td class="text-end">
                                            <span class="text-danger">
                                                <?= number_format($tour['expense'] ?? 0, 0, ',', '.') ?> ₫
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <strong class="<?= ($tour['profit'] ?? 0) >= 0 ? 'text-primary' : 'text-danger' ?>">
                                                <?= number_format($tour['profit'] ?? 0, 0, ',', '.') ?> ₫
                                            </strong>
                                        </td>
                                        <td class="text-end">
                                            <span class="badge <?= ($tour['profit_margin'] ?? 0) >= 20 ? 'bg-success' : 
                                                (($tour['profit_margin'] ?? 0) >= 10 ? 'bg-warning' : 'bg-danger') ?>">
                                                <?= number_format($tour['profit_margin'] ?? 0, 1) ?>%
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr class="table-summary">
                                    <td colspan="3"><strong>TỔNG CỘNG</strong></td>
                                    <td class="text-end"><strong><?= number_format(array_sum(array_column($tourFinancials, 'booking_count'))) ?></strong></td>
                                    <td class="text-end">
                                        <strong class="text-success">
                                            <?= number_format(array_sum(array_column($tourFinancials, 'revenue')), 0, ',', '.') ?> ₫
                                        </strong>
                                    </td>
                                    <td class="text-end">
                                        <strong class="text-danger">
                                            <?= number_format(array_sum(array_column($tourFinancials, 'expense')), 0, ',', '.') ?> ₫
                                        </strong>
                                    </td>
                                    <td class="text-end">
                                        <strong class="text-primary">
                                            <?= number_format(array_sum(array_column($tourFinancials, 'profit')), 0, ',', '.') ?> ₫
                                        </strong>
                                    </td>
                                    <td class="text-end">
                                        <?php 
                                        $totalRevenue = array_sum(array_column($tourFinancials, 'revenue'));
                                        $totalProfit = array_sum(array_column($tourFinancials, 'profit'));
                                        $avgMargin = $totalRevenue > 0 ? ($totalProfit / $totalRevenue) * 100 : 0;
                                        ?>
                                        <strong><?= number_format($avgMargin, 1) ?>%</strong>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h3 class="empty-title">Chưa có dữ liệu tài chính</h3>
                        <p class="empty-description">
                            Thử điều chỉnh bộ lọc hoặc chọn khoảng thời gian khác.
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </div>
</main>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Monthly Financial Chart
    <?php if (isset($data['monthlyRevenue'])): ?>
        const monthlyCtx = document.getElementById('monthlyFinancialChart');
        if (monthlyCtx) {
            new Chart(monthlyCtx, {
                type: 'bar',
                data: {
                    labels: <?= json_encode($data['monthlyLabels'] ?? []) ?>,
                    datasets: [{
                        label: 'Doanh Thu',
                        data: <?= json_encode($data['monthlyRevenue'] ?? []) ?>,
                        backgroundColor: '#10b981',
                        borderRadius: 8
                    }, {
                        label: 'Chi Phí',
                        data: <?= json_encode($data['monthlyExpense'] ?? []) ?>,
                        backgroundColor: '#ef4444',
                        borderRadius: 8
                    }, {
                        label: 'Lợi Nhuận',
                        data: <?= json_encode($data['monthlyProfit'] ?? []) ?>,
                        backgroundColor: '#667eea',
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top' },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + 
                                        new Intl.NumberFormat('vi-VN').format(context.parsed.y) + ' ₫';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return new Intl.NumberFormat('vi-VN', { 
                                        notation: 'compact',
                                        compactDisplay: 'short'
                                    }).format(value);
                                }
                            }
                        }
                    }
                }
            });
        }
    <?php endif; ?>

    // Tour Profit Pie Chart
    <?php if (isset($data['tourNames']) && isset($data['tourProfits'])): ?>
        const profitCtx = document.getElementById('tourProfitChart');
        if (profitCtx) {
            new Chart(profitCtx, {
                type: 'pie',
                data: {
                    labels: <?= json_encode($data['tourNames'] ?? []) ?>,
                    datasets: [{
                        data: <?= json_encode($data['tourProfits'] ?? []) ?>,
                        backgroundColor: [
                            '#667eea',
                            '#10b981',
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
                        legend: { position: 'bottom' },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = new Intl.NumberFormat('vi-VN').format(context.parsed) + ' ₫';
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((context.parsed / total) * 100).toFixed(1) + '%';
                                    return label + ': ' + value + ' (' + percentage + ')';
                                }
                            }
                        }
                    }
                }
            });
        }
    <?php endif; ?>
});

function resetFilters() {
    window.location.href = '<?= BASE_URL_ADMIN ?>&action=reports/financial';
}
</script>

<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin/reports.css">

<?php
include_once PATH_VIEW_ADMIN . 'default/footer.php';
?>
