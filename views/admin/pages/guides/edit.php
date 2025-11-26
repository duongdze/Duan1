<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';
?>

<main class="wrapper">
    <div class="main-content">
        <div class="page-header">
            <h1 class="h2">Chỉnh sửa Hướng Dẫn Viên</h1>
            <p class="text-muted">Cập nhật thông tin hướng dẫn viên</p>
        </div>

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_SESSION['error']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL_ADMIN ?>&action=guides/update" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $guide['id'] ?>">

            <div class="row g-3">
                <!-- Left Column -->
                <div class="col-lg-6">
                    <!-- Thông tin cơ bản -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fas fa-user"></i> Thông tin cơ bản
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="full_name" class="form-label fw-bold">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="full_name" name="full_name"
                                    value="<?= htmlspecialchars($guide['full_name']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="<?= htmlspecialchars($guide['email']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label fw-bold">Số điện thoại <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" id="phone" name="phone"
                                    value="<?= htmlspecialchars($guide['phone']) ?>" required>
                            </div>
                        </div>
                    </div>

                    <!-- Ảnh đại diện -->
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fas fa-image"></i> Ảnh đại diện
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($guide['avatar'])): ?>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Ảnh hiện tại:</label>
                                    <div>
                                        <img src="<?= htmlspecialchars($guide['avatar']) ?>"
                                            alt="Current avatar"
                                            class="img-thumbnail"
                                            style="max-width: 150px; max-height: 150px;">
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="mb-3">
                                <label for="avatar" class="form-label fw-bold">Thay đổi ảnh</label>
                                <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*">
                                <small class="text-muted">Để trống nếu không muốn thay đổi</small>
                            </div>
                            <div id="avatar-preview" class="mt-2"></div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-lg-6">
                    <!-- Thông tin chuyên môn -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fas fa-briefcase"></i> Thông tin chuyên môn
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="guide_type" class="form-label fw-bold">Loại hướng dẫn viên</label>
                                <select class="form-select" id="guide_type" name="guide_type">
                                    <option value="domestic" <?= ($guide['guide_type'] ?? 'domestic') == 'domestic' ? 'selected' : '' ?>>Nội địa</option>
                                    <option value="international" <?= ($guide['guide_type'] ?? '') == 'international' ? 'selected' : '' ?>>Quốc tế</option>
                                    <option value="specialized" <?= ($guide['guide_type'] ?? '') == 'specialized' ? 'selected' : '' ?>>Chuyên môn</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="specialization" class="form-label fw-bold">Chuyên môn</label>
                                <input type="text" class="form-control" id="specialization" name="specialization" 
                                       value="<?= htmlspecialchars($guide['specialization'] ?? '') ?>"
                                       placeholder="VD: Chuyên tuyến miền Bắc, Chuyên khách đoàn...">
                            </div>

                            <div class="mb-3">
                                <label for="languages" class="form-label fw-bold">Ngôn ngữ sử dụng</label>
                                <input type="text" class="form-control" id="languages" name="languages" 
                                       value="<?= htmlspecialchars($guide['languages'] ?? '') ?>"
                                       placeholder="Ví dụ: Tiếng Anh, Tiếng Trung, Tiếng Nhật">
                                <small class="text-muted">Phân cách bằng dấu phẩy</small>
                            </div>

                            <div class="mb-3">
                                <label for="experience_years" class="form-label fw-bold">Số năm kinh nghiệm</label>
                                <input type="number" class="form-control" id="experience_years" name="experience_years" 
                                       min="0" value="<?= htmlspecialchars($guide['experience_years'] ?? 0) ?>">
                            </div>

                            <div class="mb-3">
                                <label for="health_status" class="form-label fw-bold">Tình trạng sức khỏe</label>
                                <select class="form-select" id="health_status" name="health_status">
                                    <option value="">-- Chọn --</option>
                                    <option value="Tốt" <?= ($guide['health_status'] ?? '') == 'Tốt' ? 'selected' : '' ?>>Tốt</option>
                                    <option value="Khá" <?= ($guide['health_status'] ?? '') == 'Khá' ? 'selected' : '' ?>>Khá</option>
                                    <option value="Trung bình" <?= ($guide['health_status'] ?? '') == 'Trung bình' ? 'selected' : '' ?>>Trung bình</option>
                                    <option value="Cần theo dõi" <?= ($guide['health_status'] ?? '') == 'Cần theo dõi' ? 'selected' : '' ?>>Cần theo dõi</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="notes" class="form-label fw-bold">Ghi chú</label>
                                <textarea class="form-control" id="notes" name="notes" rows="4" 
                                          placeholder="Chứng chỉ, chuyên môn, kinh nghiệm đặc biệt..."><?= htmlspecialchars($guide['notes'] ?? '') ?></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Thông tin đánh giá -->
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fas fa-star"></i> Đánh giá & Hiệu suất
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Rating hiện tại:</label>
                                <div>
                                    <?php
                                    $rating = $guide['rating'] ?? 0;
                                    for ($i = 1; $i <= 5; $i++):
                                        if ($i <= $rating): ?>
                                            <i class="fas fa-star text-warning"></i>
                                        <?php else: ?>
                                            <i class="far fa-star text-warning"></i>
                                    <?php endif;
                                    endfor; ?>
                                    <span class="text-muted">(<?= number_format($rating, 1) ?>)</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Số tour đã dẫn:</label>
                                <div class="fs-4 text-primary">
                                    <i class="fas fa-route"></i> <?= number_format($guide['total_tours'] ?? 0) ?> tours
                                </div>
                            </div>

                            <div class="mb-0">
                                <label class="form-label fw-bold">Điểm hiệu suất:</label>
                                <div class="fs-4 text-success">
                                    <i class="fas fa-chart-line"></i> <?= number_format($guide['performance_score'] ?? 0, 2) ?>/5.00
                                </div>
                                <small class="text-muted">Tự động tính dựa trên rating và số tour</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-3 d-flex gap-2 mb-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Lưu thay đổi
                </button>
                <a href="<?= BASE_URL_ADMIN ?>&action=guides/detail&id=<?= $guide['id'] ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Hủy
                </a>
            </div>
        </form>
    </div>
</main>

<script>
document.getElementById('avatar').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('avatar-preview');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<label class="form-label fw-bold">Preview:</label><div><img src="${e.target.result}" alt="Preview" class="img-thumbnail" style="max-width: 200px; max-height: 200px;"></div>`;
        };
        reader.readAsDataURL(file);
    } else {
        preview.innerHTML = '';
    }
});
</script>

<?php
include_once PATH_VIEW_ADMIN . 'default/footer.php';
?>