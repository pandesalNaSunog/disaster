<?php
    include('server.php');
    if(secured()){
        include('connection.php');
        $con = connect();
        if(isset($_GET)){
            $query = "SELECT * FROM disaster_categories";
            $category = $con->query($query) or die($con->error);
            $categories = array();
            while($categoriesRow = $category->fetch_assoc()){
                $categories[] = $categoriesRow;
            }
            echo json_encode($categories);
        }
    }else{
        showError();
    }
?>