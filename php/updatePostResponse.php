<?php
    include('server.php');
    if(secured()){
        include('connection.php');
        $con = connect();
        if(isset($_POST)){
            $postId = $_POST['post_id'];
            $response = $_POST['response'];

            $query = "UPDATE posts SET response = '$response' WHERE id = '$postId'";
            $con->query($query) or die($con->error);
            echo 'ok';
        }
    }else{
        showError();
    }
?>