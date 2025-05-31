<?php
session_start();


if($_SESSION['isloggedin'] != 1){
    header("Location:login1.php");
}
else if($_SESSION['role_id'] ==2 ){
    header("Location:home.php");
}else if($_SESSION['role_id'] == 1 ){
    header("Location:admin.php");
    
}

?>