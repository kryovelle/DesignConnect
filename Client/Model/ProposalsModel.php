<?php
require_once __DIR__ . '/connexion.php';

class ProposalsModel extends Connexion {
    function connectdb() {
        $dataBase = $this->connecterBDD($this->name, $this->host, $this->user, $this->password);
        $dataBase->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dataBase->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        return $dataBase;
    }

    function getRequestById($requestId, $clientId) {
        $db = $this->connectdb();
        $stmt = $db->prepare("SELECT * FROM requests WHERE id = :id AND client_id = :client_id");
        $stmt->execute(['id' => $requestId, 'client_id' => $clientId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function getProposalsByRequestId($requestId) {
        $db = $this->connectdb();
        $query = "SELECT p.*, u.name AS designer_name, u.email AS designer_email, u.specialty,
                         u.portfolio_link, u.instagram, u.behance, u.whatsapp,
                         (SELECT GROUP_CONCAT(image_path) FROM portfolio_samples WHERE designer_id = p.designer_id AND proposal_id IS NULL LIMIT 3) as samples
                  FROM proposals p
                  JOIN users u ON p.designer_id = u.id
                  WHERE p.request_id = :request_id
                  ORDER BY p.created_at ASC";
        $stmt = $db->prepare($query);
        $stmt->execute(['request_id' => $requestId]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($results as &$r) {
            $r['samples'] = $r['samples'] ? explode(',', $r['samples']) : [];
        }
        unset($r);
        return $results;
    }

    function updateRequestStatus($requestId, $clientId, $status) {
        $db = $this->connectdb();
        $stmt = $db->prepare("UPDATE requests SET status = :status WHERE id = :id AND client_id = :client_id");
        $stmt->execute(['status' => $status, 'id' => $requestId, 'client_id' => $clientId]);
        return $stmt->rowCount() > 0;
    }
}
?>