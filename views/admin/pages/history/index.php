<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';

$entries = $histories ?? [];
?>
<div class="wrapper">
<main class="main-content">
	<h2 class="page-header">Lịch sử Tour</h2>

	<?php if (!empty($_SESSION['success'])): ?>
		<div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
	<?php endif; ?>
	<?php if (!empty($_SESSION['error'])): ?>
		<div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
	<?php endif; ?>

	<div class="mb-3">
		<form method="post" action="<?= BASE_URL_ADMIN ?>&action=tours_history/bulk_delete" id="bulkForm">
			<button type="submit" class="btn btn-danger" onclick="return confirm('Xóa các mục đã chọn?')">Xóa đã chọn</button>
			<a href="<?= BASE_URL_ADMIN ?>&action=tours_history/clear_all" class="btn btn-outline-danger ms-2" onclick="return confirm('Xóa toàn bộ lịch sử?')">Xóa toàn bộ</a>
			<a href="<?= BASE_URL_ADMIN ?>" class="btn btn-secondary ms-2">Quay lại</a>

			<div class="table-responsive mt-3">
				<table class="table table-striped">
					<thead>
						<tr>
							<th><input type="checkbox" id="selectAll"></th>
							<th>ID</th>
							<th>Hành động</th>
							<th>Người thực hiện</th>
							<th>Thời gian</th>
							<th>Chi tiết</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php if (empty($entries)): ?>
							<tr><td colspan="7">Chưa có lịch sử nào.</td></tr>
						<?php else: ?>
							<?php foreach ($entries as $e): ?>
								<tr>
									<td><input type="checkbox" name="ids[]" value="<?= $e['id'] ?>"></td>
									<td><?= $e['id'] ?></td>
									<td><?= htmlspecialchars($e['action'] ?? '') ?></td>
									<td><?= htmlspecialchars($e['user_name'] ?? $e['user'] ?? '') ?></td>
									<td><?= htmlspecialchars($e['created_at'] ?? $e['time'] ?? '') ?></td>
									<td><?= htmlspecialchars($e['details'] ?? $e['meta'] ?? '') ?></td>
									<td>
										<a href="<?= BASE_URL_ADMIN ?>&action=tours_history/delete&id=<?= $e['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Xóa mục này?')">Xóa</a>
									</td>
								</tr>
							<?php endforeach; ?>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
		</form>
	</div>
</main>
</div>

<?php
include_once PATH_VIEW_ADMIN . 'default/footer.php';
?>

<script>
document.getElementById('selectAll')?.addEventListener('change', function(e){
	document.querySelectorAll('input[name="ids[]"]').forEach(cb => cb.checked = e.target.checked);
});
</script>
