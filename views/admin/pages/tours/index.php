<?php require_once 'views/admin/default/header.php'; ?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Quản lý Tour</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="?action=/">Trang chủ</a></li>
                        <li class="breadcrumb-item active">Tours</li>
                    </ol>
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