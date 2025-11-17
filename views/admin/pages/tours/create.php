<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Thêm Tour Mới</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="?action=/">Trang chủ</a></li>
                        <li class="breadcrumb-item"><a href="?action=tours">Tours</a></li>
                        <li class="breadcrumb-item active">Thêm mới</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
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
                            <h3 class="card-title">Thông tin Tour</h3>
                        </div>

                        <form method="POST" action="?action=tours/store" enctype="multipart/form-data">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Tên tour</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>

                                <div class="form-group">
                                    <label for="type">Loại tour</label>
                                    <select class="form-control" id="type" name="type" required>
                                        <option value="">Chọn loại tour</option>
                                        <option value="trong_nuoc">Tour trong nước</option>
                                        <option value="quoc_te">Tour quốc tế</option>
                                        <option value="theo_yeu_cau">Tour theo yêu cầu</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="description">Mô tả</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="base_price">Giá cơ bản</label>
                                    <input type="number" class="form-control" id="base_price" name="base_price" required>
                                </div>

                                <div class="form-group">
                                    <label for="image">Hình ảnh</label>
                                    <input type="file" class="form-control-file" id="image" name="image">
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Lưu</button>
                                <a href="?action=tours" class="btn btn-default">Hủy</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    $(document).ready(function() {
        // Initialize summernote for rich text editor
        $('#description').summernote({
            height: 200
        });
    });
</script>

<?php
include_once PATH_VIEW_ADMIN . 'default/footer.php';
?>