<?php
require_once 'models/GuideWorkModel.php';

class GuideWorkController
{
    public function schedule()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $role = $_SESSION['role'] ?? null;
        $userId = $_SESSION['user_id'] ?? null;

        if ($role === 'guide' && $userId) {
            $guide = GuideWorkModel::getGuideByUserId($userId);
            if (!$guide) {
                die("Không tìm thấy hướng dẫn viên.");
            }

            $assignments = GuideWorkModel::getAssignmentsByGuideId($guide['id']) ?: [];
            require_once PATH_VIEW_ADMIN . 'pages/guide_works/schedule_guide.php';
        } else {
            $guides = GuideWorkModel::getAllGuides();
            $guideAssignments = [];

            foreach ($guides as $g) {
                $assignments = GuideWorkModel::getAssignmentsByGuideId($g['id']) ?: [];
                $guideAssignments[] = [
                    'guide' => $g,
                    'assignments' => $assignments
                ];
            }

            require_once PATH_VIEW_ADMIN . 'pages/guide_works/schedule_all.php';
        }
    }

    public function tourDetail()
    {
        $tourId = $_GET['id'] ?? null;
        $guideId = $_GET['guide_id'] ?? null;
        if (!$tourId || !$guideId) {
            die("Thiếu tour_id hoặc guide_id");
        }

        $tour = GuideWorkModel::getTourById($tourId);
        $assignment = GuideWorkModel::getAssignment($tourId, $guideId);
        $itineraries = GuideWorkModel::getItinerariesByTourId($tourId) ?: [];

        require_once PATH_VIEW_ADMIN . 'pages/guide_works/tour_detail.php';
    }
}
