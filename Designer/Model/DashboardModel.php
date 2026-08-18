<?php
require_once __DIR__ . '/connexion.php';
class DashboardModel extends Connexion{
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

  function getProposalsByDesigner($designerId){
    $db = $this->connectdb();
    $query = "SELECT p.id AS proposal_id, p.pitch, p.portfolio_link, p.created_at,
                     r.id AS request_id, r.title, r.budget, r.status,
                     u.name AS client_name
              FROM proposals p
              JOIN requests r ON p.request_id = r.id
              JOIN users u ON r.client_id = u.id
              WHERE p.designer_id = :designer_id
              ORDER BY p.created_at DESC";
    $stmt = $db->prepare($query);
    $stmt->execute(['designer_id' => $designerId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  function updateProposal($proposalId, $designerId, $data){
    $db = $this->connectdb();
    $query = "UPDATE proposals
              SET pitch = :pitch, portfolio_link = :portfolio_link
              WHERE id = :id AND designer_id = :designer_id";
    $stmt = $db->prepare($query);
    $stmt->execute([
      'pitch' => $data['pitch'],
      'portfolio_link' => $data['portfolio_link'],
      'id' => $proposalId,
      'designer_id' => $designerId,
    ]);
    return $stmt->rowCount() > 0;
  }

   function getContactDetails(){
    $db=$this->connectdb();
    $query="SELECT * from contact_details";
    $stmt=$db->prepare($query);
    $stmt->execute();
    $result= $stmt->fetch();
    return $result;
  }
}
?>