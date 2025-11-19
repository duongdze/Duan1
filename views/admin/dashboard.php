<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';
?>

<main class="wrapper">
    <div class="main-content">
        <div class="page-header">
            <h1 class="h2">Tổng quan</h1>
            <p class="text-muted">Chào mừng trở lại, Admin!</p>
        </div>

        <!-- KPI Cards -->
        <div class="row mb-4">
            <div class="col-md-6 col-xl-3 mb-3">
                <div class="card kpi-card">
                    <div class="card-body">
                        <div class="kpi-icon" style="background-color: #e0f2fe;">
                            <i class="fas fa-dollar-sign" style="color: #0ea5e9;"></i>
                        </div>
                        <div>
                            <div class="kpi-label">Doanh thu tháng</div>
                            <div class="kpi-value"><?= $monthlyRevenue ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3 mb-3">
                <div class="card kpi-card">
                    <div class="card-body">
                        <div class="kpi-icon" style="background-color: #fefce8;">
                            <i class="fas fa-calendar-check" style="color: #eab308;"></i>
                        </div>
                        <div>
                            <div class="kpi-label">Booking mới</div>
                            <div class="kpi-value"><?= $newBookings ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3 mb-3">
                <div class="card kpi-card">
                    <div class="card-body">
                        <div class="kpi-icon" style="background-color: #dcfce7;">
                            <i class="fas fa-map-signs" style="color: #22c55e;"></i>
                        </div>
                        <div>
                            <div class="kpi-label">Tour đang chạy</div>
                            <div class="kpi-value"><?= $ongoingTours ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3 mb-3">
                <div class="card kpi-card">
                    <div class="card-body">
                        <div class="kpi-icon" style="background-color: #fee2e2;">
                            <i class="fas fa-users" style="color: #ef4444;"></i>
                        </div>
                        <div>
                            <div class="kpi-label">Khách hàng mới</div>
                            <div class="kpi-value"><?= $newCustomers ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="row mb-4">
            <div class="col-lg-7 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        Biểu đồ doanh thu (7 tháng gần nhất)
                    </div>
                    <div class="card-body" style="height: 350px;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        Tỷ lệ loại tour
                    </div>
                    <div class="card-body" style="height: 350px;">
                        <canvas id="tourTypeChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Bookings and Upcoming Tours -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Booking chờ xác nhận
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Mã Booking</th>
                                        <th>Khách hàng</th>
                                        <th>Tên Tour</th>
                                        <th>Ngày đặt</th>
                                        <th>Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>BK-0125</td>
                                        <td>Nguyễn Văn A</td>
                                        <td>Khám phá Đà Nẵng - Hội An</td>
                                        <td>15/11/2025</td>
                                        <td><span class="badge bg-warning text-dark">Chờ xác nhận</span></td>
                                    </tr>
                                    <tr>
                                        <td>BK-0124</td>
                                        <td>Trần Thị B</td>
                                        <td>Du lịch Mộc Châu - Mùa hoa cải</td>
                                        <td>14/11/2025</td>
                                        <td><span class="badge bg-warning text-dark">Chờ xác nhận</span></td>
                                    </tr>
                                    <tr>
                                        <td>BK-0123</td>
                                        <td>Lê Văn C</td>
                                        <td>Chinh phục Fansipan</td>
                                        <td>14/11/2025</td>
                                        <td><span class="badge bg-warning text-dark">Chờ xác nhận</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
include_once PATH_VIEW_ADMIN . 'default/footer.php';
?>
