<?php
// require_once 'models/Tour.php';
// require_once 'models/Supplier.php';

class TourController
{
    protected $model;

    public function __construct()
    {
        $this->model = new Tour();
    }

    public function index()
    {
        $filters = [
            'keyword' => trim($_GET['keyword'] ?? ''),
            'type' => $_GET['type'] ?? '',
            'supplier_id' => $_GET['supplier_id'] ?? '',
            'date_from' => $_GET['date_from'] ?? '',
            'date_to' => $_GET['date_to'] ?? '',
        ];

        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $perPage = isset($_GET['per_page']) ? max(5, min(50, (int)$_GET['per_page'])) : 10;
        $sortBy = 'created_at';
        $sortOrder = 'DESC';

        $result = $this->model->getFilteredTours($filters, $page, $perPage, $sortBy, $sortOrder);
        $tours = $result['data'];
        $pagination = [
            'total' => $result['total'],
            'page' => $result['page'],
            'per_page' => $result['per_page'],
            'total_pages' => $result['total_pages'],
        ];

        $supplierModel = new Supplier();
        $suppliers = $supplierModel->select();

        $filters['supplier_id'] = $filters['supplier_id'] !== '' ? (int)$filters['supplier_id'] : '';

        require_once PATH_VIEW_ADMIN . 'pages/tours/index.php';
    }

