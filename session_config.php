<?php
session_set_cookie_params([
    'secure'   => true,
    'httponly' => true,
    'samesite' => 'Lax'
]);
session_start();

$life = (!empty($_SESSION['remember'])) ? 1296000 : 1800;

//check the expiration of the session
if(isset($_SESSION['last_activity']) && time()-$_SESSION['last_activity']> $life ){
  session_unset();
  session_destroy();
  header('Location: /DesignConnect/Public/Login/?expired=1');
  exit();
}

$_SESSION['last_activity']=time();

// refresh the cookie's expiration so it actually slides forward
setcookie(session_name(), session_id(), time() + $life, '/', '', true, true);


?>