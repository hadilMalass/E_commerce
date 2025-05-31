<?php
$conn= new mysqli("localhost","root","","liliastore");
if ($conn -> connect_error)
    //die y3ne wa2f ma ba2 ykafe
    die("unable to connect to Mysql". $conn -> connect_error);
    $result=$conn;
    ?>