<?php
require_once __DIR__ . '/connexion.php';
class HomePageModel extends Connexion{
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

 function getRequests(){
  $db = $this->connectdb();
  $query = "SELECT * FROM requests WHERE status='open' limit 3";
  $stmt=$db->prepare($query);
  $stmt->execute();
  $result =$stmt->fetchAll(PDO::FETCH_ASSOC);
  return $result; 
}



}
?>