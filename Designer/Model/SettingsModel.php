<?php
require_once __DIR__ . '/connexion.php';
class SettingsModel extends Connexion{
  function connectdb(){
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

  function getDesignerById($id){
    $db = $this->connectdb();
    $stmt = $db->prepare("SELECT * FROM users WHERE id = :id AND role = 'designer'");
    $stmt->execute(['id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  function updateProfile($id, $name, $email){
    $db = $this->connectdb();
    $stmt = $db->prepare("UPDATE users SET name = :name, email = :email WHERE id = :id");
    return $stmt->execute(['name' => $name, 'email' => $email, 'id' => $id]);
  }

  function updateDesignerInfo($id, $data){
    $db = $this->connectdb();
    $query = "UPDATE users SET specialty = :specialty, portfolio_link = :portfolio_link,
              bio = :bio, instagram = :instagram, behance = :behance, whatsapp = :whatsapp
              WHERE id = :id";
    $stmt = $db->prepare($query);
    return $stmt->execute([
      'specialty' => $data['specialty'],
      'portfolio_link' => $data['portfolio_link'],
      'bio' => $data['bio'],
      'instagram' => $data['instagram'],
      'behance' => $data['behance'],
      'whatsapp' => $data['whatsapp'],
      'id' => $id,
    ]);
  }

  function getPasswordHash($id){
    $db = $this->connectdb();
    $stmt = $db->prepare("SELECT password FROM users WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? $row['password'] : null;
  }

  function updatePassword($id, $newPassword){
    $db = $this->connectdb();
    $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
    $stmt = $db->prepare("UPDATE users SET password = :password WHERE id = :id");
    return $stmt->execute(['password' => $hashed, 'id' => $id]);
  }

  function updateNotifications($id, $notifyProposal, $notifyRequest){
    $db = $this->connectdb();
    $query = "UPDATE users SET notify_new_proposal = :np, notify_new_request = :nr WHERE id = :id";
    $stmt = $db->prepare($query);
    return $stmt->execute(['np' => $notifyProposal, 'nr' => $notifyRequest, 'id' => $id]);
  }

  function updateAvatar($id, $avatarFilename){
    $db = $this->connectdb();
    $stmt = $db->prepare("UPDATE users SET avatar = :avatar WHERE id = :id");
    return $stmt->execute(['avatar' => $avatarFilename, 'id' => $id]);
  }

  function deleteAccount($id){
    $db = $this->connectdb();
    $stmt = $db->prepare("DELETE FROM users WHERE id = :id");
    return $stmt->execute(['id' => $id]);
  }
}
?>