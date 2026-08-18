<?php
require_once __DIR__ . '/connexion.php';
class BrowseModel extends Connexion{
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

 function getDesigners($filters = []){
  $db = $this->connectdb();
  $query = "SELECT * FROM users WHERE role='designer'";
  $params = [];

  if (!empty($filters['specialty'])){
    $query .= " AND specialty = :specialty";
    $params['specialty'] = $filters['specialty'];
  }
  if (!empty($filters['q'])){
    $query .= " AND name LIKE :q";
    $params['q'] = '%' . $filters['q'] . '%';
  }
  $query .= " ORDER BY id DESC";

  $stmt = $db->prepare($query);
  $stmt->execute($params);
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
  return $result;
}

function getRequests($filters = []){
  $db = $this->connectdb();
  $query = "SELECT * FROM requests WHERE status='open'";
  $params = [];

  if (!empty($filters['category'])){
    $query .= " AND category = :category";
    $params['category'] = $filters['category'];
  }
  if (!empty($filters['budget'])){
    [$min, $max] = array_map('floatval', explode('-', $filters['budget']));
    $query .= " AND budget BETWEEN :min AND :max";
    $params['min'] = $min;
    $params['max'] = $max;
  }
  if (!empty($filters['q_req'])){
    $query .= " AND title LIKE :q_req";
    $params['q_req'] = '%' . $filters['q_req'] . '%';
  }

  switch ($filters['sort'] ?? '') {
    case 'budget_desc':
      $query .= " ORDER BY budget DESC";
      break;
    case 'deadline_asc':
      $query .= " ORDER BY deadline ASC";
      break;
    default:
      $query .= " ORDER BY id DESC";
  }

  $stmt = $db->prepare($query);
  $stmt->execute($params);
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
  return $result;
}

  function getDesignerByID($id){
     $db=$this->connectdb();
    $query="SELECT * FROM users where id=:id and role='designer'";
    $stmt=$db->prepare($query);
    $stmt->execute(['id'=>$id]);
    $result=$stmt->fetch();
    return $result;
  }

  function getRequestByID($id){
     $db=$this->connectdb();
    $query="SELECT requests.id,title,category,description,budget,deadline,reference_image,users.name,users.specialty FROM requests join users on requests.client_id=users.id where requests.id=:id ";
    $stmt=$db->prepare($query);
    $stmt->execute(['id'=>$id]);
    $result=$stmt->fetch();
    return $result;
  }

  function insertProposal($proposal,$designer_id){
    $db=$this->connectdb();
    $query="INSERT INTO proposals(request_id,designer_id,pitch,portfolio_link,created_at) values(:request_id,:designer_id,:pitch,:portfolio_link,now())";
    $stmt=$db->prepare($query);
    $stmt->execute([
      'designer_id'=>$designer_id,
      'request_id'=>$proposal['request_id'],
      'pitch'=>$proposal['pitch'],
      'portfolio_link'=>$proposal['portfolio_link']
    ]);
    $result=$stmt->fetch();
    return $db->lastInsertId();
  }

  function insertSampleImage($designer_id,$proposal_id,$image_path ,$caption){
    $db=$this->connectdb();
    $query="INSERT INTO portfolio_samples (designer_id,proposal_id,image_path,caption,created_at) values(:designer_id,:proposal_id,:image_path,:caption,now()) ";
    $stmt=$db->prepare($query);
    $stmt->execute([
      'designer_id' => $designer_id,
      'proposal_id' =>$proposal_id,
      'image_path' =>$image_path,
      'caption' =>$caption
    ]);
     $result=$stmt->fetch();
  }

  function getSamplesById($id){
    $db=$this->connectdb();
    $query="SELECT * FROM portfolio_samples where designer_id=:id";
    $stmt = $db->prepare($query);
    $stmt->execute(['id'=>$id]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  }



}
?>