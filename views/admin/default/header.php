<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_ASSETS_ADMIN ?>css/bootstrap.css">
    <link rel="stylesheet" href="<?= BASE_ASSETS_ADMIN ?>css/admin-dashboard.css">
    <link rel="stylesheet" href="<?= BASE_ASSETS_ADMIN ?>css/sidebar.css">
    <link rel="stylesheet" href="<?= BASE_ASSETS_ADMIN ?>css/tours.css">
    <!-- <link rel="stylesheet" href="<?= BASE_ASSETS_ADMIN ?>css/style.css"> -->
</head>

<body>
    <div class="admin-layout d-flex">
        <!-- Header -->
        <header class="admin-header">
            <div class="header-content">
                <div class="header-right">
                    <div class="user-profile-section">
                        <!-- Avatar -->
                        <div class="user-avatar">
                            <?php
                            $user = $_SESSION['user'] ?? null;
                            $avatarUrl = !empty($user['avatar']) ? BASE_ASSETS_UPLOADS . 'users/admin/' . $user['avatar'] : 'https://ui-avatars.com/api/?name=' . urlencode($user['name'] ?? 'User') . '&background=0D6EFD&color=fff&size=40';
                            $userName = $user['full_name'] ?? 'Guest';
                            $userRole = $user['role'] ?? 'user';
                            ?>
                            <img src="<?= $avatarUrl ?>" alt="<?= htmlspecialchars($userName) ?>" class="avatar-img">
                        </div>

                        <!-- User Info -->
                        <div class="user-info">
                            <p class="user-name"><?= htmlspecialchars($userName) ?></p>
                            <p class="user-role">
                                <?php
                                $roleLabel = match ($userRole) {
                                    'admin' => 'Quản trị viên',
                                    'hdv' => 'Hướng dẫn viên',
                                    'supplier' => 'Nhà cung cấp',
                                    default => ucfirst($userRole)
                                };
                                echo $roleLabel;
                                ?>
                            </p>
                        </div>

                        <!-- Dropdown Toggle -->
                        <div class="dropdown-container">
                            <button class="dropdown-btn" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-chevron-down"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li>
                                    <a class="dropdown-item" href="<?= BASE_URL_ADMIN ?>&action=account">
                                        <i class="fas fa-user"></i> Thông tin tài khoản
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item text-danger" href="<?= BASE_URL_ADMIN ?>&action=logout">
                                        <i class="fas fa-sign-out-alt"></i> Đăng xuất
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </header>