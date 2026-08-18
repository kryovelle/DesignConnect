<?php
class LogoutController {
  function handleLogout(){
    if (session_status() === PHP_SESSION_NONE) session_start();

    $_SESSION = [];
    session_unset();
    session_destroy();

    header('Location: /DesignConnect/Public/');
    exit;
  }
}
?>