    public function create()
    {
        // Load suppliers for supplier dropdown
        $supplierModel = new Supplier();
        $suppliers = $supplierModel->select();

        require_once PATH_VIEW_ADMIN . 'pages/tours/create.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        // Validate input
        $name = $_POST['name'] ?? '';
        $type = $_POST['type'] ?? '';
        $description = $_POST['description'] ?? '';
        $base_price = $_POST['base_price'] ?? 0;
        $policy = $_POST['policy'] ?? '';
        $supplier_id = !empty($_POST['supplier_id']) ? intval($_POST['supplier_id']) : null;

        if (empty($name) || empty($type)) {
            $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin!';
            header('Location:' . BASE_URL_ADMIN . '&action=tours/create');
            return;
        }

        try {
            $imagePath = $this->storeUploadedFile($_FILES['image'] ?? null);
            $galleryImages = $this->handleGalleryUploads($_FILES['gallery_images'] ?? null);
            $pricingOptions = $this->buildPricingPayload($_POST);
            $itinerarySchedule = $this->buildItineraryPayload($_POST);
            $partnerServices = $this->buildPartnerPayload($_POST);

            $tourId = $this->model->create([
                'name' => $name,
                'type' => $type,
                'description' => $description,
                'base_price' => $base_price,
                'policy' => $policy,
                'supplier_id' => $supplier_id,
                'image' => $imagePath,
                'pricing_options' => !empty($pricingOptions) ? json_encode($pricingOptions, JSON_UNESCAPED_UNICODE) : null,
                'itinerary_schedule' => !empty($itinerarySchedule) ? json_encode($itinerarySchedule, JSON_UNESCAPED_UNICODE) : null,
                'partner_services' => !empty($partnerServices) ? json_encode($partnerServices, JSON_UNESCAPED_UNICODE) : null,
                'gallery_images' => !empty($galleryImages) ? json_encode($galleryImages, JSON_UNESCAPED_UNICODE) : null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            if ($tourId) {
                $_SESSION['success'] = 'Thêm tour thành công!';
                header('Location:' . BASE_URL_ADMIN . '&action=tours');
            } else {
                throw new Exception('Không thể thêm tour');
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Có lỗi xảy ra: ' . $e->getMessage();
            header('Location:' . BASE_URL_ADMIN . '&action=tours/create');
        }
    }

    public function edit()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location:' . BASE_URL_ADMIN . '&action=tours');
            return;
        }

        $tour = $this->model->findById($id);
        if (!$tour) {
            $_SESSION['error'] = 'Không tìm thấy tour!';
            header('Location:' . BASE_URL_ADMIN . '&action=tours');
            return;
        }

        // Load suppliers for supplier dropdown
        $supplierModel = new Supplier();
        $suppliers = $supplierModel->select();

        $pricingOptions = !empty($tour['pricing_options'])
            ? json_decode($tour['pricing_options'], true)
            : [];
        $itinerarySchedule = !empty($tour['itinerary_schedule'])
            ? json_decode($tour['itinerary_schedule'], true)
            : [];
        $partnerServices = !empty($tour['partner_services'])
            ? json_decode($tour['partner_services'], true)
            : [];
        $galleryImages = !empty($tour['gallery_images'])
            ? json_decode($tour['gallery_images'], true)
            : [];

        require_once PATH_VIEW_ADMIN . 'pages/tours/edit.php';
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $id = $_POST['id'] ?? null;
        if (!$id) {
            header('Location:' . BASE_URL_ADMIN . '&action=tours');
            return;
        }

        // Validate input
        $name = $_POST['name'] ?? '';
        $type = $_POST['type'] ?? '';
        $description = $_POST['description'] ?? '';
        $base_price = $_POST['base_price'] ?? 0;
        $policy = $_POST['policy'] ?? '';
        $supplier_id = !empty($_POST['supplier_id']) ? intval($_POST['supplier_id']) : null;

        if (empty($name) || empty($type)) {
            $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin!';
            header('Location:' . BASE_URL_ADMIN . '&action=tours/edit&id=' . $id);
            return;
        }

        try {
            $imagePath = $this->storeUploadedFile($_FILES['image'] ?? null);
            $galleryImages = $this->handleGalleryUploads($_FILES['gallery_images'] ?? null);

            $updateData = [
                'name' => $name,
                'type' => $type,
                'description' => $description,
                'base_price' => $base_price,
                'policy' => $policy,
                'supplier_id' => $supplier_id,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            if (!empty($imagePath)) $updateData['image'] = $imagePath;
            if (!empty($galleryImages)) {
                $updateData['gallery_images'] = json_encode($galleryImages, JSON_UNESCAPED_UNICODE);
            }
            if (isset($_POST['pricing_label'])) {
                $pricingOptions = $this->buildPricingPayload($_POST);
                $updateData['pricing_options'] = !empty($pricingOptions)
                    ? json_encode($pricingOptions, JSON_UNESCAPED_UNICODE)
                    : null;
            }
            if (isset($_POST['itinerary_day'])) {
                $itinerarySchedule = $this->buildItineraryPayload($_POST);
                $updateData['itinerary_schedule'] = !empty($itinerarySchedule)
                    ? json_encode($itinerarySchedule, JSON_UNESCAPED_UNICODE)
                    : null;
            }
            if (isset($_POST['partner_service'])) {
                $partnerServices = $this->buildPartnerPayload($_POST);
                $updateData['partner_services'] = !empty($partnerServices)
                    ? json_encode($partnerServices, JSON_UNESCAPED_UNICODE)
                    : null;
            }

            $result = $this->model->updateById($id, $updateData);

            if ($result) {
                $_SESSION['success'] = 'Cập nhật tour thành công!';
                header('Location:' . BASE_URL_ADMIN . '&action=tours');
            } else {
                throw new Exception('Không thể cập nhật tour');
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Có lỗi xảy ra: ' . $e->getMessage();
            header('Location:' . BASE_URL_ADMIN . '&action=tours/edit&id=' . $id);
        }
    }

    public function delete()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location:' . BASE_URL_ADMIN . '&action=tours');
            return;
        }

        try {
            $result = $this->model->deleteById($id);
            if ($result) {
                $_SESSION['success'] = 'Xóa tour thành công!';
            } else {
                throw new Exception('Không thể xóa tour');
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Có lỗi xảy ra: ' . $e->getMessage();
        }

        header('Location:' . BASE_URL_ADMIN . '&action=tours');
    }
    public function detail() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: ?action=tours');
            return;
        }
        $tour = $this->model->findById($id);
        if(!$tour) {
            $_SESSION['error'] = 'Không tìm thấy tour!';
            header('Location: ?action=tours');
            return;
        }
        require_once PATH_VIEW_ADMIN .'pages/tours/detail.php';
    }

