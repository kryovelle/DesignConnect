<?php
class Authentification{
 function isDesigner(){
  if(isset($_SESSION['designer'])){
    return true;
  }
  return false;
 }

 function isClient(){
  if(isset($_SESSION['client'])){
    return true;
  }
  return false;
 }

 function getUserId(){

 if(isset( $_SESSION['id'])){
    return  $_SESSION['id'];
  }
  return false;
 }

 function getUserName(){
 if(isset( $_SESSION['name'])){
    return  $_SESSION['name'];
  }
  return false;
 }

 function getUserAvatar(){
   if(isset( $_SESSION['avatar'])){
    return  $_SESSION['avatar'];
  }
  return false;
 }


}

?>