<?php require_once 'views/admin/default/header.php'; ?>

<div class="container-fluid mt-4">
    <div class="row mb-3">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card text-white bg-primary h-100">
                <div class="card-body">
                    <h5 class="card-title">Tổng số tour</h5>
                    <p class="card-text display-6"><?php echo number_format($totalTours); ?></p>
                </div>
                <div class="card-footer text-end">
                    <a href="?action=tours" class="text-white">Xem chi tiết <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card text-white bg-success h-100">
                <div class="card-body">
                    <h5 class="card-title">Doanh thu</h5>
                    <p class="card-text display-6"><?php echo number_format($totalRevenue); ?> VNĐ</p>
                </div>
                <div class="card-footer text-end">
                    <a href="?action=reports/financial" class="text-white">Báo cáo <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card text-white bg-warning h-100">
                <div class="card-body">
                    <h5 class="card-title">Đơn đặt</h5>
                    <p class="card-text display-6"><?php echo number_format($totalBookings); ?></p>
                </div>
                <div class="card-footer text-end">
                    <a href="?action=bookings" class="text-white">Quản lý đặt chỗ <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card text-white bg-info h-100">
                <div class="card-body">
                    <h5 class="card-title">Hướng dẫn viên</h5>
                    <p class="card-text display-6"><?php echo number_format($totalGuides); ?></p>
                </div>
                <div class="card-footer text-end">
                    <a href="?action=guides" class="text-white">Xem HDV <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-header">Doanh thu theo thời gian</div>
                <div class="card-body">
                    <canvas id="revenueChart" height="120"></canvas>
                </div>
            </div>

            <div class="card">
                <div class="card-header">Đơn đặt gần đây</div>
                <div class="card-body table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tour</th>
                                <th>Khách</th>
                                <th>Ngày đặt</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recentBookings)): ?>
                                <?php foreach ($recentBookings as $b): ?>
                                    <tr>
                                        <td><?php echo $b['id']; ?></td>
                                        <td><?php
                                            // Try to get tour name
                                            $tourModel = new Tour();
                                            $t = $tourModel->find('*', 'id = :id', ['id' => $b['tour_id']]);
                                            echo $t ? $t['name'] : '-';
                                        ?></td>
                                        <td><?php echo $b['customer_id']; ?></td>
                                        <td><?php echo $b['booking_date']; ?></td>
                                        <td><?php echo number_format($b['total_price']); ?> VNĐ</td>
                                        <td><?php echo $b['status']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="6" class="text-center">Chưa có đặt chỗ</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-header">Tác vụ nhanh</div>
                <div class="card-body">
                    <a href="?action=tours/create" class="btn btn-primary w-100 mb-2">Thêm tour mới</a>
                    <a href="?action=bookings/create" class="btn btn-success w-100 mb-2">Tạo booking</a>
                    <a href="?action=guides/create" class="btn btn-info w-100 mb-2">Thêm HDV</a>
                </div>
            </div>

            <div class="card">
                <div class="card-header">Thông báo</div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li>• 3 booking mới chờ xác nhận</li>
                        <li>• 1 tour sắp khởi hành</li>
                        <li>• Hợp đồng nhà cung cấp sắp hết hạn</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Simple static demo data for revenue chart — you can replace with AJAX to fetch real data
const ctx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Tháng 1','Tháng 2','Tháng 3','Tháng 4','Tháng 5','Tháng 6'],
        datasets: [{
            label: 'Doanh thu',
            data: [12000000, 15000000, 10000000, 18000000, 22000000, <?php echo (int)$totalRevenue; ?>],
            borderColor: 'rgba(54, 162, 235, 1)',
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            tension: 0.3,
            fill: true
        }]
    },
    options: {
        maintainAspectRatio: false,
        scales: {
            y: {
                ticks: {
                    callback: function(value) { return value.toLocaleString() + ' VNĐ'; }
                }
            }
        }
    }
});
</script>

<?php require_once 'views/admin/default/footer.php'; ?>
