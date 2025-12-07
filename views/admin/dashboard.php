<?php extract($data); ?>

<!-- Main Content -->
 <main class="dashboard">
<div class="dashboard-container">
    <div>
        <div class="d-flex justify-content-between align-items-center mb-4 dashboard-header">
            <div>
                <h4 class="mb-1 text-primary fw-bold">Dashboard</h4>
                <p class="mb-0 text-muted">Tổng quan tình hình kinh doanh hôm nay <?= date('d/m/Y', strtotime($today)) ?></p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary" onclick="window.location.reload()">
                    <i class="fas fa-sync-alt me-1"></i> Làm mới
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row g-3 mb-4">
            <!-- Revenue Card -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100 card-stat">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="stat-icon bg-success-subtle text-success rounded-3 p-3">
                                <i class="fas fa-money-bill-wave fa-lg"></i>
                            </div>
                            <?php 
                                $revDiff = $monthlyRevenue - $lastMonthRevenue;
                                $revPercent = $lastMonthRevenue > 0 ? ($revDiff / $lastMonthRevenue) * 100 : ($monthlyRevenue > 0 ? 100 : 0);
                                $isPositive = $revDiff >= 0;
                            ?>
                            <div class="badge <?= $isPositive ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' ?> rounded-pill">
                                <i class="fas fa-arrow-<?= $isPositive ? 'up' : 'down' ?> me-1"></i><?= number_format(abs($revPercent), 1) ?>%
                            </div>
                        </div>
                        <h6 class="text-muted mb-1">Doanh thu tháng này</h6>
                        <h3 class="fw-bold mb-0"><?= number_format($monthlyRevenue) ?> ₫</h3>
                        <small class="text-muted">So với <?= number_format($lastMonthRevenue) ?> ₫ tháng trước</small>
                    </div>
                </div>
            </div>

            <!-- Bookings Card -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100 card-stat">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="stat-icon bg-primary-subtle text-primary rounded-3 p-3">
                                <i class="fas fa-calendar-check fa-lg"></i>
                            </div>
                            <span class="badge bg-primary-subtle text-primary rounded-pill">Mới nhất</span>
                        </div>
                        <h6 class="text-muted mb-1">Booking mới</h6>
                        <h3 class="fw-bold mb-0"><?= number_format($newBookings) ?></h3>
                        <small class="text-muted">Trong tháng <?= $currentMonthName ?></small>
                    </div>
                </div>
            </div>

            <!-- Customers Card -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100 card-stat">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="stat-icon bg-info-subtle text-info rounded-3 p-3">
                                <i class="fas fa-users fa-lg"></i>
                            </div>
                        </div>
                        <h6 class="text-muted mb-1">Khách hàng mới</h6>
                        <h3 class="fw-bold mb-0"><?= number_format($newCustomers) ?></h3>
                        <small class="text-muted">Đăng ký trong tháng này</small>
                    </div>
                </div>
            </div>

            <!-- Tours Card -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100 card-stat">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="stat-icon bg-warning-subtle text-warning rounded-3 p-3">
                                <i class="fas fa-plane-departure fa-lg"></i>
                            </div>
                        </div>
                        <h6 class="text-muted mb-1">Tour đang chạy</h6>
                        <h3 class="fw-bold mb-0"><?= number_format($ongoingTours) ?></h3>
                        <small class="text-muted">Đang khởi hành hôm nay</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row g-4 mb-4">
            <!-- Revenue Chart -->
            <div class="col-12 col-lg-8">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="card-title mb-0 fw-bold">Biểu đồ doanh thu 12 tháng</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="revenueChart" height="300"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Booking Status Chart -->
            <div class="col-12 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="card-title mb-0 fw-bold">Trạng thái Booking</h5>
                    </div>
                    <div class="card-body">
                        <div style="height: 250px; position: relative;">
                            <canvas id="statusChart"></canvas>
                        </div>
                        <div class="mt-3">
                            <?php foreach ($bookingStatusData['stats'] as $status): ?>
                                <div class="d-flex justify-content-between align-items-center mb-2 small">
                                    <span class="text-muted"><?= $status['status'] ?></span>
                                    <span class="fw-bold"><?= $status['count'] ?> (<?= $status['percentage'] ?>%)</span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tables Row -->
        <div class="row g-4">
            <!-- Recent Bookings -->
            <div class="col-12 col-lg-8">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 fw-bold">Booking chờ xử lý gần đây</h5>
                        <a href="<?= BASE_URL_ADMIN ?>&action=bookings" class="btn btn-sm btn-light">Xem tất cả</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 rounded-start-2">ID</th>
                                    <th class="border-0">Khách hàng</th>
                                    <th class="border-0">Tour</th>
                                    <th class="border-0">Ngày đặt</th>
                                    <th class="border-0 rounded-end-2">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($pendingBookings)): ?>
                                    <?php foreach ($pendingBookings as $booking): ?>
                                    <tr>
                                        <td><span class="badge bg-light text-dark border">#<?= $booking['id'] ?></span></td>
                                        <td>
                                            <div class="fw-bold"><?= htmlspecialchars($booking['customer_name'] ?? 'Khách lẻ') ?></div>
                                        </td>
                                        <td>
                                            <div class="text-truncate" style="max-width: 250px;" title="<?= htmlspecialchars($booking['tour_name']) ?>">
                                                <?= htmlspecialchars($booking['tour_name']) ?>
                                            </div>
                                        </td>
                                        <td><?= date('d/m/Y H:i', strtotime($booking['booking_date'])) ?></td>
                                        <td>
                                            <a href="<?= BASE_URL_ADMIN ?>&action=bookings/edit&id=<?= $booking['id'] ?>" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i> Xử lý
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">Không có booking nào đang chờ xử lý</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Upcoming Tours -->
            <div class="col-12 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="card-title mb-0 fw-bold">Tour sắp khởi hành</h5>
                    </div>
                    <div class="list-group list-group-flush">
                        <?php if (!empty($upcomingTours)): ?>
                            <?php foreach ($upcomingTours as $tour): ?>
                            <div class="list-group-item border-0 px-0 d-flex align-items-center gap-3">
                                <div class="bg-light rounded p-2 text-center" style="min-width: 60px;">
                                    <div class="fw-bold text-primary"><?= date('d', strtotime($tour['departure_date'])) ?></div>
                                    <div class="small text-muted text-uppercase"><?= date('M', strtotime($tour['departure_date'])) ?></div>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <h6 class="mb-1 text-truncate"><?= htmlspecialchars($tour['name']) ?></h6>
                                    <div class="small text-muted">
                                        <i class="fas fa-users me-1"></i> <?= $tour['booked_seats'] ?>/<?= $tour['max_seats'] ?> khách
                                    </div>
                                    <div class="progress mt-1" style="height: 4px;">
                                        <?php 
                                            $percent = ($tour['booked_seats'] / $tour['max_seats']) * 100;
                                            $colorClass = $percent >= 90 ? 'bg-danger' : ($percent >= 50 ? 'bg-success' : 'bg-warning');
                                        ?>
                                        <div class="progress-bar <?= $colorClass ?>" role="progressbar" style="width: <?= $percent ?>%"></div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center py-4 text-muted">Không có tour sắp khởi hành</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</main>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Data
    const revenueData = <?= json_encode($revenueData) ?>;
    const revLabels = revenueData.map(d => d.month);
    const revValues = revenueData.map(d => d.revenue);

    // Render Revenue Chart
    const ctxRev = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctxRev, {
        type: 'line',
        data: {
            labels: revLabels,
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: revValues,
                borderColor: '#0d6efd', // Bootstrap primary
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                borderWidth: 2,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#0d6efd',
                pointRadius: 4,
                pointHoverRadius: 6
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
                        label: function(context) {
                            return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(context.raw);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        borderDash: [2, 4],
                        color: '#f0f0f0'
                    },
                    ticks: {
                        callback: function(value) {
                            if (value >= 1000000) return (value/1000000).toFixed(0) + 'tr';
                            if (value >= 1000) return (value/1000).toFixed(0) + 'k';
                            return value;
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Booking Status Data
    const statusStats = <?= json_encode($bookingStatusData['stats']) ?>;
    const statusLabels = statusStats.map(d => d.status);
    const statusValues = statusStats.map(d => d.count);
    const statusColors = ['#ffc107', '#0dcaf0', '#0d6efd', '#198754', '#20c997', '#dc3545']; // Matches status order if standard

    // Render Status Pie Chart
    const ctxStatus = document.getElementById('statusChart').getContext('2d');
    new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: statusLabels,
            datasets: [{
                data: statusValues,
                backgroundColor: [
                    '#ffc107', // Warning/Pending
                    '#198754', // Success/Confirmed
                    '#0dcaf0', // Info/Deposited
                    '#0d6efd', // Primary/Paid
                    '#20c997', // Teal/Completed
                    '#dc3545'  // Danger/Cancelled
                ],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false // Custom legend built in HTML
                }
            },
            cutout: '70%'
        }
    });
});
</script>
