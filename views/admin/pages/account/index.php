<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';

$user = $_SESSION['user'] ?? null;
?>

<main class="wrapper">
    <div class="main-content">
        <div class="page-header">
            <h1 class="h2">Thông tin tài khoản</h1>
            <p class="text-muted">Quản lý thông tin cá nhân của bạn</p>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Thông tin chung</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted">Tên:</label>
                                <p class="fw-500"><?= htmlspecialchars($user['full_name'] ?? 'N/A') ?></p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted">Email:</label>
                                <p class="fw-500"><?= htmlspecialchars($user['email'] ?? 'N/A') ?></p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted">Vai trò:</label>
                                <p class="fw-500">
                                    <?php
                                    $roleLabel = match ($user['role'] ?? 'user') {
                                        'admin' => 'Quản trị viên',
                                        'hdv' => 'Hướng dẫn viên',
                                        'supplier' => 'Nhà cung cấp',
                                        default => ucfirst($user['role'] ?? 'user')
                                    };
                                    echo $roleLabel;
                                    ?>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted">Số điện thoại:</label>
                                <p class="fw-500"><?= htmlspecialchars($user['phone'] ?? 'N/A') ?></p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted">Địa chỉ:</label>
                                <p class="fw-500"><?= htmlspecialchars($user['address'] ?? 'N/A') ?></p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted">Ngày tham gia:</label>
                                <p class="fw-500"><?= !empty($user['created_at']) ? date('d/m/Y', strtotime($user['created_at'])) : 'N/A' ?></p>
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex gap-2">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editAccountModal">
                                <i class="fas fa-edit"></i> Chỉnh sửa thông tin
                            </button>
                            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                <i class="fas fa-lock"></i> Đổi mật khẩu
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Ảnh đại diện</h5>
                    </div>
                    <div class="card-body text-center">
                        <?php
                        $avatarUrl = !empty($user['avatar']) ? BASE_ASSETS_UPLOADS . $user['avatar'] : 'https://ui-avatars.com/api/?name=' . urlencode($user['name'] ?? 'User') . '&background=0D6EFD&color=fff&size=200';
                        ?>
                        <img src="<?= $avatarUrl ?>" alt="<?= htmlspecialchars($user['full_name']) ?>" class="rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #e9ecef;">

                        <div class="mt-3">
                            <form id="avatarForm" enctype="multipart/form-data">
                                <input type="file" id="avatarInput" name="avatar" accept="image/*" style="display: none;">
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="document.getElementById('avatarInput').click()">
                                    <i class="fas fa-camera"></i> Thay đổi ảnh
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="mb-0">Hoạt động</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small">
                            <i class="fas fa-clock"></i> Truy cập lần cuối: <br>
                            <strong>
                                <?php
                                $timezone = new DateTimeZone('Asia/Ho_Chi_Minh');
                                $lastLoginTime = !empty($_SESSION['user']['last_login']) ? new DateTime($_SESSION['user']['last_login'], new DateTimeZone('UTC')) : new DateTime('now', new DateTimeZone('UTC'));
                                $lastLoginTime->setTimezone($timezone);
                                echo $lastLoginTime->format('d/m/Y H:i');
                                ?>
                            </strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
include_once PATH_VIEW_ADMIN . 'default/footer.php';
?>