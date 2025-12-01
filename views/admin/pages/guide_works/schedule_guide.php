<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';
?>
<main class="wrapper">
  <div class="main-content">
    <div class="page-header mb-4">
      <h1 class="h3">Lịch làm việc của tôi</h1>
      <p class="text-muted">Các tour bạn được phân công</p>
    </div>

    <div class="card">
      <div class="card-body">
        <?php if (empty($assignments)): ?>
          <p class="text-muted">Bạn chưa có tour nào được phân công.</p>
        <?php else: ?>
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr><th>Tour</th><th>Thời gian</th><th>Trạng thái</th><th></th></tr>
              </thead>
              <tbody>
                <?php foreach ($assignments as $a): ?>
                  <tr>
                    <td><?= htmlspecialchars($a['tour_name'] ?? '') ?></td>
                    <td>
                      <?php if (!empty($a['start_date'])): ?>
                        <?= htmlspecialchars(date('d/m/Y', strtotime($a['start_date']))) ?>
                      <?php else: ?>
                        -
                      <?php endif; ?>
                      &nbsp;-&nbsp;
                      <?php if (!empty($a['end_date'])): ?>
                        <?= htmlspecialchars(date('d/m/Y', strtotime($a['end_date']))) ?>
                      <?php else: ?>
                        -
                      <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($a['status'] ?? '') ?></td>
                    <td>
                      <a href="<?= BASE_URL_ADMIN ?>&action=guide/tourDetail&id=<?= $a['tour_id'] ?>&guide_id=<?= $a['guide_id'] ?>" class="btn btn-sm btn-info">
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
  </div>
</main>
<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>
