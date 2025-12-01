<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';
include_once PATH_VIEW_ADMIN . 'components/advanced_filters.php';
?>

<main class="wrapper">
    <div class="main-content">
        <div class="page-header">
            <h1 class="h2">Báo cáo Tỷ lệ Chuyển đổi</h1>
            <p class="text-muted">Phân tích hiệu quả chuyển đổi từ inquiry đến booking hoàn thành</p>
        </div>

        <!-- Advanced Filters -->
        <?php
        renderAdvancedFilters($filters ?? [], $filterOptions ?? [], 'conversion');
        ?>

        <!-- KPI Cards -->
        <div class="row mb-4">
            <div class="col-md-6 col-xl-3 mb-3">
                <div class="card kpi-card">
                    <div class="card-body">
                        <div class="kpi-icon" style="background-color: #e0f2fe;">
                            <i class="fas fa-percentage" style="color: #0ea5e9;"></i>
                        </div>
                        <div>
                            <div class="kpi-label">Tỷ lệ Chuyển đổi</div>
                            <div class="kpi-value"><?= number_format($conversionData['conversion_rates']['booking_to_payment'] ?? 0, 1) ?>%</div>
                            <small class="<?= ($growthData['growth']['booking_to_payment'] ?? 0) >= 0 ? 'text-success' : 'text-danger' ?>">
                                <i class="fas fa-arrow-<?= ($growthData['growth']['booking_to_payment'] ?? 0) >= 0 ? 'up' : 'down' ?>"></i>
                                <?= number_format($growthData['growth']['booking_to_payment'] ?? 0, 1) ?>% so với kỳ trước
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3 mb-3">
                <div class="card kpi-card">
                    <div class="card-body">
                        <div class="kpi-icon" style="background-color: #dcfce7;">
                            <i class="fas fa-phone" style="color: #22c55e;"></i>
                        </div>
                        <div>
                            <div class="kpi-label">Tổng Inquiries</div>
                            <div class="kpi-value"><?= number_format($conversionData['total_inquiries'] ?? 0) ?></div>
                            <small class="text-muted">
                                <?= number_format($conversionData['conversion_rates']['inquiry_to_booking'] ?? 0, 1) ?>% chuyển thành booking
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3 mb-3">
                <div class="card kpi-card">
                    <div class="card-body">
                        <div class="kpi-icon" style="background-color: #fef3c7;">
                            <i class="fas fa-calendar-check" style="color: #f59e0b;"></i>
                        </div>
                        <div>
                            <div class="kpi-label">Tổng Bookings</div>
                            <div class="kpi-value"><?= number_format($conversionData['total_bookings'] ?? 0) ?></div>
                            <small class="text-muted">
                                <?= number_format($conversionData['avg_booking_value'] ?? 0, 0, ',', '.') ?> VNĐ/trung bình
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3 mb-3">
                <div class="card kpi-card">
                    <div class="card-body">
                        <div class="kpi-icon" style="background-color: #fee2e2;">
                            <i class="fas fa-clock" style="color: #ef4444;"></i>
                        </div>
                        <div>
                            <div class="kpi-label">Thời gian Chuyển đổi</div>
                            <div class="kpi-value"><?= number_format($timeAnalysis['avg_hours'] ?? 0, 1) ?> giờ</div>
                            <small class="text-muted">
                                <?= number_format($timeAnalysis['avg_days'] ?? 0, 1) ?> ngày trung bình
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Funnel Analysis -->
        <div class="row mb-4">
            <div class="col-lg-8 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Phân tích Funnel Chuyển đổi</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="funnelChart" height="80"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Tỷ lệ Chuyển đổi theo Giai đoạn</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Giai đoạn</th>
                                        <th class="text-right">Tỷ lệ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($conversionData['conversion_rates'])): ?>
                                        <?php foreach ($conversionData['conversion_rates'] as $key => $rate): ?>
                                            <tr>
                                                <td><?= $this->formatConversionLabel($key) ?></td>
                                                <td class="text-right">
                                                    <span class="badge bg-primary"><?= number_format($rate, 1) ?>%</span>
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
        </div>

        <!-- Conversion by Source & Category -->
        <div class="row mb-4">
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Tỷ lệ Chuyển đổi theo Nguồn</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="sourceConversionChart" height="120"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Tỷ lệ Chuyển đổi theo Danh mục</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="categoryConversionChart" height="120"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Tours by Conversion Rate -->
        <div class="row mb-4">
            <div class="col-lg-8 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Top Tours theo Tỷ lệ Chuyển đổi</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Tour</th>
                                        <th class="text-right">Số Booking</th>
                                        <th class="text-right">Thành công</th>
                                        <th class="text-right">Tỷ lệ</th>
                                        <th class="text-right">Giá trị</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($topTours)): ?>
                                        <?php foreach ($topTours as $tour): ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm bg-light rounded-circle me-2">
                                                            <i class="fas fa-map-marked-alt text-primary"></i>
                                                        </div>
                                                        <div>
                                                            <div class="fw-medium"><?= htmlspecialchars($tour['tour_name']) ?></div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-right">
                                                    <span class="badge bg-info"><?= $tour['total_bookings'] ?></span>
                                                </td>
                                                <td class="text-right">
                                                    <span class="badge bg-success"><?= $tour['successful_bookings'] ?></span>
                                                </td>
                                                <td class="text-right">
                                                    <div class="progress" style="height: 20px; min-width: 100px;">
                                                        <div class="progress-bar <?= ($tour['conversion_rate'] >= 50) ? 'bg-success' : (($tour['conversion_rate'] >= 25) ? 'bg-warning' : 'bg-danger') ?>"
                                                            style="width: <?= min($tour['conversion_rate'], 100) ?>%">
                                                            <?= number_format($tour['conversion_rate'], 1) ?>%
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-right">
                                                    <span class="text-success fw-medium">
                                                        <?= number_format($tour['total_value'], 0, ',', '.') ?>
                                                    </span>
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

            <div class="col-lg-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Phân phối Thời gian Chuyển đổi</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="timeDistributionChart" height="150"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Conversion Trend -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Xu hướng Tỷ lệ Chuyển đổi theo Tháng</h5>
            </div>
            <div class="card-body">
                <canvas id="monthlyConversionChart" height="100"></canvas>
            </div>
        </div>
    </div>
