<?php
require_once __DIR__ . '/../Model/HomePageModel.php';
require_once __DIR__ . '/../Model/ContactModel.php';
require_once __DIR__ . '/../View/HomePageView.php';
require_once __DIR__ . '/./Authentification.php';
Class HomePageController extends Authentification{

function displayHomePage(){
 $name=$this->getUserName();
 $avatar=$this->getUserAvatar();
  $model= new HomePageModel();
  $modelC = new ContactModel();
  $contact= $modelC->getContactDetails();
  $requests=$model->getRequests();
  $view = new HomePageView();
  $view->displayHomePageView($contact,$name,$requests,$avatar);
}

function handlePostRedirect(){
  if($this->isClient()){
    header('Location: /DesignConnect/Client/');
    exit();
  }
  else{
    header('Location: /DesignConnect/Public/Login/');
    exit();
  }

}


}

?>