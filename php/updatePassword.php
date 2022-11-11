<?php
    include('server.php');
    if(secured()){
        include('connection.php');
        $con = connect();
        session_start();

        if(isset($_POST) && isset($_SESSION['admin_id'])){
            $adminId = $_SESSION['admin_id'];
            $password = htmlspecialchars($_POST['password']);
            $password = password_hash($password, PASSWORD_DEFAULT);
            $query = "UPDATE users SET password = '$password' WHERE id = $adminId";
            $con->query($query) or die($con->error);
            echo 'ok';
        }else{
            echo 'invalid';
        }
    }else{
        showError();
    }
?>