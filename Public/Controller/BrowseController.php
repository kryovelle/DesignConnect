<?php
require_once __DIR__ . '/../Model/BrowseModel.php';
require_once __DIR__ . '/../Model/ContactModel.php';
require_once __DIR__ . '/../View/BrowseView.php';
require_once __DIR__ . '/../View/DesignerDetailView.php';
require_once __DIR__ . '/../View/RequestDetailView.php';
require_once __DIR__ . '/Authentification.php';
Class BrowseController extends Authentification{

function displayBrowse(){
  $mode = $_GET['mode'] ?? 'designers';
  if (!in_array($mode, ['designers', 'requests'], true)){
    $mode = 'designers';
  }

  $filters = [
    'specialty' => trim($_GET['specialty'] ?? ''),
    'q'         => trim($_GET['q'] ?? ''),
    'category'  => trim($_GET['category'] ?? ''),
    'budget'    => trim($_GET['budget'] ?? ''),
    'sort'      => trim($_GET['sort'] ?? 'newest'),
    'q_req'     => trim($_GET['q_req'] ?? ''),
  ];

  $model = new BrowseModel();
  $modelC = new ContactModel();
  $contact = $modelC->getContactDetails();
  $designers = $model->getDesigners($filters);
  $requests = $model->getRequests($filters);
  $view = new BrowseView();
 $name=$this->getUserName();
  $avatar=$this->getUserAvatar();
  $view->displayBrowseView($contact, $designers, $requests, $mode, $filters,$name,$avatar);
}

function displayDesignerDetail($id){
 $model= new BrowseModel();
 $designer=$model->getDesignerByID($id);
 $samples=$model->getSamplesById($id);

 $modelC = new ContactModel();
 $contact= $modelC->getContactDetails();

 $view = new DesignerDetailView();
 $name=$this->getUserName();
 $avatar=$this->getUserAvatar();
 $view->displayDesignerDetailView($contact,$designer,$samples,$name,$avatar);

}

function displayRequestDetail($id){
  $model= new BrowseModel();
   $request=$model->getRequestByID($id);

 $modelC = new ContactModel();
  $contact= $modelC->getContactDetails();

 $view = new RequestDetailView();
 $name=$this->getUserName();
 $avatar=$this->getUserAvatar();
 $view->displayRequestDetailView($contact,$request,$name,$avatar);
}

function handleProposals($proposal) {
    if ($this->isDesigner()) {
        $designer_id = $this->getUserId();
        $model = new BrowseModel();

        try {
            $proposal_id = $model->insertProposal($proposal, $designer_id);
            
            if (!empty($proposal['sample_photo'])) {
                $model->insertSampleImage($designer_id, $proposal_id, $proposal['sample_photo'], $proposal['caption']);
            }
            $_SESSION['proposalSent'] = true;
        } catch (Exception $e) {
            error_log('insertProposal failed: ' . $e->getMessage());
            $_SESSION['alreadySent'] = true;
        }
    } else {
        $_SESSION['proposalSent'] = false;
    }

    header('Location: /DesignConnect/Public/RequestDetail/?id=' . urlencode($proposal['request_id']));
    exit;
}



}

?>