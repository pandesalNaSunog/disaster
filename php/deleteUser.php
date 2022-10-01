<?php
    include('server.php');
    if(secured()){
        include('connection.php');
        $con = connect();

        if(isset($_POST)){
            $userId = $_POST['user_id'];
            $query = "SELECT * FROM users WHERE id = '$userId'";
            $user = $con->query($query) or die($con->error);
            if($userRow = $user->fetch_assoc()){
                $query = "DELETE FROM users WHERE id = '$userId'";
                $con->query($query) or die($con->error);
                echo 'ok';
            }else{
                echo 'not exists';
            }
        }
    }else{
        showError();
    }
    
?>