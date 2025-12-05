<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';

$isEdit = isset($supplier) && !empty($supplier);
$pageTitle = $isEdit ? 'Chỉnh Sửa Nhà Cung Cấp' : 'Thêm Nhà Cung Cấp Mới';
$actionUrl = BASE_URL_ADMIN . '&action=suppliers/' . ($isEdit ? 'update' : 'store');
?>

<main class="dashboard supplier-form-page">
    <div class="dashboard-container">
        <!-- Modern Page Header -->
        <header class="dashboard-header">
            <div class="header-content">
                <div class="header-left">
                    <div class="breadcrumb-modern">
                        <a href="<?= BASE_URL_ADMIN ?>&action=/" class="breadcrumb-link">
                            <i class="fas fa-home"></i>
                            <span>Dashboard</span>
                        </a>
                        <span class="breadcrumb-separator">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                        <a href="<?= BASE_URL_ADMIN ?>&action=suppliers" class="breadcrumb-link">
                            <i class="fas fa-handshake"></i>
                            <span>Nhà Cung Cấp</span>
                        </a>
                        <span class="breadcrumb-separator">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                        <span class="breadcrumb-current"><?= $pageTitle ?></span>
                    </div>
                    <div class="page-title-section">
                        <h1 class="page-title">
                            <i class="fas fa-<?= $isEdit ? 'edit' : 'plus-circle' ?> title-icon"></i>
                            <?= $pageTitle ?>
                        </h1>
                        <p class="page-subtitle"><?= $isEdit ? 'Cập nhật thông tin nhà cung cấp' : 'Thêm đối tác mới vào hệ thống' ?></p>
                    </div>
                </div>
                <div class="header-right">
                    <a href="<?= BASE_URL_ADMIN . '&action=suppliers' ?>" class="btn btn-modern btn-secondary">
                        <i class="fas fa-times me-2"></i>
                        Hủy bỏ
                    </a>
                    <button type="submit" form="supplier-form" class="btn btn-modern btn-primary">
                        <i class="fas fa-save me-2"></i>
                        <?= $isEdit ? 'Cập Nhật' : 'Tạo Mới' ?>
                    </button>
                </div>
            </div>
        </header>

        <!-- Alert Messages -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert-modern alert-danger alert-dismissible fade show" role="alert">
                <div class="alert-content">
                    <i class="fas fa-exclamation-circle alert-icon"></i>
                    <span><?= $_SESSION['error'] ?></span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Supplier Form -->
        <form method="POST" action="<?= $actionUrl ?>" id="supplier-form">
            <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?= $supplier['id'] ?>">
            <?php endif; ?>

            <div class="row">
                <!-- Main Content -->
                <div class="col-lg-8">
                    <!-- Basic Information Card -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-info-circle text-primary me-2"></i>
                                Thông tin cơ bản
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="name" name="name" 
                                               value="<?= htmlspecialchars($supplier['name'] ?? '') ?>" 
                                               required placeholder=" ">
                                        <label for="name">Tên Nhà Cung Cấp <span class="text-danger">*</span></label>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <select class="form-select" id="type" name="type">
                                            <option value="">-- Chọn loại --</option>
                                            <option value="hotel" <?= ($supplier['type'] ?? '') == 'hotel' ? 'selected' : '' ?>>Khách sạn</option>
                                            <option value="restaurant" <?= ($supplier['type'] ?? '') == 'restaurant' ? 'selected' : '' ?>>Nhà hàng</option>
                                            <option value="transportation" <?= ($supplier['type'] ?? '') == 'transportation' ? 'selected' : '' ?>>Vận chuyển</option>
                                            <option value="entertainment" <?= ($supplier['type'] ?? '') == 'entertainment' ? 'selected' : '' ?>>Giải trí</option>
                                            <option value="guide" <?= ($supplier['type'] ?? '') == 'guide' ? 'selected' : '' ?>>Hướng dẫn viên</option>
                                            <option value="other" <?= ($supplier['type'] ?? '') == 'other' ? 'selected' : '' ?>>Khác</option>
                                        </select>
                                        <label for="type">Loại Dịch Vụ</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information Card -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-address-book text-success me-2"></i>
                                Thông tin liên hệ
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="contact_person" name="contact_person" 
                                               value="<?= htmlspecialchars($supplier['contact_person'] ?? '') ?>" 
                                               placeholder=" ">
                                        <label for="contact_person">Người Liên Hệ</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="tel" class="form-control" id="phone" name="phone" 
                                               value="<?= htmlspecialchars($supplier['phone'] ?? '') ?>" 
                                               placeholder=" ">
                                        <label for="phone">Số Điện Thoại</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="email" class="form-control" id="email" name="email" 
                                               value="<?= htmlspecialchars($supplier['email'] ?? '') ?>" 
                                               placeholder=" ">
                                        <label for="email">Email</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="number" class="form-control" id="rating" name="rating" 
                                               min="0" max="5" step="0.1"
                                               value="<?= htmlspecialchars($supplier['rating'] ?? '') ?>" 
                                               placeholder=" ">
                                        <label for="rating">Đánh Giá (0-5)</label>
                                    </div>
                                    <small class="text-muted d-block mt-2">Nhập đánh giá từ 0 đến 5 sao</small>
                                </div>

                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea class="form-control" id="address" name="address" 
                                                  style="height: 100px" placeholder=" "><?= htmlspecialchars($supplier['address'] ?? '') ?></textarea>
                                        <label for="address">Địa Chỉ</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information Card -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-file-alt text-warning me-2"></i>
                                Ghi chú & Mô tả
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="form-floating">
                                <textarea class="form-control" id="description" name="description" 
                                          style="height: 150px" placeholder=" "><?= htmlspecialchars($supplier['description'] ?? '') ?></textarea>
                                <label for="description">Mô Tả Chi Tiết</label>
                            </div>
                            <small class="text-muted d-block mt-2">
                                <i class="fas fa-lightbulb me-1"></i>
                                Ghi chú chi tiết giúp quản lý và đánh giá nhà cung cấp tốt hơn
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Form Actions -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Thao tác</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button type="submit" form="supplier-form" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>
                                    <?= $isEdit ? 'Cập nhật' : 'Tạo mới' ?>
                                </button>
                                <a href="<?= BASE_URL_ADMIN . '&action=suppliers' ?>" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>
                                    Quay lại
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Info Card -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                Hướng dẫn
                            </h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    <small>Điền đầy đủ thông tin liên hệ</small>
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    <small>Đánh giá giúp theo dõi chất lượng</small>
                                </li>
                                <li class="mb-0">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    <small>Mô tả chi tiết để dễ quản lý</small>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('supplier-form');
    
    form.addEventListener('submit', function(e) {
        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const rating = document.getElementById('rating').value;
        
        if (!name) {
            e.preventDefault();
            alert('Vui lòng nhập tên nhà cung cấp');
            document.getElementById('name').focus();
            return false;
        }
        
        if (email && !validateEmail(email)) {
            e.preventDefault();
            alert('Email không hợp lệ');
            document.getElementById('email').focus();
            return false;
        }
        
        if (rating && (parseFloat(rating) < 0 || parseFloat(rating) > 5)) {
            e.preventDefault();
            alert('Đánh giá phải từ 0 đến 5');
            document.getElementById('rating').focus();
            return false;
        }
    });
    
    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
});
</script>

<?php
include_once PATH_VIEW_ADMIN . 'default/footer.php';
?>
