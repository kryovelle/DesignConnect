<?php

Class Authentification {
  function requireLogin(){
    if (session_status() === PHP_SESSION_NONE) session_start();
     if (!isset($_SESSION['designer']) || empty($_SESSION['designer'])) {
        header('Location: /DesignConnect/Public/Login/');
        exit;
    }
  }

    function getDesignerId(){
    return $_SESSION['id'];
  }
  function getDesignerName(){
    return $_SESSION['name'];
  }
   function getUserAvatar(){
   if(isset( $_SESSION['avatar'])){
    return  $_SESSION['avatar'];
  }
  return false;
 }

}

?>