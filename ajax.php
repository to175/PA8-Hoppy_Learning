<?php
session_start();
if(isset($_POST['lecture'])&&isset($_SESSION['user'])){
  
  include 'bdd.php';
  $req = $pdo->prepare("SELECT Suivi_ID FROM hoppy_suivi WHERE Lecture_ID=:lecture AND User_ID=:id");
  $req->execute(array(
    ':lecture' => $_POST['lecture'],
    ':id' => $_SESSION['id']
  ));
  
  if($req->rowCount()==0){//existe pas
    $req = $pdo->prepare("INSERT INTO hoppy_suivi (Lecture_ID, User_ID, Course_DateBegin) 
                          VALUES (:lecture, :id, NOW())");
    $req->execute(array(
      ':lecture' => $_POST['lecture'],
      ':id' => $_SESSION['id']
    ));
  }
  
}else if(isset($_POST['deleteS'])&&isset($_SESSION['id'])){
  include 'bdd.php';
  $req = $pdo->prepare("DELETE FROM hoppy_suivi WHERE Lecture_ID=:lecture AND User_ID=:id");
  $req->execute(array(
    ':lecture' => $_POST['deleteS'],
    ':id' => $_SESSION['id']
  ));
}else{
  header('Location: login.php');
}



?>