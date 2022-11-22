<?php
    include('server.php');
    if(secured()){
        include('connection.php');
        $con = connect();

        if(isset($_POST)){
            $id = htmlspecialchars($_POST['id']);
            $barangay = htmlspecialchars($_POST['barangay']);

            $query = "UPDATE barangays SET barangay = '$barangay' WHERE id = '$id'";
            $con->query($query) or die($con->error);
            echo 'ok';
        }
    }else{
        showError();
    }
?>