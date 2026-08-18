<?php
require_once __DIR__ . '/connexion.php';
class ContactModel extends Connexion{
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

  function getContactDetails(){
    $db=$this->connectdb();
    $query="SELECT * from contact_details";
    $stmt=$db->prepare($query);
    $stmt->execute();
    $result= $stmt->fetch();
    return $result;
  }

  function insertContactDetails($contact_messages){
    $db=$this->connectdb();
    $query="INSERT INTO contact_messages(name,email,subject,message,created_at) values (:name,:email,:subject,:message,now())";
    $stmt=$db->prepare($query);
    $stmt->execute([
        'name'=>$contact_messages['name'],
        'email'=>$contact_messages['email'],
        'subject'=>$contact_messages['subject'],
        'message'=>$contact_messages['message']
    ]);
  }


}
?>