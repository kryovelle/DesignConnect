<?php
require_once __DIR__ . '/../session_config.php';
require_once __DIR__ . '/Controller/DashboardController.php';
require_once __DIR__ . '/Controller/PortfolioController.php';
require_once __DIR__ . '/Controller/SettingsController.php';

if (isset($_POST['editProposal'])) {
  $data = [
    'pitch' => $_POST['pitch'],
    'portfolio_link' => $_POST['portfolio_link'],
  ];
  $controller = new DashboardController();
  $controller->editProposal($_POST['proposal_id'], $data);
}

if (isset($_POST['addSample'])) {
  $controller = new PortfolioController();
  $controller->addSample($_POST['caption'] ?? '');
}

if (isset($_POST['deleteSample'])) {
  $controller = new PortfolioController();
  $controller->deleteSample($_POST['sample_id']);
}


if (isset($_POST['updateProfile'])) {
  $controller = new SettingsController();
  $controller->updateProfile($_POST['name'], $_POST['email']);
}

if (isset($_POST['updateAvatar'])) {
  $controller = new SettingsController();
  $controller->updateAvatar();
}

if (isset($_POST['updateDesignerInfo'])) {
  $data = [
    'specialty' => $_POST['specialty'],
    'portfolio_link' => $_POST['portfolio_link'],
    'bio' => $_POST['bio'],
    'instagram' => $_POST['instagram'],
    'behance' => $_POST['behance'],
    'whatsapp' => $_POST['whatsapp'],
  ];
  $controller = new SettingsController();
  $controller->updateDesignerInfo($data);
}

if (isset($_POST['updatePassword'])) {
  $controller = new SettingsController();
  $controller->updatePassword($_POST['current_password'], $_POST['new_password'], $_POST['confirm_password']);
}

if (isset($_POST['updateNotifications'])) {
  $notifyProposal = isset($_POST['notify_new_proposal']) ? 1 : 0;
  $notifyRequest = isset($_POST['notify_new_request']) ? 1 : 0;
  $controller = new SettingsController();
  $controller->updateNotifications($notifyProposal, $notifyRequest);
}

if (isset($_POST['deleteAccount'])) {
  $controller = new SettingsController();
  $controller->deleteAccount($_POST['confirm_text']);
}
?>