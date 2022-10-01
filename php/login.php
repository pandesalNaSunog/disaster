<?php
    session_start();
    include('connection.php');
    include('server.php');

    if(secured()){
        $con = connect();
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);
    
        $query = "SELECT * FROM users WHERE email = '$email'";
        $user = $con->query($query) or die($con->error);
        if($userRow = $user->fetch_assoc()){
            if(password_verify($password, $userRow['password'])){
                $_SESSION['admin_id'] = $userRow['id'];
                echo 'panel.html';
            }else{
                echo 'invalid';
            }
        }else{
            echo 'invalid';
        }
    }else{
        showError();
    }
    
    

?>