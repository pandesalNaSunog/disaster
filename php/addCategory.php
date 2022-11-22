<?php
    include('server.php');
    if(secured()){
        include('connection.php');
        $con = connect();
        $today = currentDate();
        if(isset($_POST)){
            $category = htmlspecialchars($_POST['category']);

            $query = "SELECT * FROM disaster_categories WHERE category = '$category'";
            $categoryQuery = $con->query($query) or die($con->error);

            if($categoryRow = $categoryQuery->fetch_assoc()){
                echo 'exists';
            }else{
                $query = "INSERT INTO disaster_categories(`category`,`created_at`,`updated_at`)VALUES('$category','$today', '$today')";
                $con->query($query) or die($con->error);

                $query = "SELECT * FROM disaster_categories WHERE id = LAST_INSERT_ID()";
                $categoryQuery = $con->query($query) or die($con->error);
                $categoryRowTwo = $categoryQuery->fetch_assoc();

                echo json_encode($categoryRowTwo);
            }
        }
    }else{
        showError();
    }
?>