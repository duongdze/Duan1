<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';
?>
<main class="wrapper">
	<div class="main-content">
		<div class="container-fluid">
			<div class="position-relative mb-4" style="padding-right:24px;">
				<h2 class="mb-0">Nhật ký Tour</h2>
				<a href="<?= BASE_URL_ADMIN . '&action=tour_logs/create' ?>" class="btn btn-primary" id="btnAddLog" style="position:absolute; right:0; top:0;">Thêm nhật ký</a>
			</div>

			<div class="card mx-auto" style="max-width:1200px; border-radius:10px; box-shadow:0 6px 18px rgba(46,61,73,.08);">
				<div class="card-body p-3">
					<div class="table-responsive">
						<table class="table table-striped align-middle mb-0">
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
												<div class="text-truncate d-inline-block w-100" style="max-width:150px;" title="<?= htmlspecialchars($log['description'] ?? '') ?>"><?= htmlspecialchars($log['description'] ?? '') ?></div>
											</td>
											<td>
												<div class="text-truncate d-inline-block w-100" style="max-width:150px;" title="<?= htmlspecialchars($log['issue'] ?? '') ?>"><?= htmlspecialchars($log['issue'] ?? '') ?></div>
											</td>
											<td>
												<div class="text-truncate d-inline-block w-100" style="max-width:150px;" title="<?= htmlspecialchars($log['solution'] ?? '') ?>"><?= htmlspecialchars($log['solution'] ?? '') ?></div>
											</td>
											<td>
												<div class="text-truncate d-inline-block w-100" style="max-width:150px;" title="<?= htmlspecialchars($log['customer_feedback'] ?? '') ?>"><?= htmlspecialchars($log['customer_feedback'] ?? '') ?></div>
											</td>

											<td><?= htmlspecialchars($log['weather'] ?? '') ?></td>

											<td>
												<div class="text-truncate d-inline-block w-100" style="max-width:150px;" title="<?= htmlspecialchars($log['incident'] ?? '') ?>"><?= htmlspecialchars($log['incident'] ?? '') ?></div>
											</td>

											<td><?= htmlspecialchars($log['health_status'] ?? '') ?></td>
											<td><?= htmlspecialchars($log['special_activity'] ?? '') ?></td>

											<td>
												<div class="text-truncate d-inline-block w-100" style="max-width:150px;" title="<?= htmlspecialchars($log['handling_notes'] ?? '') ?>"><?= htmlspecialchars($log['handling_notes'] ?? '') ?></div>
											</td>

											<td><?= htmlspecialchars($log['guide_rating'] ?? '') ?></td>

											<td style="white-space:nowrap">
												<a class="btn btn-sm btn-outline-secondary" href="<?= BASE_URL_ADMIN . '&action=tour_logs/edit&id=' . urlencode($log['id']) ?>">Sửa</a>
												<form method="post" action="<?= BASE_URL_ADMIN . '&action=tour_logs/delete' ?>" style="display:inline-block;margin-left:6px" onsubmit="return confirm('Bạn có chắc muốn xóa nhật ký này?')">
													<input type="hidden" name="id" value="<?= htmlspecialchars($log['id']) ?>">
													<button class="btn btn-sm btn-danger">Xóa</button>
												</form>
											</td>
										</tr>
									<?php endforeach; ?>
								<?php else: ?>
									<tr>
										<td colspan="14" class="text-center">Chưa có nhật ký nào.</td>
									</tr>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
<?php
include_once PATH_VIEW_ADMIN . 'default/footer.php';
?>