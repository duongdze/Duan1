<main class="wrapper">
    <div class="main-content">
        <div class="page-header d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h2">Quản lý Tour</h1>
                <p class="text-muted">Toàn bộ các tour đang được quản lý trên hệ thống.</p>
            </div>
            <a href="/Duan1/admin/tours/create" class="btn btn-primary"><i class="fas fa-plus"></i> Tạo tour mới</a>
        </div>

        <div class="card">
            <div class="card-header">
                Danh sách Tour
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Tên Tour</th>
                                <th>Loại Tour</th>
                                <th>Ngày tạo</th>
                                <th>Giá</th>
                                <th>Trạng thái</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Khám phá Đà Nẵng - Hội An</td>
                                <td>Trong nước</td>
                                <td>01/10/2025</td>
                                <td>5,000,000 ₫</td>
                                <td><span class="badge bg-success">Đang mở bán</span></td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
                                    <a href="#" class="btn btn-sm btn-outline-secondary"><i class="fas fa-edit"></i></a>
                                    <a href="#" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td>Du lịch Mộc Châu - Mùa hoa cải</td>
                                <td>Trong nước</td>
                                <td>15/09/2025</td>
                                <td>2,800,000 ₫</td>
                                <td><span class="badge bg-success">Đang mở bán</span></td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
                                    <a href="#" class="btn btn-sm btn-outline-secondary"><i class="fas fa-edit"></i></a>
                                    <a href="#" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                             <tr>
                                <td>Chinh phục Fansipan</td>
                                <td>Trong nước</td>
                                <td>05/09/2025</td>
                                <td>3,500,000 ₫</td>
                                <td><span class="badge bg-secondary">Đã đóng</span></td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
                                    <a href="#" class="btn btn-sm btn-outline-secondary"><i class="fas fa-edit"></i></a>
                                    <a href="#" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td>Khám phá Châu Âu 5 nước</td>
                                <td>Quốc tế</td>
                                <td>20/08/2025</td>
                                <td>55,000,000 ₫</td>
                                <td><span class="badge bg-success">Đang mở bán</span></td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
                                    <a href="#" class="btn btn-sm btn-outline-secondary"><i class="fas fa-edit"></i></a>
                                    <a href="#" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success">
                            <?php
                            echo $_SESSION['success'];
                            unset($_SESSION['success']);
                            ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger">
                            <?php
                            echo $_SESSION['error'];
                            unset($_SESSION['error']);
                            ?>
                        </div>
                    <?php endif; ?>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Danh sách Tour</h3>
                            <div class="card-tools">
                                <a href="?action=tours/create" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Thêm mới
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <table id="tourTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên tour</th>
                                        <th>Loại</th>
                                        <th>Giá cơ bản</th>
                                        <th>Ngày tạo</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($tours as $tour): ?>
                                    <tr>
                                        <td><?php echo $tour['id']; ?></td>
                                        <td><?php echo $tour['name']; ?></td>
                                        <td><?php echo $tour['type']; ?></td>
                                        <td><?php echo number_format($tour['base_price']); ?> VNĐ</td>
                                        <td><?php echo $tour['created_at']; ?></td>
                                        <td>
                                            <a href="<?= BASE_URL_ADMIN ?>&action=tours/edit&id=<?php echo $tour['id']; ?>" 
                                               class="btn btn-info btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="?action=tours/delete&id=<?php echo $tour['id']; ?>" 
                                               class="btn btn-danger btn-sm"
                                               onclick="return confirm('Bạn có chắc muốn xóa tour này?');">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    $(document).ready(function() {
        $('#tourTable').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true
        });
    });
</script>

<?php require_once 'views/admin/default/footer.php'; ?>
