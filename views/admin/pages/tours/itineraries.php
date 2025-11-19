<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';
?>

<main class="wrapper">
    <div class="main-content">
        <div class="page-header">
            <h1 class="h2">Lịch trình Tour</h1>
            <p class="text-muted">Quản lý lịch trình cho tour #<?= htmlspecialchars($_GET['tour_id'] ?? '') ?></p>
        </div>

        <div class="card">
            <div class="card-body">
                <p class="text-muted">Chưa có chức năng quản lý lịch trình. Bạn có thể thêm CRUD cho bảng <code>itineraries</code> sau.</p>
                <a href="?action=tours" class="btn btn-secondary">Quay về Tour</a>
            </div>
        </div>
    </div>
</main>

<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>