</main>

<?php
// Helper function to format conversion labels
function formatConversionLabel($key)
{
    $labels = [
        'inquiry_to_booking' => 'Inquiry → Booking',
        'booking_to_confirmation' => 'Booking → Confirm',
        'booking_to_deposit' => 'Booking → Deposit',
        'booking_to_payment' => 'Booking → Payment',
        'booking_to_completion' => 'Booking → Complete',
        'confirmation_to_payment' => 'Confirm → Payment',
        'deposit_to_payment' => 'Deposit → Payment'
    ];
    return $labels[$key] ?? $key;
}
?>

<script>
    // Funnel Chart
    const funnelCtx = document.getElementById('funnelChart').getContext('2d');
    const funnelData = <?php echo json_encode($funnelAnalysis ?? []); ?>;

    new Chart(funnelCtx, {
        type: 'bar',
        data: {
            labels: funnelData.map(item => item.stage),
            datasets: [{
                label: 'Số lượng',
                data: funnelData.map(item => item.count),
                backgroundColor: [
                    '#3b82f6',
                    '#10b981',
                    '#f59e0b',
                    '#ef4444',
                    '#8b5cf6',
                    '#06b6d4'
                ],
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        afterLabel: function(context) {
                            const item = funnelData[context.dataIndex];
                            return `Tỷ lệ: ${item.conversion_rate.toFixed(1)}%\nDropoff: ${item.dropoff_rate.toFixed(1)}%`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Số lượng'
                    }
                }
            }
        }
    });

    // Source Conversion Chart
    const sourceCtx = document.getElementById('sourceConversionChart').getContext('2d');
    const sourceData = <?php echo json_encode($sourceConversion ?? []); ?>;

    new Chart(sourceCtx, {
        type: 'doughnut',
        data: {
            labels: sourceData.map(item => item.source),
            datasets: [{
                data: sourceData.map(item => item.conversion_rate),
                backgroundColor: [
                    '#3b82f6',
                    '#10b981',
                    '#f59e0b',
                    '#ef4444',
                    '#8b5cf6',
                    '#06b6d4'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `${context.label}: ${context.parsed.toFixed(1)}%`;
                        }
                    }
                }
            }
        }
    });

    // Category Conversion Chart
    const categoryCtx = document.getElementById('categoryConversionChart').getContext('2d');
    const categoryData = <?php echo json_encode($categoryConversion ?? []); ?>;

    new Chart(categoryCtx, {
        type: 'bar',
        data: {
            labels: categoryData.map(item => item.category_name),
            datasets: [{
                label: 'Tỷ lệ chuyển đổi (%)',
                data: categoryData.map(item => item.conversion_rate),
                backgroundColor: '#3b82f6',
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Tỷ lệ chuyển đổi (%)'
                    }
                }
            }
        }
    });

    // Time Distribution Chart
    const timeDistCtx = document.getElementById('timeDistributionChart').getContext('2d');
    const timeData = <?php echo json_encode($timeAnalysis['distribution'] ?? []); ?>;

    new Chart(timeDistCtx, {
        type: 'pie',
        data: {
            labels: Object.keys(timeData),
            datasets: [{
                data: Object.values(timeData),
                backgroundColor: [
                    '#10b981',
                    '#3b82f6',
                    '#f59e0b',
                    '#ef4444',
                    '#8b5cf6',
                    '#06b6d4'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Monthly Conversion Trend
    const monthlyCtx = document.getElementById('monthlyConversionChart').getContext('2d');
    const monthlyData = <?php echo json_encode($monthlyConversion ?? []); ?>;

    new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: monthlyData.map(item => item.month_label),
            datasets: [{
                label: 'Tỷ lệ chuyển đổi (%)',
                data: monthlyData.map(item => item.conversion_rate),
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Tỷ lệ chuyển đổi (%)'
                    }
                }
            }
        }
    });
</script>

<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>