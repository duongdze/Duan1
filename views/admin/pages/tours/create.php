<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';
?>

<!-- Quill editor styles -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<main class="wrapper">
    <div class="main-content">
        <div class="page-header">
            <h1 class="h2">Thêm Tour Mới</h1>
            <p class="text-muted">Tạo tour mới cho hệ thống</p>
        </div>

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_SESSION['error']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form method="POST" action="?action=tours/store" enctype="multipart/form-data">
            <div class="row g-3">
                <!-- Left Column -->
                <div class="col-lg-6">
                    <!-- Thông tin cơ bản -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fas fa-info-circle"></i> Thông tin cơ bản
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="name" class="form-label fw-500">Tên tour</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Tour du lịch Quảng Bình" required>
                            </div>

                            <div class="mb-3">
                                <label for="type" class="form-label fw-500">Danh mục tour</label>
                                <select class="form-select" id="type" name="type" required>
                                    <option value="">Trong nước</option>
                                    <option value="trong_nuoc">Tour trong nước</option>
                                    <option value="quoc_te">Tour quốc tế</option>
                                    <option value="theo_yeu_cau">Tour theo yêu cầu</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="supplier_id" class="form-label fw-500">Nhà cung cấp</label>
                                <select class="form-select" id="supplier_id" name="supplier_id">
                                    <option value="">Chọn nhà cung cấp</option>
                                    <?php if (!empty($suppliers)): ?>
                                        <?php foreach ($suppliers as $s): ?>
                                            <option value="<?= htmlspecialchars($s['id']) ?>"><?= htmlspecialchars($s['name']) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="base_price" class="form-label fw-500">Giá cơ bản</label>
                                <input type="number" class="form-control" id="base_price" name="base_price" placeholder="1000000" required>
                            </div>
                        </div>
                    </div>

                    <!-- Thông tin khác -->
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fas fa-align-left"></i> Mô tả
                            </h5>
                        </div>
                        <div class="card-body">
                            <label for="description" class="form-label fw-500">Nhập mô tả</label>
                            <input type="hidden" id="input-description" name="description">
                            <div id="editor-description" class="quill-editor"></div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-lg-6">
                    <!-- Lịch trình -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fas fa-calendar"></i> Lịch trình
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label fw-500">Số ngày</label>
                                    <div class="input-group">
                                        <span class="input-group-text">10</span>
                                        <span class="input-group-text">ngày</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-500">Số đêm</label>
                                    <div class="input-group">
                                        <span class="input-group-text">11</span>
                                        <span class="input-group-text">đêm</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label fw-500">Ngày khởi hành</label>
                                    <input type="date" class="form-control" placeholder="10 ngày">
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-500">Ngày kết thúc</label>
                                    <input type="date" class="form-control" placeholder="11 đêm">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-500">Lịch trình chi tiết</label>
                                <textarea class="form-control" rows="6" placeholder="Hoạt động"></textarea>
                            </div>

                            <button type="button" class="btn btn-outline-primary btn-sm w-100">
                                <i class="fas fa-list"></i> Thêm lịch trình
                            </button>
                        </div>
                    </div>

                    <!-- Hình ảnh -->
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fas fa-image"></i> Hình ảnh
                            </h5>
                        </div>
                        <div class="card-body text-center">
                            <div class="mb-3 p-4 bg-light rounded border border-dashed" id="image-preview">
                                <i class="fas fa-image fa-3x text-muted"></i>
                                <p class="text-muted small mt-2">Chưa có hình ảnh</p>
                            </div>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chính sách & Actions -->
            <div class="card mt-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-file-alt"></i> Chính sách
                    </h5>
                </div>
                <div class="card-body">
                    <label for="policy" class="form-label fw-500">Nhập chính sách</label>
                    <input type="hidden" id="input-policy" name="policy">
                    <div id="editor-policy" class="quill-editor"></div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-3 d-flex gap-2 mb-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tạo mới
                </button>
                <a href="?action=tours" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Hủy
                </a>
            </div>
        </form>
</main>

<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Quill editors
        var quillDesc = new Quill('#editor-description', {
            theme: 'snow',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline', 'strike'],
                    ['blockquote', 'code-block'],
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }],
                    [{
                        'size': ['small', false, 'large', 'huge']
                    }],
                    [{
                        'header': [1, 2, 3, 4, 5, 6, false]
                    }],
                    ['link'],
                    ['clean']
                ]
            }
        });

        var quillPolicy = new Quill('#editor-policy', {
            theme: 'snow',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline', 'strike'],
                    ['blockquote', 'code-block'],
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }],
                    [{
                        'size': ['small', false, 'large', 'huge']
                    }],
                    [{
                        'header': [1, 2, 3, 4, 5, 6, false]
                    }],
                    ['link'],
                    ['clean']
                ]
            }
        });

        var form = document.querySelector('form[action="?action=tours/store"]');
        if (form) {
            form.addEventListener('submit', function(e) {
                document.getElementById('input-description').value = quillDesc.root.innerHTML;
                document.getElementById('input-policy').value = quillPolicy.root.innerHTML;
            });
        }

        // Image preview
        var imageInput = document.getElementById('image');
        if (imageInput) {
            imageInput.addEventListener('change', function(e) {
                var file = e.target.files[0];
                if (file) {
                    var reader = new FileReader();
                    reader.onload = function(event) {
                        var preview = document.createElement('img');
                        preview.src = event.target.result;
                        preview.className = 'form-image-preview';

                        var previewContainer = document.getElementById('image-preview');
                        if (previewContainer) {
                            previewContainer.innerHTML = '';
                            previewContainer.appendChild(preview);
                        }
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    });
</script>

<?php
include_once PATH_VIEW_ADMIN . 'default/footer.php';
?>
