<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';
?>
<main class="wrapper">
  <div class="main-content">
    <div class="container-fluid">
      <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <div>
          <h1 class="h3 mb-0">Chi tiết tour: <?= htmlspecialchars($tour['name']) ?></h1>
          <p class="text-muted small">Thông tin tour, lịch trình và nhiệm vụ của HDV</p>
        </div>
        <a href="<?= BASE_URL_ADMIN . '&action=guide/schedule' ?>" class="btn btn-secondary">Quay lại</a>
      </div>

      <div class="card mb-4">
        <div class="card-body">
          <p><strong>Danh mục:</strong> <?= htmlspecialchars($tour['category_name']) ?></p>
          <p><strong>Mô tả:</strong> <?= nl2br(htmlspecialchars($tour['description'])) ?></p>
          <?php if (!empty($assignment)): ?>
            <hr>
            <p><strong>Trạng thái phân công:</strong> <?= htmlspecialchars($assignment['status'] ?? '') ?></p>
            <p><strong>Tài xế / Liên hệ:</strong> <?= htmlspecialchars($assignment['driver_name'] ?? '') ?></p>
            <p><strong>Ngày bắt đầu:</strong> <?= htmlspecialchars($assignment['start_date'] ?? '') ?> — <strong>Ngày kết thúc:</strong> <?= htmlspecialchars($assignment['end_date'] ?? '') ?></p>
          <?php endif; ?>
        </div>
      </div>

      <div class="card mb-4">
        <div class="card-body">
          <h4>Lịch trình tour</h4>
          <?php foreach ($itineraries as $day): ?>
            <div class="card mb-3">
              <div class="card-header">Ngày <?= htmlspecialchars($day['day_number']) ?> - <?= htmlspecialchars($day['day_label']) ?></div>
              <div class="card-body">
                <p><strong>Thời gian:</strong> <?= htmlspecialchars($day['time_start']) ?> - <?= htmlspecialchars($day['time_end']) ?></p>
                <p><strong>Hoạt động:</strong> <?= nl2br(htmlspecialchars($day['activities'])) ?></p>
                <p><strong>Mô tả:</strong> <?= nl2br(htmlspecialchars($day['description'])) ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</main>
<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>
