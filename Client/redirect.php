<?php
require_once __DIR__ . '/../session_config.php';
require_once __DIR__ . '/Controller/DashboardController.php';
require_once __DIR__ . '/Controller/ProposalsController.php';
require_once __DIR__ . '/Controller/SettingsController.php';

if (isset($_POST['createRequest'])) {
    $data = [
        'title' => $_POST['title'],
        'category' => $_POST['category'],
        'description' => $_POST['description'],
        'budget' => $_POST['budget'],
        'deadline' => $_POST['deadline'],
    ];
    (new DashboardController())->createRequest($data);
}

if (isset($_POST['editRequest'])) {
    $data = [
        'title' => $_POST['title'],
        'category' => $_POST['category'],
        'description' => $_POST['description'],
        'budget' => $_POST['budget'],
        'deadline' => $_POST['deadline'],
        'status' => $_POST['status'],
    ];
    (new DashboardController())->editRequest($_POST['request_id'], $data);
}

if (isset($_POST['updateStatus'])) {
    (new ProposalsController())->updateRequestStatus($_POST['request_id'], $_POST['status']);
}

if (isset($_POST['updateProfile'])) {
    $data = ['name' => $_POST['name'], 'email' => $_POST['email']];
    (new SettingsController())->updateProfile($data);
}

if (isset($_POST['updatePassword'])) {
    (new SettingsController())->updatePassword($_POST['current_password'], $_POST['new_password'], $_POST['confirm_password']);
}

if (isset($_POST['updateNotifications'])) {
    $data = [
        'notify_new_proposal' => isset($_POST['notify_new_proposal']) ? 1 : 0,
        'notify_new_request' => isset($_POST['notify_new_request']) ? 1 : 0,
    ];
    (new SettingsController())->updateNotifications($data);
}

if (isset($_POST['deleteAccount'])) {
    (new SettingsController())->deleteAccount($_POST['confirm_delete']);
}
?>