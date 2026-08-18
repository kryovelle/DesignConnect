<?php
require_once __DIR__ . '/../session_config.php';

require_once __DIR__ . '/Controller/HomePageController.php';
require_once __DIR__ . '/Controller/ContactController.php';
require_once __DIR__ . '/Controller/BrowseController.php';
require_once __DIR__ . '/Controller/LoginController.php';
require_once __DIR__ . '/Controller/JoinController.php';
$request = $_SERVER['REQUEST_URI'];

$request = explode('?', $request)[0];


switch ($request) {
  case '/DesignConnect/Public/':
  case '/DesignConnect/Public/HomePage/':
  case '/DesignConnect/Public/#how':
    $controller= new HomePageController();
    $controller->displayHomePage();
  break;

  case '/DesignConnect/Public/Contact/':
    $controller= new ContactController();
    $controller->displayContact();
  break;

  case '/DesignConnect/Public/Browse/':
    $controller= new BrowseController();
    $controller->displayBrowse();
  break;

  case '/DesignConnect/Public/DesignerDetail/':
    $id=$_GET['id']; 
    $controller= new BrowseController();
    $controller->displayDesignerDetail($id);
  break;

   case '/DesignConnect/Public/RequestDetail/':
    $id=$_GET['id']; 
    $controller= new BrowseController();
    $controller->displayRequestDetail($id);
  break;

  case '/DesignConnect/Public/Login/':
    $controller= new LoginController();
    $controller->displayLogin();
  break;

   case '/DesignConnect/Public/Join/':
    $controller= new JoinController();
    $controller->displayJoin();
  break;
  case '/DesignConnect/Public/post/':
    $controller = new HomePageController();
    $controller->handlePostRedirect();
    break;


  default:
       $controller= new HomePageController();
    $controller->displayHomePage();
  break;

}



?>