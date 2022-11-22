<?php
    include('server.php');
    if(secured()){
        include('connection.php');
        $con = connect();

        if(isset($_POST)){
            $id = htmlspecialchars($_POST['id']);
            $query = "DELETE FROM barangays WHERE id = '$id'";
            $con->query($query) or die($con->error);
            echo 'ok';
        }
    }else{
        showError();
    }
?>