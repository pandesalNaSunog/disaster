<?php
    //$con = new mysqli("localhost","u568496919_disaster","DisasterPassword11","u568496919_disaster_db");
    $con = new mysqli("localhost","root","","disaster_db");

    $password = password_hash('password', PASSWORD_DEFAULT);
    $query = "INSERT INTO users(`email`,`password`,`user_type`)VALUES('user@admin.com','$password','admin')";
    $con->query($query) or die($con->error);
    echo 'ok';
?>