<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';
?>
<main class="wrapper">
	<div class="main-content">
		<!-- Page Header -->
		<div class="tour-logs-header">
			<h2>Nhật ký Tour</h2>
			<a href="<?= BASE_URL_ADMIN . '&action=tour_logs/create' ?>" class="btn btn-primary">
				<i class="fas fa-plus me-2"></i>Thêm nhật ký
			</a>
		</div>

		<!-- Logs Table Card -->
		<div class="tour-logs-card">
			<div class="card-body p-0">
				<div class="table-responsive">
					<table class="tour-logs-table table table-hover align-middle mb-0">
						<thead>
							<tr>
								<th>Ngày</th>
								<th>Tour</th>
								<th>HDV</th>
								<th>Mô tả</th>
								<th>Issue</th>
								<th>Solution</th>
								<th>Phản hồi KH</th>
								<th>Thời tiết</th>
								<th>Incident</th>
								<th>Tình trạng KH</th>
								<th>Hoạt động ĐB</th>
								<th>Ghi chú xử lý</th>
								<th>Đánh giá HDV</th>
								<th>Hành động</th>
							</tr>
						</thead>
						<tbody>
							<?php if (!empty($logs)): ?>
								<?php foreach ($logs as $log): ?>
									<tr>
										<td><?= htmlspecialchars($log['date']) ?></td>
										<td><?= htmlspecialchars($log['tour_name'] ?? '') ?></td>
										<td><?= htmlspecialchars($log['guide_name'] ?? '') ?></td>

										<td>
											<span class="log-cell-truncate" title="<?= htmlspecialchars($log['description'] ?? '') ?>">
												<?= htmlspecialchars($log['description'] ?? '') ?>
											</span>
										</td>
										<td>
											<span class="log-cell-truncate" title="<?= htmlspecialchars($log['issue'] ?? '') ?>">
												<?= htmlspecialchars($log['issue'] ?? '') ?>
											</span>
										</td>
										<td>
											<span class="log-cell-truncate" title="<?= htmlspecialchars($log['solution'] ?? '') ?>">
												<?= htmlspecialchars($log['solution'] ?? '') ?>
											</span>
										</td>
										<td>
											<span class="log-cell-truncate" title="<?= htmlspecialchars($log['customer_feedback'] ?? '') ?>">
												<?= htmlspecialchars($log['customer_feedback'] ?? '') ?>
											</span>
										</td>

										<td><?= htmlspecialchars($log['weather'] ?? '') ?></td>

										<td>
											<span class="log-cell-truncate" title="<?= htmlspecialchars($log['incident'] ?? '') ?>">
												<?= htmlspecialchars($log['incident'] ?? '') ?>
											</span>
										</td>

										<td><?= htmlspecialchars($log['health_status'] ?? '') ?></td>
										<td><?= htmlspecialchars($log['special_activity'] ?? '') ?></td>

										<td>
											<span class="log-cell-truncate" title="<?= htmlspecialchars($log['handling_notes'] ?? '') ?>">
												<?= htmlspecialchars($log['handling_notes'] ?? '') ?>
											</span>
										</td>

										<td><?= htmlspecialchars($log['guide_rating'] ?? '') ?></td>

										<td>
											<div class="log-actions">
												<a class="btn btn-sm btn-outline-primary" href="<?= BASE_URL_ADMIN . '&action=tour_logs/edit&id=' . urlencode($log['id']) ?>">
													<i class="fas fa-edit"></i> Sửa
												</a>
												<form method="post" action="<?= BASE_URL_ADMIN . '&action=tour_logs/delete' ?>" onsubmit="return confirm('Bạn có chắc muốn xóa nhật ký này?')">
													<input type="hidden" name="id" value="<?= htmlspecialchars($log['id']) ?>">
													<button class="btn btn-sm btn-outline-danger">
														<i class="fas fa-trash"></i> Xóa
													</button>
												</form>
											</div>
										</td>
									</tr>
								<?php endforeach; ?>
							<?php else: ?>
								<tr>
									<td colspan="14">
										<div class="tour-logs-empty">
											<i class="fas fa-book-open"></i>
											<p>Chưa có nhật ký nào.</p>
										</div>
									</td>
								</tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</main>
<?php
include_once PATH_VIEW_ADMIN . 'default/footer.php';
?>