<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';

$conversionData = $data['conversionData'] ?? [];
$topTours = $data['topTours'] ?? [];
$sourceConversion = $data['sourceConversion'] ?? [];
$filters = $data['filters'] ?? [];
?>

<main class="dashboard">
    <div class="dashboard-container">
        <header class="dashboard-header">
            <div class="header-content">
                <div class="header-left">
                    <div class="breadcrumb-modern">
                        <a href="<?= BASE_URL_ADMIN ?>&action=/" class="breadcrumb-link">
                            <i class="fas fa-home"></i><span>Dashboard</span>
                        </a>
                        <span class="breadcrumb-separator"><i class="fas fa-chevron-right"></i></span>
                        <a href="<?= BASE_URL_ADMIN ?>&action=reports" class="breadcrumb-link">
                            <span>Báo Cáo</span>
                        </a>
                        <span class="breadcrumb-separator"><i class="fas fa-chevron-right"></i></span>
                        <span class="breadcrumb-current">Chuyển Đổi</span>
                    </div>
                    <div class="page-title-section">
                        <h1 class="page-title">
                            <i class="fas fa-funnel-dollar title-icon"></i>
                            Báo Cáo Tỷ Lệ Chuyển Đổi
                        </h1>
                        <p class="page-subtitle">Phân tích funnel và conversion rate</p>
                    </div>
                </div>
            </div>
        </header>

        <!-- Conversion Rate Cards -->
        <section class="kpi-section">
            <div class="kpi-grid">
                <div class="kpi-card kpi-info">
                    <div class="kpi-icon"><i class="fas fa-eye"></i></div>
                    <div class="kpi-content">
                        <div class="kpi-label">Tổng Inquiry</div>
                        <div class="kpi-value"><?= number_format($conversionData['total_inquiries'] ?? 0) ?></div>
                    </div>
                </div>
                <div class="kpi-card kpi-warning">
                    <div class="kpi-icon"><i class="fas fa-calendar-check"></i></div>
                    <div class="kpi-content">
                        <div class="kpi-label">Tổng Booking</div>
                        <div class="kpi-value"><?= number_format($conversionData['total_bookings'] ?? 0) ?></div>
                        <div class="kpi-trend">
                            <span><?= number_format($conversionData['conversion_rates']['inquiry_to_booking'] ?? 0, 1) ?>%</span>
                            <small>conversion từ inquiry</small>
                        </div>
                    </div>
                </div>
                <div class="kpi-card kpi-success">
                    <div class="kpi-icon"><i class="fas fa-dollar-sign"></i></div>
                    <div class="kpi-content">
                        <div class="kpi-label">Đã Thanh Toán</div>
                        <div class="kpi-value"><?= number_format($conversionData['total_payments'] ?? 0) ?></div>
                        <div class="kpi-trend trend-up">
                            <span><?= number_format($conversionData['conversion_rates']['booking_to_payment'] ?? 0, 1) ?>%</span>
                            <small>conversion từ booking</small>
                        </div>
                    </div>
                </div>
                <div class="kpi-card kpi-primary">
                    <div class="kpi-icon"><i class="fas fa-percentage"></i></div>
                    <div class="kpi-content">
                        <div class="kpi-label">Tỷ Lệ Tổng Thể</div>
                        <div class="kpi-value"><?= number_format($conversionData['conversion_rates']['overall'] ?? 0, 1) ?>%</div>
                        <div class="kpi-trend">
                            <span>Inquiry → Payment</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Funnel Chart -->
        <section class="charts-section">
            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title"><i class="fas fa-filter"></i>Funnel Chuyển Đổi</h3>
                </div>
                <div class="chart-body">
                    <canvas id="funnelChart"></canvas>
                </div>
            </div>
        </section>

        <!-- Top Converting Tours -->
        <section class="tours-section">
            <div class="tours-header">
                <h3 class="tours-title"><i class="fas fa-trophy"></i>Top Tours Theo Tỷ Lệ Chuyển Đổi</h3>
            </div>
            <div class="tours-container">
                <?php if (!empty($topTours)): ?>
                    <div class="table-responsive">
                        <table class="table table-modern">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tên Tour</th>
                                    <th>Inquiry</th>
                                    <th>Booking</th>
                                    <th>Payment</th>
                                    <th>Tỷ Lệ Chuyển Đổi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice($topTours, 0, 10) as $index => $tour): ?>
                                    <tr>
                                        <td><strong><?= $index + 1 ?></strong></td>
                                        <td><?= htmlspecialchars($tour['tour_name']) ?></td>
                                        <td><?= number_format($tour['inquiries'] ?? 0) ?></td>
                                        <td><?= number_format($tour['bookings'] ?? 0) ?></td>
                                        <td><?= number_format($tour['payments'] ?? 0) ?></td>
                                        <td>
                                            <span class="badge <?= ($tour['conversion_rate'] ?? 0) >= 50 ? 'bg-success' : 
                                                (($tour['conversion_rate'] ?? 0) >= 25 ? 'bg-warning' : 'bg-danger') ?>">
                                                <?= number_format($tour['conversion_rate'] ?? 0, 1) ?>%
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state-mini">
                        <i class="fas fa-inbox"></i>
                        <p>Chưa có dữ liệu</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    new Chart(document.getElementById('funnelChart'), {
        type: 'bar',
        data: {
            labels: ['Inquiry', 'Booking', 'Payment', 'Completed'],
            datasets: [{
                label: 'Số lượng',
                data: [
                    <?= $conversionData['total_inquiries'] ?? 0 ?>,
                    <?= $conversionData['total_bookings'] ?? 0 ?>,
                    <?= $conversionData['total_payments'] ?? 0 ?>,
                    <?= $conversionData['total_completed'] ?? 0 ?>
                ],
                backgroundColor: ['#3b82f6', '#f59e0b', '#10b981', '#667eea']
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { x: { beginAtZero: true } }
        }
    });
});
</script>
<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin/reports.css">
<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>
