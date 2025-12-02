<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';

// Format currency
function formatCurrency($amount)
{
    return number_format($amount, 0, ',', '.') . ' ₫';
}

// Calculate percentage change
function getPercentageChange($current, $previous)
{
    if ($previous == 0) return 0;
    return round((($current - $previous) / $previous) * 100, 1);
}

$revenueChange = getPercentageChange($monthlyRevenue, $lastMonthRevenue);
?>

<main class="wrapper">
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">Tổng quan</h1>
                <p class="text-muted mb-0">Chào mừng trở lại, <?= $_SESSION['user']['full_name'] ?? 'Quản trị viên' ?>!</p>
            </div>
            <div class="text-end">
                <span class="badge bg-light text-dark">Hôm nay: <?= date('d/m/Y') ?></span>
            </div>
        </div>

        <!-- KPI Cards -->
        <div class="row g-4 mb-4">
            <!-- Doanh thu tháng -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="icon-shape icon-lg bg-soft-primary text-primary rounded-3">
                                <i class="fas fa-wallet"></i>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-soft-<?= $revenueChange >= 0 ? 'success' : 'danger' ?> text-<?= $revenueChange >= 0 ? 'success' : 'danger' ?> small">
                                    <?= $revenueChange >= 0 ? '↑' : '↓' ?>
                                    <?= abs($revenueChange) ?>%
                                </span>
                                <div class="text-muted small">So với <?= $lastMonthName ?></div>
                            </div>
                        </div>
                        <h3 class="mb-1"><?= formatCurrency($monthlyRevenue) ?></h3>
                        <p class="text-muted mb-0">Doanh thu <?= $currentMonthName ?></p>
                    </div>
                </div>
            </div>

            <!-- Booking mới -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="icon-shape icon-lg bg-soft-warning text-warning rounded-3">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-soft-success text-success small">+<?= $newBookings ?> mới</span>
                                <div class="text-muted small">Trong tháng</div>
                            </div>
                        </div>
                        <h3 class="mb-1"><?= $newBookings ?></h3>
                        <p class="text-muted mb-0">Đơn đặt tour mới</p>
                    </div>
                </div>
            </div>

            <!-- Tour đang chạy -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="icon-shape icon-lg bg-soft-success text-success rounded-3">
                                <i class="fas fa-map-marked-alt"></i>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-soft-info text-info small"><?= isset($availableGuides) && is_array($availableGuides) ? count($availableGuides) : 0 ?> HDV sẵn sàng</span>
                                <div class="text-muted small">Tổng số tour</div>
                            </div>
                        </div>
                        <h3 class="mb-1"><?= $ongoingTours ?></h3>
                        <p class="text-muted mb-0">Tour đang hoạt động</p>
                    </div>
                </div>
            </div>

            <!-- Khách hàng mới -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="icon-shape icon-lg bg-soft-info text-info rounded-3">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-soft-primary text-primary small"><?= $totalGuides ?? 0 ?> HDV</span>
                                <div class="text-muted small">Tổng số khách mới</div>
                            </div>
                        </div>
                        <h3 class="mb-1"><?= $newCustomers ?></h3>
                        <p class="text-muted mb-0">Khách hàng mới</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row g-4 mb-4">
            <!-- Revenue Chart -->
            <div class="col-12 col-xl-8">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 pb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Tổng quan doanh thu</h5>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="revenueFilter" data-bs-toggle="dropdown" aria-expanded="false">
                                    12 tháng gần nhất
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="revenueFilter">
                                    <li><a class="dropdown-item" href="#">7 ngày gần nhất</a></li>
                                    <li><a class="dropdown-item active" href="#">12 tháng gần nhất</a></li>
                                    <li><a class="dropdown-item" href="#">Năm nay</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-3">
                        <div class="chart-container" style="height: 300px;">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Status -->
            <div class="col-12 col-xl-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0">
                        <h5 class="card-title mb-0">Trạng thái đơn hàng</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="height: 250px;">
                            <canvas id="bookingStatusChart"></canvas>
                        </div>
                        <div class="mt-4">
                            <?php if (!empty($bookingStatusData['stats'])):
                                $stats = $bookingStatusData['stats'];
                                $totalBookings = $bookingStatusData['total_bookings'];
                            ?>
                                <?php foreach ($stats as $status): ?>
                                    <div class="mb-2">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="text-muted"><?= $status['status'] ?></span>
                                            <span class="fw-medium"><?= $status['percentage'] ?>% (<?= $status['count'] ?>)</span>
                                        </div>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-<?= getStatusColor($status['status']) ?>"
                                                role="progressbar"
                                                style="width: <?= $status['percentage'] ?>%"
                                                aria-valuenow="<?= $status['percentage'] ?>"
                                                aria-valuemin="0"
                                                aria-valuemax="100">
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                                <div class="text-center mt-3">
                                    <span class="badge bg-primary">Tổng đơn: <?= $totalBookings ?></span>
                                    <span class="badge bg-success ms-2">Doanh thu: <?= number_format($bookingStatusData['total_revenue']) ?>₫</span>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-3 text-muted">
                                    <i class="fas fa-info-circle me-2"></i> Chưa có dữ liệu thống kê
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Row -->
        <div class="row g-4">
            <!-- Pending Bookings -->
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Đơn đặt chờ xác nhận</h5>
                        <a href="<?= BASE_URL_ADMIN ?>?act=bookings&status=pending" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
                    </div>
                    <div class="card-body p-0">
                        <?php if (!empty($pendingBookings)): ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($pendingBookings as $booking): ?>
                                    <a href="<?= BASE_URL_ADMIN ?>?act=bookings&id=<?= $booking['id'] ?>" class="list-group-item list-group-item-action border-0 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <div class="avatar-sm">
                                                    <span class="avatar-title rounded-circle bg-soft-<?= getStatusColor($booking['status']) ?> text-<?= getStatusColor($booking['status']) ?> fw-bold">
                                                        <?= substr($booking['customer_name'], 0, 1) ?>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h6 class="mb-0">#<?= $booking['id'] ?> - <?= $booking['customer_name'] ?></h6>
                                                    <span class="badge bg-soft-<?= getStatusColor($booking['status']) ?> text-<?= getStatusColor($booking['status']) ?> small">
                                                        <?= getStatusText($booking['status']) ?>
                                                    </span>
                                                </div>
                                                <p class="text-muted mb-0 small">
                                                    <?= $booking['tour_name'] ?>
                                                    <br>
                                                    <i class="far fa-calendar-alt me-1"></i> <?= date('d/m/Y H:i', strtotime($booking['booking_date'])) ?>
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <div class="mb-3">
                                    <i class="fas fa-calendar-check fa-3x text-muted"></i>
                                </div>
                                <h6 class="text-muted mb-0">Không có đơn đặt nào đang chờ xử lý</h6>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Upcoming Tours -->
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Tour sắp khởi hành</h5>
                        <a href="<?= BASE_URL_ADMIN ?>?act=tours" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
                    </div>
                    <div class="card-body p-0">
                        <?php if (!empty($upcomingTours)): ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($upcomingTours as $tour): ?>
                                    <div class="list-group-item border-0 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <img src="<?= !empty($tour['image_url']) ? $tour['image_url'] : 'assets/admin/img/default-tour.jpg' ?>"
                                                    alt="<?= $tour['name'] ?>"
                                                    class="rounded"
                                                    style="width: 80px; height: 60px; object-fit: cover;">
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h6 class="mb-0"><?= $tour['name'] ?></h6>
                                                    <span class="badge bg-soft-primary">
                                                        <?= $tour['available_seats'] > 0 ? $tour['available_seats'] . ' chỗ trống' : 'Hết chỗ' ?>
                                                    </span>
                                                </div>
                                                <div class="d-flex justify-content-between text-muted small mt-1">
                                                    <span><i class="far fa-calendar-alt me-1"></i> <?= date('d/m/Y', strtotime($tour['departure_date'])) ?></span>
                                                    <span class="text-primary fw-bold"><?= formatCurrency($tour['price']) ?></span>
                                                </div>
                                                <div class="progress mt-2" style="height: 6px;">
                                                    <?php
                                                    $bookingPercentage = $tour['max_seats'] > 0 ? ($tour['booked_seats'] / $tour['max_seats']) * 100 : 0;
                                                    $bookingPercentage = min(100, $bookingPercentage);
                                                    ?>
                                                    <div class="progress-bar bg-<?= $bookingPercentage >= 80 ? 'success' : ($bookingPercentage >= 50 ? 'info' : 'warning') ?>"
                                                        role="progressbar"
                                                        style="width: <?= $bookingPercentage ?>%"
                                                        aria-valuenow="<?= $bookingPercentage ?>"
                                                        aria-valuemin="0"
                                                        aria-valuemax="100">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <div class="mb-3">
                                    <i class="fas fa-map-marked-alt fa-3x text-muted"></i>
                                </div>
                                <h6 class="text-muted mb-0">Không có tour nào sắp khởi hành</h6>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Helper function to format currency
    function formatCurrency(value) {
        return new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
        }).format(value).replace('₫', '₫');
    }

    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: <?= json_encode(array_column($revenueData, 'month')) ?>,
            datasets: [{
                label: 'Doanh thu',
                data: <?= json_encode(array_column($revenueData, 'revenue')) ?>,
                backgroundColor: 'rgba(14, 165, 233, 0.1)',
                borderColor: '#0ea5e9',
                borderWidth: 2,
                tension: 0.3,
                fill: true,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#0ea5e9',
                pointBorderWidth: 2,
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
                            return 'Doanh thu: ' + formatCurrency(context.raw);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        drawBorder: false,
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        callback: function(value) {
                            if (value >= 1000000) {
                                return (value / 1000000).toFixed(1) + 'M';
                            } else if (value >= 1000) {
                                return (value / 1000).toFixed(0) + 'K';
                            }
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

    // Booking Status Chart
    const bookingStatusCtx = document.getElementById('bookingStatusChart').getContext('2d');
    const bookingStatusChart = new Chart(bookingStatusCtx, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode(array_map(function ($item) {
                        return ucfirst($item['status']);
                    }, $bookingStatusData)) ?>,
            datasets: [{
                data: <?= json_encode(array_column($bookingStatusData, 'count')) ?>,
                backgroundColor: [
                    '#10b981', // success
                    '#f59e0b', // warning
                    '#ef4444', // danger
                    '#3b82f6', // info
                    '#8b5cf6' // purple
                ],
                borderWidth: 0,
                cutout: '70%'
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
            cutoutPercentage: 70
        }
    });

    // Update charts on window resize
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            revenueChart.resize();
            bookingStatusChart.resize();
        }, 250);
    });
</script>

<?php
// Helper functions for status display
function getStatusColor($status)
{
    $statusMap = [
        'pending' => 'warning',
        'confirmed' => 'info',
        'deposited' => 'primary',
        'paid' => 'success',
        'cancelled' => 'danger',
        'completed' => 'secondary'
    ];
    return $statusMap[strtolower($status)] ?? 'secondary';
}

function getStatusText($status)
{
    $statusMap = [
        'pending' => 'Chờ xác nhận',
        'confirmed' => 'Đã xác nhận',
        'deposited' => 'Đã đặt cọc',
        'paid' => 'Đã thanh toán',
        'cancelled' => 'Đã hủy',
        'completed' => 'Hoàn thành'
    ];
    return $statusMap[strtolower($status)] ?? $status;
}
?>

<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>