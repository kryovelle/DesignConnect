<?php
require_once __DIR__ . '/../Model/DashboardModel.php';
require_once __DIR__ . '/../View/DashboardView.php';
require_once __DIR__ . '/Authentification.php';

class DashboardController extends Authentification {

  function displayDashboard(){
    $this->requireLogin();
    $designerId = $this->getDesignerId();

    $model = new DashboardModel();
    $proposals = $model->getProposalsByDesigner($designerId);

    foreach ($proposals as &$p){
      $p['sent_ago'] = $this->timeAgo($p['created_at']);
    }
    unset($p);

    $stats = ['total' => count($proposals), 'open' => 0, 'in_progress' => 0, 'completed' => 0];
    foreach ($proposals as $p){
      if (isset($stats[$p['status']])) $stats[$p['status']]++;
    }
    $name=$this->getDesignerName();

    $view = new DashboardView();
     $avatar= $this->getUserAvatar();
    $view->displayDashboardView( $proposals, $stats,$name,$avatar);
  }

  function editProposal($proposalId, $data){
    $this->requireLogin();
    $designerId = $this->getDesignerId();

    $model = new DashboardModel();
    $updated = $model->updateProposal($proposalId, $designerId, $data);

    $_SESSION['success'] = $updated ? 1 : 0;
    header('Location: /DesignConnect/Designer/Dashboard/');
    exit;
  }

  private function timeAgo($datetime){
    $diff = time() - strtotime($datetime);
    if ($diff < 60) return 'just now';
    if ($diff < 3600){ $m = floor($diff/60); return $m . ' minute' . ($m > 1 ? 's' : '') . ' ago'; }
    if ($diff < 86400){ $h = floor($diff/3600); return $h . ' hour' . ($h > 1 ? 's' : '') . ' ago'; }
    if ($diff < 604800){ $d = floor($diff/86400); return $d . ' day' . ($d > 1 ? 's' : '') . ' ago'; }
    $w = floor($diff/604800);
    return $w . ' week' . ($w > 1 ? 's' : '') . ' ago';
  }
}
?>