<?php
require_once __DIR__ . '/connexion.php';

class JoinModel extends Connexion {

    function connectdb() {
        $dataBase = $this->connecterBDD(
            $this->name,
            $this->host,
            $this->user,
            $this->password
        );
        $dataBase->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dataBase->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        return $dataBase;
    }

    function InsertUser($user) {
        $db = $this->connectdb();
       
        $hashedPassword = password_hash($user['password'], PASSWORD_DEFAULT);

        $query = "INSERT INTO users (name, email, password, role, specialty, created_at) 
                  VALUES (:name, :email, :password, :role, :specialty, NOW())";
        $stmt = $db->prepare($query);
        $result = $stmt->execute([
            ':name'      => $user['name'],
            ':email'     => $user['email'],
            ':password'  => $hashedPassword,
            ':role'      => $user['role'],
            ':specialty' => $user['specialty'] ?: null
        ]);

        $id = $db->lastInsertId();

        return [
            'id'      => $id,
            'failed'  => $result ? 0 : 1,
            'success' => $result ? 1 : 0
        ];
    }

    function getUserByEmail($email) {
        $db = $this->connectdb();
        $query = "SELECT * FROM users WHERE email = :email";
        $stmt = $db->prepare($query);
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(); // false if not found
    }
}
?>