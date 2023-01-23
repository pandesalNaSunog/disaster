<?php
    include('server.php');
    if(secured()){
        include('connection.php');
        $con = connect();

        $query = "SELECT * FROM posts WHERE `read` = 'no'";
        $result = $con->query($query);
        $rows = mysqli_num_rows($result);

        $query = "UPDATE posts SET `read` = 'yes'";
        $con->query($query);
        echo $rows;
    }else{
        showError();
    }
?>