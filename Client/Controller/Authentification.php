<?php

Class Authentification {
  function requireLogin(){
    if (session_status() === PHP_SESSION_NONE) session_start();
     if (!isset($_SESSION['client']) || empty($_SESSION['client'])) {
        header('Location: /DesignConnect/Public/Login/');
        exit;
    }
  }

    function getClientId(){
    return $_SESSION['id'];
  }
  function getClientName(){
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