    private function storeUploadedFile(?array $file): ?string
    {
        if (empty($file) || ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            return null;
        }

        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $origName = $file['name'] ?? '';
        $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
        if (empty($ext) || !in_array($ext, $allowed, true)) {
            throw new Exception('Định dạng ảnh không hợp lệ. Vui lòng tải lên jpg, png, gif hoặc webp.');
        }

        $uploadDir = PATH_ROOT . 'assets/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $newName = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
        $dest = $uploadDir . $newName;
        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            throw new Exception('Không thể lưu ảnh. Vui lòng thử lại.');
        }

        return BASE_ASSETS_UPLOADS . $newName;
    }

    private function handleGalleryUploads(?array $files): array
    {
        if (empty($files) || empty($files['name']) || !is_array($files['name'])) {
            return [];
        }

        $images = [];
        foreach ($files['name'] as $index => $name) {
            if (empty($name)) {
                continue;
            }
            $singleFile = [
                'name' => $name,
                'type' => $files['type'][$index] ?? null,
                'tmp_name' => $files['tmp_name'][$index] ?? null,
                'error' => $files['error'][$index] ?? UPLOAD_ERR_NO_FILE,
                'size' => $files['size'][$index] ?? 0
            ];

            $path = $this->storeUploadedFile($singleFile);
            if ($path) {
                $images[] = $path;
            }
        }

        return $images;
    }

    private function buildPricingPayload(array $request): array
    {
        $labels = $request['pricing_label'] ?? [];
        $prices = $request['pricing_price'] ?? [];
        $descriptions = $request['pricing_description'] ?? [];

        if (!is_array($labels)) {
            return [];
        }

        $entries = [];
        foreach ($labels as $index => $label) {
            $label = trim((string) $label);
            $price = $prices[$index] ?? null;
            $desc = trim((string)($descriptions[$index] ?? ''));

            if ($label === '' && ($price === null || $price === '')) {
                continue;
            }

            $entries[] = [
                'label' => $label,
                'price' => is_numeric($price) ? (float)$price : null,
                'description' => $desc
            ];
        }

        return $entries;
    }

    private function buildItineraryPayload(array $request): array
    {
        $days = $request['itinerary_day'] ?? [];
        $timeStarts = $request['itinerary_time_start'] ?? [];
        $timeEnds = $request['itinerary_time_end'] ?? [];
        $titles = $request['itinerary_title'] ?? [];
        $descriptions = $request['itinerary_description'] ?? [];

        if (!is_array($days)) {
            return [];
        }

        $entries = [];
        foreach ($days as $index => $day) {
            $title = trim((string)($titles[$index] ?? ''));
            $description = trim((string)($descriptions[$index] ?? ''));
            $dayValue = trim((string)$day);
            $timeStart = trim((string)($timeStarts[$index] ?? ''));
            $timeEnd = trim((string)($timeEnds[$index] ?? ''));

            if ($dayValue === '' && $title === '' && $description === '') {
                continue;
            }

            $entries[] = [
                'day' => $dayValue !== '' ? $dayValue : null,
                'time_start' => $timeStart !== '' ? $timeStart : null,
                'time_end' => $timeEnd !== '' ? $timeEnd : null,
                'title' => $title,
                'description' => $description
            ];
        }

        return $entries;
    }

    private function buildPartnerPayload(array $request): array
    {
        $services = $request['partner_service'] ?? [];
        $names = $request['partner_name'] ?? [];
        $contacts = $request['partner_contact'] ?? [];
        $notes = $request['partner_notes'] ?? [];

        if (!is_array($services)) {
            return [];
        }

        $entries = [];
        foreach ($services as $index => $service) {
            $name = trim((string)($names[$index] ?? ''));
            $contact = trim((string)($contacts[$index] ?? ''));
            $note = trim((string)($notes[$index] ?? ''));
            $serviceType = trim((string)$service) ?: 'other';

            if ($name === '' && $contact === '' && $note === '') {
                continue;
            }

            $entries[] = [
                'service_type' => $serviceType,
                'name' => $name,
                'contact' => $contact,
                'notes' => $note
            ];
        }

        return $entries;
    }
}
