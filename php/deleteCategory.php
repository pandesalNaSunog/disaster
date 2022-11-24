<?php
    include('server.php');
    if(secured()){
        include('connection.php');
        $con = connect();

        if(isset($_POST)){
            $categoryId = htmlspecialchars($_POST['category_id']);

            $query = "DELETE FROM posts WHERE disaster_category_id = '$categoryId'";
            $con->query($query) or die($con->error);

            $query = "DELETE FROM disaster_categories WHERE id = '$categoryId'";
            $con->query($query) or die($con->error);
            echo 'ok';
        }
    }else{
        showError();
    }
?>