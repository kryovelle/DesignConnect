<?php
require_once __DIR__ . '/Authentification.php';
require_once __DIR__ . '/../Model/SettingsModel.php';
require_once __DIR__ . '/../View/SettingsView.php';

class SettingsController extends Authentification {

    function displaySettings() {
        $this->requireLogin();
        $clientId = $this->getClientId();

        $model = new SettingsModel();
        $user = $model->getUserById($clientId);
        if (!$user) { header('Location: /DesignConnect/Client/Dashboard/'); exit; }

        $view = new SettingsView();
         $avatar= $this->getUserAvatar();
        $view->displaySettingsView($user, $this->getClientName(),$avatar);
    }

    function updateProfile($data) {
        $this->requireLogin();
        $clientId = $this->getClientId();

        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../images/';
            $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
            $fileName = 'avatar_' . uniqid() . '.' . $ext;
            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadDir . $fileName)) {
                $data['avatar'] = $fileName; // filename only — the view prefixes /DesignConnect/images/
                $_SESSION['avatar']=$fileName;
            }
        }

        $model = new SettingsModel();
        $updated = $model->updateProfile($clientId, $data);

        if ($updated) {
            $_SESSION['name'] = $data['name']; // keep session in sync with getClientName()
        }
        $_SESSION['success'] = $updated ? 1 : 0;
        $_SESSION['message'] = $updated ? 'Profile updated successfully!' : 'No changes were made.';
        header('Location: /DesignConnect/Client/Settings/');
        exit;
    }

    function updatePassword($currentPassword, $newPassword, $confirmPassword) {
        $this->requireLogin();
        $clientId = $this->getClientId();
        $model = new SettingsModel();

        // password change is optional — only act if the new-password field was actually filled
        if ($newPassword === '') {
            $_SESSION['success'] = 0;
            $_SESSION['message'] = 'Enter a new password to update it.';
            header('Location: /DesignConnect/Client/Settings/');
            exit;
        }

        if ($newPassword !== $confirmPassword) {
            $_SESSION['success'] = 0;
            $_SESSION['message'] = 'New passwords do not match.';
            header('Location: /DesignConnect/Client/Settings/');
            exit;
        }

        $currentHash = $model->getPasswordHash($clientId);
        if (!$currentHash || !password_verify($currentPassword, $currentHash)) {
            $_SESSION['success'] = 0;
            $_SESSION['message'] = 'Current password is incorrect.';
            header('Location: /DesignConnect/Client/Settings/');
            exit;
        }

        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
        $updated = $model->updatePassword($clientId, $hashed);

        $_SESSION['success'] = $updated ? 1 : 0;
        $_SESSION['message'] = $updated ? 'Password updated successfully!' : 'Failed to update password.';
        header('Location: /DesignConnect/Client/Settings/');
        exit;
    }

    function updateNotifications($data) {
        $this->requireLogin();
        $clientId = $this->getClientId();

        $model = new SettingsModel();
        $updated = $model->updateNotifications($clientId, $data);

        $_SESSION['success'] = $updated ? 1 : 0;
        $_SESSION['message'] = $updated ? 'Notification preferences updated!' : 'No changes were made.';
        header('Location: /DesignConnect/Client/Settings/');
        exit;
    }

    function deleteAccount($confirmText) {
        $this->requireLogin();
        $clientId = $this->getClientId();

        if (strtoupper(trim($confirmText)) !== 'DELETE') {
            $_SESSION['success'] = 0;
            $_SESSION['message'] = 'Type DELETE to confirm.';
            header('Location: /DesignConnect/Client/Settings/');
            exit;
        }

        $model = new SettingsModel();
        $model->deleteAccount($clientId);

        session_unset();
        session_destroy();
        header('Location: /DesignConnect/Public/');
        exit;
    }
}
?>