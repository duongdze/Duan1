<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';

$tour_id = $_GET['tour_id'] ?? '';
?>
<main class="wrapper">
    <div class="main-content">
        <div class="page-header">
            <h1 class="h2">Thêm Phiên bản Tour</h1>
            <p class="text-muted">Thêm phiên bản cho tour #<?= htmlspecialchars($tour_id) ?></p>
        </div>

        <div class="card">
            <div class="card-body">
                <form method="POST" action="?action=tours/versions/store">
                    <input type="hidden" name="tour_id" value="<?= htmlspecialchars($tour_id) ?>">

                    <div class="mb-3">
                        <label class="form-label">Tên phiên bản</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Ngày bắt đầu</label>
                            <input type="date" name="start_date" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ngày kết thúc</label>
                            <input type="date" name="end_date" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3 mt-3">
                        <label class="form-label">Giá</label>
                        <input type="number" name="price" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ghi chú</label>
                        <textarea name="notes" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mt-2">
                        <button class="btn btn-primary">Lưu</button>
                        <a href="?action=tours/versions&tour_id=<?= htmlspecialchars($tour_id) ?>" class="btn btn-secondary">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>