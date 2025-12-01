<?php
require_once PATH_MODEL . 'HistoryModel.php';

class TourHistoryController
{
	protected $model;

	public function __construct()
	{
		$this->model = new HistoryModel();
	}

	// List history entries
	public function index()
	{
		$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
		$perPage = 20;

		$histories = $this->model->getAll($page, $perPage);

		require_once PATH_VIEW_ADMIN . 'pages/history/index.php';
	}

	// Delete single entry
	public function delete()
	{
		$id = $_GET['id'] ?? null;
		if (!$id) {
			$_SESSION['error'] = 'ID lịch sử không hợp lệ.';
			header('Location: ' . BASE_URL_ADMIN . '&action=tours_history');
			exit;
		}

		try {
			$this->model->deleteById($id);
			$_SESSION['success'] = 'Đã xóa lịch sử.';
		} catch (Exception $e) {
			$_SESSION['error'] = 'Xóa thất bại: ' . $e->getMessage();
		}

		header('Location: ' . BASE_URL_ADMIN . '&action=tours_history');
		exit;
	}

	// Bulk delete (POST ids[])
	public function bulk_delete()
	{
		$ids = $_POST['ids'] ?? [];
		if (empty($ids) || !is_array($ids)) {
			$_SESSION['error'] = 'Vui lòng chọn ít nhất một mục để xóa.';
			header('Location: ' . BASE_URL_ADMIN . '&action=tours_history');
			exit;
		}

		try {
			$this->model->deleteByIds($ids);
			$_SESSION['success'] = 'Đã xóa các mục đã chọn.';
		} catch (Exception $e) {
			$_SESSION['error'] = 'Xóa thất bại: ' . $e->getMessage();
		}

		header('Location: ' . BASE_URL_ADMIN . '&action=tours_history');
		exit;
	}

	// Clear all history
	public function clear_all()
	{
		try {
			$this->model->clearAll();
			$_SESSION['success'] = 'Đã xóa toàn bộ lịch sử.';
		} catch (Exception $e) {
			$_SESSION['error'] = 'Thao tác thất bại: ' . $e->getMessage();
		}

		header('Location: ' . BASE_URL_ADMIN . '&action=tours_history');
		exit;
	}
}
