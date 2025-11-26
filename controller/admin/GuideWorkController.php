<?php
require_once 'models/GuideWorkModel.php';

class GuideWorkController {
    public function schedule() {
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

    public function tourDetail() {
        $tourId = $_GET['id'] ?? null;
        $guideId = $_GET['guide_id'] ?? null;
        if (!$tourId || !$guideId) {
            die("Thiếu tour_id hoặc guide_id");
        }

        $tour = GuideWorkModel::getTourById($tourId);
        $assignment = GuideWorkModel::getAssignment($tourId, $guideId);
        $itineraries = GuideWorkModel::getItinerariesByTourId($tourId) ?: [];
        $logs = GuideWorkModel::getLogsByTourAndGuide($tourId, $guideId) ?: [];

        $logsByDate = [];
        foreach ($logs as $l) {
            $dayKey = substr($l['date'], 0, 10);
            $logsByDate[$dayKey][] = $l;
        }

        require_once PATH_VIEW_ADMIN . 'pages/guide_works/tour_detail.php';
    }
}
