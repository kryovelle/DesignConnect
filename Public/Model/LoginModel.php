<?php
require_once __DIR__ . '/connexion.php';
class LoginModel extends Connexion{
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

  function getUserByEmail($email){
    $db=$this->connectdb();
    $query="SELECT * from users where email=:email";
    $stmt=$db->prepare($query);
    $stmt->execute([
      'email'=>$email
    ]);
    $result=$stmt->fetch();
    return $result;
  }
}