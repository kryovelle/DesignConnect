<?php
require_once __DIR__ . '/../View/LoginView.php';
require_once __DIR__ . '/../Model/LoginModel.php';
class LoginController{
  function displayLogin(){
    $view = new LoginView();
    $view->displayLoginView();
  }

 function handleLogin($user){
  $model = new LoginModel();
  $user_db = $model->getUserByEmail($user['email']);

  if ($user_db && password_verify($user['password'], $user_db['password'])) {

    $_SESSION = [];
    session_regenerate_id(true);
    $_SESSION['id'] = $user_db['id'];
    $_SESSION['name'] = $user_db['name'];
    $_SESSION['avatar'] = $user_db['avatar'];
    $role = $user_db['role'];

    if ($user['remember-me'] == 'on') {
      $_SESSION['remember'] = 1;
    }

    switch ($role) {
      case 'designer':
        $_SESSION['designer'] = true;
        $_SESSION['role'] = 'designer';
        header('Location: /DesignConnect/Designer/');
        exit;

      case 'client':
        $_SESSION['client'] = true;
        $_SESSION['role'] = 'client';
        header('Location: /DesignConnect/Client/');
        exit;

      default:
        $_SESSION['loginfailed'] = 1;
        header('Location: /DesignConnect/Public/Login/');
        exit;
    }
  }

  // only reached if $user_db was false, or password_verify() failed
  $_SESSION['loginfailed'] = 1;
  header('Location: /DesignConnect/Public/Login/');
  exit;
}
}

?>