<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Sửa Tour</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="?action=/">Trang chủ</a></li>
                        <li class="breadcrumb-item"><a href="?action=tours">Tours</a></li>
                        <li class="breadcrumb-item active">Sửa</li>
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

                        <form method="POST" action="?action=tours/update" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?php echo $tour['id']; ?>">

                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Tên tour</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="<?php echo $tour['name']; ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="type">Loại tour</label>
                                    <select class="form-control" id="type" name="type" required>
                                        <option value="">Chọn loại tour</option>
                                        <option value="trong_nuoc" <?php echo $tour['type'] == 'trong_nuoc' ? 'selected' : ''; ?>>
                                            Tour trong nước
                                        </option>
                                        <option value="quoc_te" <?php echo $tour['type'] == 'quoc_te' ? 'selected' : ''; ?>>
                                            Tour quốc tế
                                        </option>
                                        <option value="theo_yeu_cau" <?php echo $tour['type'] == 'theo_yeu_cau' ? 'selected' : ''; ?>>
                                            Tour theo yêu cầu
                                        </option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="description">Mô tả</label>
                                    <textarea class="form-control" id="description" name="description" rows="3">
                                        <?php echo $tour['description']; ?>
                                    </textarea>
                                </div>

                                <div class="form-group">
                                    <label for="base_price">Giá cơ bản</label>
                                    <input type="number" class="form-control" id="base_price" name="base_price"
                                        value="<?php echo $tour['base_price']; ?>" required>
                                </div>

                                <?php if ($tour['image']): ?>
                                    <div class="form-group">
                                        <label>Hình ảnh hiện tại</label>
                                        <div>
                                            <img src="<?php echo $tour['image']; ?>" alt="Tour Image" style="max-width: 200px;">
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="form-group">
                                    <label for="image">Thay đổi hình ảnh</label>
                                    <input type="file" class="form-control-file" id="image" name="image">
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Cập nhật</button>
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