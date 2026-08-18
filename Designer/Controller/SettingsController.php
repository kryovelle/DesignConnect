<?php
require_once __DIR__ . '/../Model/SettingsModel.php';
require_once __DIR__ . '/../View/SettingsView.php';
require_once __DIR__ . '/Authentification.php';

class SettingsController extends Authentification {

  function displaySettings(){
    $this->requireLogin();
    $designerId = $this->getDesignerId();
    $name = $this->getDesignerName();

    $model = new SettingsModel();
    $designer = $model->getDesignerById($designerId);

    $view = new SettingsView();
     $avatar= $this->getUserAvatar();
    $view->displaySettingsView($designer, $name,$avatar);
  }

  function updateProfile($name, $email){
    $this->requireLogin();
    $designerId = $this->getDesignerId();

    $model = new SettingsModel();
    $ok = $model->updateProfile($designerId, $name, $email);

    if ($ok){
      $_SESSION['name'] = $name; // keep session in sync with getDesignerName()
    }
    $_SESSION['success'] = $ok ? 1 : 0;
    header('Location: /DesignConnect/Designer/Settings/');
    exit;
  }

  function updateDesignerInfo($data){
    $this->requireLogin();
    $designerId = $this->getDesignerId();

    $model = new SettingsModel();
    $ok = $model->updateDesignerInfo($designerId, $data);

    $_SESSION['success'] = $ok ? 1 : 0;
    header('Location: /DesignConnect/Designer/Settings/');
    exit;
  }

  function updatePassword($currentPassword, $newPassword, $confirmPassword){
    $this->requireLogin();
    $designerId = $this->getDesignerId();
    $model = new SettingsModel();

    if ($newPassword === '' || $newPassword !== $confirmPassword){
      $_SESSION['success'] = 0;
      header('Location: /DesignConnect/Designer/Settings/');
      exit;
    }

    $currentHash = $model->getPasswordHash($designerId);
    if (!$currentHash || !password_verify($currentPassword, $currentHash)){
      $_SESSION['success'] = 0;
      header('Location: /DesignConnect/Designer/Settings/');
      exit;
    }

    $ok = $model->updatePassword($designerId, $newPassword);
    $_SESSION['success'] = $ok ? 1 : 0;
    header('Location: /DesignConnect/Designer/Settings/');
    exit;
  }

  function updateNotifications($notifyProposal, $notifyRequest){
    $this->requireLogin();
    $designerId = $this->getDesignerId();

    $model = new SettingsModel();
    $ok = $model->updateNotifications($designerId, $notifyProposal, $notifyRequest);

    $_SESSION['success'] = $ok ? 1 : 0;
    header('Location: /DesignConnect/Designer/Settings/');
    exit;
  }

  function updateAvatar(){
    $this->requireLogin();
    $designerId = $this->getDesignerId();

    if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK){
      $_SESSION['success'] = 0;
      header('Location: /DesignConnect/Designer/Settings/');
      exit;
    }

    $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
    $filename = uniqid('avatar_') . '.' . $ext;
    $destination = __DIR__ . '/../../images/' . $filename;

    $model = new SettingsModel();
    if (move_uploaded_file($_FILES['avatar']['tmp_name'], $destination)){
      $model->updateAvatar($designerId, $filename);
      $_SESSION['success'] = 1;
      $_SESSION['avatar']=$filename;
    } else {
      $_SESSION['success'] = 0;
    }

    header('Location: /DesignConnect/Designer/Settings/');
    exit;
  }

  function deleteAccount($confirmText){
    $this->requireLogin();
    $designerId = $this->getDesignerId();

    if (strtoupper(trim($confirmText)) !== 'DELETE'){
      $_SESSION['success'] = 0;
      header('Location: /DesignConnect/Designer/Settings/');
      exit;
    }

    $model = new SettingsModel();
    $model->deleteAccount($designerId);

    session_unset();
    session_destroy();
    header('Location: /DesignConnect/Designer/');
    exit;
  }
}
?>