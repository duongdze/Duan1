<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';

$tour_id = $_GET['tour_id'] ?? '';
?>
<main class="wrapper">
    <div class="main-content">
        <div class="page-header">
            <h1 class="h2">Thêm Phiên Bản Tour</h1>
            <p class="text-muted">
                <a href="?action=tours" class="text-decoration-none">Tours</a> 
                <i class="fas fa-chevron-right mx-2" style="font-size: 0.75rem;"></i>
                <a href="?action=tours_versions&tour_id=<?= htmlspecialchars($tour_id) ?>" class="text-decoration-none">
                    <?= htmlspecialchars($tour['name'] ?? '') ?>
                </a>
                <i class="fas fa-chevron-right mx-2" style="font-size: 0.75rem;"></i>
                Thêm mới
            </p>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i><?= $_SESSION['error'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Thông Tin Phiên Bản</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="?action=tours_versions/store" id="versionForm">
                    <input type="hidden" name="tour_id" value="<?= htmlspecialchars($tour_id) ?>">

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">
                                Tên phiên bản <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   class="form-control" 
                                   placeholder="VD: Mùa hè 2024, Tết Nguyên Đán..." 
                                   required
                                   value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                            <small class="text-muted">Tên gợi nhớ cho phiên bản tour này</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="fas fa-calendar-alt me-1"></i>Ngày bắt đầu
                            </label>
                            <input type="date" 
                                   name="start_date" 
                                   class="form-control"
                                   value="<?= htmlspecialchars($_POST['start_date'] ?? '') ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="fas fa-calendar-alt me-1"></i>Ngày kết thúc
                            </label>
                            <input type="date" 
                                   name="end_date" 
                                   class="form-control"
                                   value="<?= htmlspecialchars($_POST['end_date'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">
                                <i class="fas fa-money-bill-wave me-1"></i>Giá (VNĐ)
                            </label>
                            <input type="number" 
                                   name="price" 
                                   class="form-control" 
                                   min="0"
                                   step="1000"
                                   placeholder="0"
                                   value="<?= htmlspecialchars($_POST['price'] ?? '') ?>">
                            <small class="text-muted">Để trống hoặc 0 nếu sử dụng giá cơ bản của tour</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">
                                <i class="fas fa-sticky-note me-1"></i>Ghi chú
                            </label>
                            <textarea name="notes" 
                                      class="form-control" 
                                      rows="4"
                                      placeholder="Thông tin bổ sung về phiên bản này..."><?= htmlspecialchars($_POST['notes'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Lưu Phiên Bản
                        </button>
                        <a href="?action=tours_versions&tour_id=<?= htmlspecialchars($tour_id) ?>" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
// Client-side validation
document.getElementById('versionForm').addEventListener('submit', function(e) {
    const startDate = document.querySelector('input[name="start_date"]').value;
    const endDate = document.querySelector('input[name="end_date"]').value;
    
    if (startDate && endDate && new Date(endDate) < new Date(startDate)) {
        e.preventDefault();
        alert('Ngày kết thúc phải sau ngày bắt đầu!');
        return false;
    }
});
</script>

<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>
