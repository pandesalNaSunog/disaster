<?php
    include('server.php');
    if(secured()){
        session_start();
        include('connection.php');
        $con = connect();

        if(isset($_POST) && isset($_SESSION['admin_id'])){
            $password = htmlspecialchars($_POST['password']);

            $adminId = $_SESSION['admin_id'];

            $query = "SELECT * FROM users WHERE id = '$adminId' AND user_type = 'admin'";

            $admin = $con->query($query) or die($con->error);
            if($adminRow = $admin->fetch_assoc()){
                if(password_verify($password, $adminRow['password'])){
                    echo 'ok';
                }else{
                    echo 'invalid';
                }
            }else{
                echo 'invalid';
            }
        }else{
            echo 'session expired';
        }
    }else{
        showError();
    }
?>