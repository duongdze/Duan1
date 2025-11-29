<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';
?>

<main class="wrapper">
    <div class="main-content">
        <div class="page-header">
            <h1 class="h2">Thêm Hướng Dẫn Viên Mới</h1>
            <p class="text-muted">Tạo hồ sơ cho hướng dẫn viên mới</p>
        </div>

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_SESSION['error']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL_ADMIN ?>&action=guides/store" enctype="multipart/form-data">
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
                                <input type="text" class="form-control" id="full_name" name="full_name" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label fw-bold">Số điện thoại <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" id="phone" name="phone" required>
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
                            <div class="mb-3">
                                <label for="avatar" class="form-label fw-bold">Chọn ảnh</label>
                                <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*">
                                <small class="text-muted">Định dạng: JPG, PNG, GIF. Kích thước tối đa: 5MB</small>
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
                                    <option value="domestic">Nội địa</option>
                                    <option value="international">Quốc tế</option>
                                    <option value="specialized">Chuyên môn</option>
                                </select>
                                <small class="text-muted">Phân loại theo loại tour</small>
                            </div>

                            <div class="mb-3">
                                <label for="specialization" class="form-label fw-bold">Chuyên môn</label>
                                <input type="text" class="form-control" id="specialization" name="specialization"
                                    placeholder="VD: Chuyên tuyến miền Bắc, Chuyên khách đoàn...">
                                <small class="text-muted">Mô tả chuyên môn cụ thể</small>
                            </div>

                            <div class="mb-3">
                                <label for="languages" class="form-label fw-bold">Ngôn ngữ sử dụng</label>
                                <input type="text" class="form-control" id="languages" name="languages"
                                    placeholder="Ví dụ: Tiếng Anh, Tiếng Trung, Tiếng Nhật">
                                <small class="text-muted">Phân cách bằng dấu phẩy</small>
                            </div>

                            <div class="mb-3">
                                <label for="experience_years" class="form-label fw-bold">Số năm kinh nghiệm</label>
                                <input type="number" class="form-control" id="experience_years" name="experience_years"
                                    min="0" value="0">
                            </div>

                            <div class="mb-3">
                                <label for="health_status" class="form-label fw-bold">Tình trạng sức khỏe</label>
                                <select class="form-select" id="health_status" name="health_status">
                                    <option value="">-- Chọn --</option>
                                    <option value="Tốt">Tốt</option>
                                    <option value="Khá">Khá</option>
                                    <option value="Trung bình">Trung bình</option>
                                    <option value="Cần theo dõi">Cần theo dõi</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="notes" class="form-label fw-bold">Ghi chú</label>
                                <textarea class="form-control" id="notes" name="notes" rows="4"
                                    placeholder="Chứng chỉ, chuyên môn, kinh nghiệm đặc biệt..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Thông tin hệ thống -->
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fas fa-info-circle"></i> Thông tin hệ thống
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-key"></i> Mật khẩu mặc định: <strong>123456</strong>
                                <br>
                                <small>HDV có thể đổi mật khẩu sau khi đăng nhập lần đầu</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-3 d-flex gap-2 mb-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Tạo hướng dẫn viên
                </button>
                <a href="<?= BASE_URL_ADMIN ?>&action=guides" class="btn btn-secondary">
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
                preview.innerHTML = `<img src="${e.target.result}" alt="Preview" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">`;
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