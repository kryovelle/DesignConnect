<?php
require_once __DIR__ . '/../Model/ContactModel.php';
require_once __DIR__ . '/../View/ContactView.php';
require_once __DIR__ . '/./Authentification.php';
Class ContactController extends Authentification{

function displayContact(){
  $model= new ContactModel();
  $contact=$model->getContactDetails();
  $view = new ContactView();
  $name=$this->getUserName();
  $avatar=$this->getUserAvatar();
  $view->displayContactView($contact,$name,$avatar);
}

function saveContactDetails($contact_messages){
  $model=new ContactModel();
  $model->insertContactDetails($contact_messages);
  $_SESSION['success']=1;
  header('Location: /DesignConnect/Public/Contact/');
}

}

?>