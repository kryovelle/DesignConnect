<?php
require_once __DIR__ . '/Authentification.php';
require_once __DIR__ . '/../Model/DashboardModel.php';
require_once __DIR__ . '/../View/DashboardView.php';

class DashboardController extends Authentification {
    function displayDashboard() {
        $this->requireLogin();
        $clientId = $this->getClientId();

        $model = new DashboardModel();
        $requests = $model->getRequestsByClient($clientId);

        // Format dates and calculate stats
        foreach ($requests as &$r) {
            $r['created_ago'] = $this->timeAgo($r['created_at']);
            $r['deadline_formatted'] = date('M d, Y', strtotime($r['deadline']));
        }
        unset($r);

        $stats = ['total' => count($requests), 'open' => 0, 'in_progress' => 0, 'completed' => 0];
        foreach ($requests as $r) {
            if (isset($stats[$r['status']])) {
                $stats[$r['status']]++;
            }
        }

        $name = $this->getClientName();
        $view = new DashboardView();
        $avatar= $this->getUserAvatar();
        $view->displayDashboardView($requests, $stats, $name,$avatar);
    }

    function editRequest($requestId, $data) {
        $this->requireLogin();
        $clientId = $this->getClientId();

        // Handle file upload for reference image in edit
          if (isset($_FILES['reference_image']) && $_FILES['reference_image']['error'] === UPLOAD_ERR_OK) {
             $uploadDir = __DIR__ . '/../../images/';
              
              // Create directory if it doesn't exist
              if (!is_dir($uploadDir)) {
                  mkdir($uploadDir, 0777, true);
              }
              
              $fileExtension = pathinfo($_FILES['reference_image']['name'], PATHINFO_EXTENSION);
              $fileName = 'ref_' . time() . '_' . uniqid() . '.' . $fileExtension;
              $uploadPath = $uploadDir . $fileName;
              
              if (move_uploaded_file($_FILES['reference_image']['tmp_name'], $uploadPath)) {
                  $data['reference_image'] = $fileName;
              }
          }

        $model = new DashboardModel();
        $updated = $model->updateRequest($requestId, $clientId, $data);

        $_SESSION['success'] = $updated ? 1 : 0;
        header('Location: /DesignConnect/Client/Dashboard/');
        exit;
    }

    function createRequest($data) {
        $this->requireLogin();
        $clientId = $this->getClientId();

                    // Handle file upload for reference image in create
            if (isset($_FILES['reference_image']) && $_FILES['reference_image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../../images/';
                
                // Create directory if it doesn't exist
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $fileExtension = pathinfo($_FILES['reference_image']['name'], PATHINFO_EXTENSION);
                $fileName = 'ref_' . time() . '_' . uniqid() . '.' . $fileExtension;
                $uploadPath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['reference_image']['tmp_name'], $uploadPath)) {
                    $data['reference_image'] = $fileName;
                }
            }

        $model = new DashboardModel();
        $newId = $model->createRequest($clientId, $data);

        $_SESSION['success'] = $newId ? 1 : 0;
        header('Location: /DesignConnect/Client/Dashboard/');
        exit;
    }

    private function timeAgo($datetime) {
        $diff = time() - strtotime($datetime);
        if ($diff < 60) return 'just now';
        if ($diff < 3600) {
            $m = floor($diff/60);
            return $m . ' minute' . ($m > 1 ? 's' : '') . ' ago';
        }
        if ($diff < 86400) {
            $h = floor($diff/3600);
            return $h . ' hour' . ($h > 1 ? 's' : '') . ' ago';
        }
        if ($diff < 604800) {
            $d = floor($diff/86400);
            return $d . ' day' . ($d > 1 ? 's' : '') . ' ago';
        }
        $w = floor($diff/604800);
        return $w . ' week' . ($w > 1 ? 's' : '') . ' ago';
    }
}
?>