<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';

$tour_id = $_GET['tour_id'] ?? '';
?>
<main class="wrapper">
    <div class="main-content">
        <div class="page-header">
            <h1 class="h2">Thêm Lịch trình</h1>
            <p class="text-muted">Thêm lịch trình cho tour #<?= htmlspecialchars($tour_id) ?></p>
        </div>

        <div class="card">
            <div class="card-body">
                <form method="POST" action="?action=tours/itineraries/store" enctype="multipart/form-data">
                    <input type="hidden" name="tour_id" value="<?= htmlspecialchars($tour_id) ?>">

                    <div class="mb-3">
                        <label class="form-label">Ngày (số ngày)</label>
                        <input type="number" name="day_number" class="form-control" min="1" value="1">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tiêu đề</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Hoạt động</label>
                        <textarea name="activities" class="form-control" rows="4"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Hình ảnh (tùy chọn)</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>

                    <div class="mt-2">
                        <button class="btn btn-primary">Lưu</button>
                        <a href="?action=tours/itineraries&tour_id=<?= htmlspecialchars($tour_id) ?>" class="btn btn-secondary">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>