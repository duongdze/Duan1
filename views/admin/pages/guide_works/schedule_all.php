<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';
?>
<main class="wrapper">
  <div class="main-content">
    <div class="page-header mb-4">
      <h1 class="h3">Lịch làm việc của tất cả HDV</h1>
      <p class="text-muted">Danh sách tour được phân công cho từng hướng dẫn viên</p>
    </div>

    <?php foreach ($guideAssignments as $group): ?>
      <div class="card mb-4">
        <div class="card-header bg-light">
          <strong><?= htmlspecialchars($group['guide']['full_name']) ?></strong>
          — <?= htmlspecialchars($group['guide']['email']) ?> | <?= htmlspecialchars($group['guide']['phone']) ?>
        </div>
        <div class="card-body">
          <?php if (empty($group['assignments'])): ?>
            <p class="text-muted">Chưa có tour nào được phân công.</p>
          <?php else: ?>
            <div class="table-responsive">
              <table class="table table-bordered">
                <thead>
                  <tr><th>Tour</th><th>Thời gian</th><th>Trạng thái</th><th></th></tr>
                </thead>
                <tbody>
                  <?php foreach ($group['assignments'] as $a): ?>
                    <tr>
                      <td><?= htmlspecialchars($a['tour_name']) ?></td>
                      <td><?= htmlspecialchars($a['start_date']) ?> - <?= htmlspecialchars($a['end_date']) ?></td>
                      <td><?= htmlspecialchars($a['status']) ?></td>
                      <td>
                        <a href="<?= BASE_URL_ADMIN ?>&action=guide/tourDetail&id=<?= $a['tour_id'] ?>&guide_id=<?= $group['guide']['id'] ?>" class="btn btn-sm btn-info">
                          <i class="fas fa-eye"></i> Chi tiết
                        </a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</main>
<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>
