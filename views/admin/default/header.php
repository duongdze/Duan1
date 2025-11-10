<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header</title>
    <link rel="stylesheet" href="<?= BASE_ASSETS_ADMIN ?>css/style.css">
    <link rel="stylesheet" href="<?= BASE_ASSETS_ADMIN ?>css/bootstrap.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
</head>

<body>
    <?php
    // Bảo vệ trang admin, yêu cầu đăng nhập
    if (empty($_SESSION['user'])) {
        header('Location: ' . BASE_URL_ADMIN . '&action=login');
        exit;
    }
    ?>
    <!-- Page wrapper with sidebar -->
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar-wrapper" class="bg-dark text-white" style="width:250px; min-height:100vh;">
            <div class="sidebar-heading p-3 border-bottom">
                <strong>Admin Panel</strong>
            </div>
            <div class="list-group list-group-flush">
                <a href="?action=/" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                </a>
                <a href="?action=tours" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="fas fa-map-signs me-2"></i> Tours
                </a>
                <a href="?action=bookings" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="fas fa-book-open me-2"></i> Bookings
                </a>
                <a href="?action=guides" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="fas fa-user-tie me-2"></i> Guides
                </a>
                <a href="?action=suppliers" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="fas fa-handshake me-2"></i> Suppliers
                </a>
                <a href="?action=reports/financial" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="fas fa-chart-line me-2"></i> Reports
                </a>
                <a href="?action=logout" class="list-group-item list-group-item-action bg-dark text-white mt-3">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </a>
            </div>
        </nav>

        <!-- Page Content -->
        <div id="page-content-wrapper" class="flex-fill">
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                <div class="container-fluid">
                    <button class="btn btn-sm btn-primary" id="menu-toggle">Toggle Menu</button>
                    <div class="ms-auto d-flex align-items-center">
                        <?php
                            $userName = htmlspecialchars($_SESSION['user']['full_name'] ?? ($_SESSION['user']['username'] ?? 'Admin'));
                            // Attempt to use an uploaded avatar if available, otherwise fallback to Gravatar/placeholder
                            $avatarPath = '';
                            if (!empty($_SESSION['user']['avatar'])) {
                                $avatarPath = BASE_ASSETS_ADMIN . 'image/' . ltrim($_SESSION['user']['avatar'], '/');
                            }
                        ?>
                        <div class="d-flex align-items-center">
                            <img src="<?= $avatarPath ?: 'https://www.gravatar.com/avatar/?d=mp' ?>" alt="avatar" style="width:36px;height:36px;border-radius:50%;object-fit:cover;" onerror="this.src='https://www.gravatar.com/avatar/?d=mp'">
                            <div class="ms-2 text-end">
                                <div class="fw-semibold" style="line-height:1"><?= $userName ?></div>
                                <a href="?action=logout" class="text-decoration-none small">Đăng xuất</a>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>