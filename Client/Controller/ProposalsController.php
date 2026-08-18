<?php
require_once __DIR__ . '/Authentification.php';
require_once __DIR__ . '/../Model/ProposalsModel.php';
require_once __DIR__ . '/../View/ProposalsView.php';

class ProposalsController extends Authentification {
    
    function displayProposals() {
        $this->requireLogin();
        $clientId = $this->getClientId();
        
        // Get request_id from URL
        $requestId = $_GET['request_id'] ?? null;
        if (!$requestId) {
            header('Location: /DesignConnect/Client/Dashboard/');
            exit;
        }
        
        $model = new ProposalsModel();
        
        // Get request details
        $request = $model->getRequestById($requestId, $clientId);
        if (!$request) {
            header('Location: /DesignConnect/Client/Dashboard/');
            exit;
        }
        
        // Get proposals for this request
        $proposals = $model->getProposalsByRequestId($requestId);   
        $name = $this->getClientName();
        $view = new ProposalsView();
         $avatar= $this->getUserAvatar();
        $view->displayProposalsView($request, $proposals, $name,$avatar);
    }
    
    function updateRequestStatus($requestId, $status) {
        $this->requireLogin();
        $clientId = $this->getClientId();
        
        $model = new ProposalsModel();
        $updated = $model->updateRequestStatus($requestId, $clientId, $status);
        
        $_SESSION['success'] = $updated ? 1 : 0;
        header('Location: /DesignConnect/Client/viewProposals/?request_id=' . $requestId);
        exit;
    }
}
?>