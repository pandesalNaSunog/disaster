<?php
    include('server.php');
    if(secured()){
        include('connection.php');
        $con = connect();
        if(isset($_POST['user_id'])){
            $userId = htmlspecialchars($_POST['user_id']);
            $query = "UPDATE users SET approved = 'yes' WHERE id = '$userId'";
            $con->query($query) or die($con->error);
            echo 'ok';
        }
    }else{
        showError();
    }
?>