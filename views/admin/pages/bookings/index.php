<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';
?>
<main class="wrapper">
    <div class="main-content">
        <div class="page-header d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h2">Quản lý Booking</h1>
                <p class="text-muted">Danh sách tất cả các booking trong hệ thống.</p>
            </div>
            <a href="<?= BASE_URL_ADMIN . '&action=bookings/create' ?>"><button class="btn btn-primary"><i class="fas fa-plus"></i> Tạo booking mới</button></a>
        </div>

        <div class="card">
            <div class="card-header">
                Danh sách Booking
            </div>
            <div class="card-body">
                <?php if (!empty($bookings)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Mã Booking</th>
                                    <th>Khách hàng</th>
                                    <th>Tên Tour</th>
                                    <th>Ngày đặt</th>
                                    <th>Tổng tiền</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($bookings as $booking) : ?>
                                    <tr>
                                        <td><?= $booking['id'] ?></td>
                                        <td><?= $booking['customer_name'] ?></td>
                                        <td><?= $booking['tour_name'] ?></td>
                                        <td><?= $booking['booking_date'] ?></td>
                                        <td><?= number_format($booking['total_price'], 0, ',', '.') ?> ₫</td>
                                        <td>
                                            <?php
                                            $statusText = 'Chờ Xác Nhận';
                                            $statusClass = 'warning';
                                            if ($booking['status'] === 'hoan_tat') {
                                                $statusText = 'Hoàn Tất';
                                                $statusClass = 'success';
                                            } elseif ($booking['status'] === 'da_coc') {
                                                $statusText = 'Đã Cọc';
                                                $statusClass = 'info';
                                            } elseif ($booking['status'] === 'da_huy') {
                                                $statusText = 'Đã Hủy';
                                                $statusClass = 'danger';
                                            }
                                            ?>
                                            <span class="badge bg-<?= $statusClass ?>"><?= $statusText ?></span>
                                        </td>
                                        <td>
                                            <a href="<?= BASE_URL_ADMIN . '&action=bookings/detail&id=' . $booking['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
                                            <a href="<?= BASE_URL_ADMIN . '&action=bookings/edit&id=' . $booking['id'] ?>" class="btn btn-sm btn-outline-secondary"><i class="fas fa-edit"></i></a>
                                            <a href="<?= BASE_URL_ADMIN . '&action=bookings/delete&id=' . $booking['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa booking này?')"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted">Chưa có booking nào.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>
<?php
include_once PATH_VIEW_ADMIN . 'default/footer.php';
?>