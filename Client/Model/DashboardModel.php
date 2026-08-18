<?php
require_once __DIR__ . '/connexion.php';

class DashboardModel extends Connexion {
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

    function getRequestsByClient($clientId) {
        $db = $this->connectdb();
        $query = "SELECT r.*, 
                         (SELECT COUNT(*) FROM proposals WHERE request_id = r.id) as proposal_count
                  FROM requests r
                  WHERE r.client_id = :client_id
                  ORDER BY r.created_at DESC";
        $stmt = $db->prepare($query);
        $stmt->execute(['client_id' => $clientId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

   function updateRequest($requestId, $clientId, $data) {
    $db = $this->connectdb();
    
    // Check if reference_image is provided
    if (isset($data['reference_image']) && !empty($data['reference_image'])) {
        $query = "UPDATE requests
                  SET title = :title, 
                      category = :category, 
                      description = :description,
                      budget = :budget,
                      deadline = :deadline,
                      status = :status,
                      reference_image = :reference_image
                  WHERE id = :id AND client_id = :client_id";
        $params = [
            'title' => $data['title'],
            'category' => $data['category'],
            'description' => $data['description'],
            'budget' => $data['budget'],
            'deadline' => $data['deadline'],
            'status' => $data['status'],
            'reference_image' => $data['reference_image'],
            'id' => $requestId,
            'client_id' => $clientId,
        ];
    } else {
        $query = "UPDATE requests
                  SET title = :title, 
                      category = :category, 
                      description = :description,
                      budget = :budget,
                      deadline = :deadline,
                      status = :status
                  WHERE id = :id AND client_id = :client_id";
        $params = [
            'title' => $data['title'],
            'category' => $data['category'],
            'description' => $data['description'],
            'budget' => $data['budget'],
            'deadline' => $data['deadline'],
            'status' => $data['status'],
            'id' => $requestId,
            'client_id' => $clientId,
        ];
    }
    
    $stmt = $db->prepare($query);
    $stmt->execute($params);
    return $stmt->rowCount() > 0;
}

    function createRequest($clientId, $data) {
    $db = $this->connectdb();
    
    $query = "INSERT INTO requests (client_id, title, category, description, budget, deadline, status, created_at, reference_image)
              VALUES (:client_id, :title, :category, :description, :budget, :deadline, 'open', NOW(), :reference_image)";
    
    $stmt = $db->prepare($query);
    $stmt->execute([
        'client_id' => $clientId,
        'title' => $data['title'],
        'category' => $data['category'],
        'description' => $data['description'],
        'budget' => $data['budget'],
        'deadline' => $data['deadline'],
        'reference_image' => $data['reference_image'] ?? null,
    ]);
    return $db->lastInsertId();
}

    function getRequestDetails($requestId, $clientId) {
        $db = $this->connectdb();
        $query = "SELECT * FROM requests WHERE id = :id AND client_id = :client_id";
        $stmt = $db->prepare($query);
        $stmt->execute(['id' => $requestId, 'client_id' => $clientId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>