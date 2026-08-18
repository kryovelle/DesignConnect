<?php
require_once __DIR__ . '/../Model/PortfolioModel.php';
require_once __DIR__ . '/../View/PortfolioView.php';
require_once __DIR__ . '/Authentification.php';

class PortfolioController extends Authentification {

  function displayPortfolio(){
    $this->requireLogin();
    $designerId = $this->getDesignerId();

    $model = new PortfolioModel();
    $samples = $model->getSamplesByDesigner($designerId);

    $modelC = new DashboardModel();

    $name=$this->getDesignerName();
    $view = new PortfolioView();
     $avatar= $this->getUserAvatar();
    $view->displayPortfolioView($samples,$name,$avatar);
  }

  function addSample($caption){
    $this->requireLogin();
    $designerId = $this->getDesignerId();

    $imagePath = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK){
      $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
      $filename = uniqid('sample_') . '.' . $ext;
      $destination = __DIR__ . '/../../images/' . $filename;
      if (move_uploaded_file($_FILES['photo']['tmp_name'], $destination)){
        $imagePath = $filename;
      }
    }

    if ($imagePath === null){
      $_SESSION['success'] = 0;
      header('Location: /DesignConnect/Designer/Portfolio/');
      exit;
    }

    $model = new PortfolioModel();
    $ok = $model->insertSample($designerId, $imagePath, $caption);

    $_SESSION['success'] = $ok ? 1 : 0;
    header('Location: /DesignConnect/Designer/Portfolio/');
    exit;
  }

  function deleteSample($sampleId){
    $this->requireLogin();
    $designerId = $this->getDesignerId();

    $model = new PortfolioModel();
    $model->deleteSample($sampleId, $designerId);

    header('Location: /DesignConnect/Designer/Portfolio/');
    exit;
  }
}
?>