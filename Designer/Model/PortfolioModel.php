<?php
require_once __DIR__ . '/connexion.php';
class PortfolioModel extends Connexion{
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

  // General portfolio samples only — proposal_id IS NULL means it's not attached to any specific proposal
  function getSamplesByDesigner($designerId){
    $db = $this->connectdb();
    $query = "SELECT id, image_path, caption, created_at
              FROM portfolio_samples
              WHERE designer_id = :designer_id AND proposal_id IS NULL
              ORDER BY created_at DESC";
    $stmt = $db->prepare($query);
    $stmt->execute(['designer_id' => $designerId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  function insertSample($designerId, $imagePath, $caption){
    $db = $this->connectdb();
    $query = "INSERT INTO portfolio_samples (designer_id, proposal_id, image_path, caption, created_at)
              VALUES (:designer_id, NULL, :image_path, :caption, NOW())";
    $stmt = $db->prepare($query);
    return $stmt->execute([
      'designer_id' => $designerId,
      'image_path' => $imagePath,
      'caption' => $caption,
    ]);
  }

  function deleteSample($sampleId, $designerId){
    $db = $this->connectdb();
    $query = "DELETE FROM portfolio_samples WHERE id = :id AND designer_id = :designer_id AND proposal_id IS NULL";
    $stmt = $db->prepare($query);
    $stmt->execute(['id' => $sampleId, 'designer_id' => $designerId]);
    return $stmt->rowCount() > 0;
  }
}
?>