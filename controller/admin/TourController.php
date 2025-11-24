<?php
class TourController
{
    protected $model;

    public function __construct()
    {
        $this->model = new Tour();
    }

    public function index()
    {
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $perPage = isset($_GET['per_page']) ? max(5, min(50, (int)$_GET['per_page'])) : 12;

        // Build filters from GET parameters
        $filters = [];

        // Search keyword
        if (!empty($_GET['keyword'])) {
            $filters['keyword'] = trim($_GET['keyword']);
        }

        // Category filter
        if (!empty($_GET['category_id'])) {
            $filters['category_id'] = (int)$_GET['category_id'];
        }

        // Supplier filter
        if (!empty($_GET['supplier_id'])) {
            $filters['supplier_id'] = (int)$_GET['supplier_id'];
        }

        // Date range filters
        if (!empty($_GET['date_from'])) {
            $filters['date_from'] = $_GET['date_from'];
        }
        if (!empty($_GET['date_to'])) {
            $filters['date_to'] = $_GET['date_to'];
        }

        // Price range filters
        if (!empty($_GET['price_min'])) {
            $filters['price_min'] = (float)$_GET['price_min'];
        }
        if (!empty($_GET['price_max'])) {
            $filters['price_max'] = (float)$_GET['price_max'];
        }

        // Rating filter
        if (!empty($_GET['rating_min'])) {
            $filters['rating_min'] = (float)$_GET['rating_min'];
        }

        // Sorting
        if (!empty($_GET['sort_by'])) {
            $filters['sort_by'] = $_GET['sort_by'];
            $filters['sort_dir'] = $_GET['sort_dir'] ?? 'DESC';
        }

        $result = $this->model->getAllTours($page, $perPage, $filters);
        $tours = $result['data'];
        $pagination = [
            'total' => $result['total'],
            'page' => $result['page'],
            'per_page' => $result['per_page'],
            'total_pages' => $result['total_pages'],
        ];

        // Get filter options for dropdowns
        $categoryModel = new TourCategory();
        $categories = $categoryModel->select();

        $supplierModel = new Supplier();
        $suppliers = $supplierModel->select();

        // Get statistics for header
        $stats = $this->getTourStatistics();

        require_once PATH_VIEW_ADMIN . 'pages/tours/index.php';
    }

    private function getTourStatistics()
    {
        // Total tours
        $totalTours = $this->model->count();

        // Active tours (ongoing)
        $activeTours = $this->model->getOngoingTours();

        // Total bookings
        $bookingModel = new Booking();
        $totalBookings = $bookingModel->count();

        // Average rating
        $avgRatingSql = "SELECT ROUND(AVG(rating), 1) as avg_rating FROM tour_feedbacks";
        $stmt = BaseModel::getPdo()->prepare($avgRatingSql);
        $stmt->execute();
        $avgRating = $stmt->fetch()['avg_rating'] ?? 0;

        return [
            'total_tours' => $totalTours,
            'active_tours' => $activeTours,
            'total_bookings' => $totalBookings,
            'avg_rating' => $avgRating
        ];
    }
    public function create()
    {


        // Load suppliers for supplier dropdown
        $supplierModel = new Supplier();
        $suppliers = $supplierModel->select();

        // Load categories for category dropdown
        $categoryModel = new TourCategory();
        $categories = $categoryModel->select();


        require_once PATH_VIEW_ADMIN . 'pages/tours/create.php';
    }

