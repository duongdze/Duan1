<?php
include_once 'c:/laragon/www/Duan1/views/admin/default/header.php';
include_once 'c:/laragon/www/Duan1/views/admin/default/sidebar.php';
?>

<main class="wrapper">
    <div class="main-content">
        <div class="page-header d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h2">Quản lý Booking</h1>
                <p class="text-muted">Danh sách tất cả các booking trong hệ thống.</p>
            </div>
            <button class="btn btn-primary"><i class="fas fa-plus"></i> Tạo booking mới</button>
        </div>

        <div class="card">
            <div class="card-header">
                Danh sách Booking
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
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>BK-0125</td>
                                <td>Nguyễn Văn A</td>
                                <td>Khám phá Đà Nẵng - Hội An</td>
                                <td>15/11/2025</td>
                                <td>5,000,000 ₫</td>
                                <td><span class="badge bg-warning text-dark">Chờ xác nhận</span></td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
                                    <a href="#" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td>BK-0122</td>
                                <td>Phạm Thị D</td>
                                <td>Tour Xuyên Việt 10N9Đ</td>
                                <td>12/11/2025</td>
                                <td>25,000,000 ₫</td>
                                <td><span class="badge bg-success">Hoàn tất</span></td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
                                    <a href="#" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                             <tr>
                                <td>BK-0121</td>
                                <td>Lê Văn C</td>
                                <td>Chinh phục Fansipan</td>
                                <td>11/11/2025</td>
                                <td>3,500,000 ₫</td>
                                <td><span class="badge bg-info">Đã cọc</span></td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
                                    <a href="#" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td>BK-0120</td>
                                <td>Trần Thị B</td>
                                <td>Du lịch Mộc Châu - Mùa hoa cải</td>
                                <td>10/11/2025</td>
                                <td>2,800,000 ₫</td>
                                <td><span class="badge bg-danger">Đã hủy</span></td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
                                    <a href="#" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
include_once 'c:/laragon/www/Duan1/views/admin/default/footer.php';
?>