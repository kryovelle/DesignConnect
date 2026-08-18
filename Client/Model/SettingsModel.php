<?php
require_once __DIR__ . '/connexion.php';

class SettingsModel extends Connexion {
    function connectdb() {
        $dataBase = $this->connecterBDD($this->name, $this->host, $this->user, $this->password);
        $dataBase->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dataBase->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        return $dataBase;
    }

    function getUserById($userId) {
        $db = $this->connectdb();
        $stmt = $db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function updateProfile($userId, $data) {
        $db = $this->connectdb();
        if (!empty($data['avatar'])) {
            $query = "UPDATE users SET name=:name, email=:email, avatar=:avatar WHERE id=:id";
            $params = ['name' => $data['name'], 'email' => $data['email'], 'avatar' => $data['avatar'], 'id' => $userId];
        } else {
            $query = "UPDATE users SET name=:name, email=:email WHERE id=:id";
            $params = ['name' => $data['name'], 'email' => $data['email'], 'id' => $userId];
        }
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        return $stmt->rowCount() > 0;
    }

    function getPasswordHash($userId) {
        $db = $this->connectdb();
        $stmt = $db->prepare("SELECT password FROM users WHERE id = :id");
        $stmt->execute(['id' => $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['password'] : null;
    }

    function updatePassword($userId, $hashedPassword) {
        $db = $this->connectdb();
        $stmt = $db->prepare("UPDATE users SET password = :password WHERE id = :id");
        $stmt->execute(['password' => $hashedPassword, 'id' => $userId]);
        return $stmt->rowCount() > 0;
    }

    function updateNotifications($userId, $data) {
        $db = $this->connectdb();
        $stmt = $db->prepare("UPDATE users SET notify_new_proposal=:np, notify_new_request=:nr WHERE id=:id");
        $stmt->execute(['np' => $data['notify_new_proposal'] ?? 0, 'nr' => $data['notify_new_request'] ?? 0, 'id' => $userId]);
        return $stmt->rowCount() > 0;
    }

    function deleteAccount($userId) {
        $db = $this->connectdb();
        $stmt = $db->prepare("DELETE FROM users WHERE id = :id");
        $stmt->execute(['id' => $userId]);
        return $stmt->rowCount() > 0;
    }
}
?>