    public function store()
    {
        // Validate required fields
        $requiredFields = ['name', 'category_id', 'supplier_id', 'base_price'];
        foreach ($requiredFields as $field) {
            if (empty($_POST[$field])) {
                $_SESSION['error'] = "Trường {$field} là bắt buộc.";
                header('Location: ' . BASE_URL_ADMIN . '&action=tours/create');
                return;
            }
        }

        try {
            // Prepare tour basic data
            $tourData = [
                'name' => trim($_POST['name']),
                'category_id' => (int)$_POST['category_id'],
                'supplier_id' => (int)$_POST['supplier_id'],
                'description' => $_POST['description'] ?? '',
                'base_price' => (float)$_POST['base_price'],
                'policy' => $_POST['policy'] ?? '',
            ];

            // Handle image uploads
            $uploadedImages = [];
            if (!empty($_FILES['image_url']['name'][0])) {
                // Use PATH_ASSETS_UPLOADS for file system operations
                $uploadDir = PATH_ASSETS_UPLOADS . 'tours/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                foreach ($_FILES['image_url']['tmp_name'] as $index => $tmpName) {
                    if (!empty($tmpName)) {
                        $originalName = $_FILES['image_url']['name'][$index];
                        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
                        $newName = uniqid('tour_') . '.' . $extension;
                        $filePath = $uploadDir . $newName;

                        if (move_uploaded_file($tmpName, $filePath)) {
                            $uploadedImages[] = [
                                // Store only the relative path from uploads directory
                                // BASE_ASSETS_UPLOADS will be prepended when displaying
                                'path' => 'tours/' . $newName,
                                'is_main' => ($index === 0) ? true : false
                            ];
                        }
                    }
                }
            }

            // Parse JSON data from form
            $pricingOptions = json_decode($_POST['tour_pricing_options'] ?? '[]', true);
            $itineraries = json_decode($_POST['tour_itinerary'] ?? '[]', true);
            $partners = json_decode($_POST['tour_partners'] ?? '[]', true);

            // Create tour with all related data
            $tourId = $this->model->createTour($tourData, $pricingOptions, $itineraries, $partners, $uploadedImages);

            // Images were inserted inside createTour; main image is derived from `tour_gallery_images`.

            $_SESSION['success'] = 'Tour đã được tạo thành công!';
            header('Location: ' . BASE_URL_ADMIN . '&action=tours');
            exit;
        } catch (Exception $e) {
            $_SESSION['error'] = 'Có lỗi xảy ra khi tạo tour: ' . $e->getMessage();
            header('Location: ' . BASE_URL_ADMIN . '&action=tours/create');
            exit;
        }
    }

    public function edit()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'ID Tour không hợp lệ.';
            header('Location: ' . BASE_URL_ADMIN . '&action=tours');
            return;
        }

        // Load main tour
        $tour = $this->model->find('*', 'id = :id', ['id' => $id]);
        if (!$tour) {
            $_SESSION['error'] = 'Không tìm thấy Tour.';
            header('Location: ' . BASE_URL_ADMIN . '&action=tours');
            return;
        }

        // Load dropdown data
        $categoryModel = new TourCategory();
        $categories = $categoryModel->select();

        $supplierModel = new Supplier();
        $suppliers = $supplierModel->select();

        // Related entities
        $pricingModel = new TourPricing();
        $pricingOptions = $pricingModel->getByTourId($id);

        $itineraryModel = new TourItinerary();
        $itinerarySchedule = $itineraryModel->select('*', 'tour_id = :tid', ['tid' => $id], 'day_number ASC');

        $partnerModel = new TourPartner();
        $partnerServices = $partnerModel->getByTourId($id);

        $imageModel = new TourImage();
        $images = $imageModel->getByTourId($id);

        // Map images to objects {id, url} for the edit view (use public URL)
        $allImages = array_map(function ($img) {
            return [
                'id' => $img['id'] ?? null,
                'url' => BASE_ASSETS_UPLOADS . ($img['image_url'] ?? ''),
                'main' => !empty($img['main_img']) ? 1 : 0,
            ];
        }, $images ?: []);

        require_once PATH_VIEW_ADMIN . 'pages/tours/edit.php';
    }



    public function update()
    {
        $id = $_POST['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'ID Tour không hợp lệ.';
            header('Location: ' . BASE_URL_ADMIN . '&action=tours');
            return;
        }

        // Basic validation
        $requiredFields = ['name', 'category_id', 'supplier_id', 'base_price'];
        foreach ($requiredFields as $field) {
            if (empty($_POST[$field])) {
                $_SESSION['error'] = "Trường {$field} là bắt buộc.";
                header('Location: ' . BASE_URL_ADMIN . '&action=tours/edit&id=' . urlencode($id));
                return;
            }
        }

        try {
            $this->model->beginTransaction();

            // Prepare tour basic data for update
            $tourData = [
                'name' => trim($_POST['name']),
                'category_id' => (int)$_POST['category_id'],
                'supplier_id' => (int)$_POST['supplier_id'],
                'description' => $_POST['description'] ?? '',
                'base_price' => (float)$_POST['base_price'],
                'policy' => $_POST['policy'] ?? '',
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            // Update tour basic info
            $this->model->update($tourData, 'id = :id', ['id' => $id]);

            $imageModel = new TourImage();

            // 1) Handle deleted images (could be IDs or URLs)
            $deleted = $_POST['deleted_images'] ?? [];
            if (!empty($deleted) && is_array($deleted)) {
                foreach ($deleted as $del) {
                    // If numeric -> treat as id
                    if (ctype_digit((string)$del)) {
                        $img = $imageModel->find('*', 'id = :id', ['id' => $del]);
                        if ($img) {
                            $path = PATH_ASSETS_UPLOADS . ($img['image_url'] ?? '');
                            if (!empty($img['image_url']) && file_exists($path)) {
                                @unlink($path);
                            }
                            $imageModel->delete('id = :id', ['id' => $del]);
                        }
                    } else {
                        // treat as URL (relative path)
                        $img = $imageModel->find('*', 'image_url = :url AND tour_id = :tid', ['url' => $del, 'tid' => $id]);
                        if ($img) {
                            $path = PATH_ASSETS_UPLOADS . ($img['image_url'] ?? '');
                            if (!empty($img['image_url']) && file_exists($path)) {
                                @unlink($path);
                            }
                            $imageModel->delete('id = :id', ['id' => $img['id']]);
                        }
                    }
                }
            }

            // 2) Handle primary image selection (existing image id or url)
            $newPrimary = $_POST['new_primary_image_url'] ?? '';
            if (!empty($newPrimary)) {
                // reset previous main flags
                $stmt = BaseModel::getPdo()->prepare("UPDATE tour_gallery_images SET main_img = 0 WHERE tour_id = :tid");
                $stmt->execute(['tid' => $id]);

                if (ctype_digit((string)$newPrimary)) {
                    $imageModel->update(['main_img' => 1], 'id = :id', ['id' => $newPrimary]);
                } else {
                    // try match by url
                    $imageModel->update(['main_img' => 1], 'image_url = :url AND tour_id = :tid', ['url' => $newPrimary, 'tid' => $id]);
                }
            }

            // 3) Handle uploaded files: main single `image` and gallery `gallery_images[]`
            $uploadDir = PATH_ASSETS_UPLOADS . 'tours/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // If a new primary file was uploaded (single file input named `image`)
            if (!empty($_FILES['image']['tmp_name'])) {
                if (is_uploaded_file($_FILES['image']['tmp_name'])) {
                    $originalName = $_FILES['image']['name'];
                    $extension = pathinfo($originalName, PATHINFO_EXTENSION);
                    $newName = uniqid('tour_') . '.' . $extension;
                    $filePath = $uploadDir . $newName;
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $filePath)) {
                        // clear previous main flags
                        $stmt = BaseModel::getPdo()->prepare("UPDATE tour_gallery_images SET main_img = 0 WHERE tour_id = :tid");
                        $stmt->execute(['tid' => $id]);

                        // insert new image as main (append sort_order at end)
                        $maxOrder = 0;
                        $row = BaseModel::getPdo()->prepare("SELECT MAX(sort_order) as mo FROM tour_gallery_images WHERE tour_id = :tid");
                        $row->execute(['tid' => $id]);
                        $r = $row->fetch();
                        if ($r && isset($r['mo'])) $maxOrder = (int)$r['mo'];

                        $imageModel->insert([
                            'tour_id' => $id,
                            'image_url' => 'tours/' . $newName,
                            'caption' => '',
                            'main_img' => 1,
                            'sort_order' => $maxOrder + 1,
                        ]);
                    }
                }
            }

            // Multiple gallery uploads
            if (!empty($_FILES['gallery_images']) && !empty($_FILES['gallery_images']['tmp_name'])) {
                foreach ($_FILES['gallery_images']['tmp_name'] as $index => $tmpName) {
                    if (!empty($tmpName) && is_uploaded_file($tmpName)) {
                        $originalName = $_FILES['gallery_images']['name'][$index];
                        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
                        $newName = uniqid('tour_') . '.' . $extension;
                        $filePath = $uploadDir . $newName;
                        if (move_uploaded_file($tmpName, $filePath)) {
                            // insert as non-main by default
                            $maxOrder = 0;
                            $row = BaseModel::getPdo()->prepare("SELECT MAX(sort_order) as mo FROM tour_gallery_images WHERE tour_id = :tid");
                            $row->execute(['tid' => $id]);
                            $r = $row->fetch();
                            if ($r && isset($r['mo'])) $maxOrder = (int)$r['mo'];

                            $imageModel->insert([
                                'tour_id' => $id,
                                'image_url' => 'tours/' . $newName,
                                'caption' => '',
                                'main_img' => 0,
                                'sort_order' => $maxOrder + 1,
                            ]);
                        }
                    }
                }
            }

            // Parse JSON arrays for pricing/itineraries/partners and update related tables
            $pricingOptions = json_decode($_POST['tour_pricing_options'] ?? '[]', true);
            $itineraries = json_decode($_POST['tour_itinerary'] ?? '[]', true);
            $partners = json_decode($_POST['tour_partners'] ?? '[]', true);

            // For simplicity: delete existing related rows and re-insert (safer to implement upsert later)
            $pricingModel = new TourPricing();
            $itineraryModel = new TourItinerary();
            $partnerModel = new TourPartner();

            $pricingModel->delete('tour_id = :tid', ['tid' => $id]);
            foreach ($pricingOptions as $opt) {
                $pricingModel->insert([
                    'tour_id' => $id,
                    'label' => $opt['label'] ?? '',
                    'price' => $opt['price'] ?? 0,
                    'description' => $opt['description'] ?? '',
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            }

            $itineraryModel->delete('tour_id = :tid', ['tid' => $id]);
            foreach ($itineraries as $index => $it) {
                $dayNumber = $index + 1;
                if (isset($it['day']) && preg_match('/(\d+)/', $it['day'], $m)) {
                    $dayNumber = (int)$m[1];
                }
                $itineraryModel->insert([
                    'tour_id' => $id,
                    'day_label' => $it['day'] ?? '',
                    'day_number' => $dayNumber,
                    'time_start' => $it['time_start'] ?? null,
                    'time_end' => $it['time_end'] ?? null,
                    'title' => $it['title'] ?? '',
                    'description' => $it['description'] ?? '',
                    'activities' => $it['activities'] ?? '',
                    'image_url' => $it['image_url'] ?? '',
                ]);
            }

            $partnerModel->delete('tour_id = :tid', ['tid' => $id]);
            foreach ($partners as $p) {
                $partnerModel->insert([
                    'tour_id' => $id,
                    'service_type' => $p['service_type'] ?? 'other',
                    'partner_name' => $p['name'] ?? '',
                    'contact' => $p['contact'] ?? '',
                    'notes' => $p['notes'] ?? '',
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            }

            $this->model->commit();

            $_SESSION['success'] = 'Cập nhật tour thành công.';
            header('Location: ' . BASE_URL_ADMIN . '&action=tours');
            exit;
        } catch (Exception $e) {
            $this->model->rollBack();
            $_SESSION['error'] = 'Có lỗi khi cập nhật tour: ' . $e->getMessage();
            header('Location: ' . BASE_URL_ADMIN . '&action=tours/edit&id=' . urlencode($id));
            exit;
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
            $result = $this->model->removeTour($id);
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
    public function detail()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'ID Tour không hợp lệ.';
            header('Location: ' . BASE_URL_ADMIN . '&action=tours');
            return;
        }

        // Load main tour (reuse model)
        $tour = $this->model->find('*', 'id = :id', ['id' => $id]);
        if (!$tour) {
            $_SESSION['error'] = 'Không tìm thấy Tour.';
            header('Location: ' . BASE_URL_ADMIN . '&action=tours');
            return;
        }

        // Load related data for detail view
        $pricingModel = new TourPricing();
        $pricingOptions = $pricingModel->getByTourId($id);

        $itineraryModel = new TourItinerary();
        $itinerarySchedule = $itineraryModel->select('*', 'tour_id = :tid', ['tid' => $id], 'day_number ASC');

        $partnerModel = new TourPartner();
        $partnerServices = $partnerModel->getByTourId($id);

        $imageModel = new TourImage();
        $images = $imageModel->getByTourId($id);
        $allImages = array_map(function ($img) {
            return [
                'id' => $img['id'] ?? null,
                'url' => BASE_ASSETS_UPLOADS . ($img['image_url'] ?? ''),
                'main' => !empty($img['main_img']) ? 1 : 0,
            ];
        }, $images ?: []);

        // Also compute avg rating and booking count if not present
        if (!isset($tour['avg_rating'])) {
            $stmt = BaseModel::getPdo()->prepare("SELECT AVG(rating) as avg_rating FROM tour_feedbacks WHERE tour_id = :tid");
            $stmt->execute(['tid' => $id]);
            $tour['avg_rating'] = $stmt->fetch()['avg_rating'] ?? 0;
        }
        if (!isset($tour['booking_count'])) {
            $stmt = BaseModel::getPdo()->prepare("SELECT COUNT(*) as bc FROM bookings WHERE tour_id = :tid");
            $stmt->execute(['tid' => $id]);
            $tour['booking_count'] = $stmt->fetch()['bc'] ?? 0;
        }

        require_once PATH_VIEW_ADMIN . 'pages/tours/detail.php';
    }
}
