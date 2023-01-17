<?php
    include('server.php');
    if(secured()){
        include('connection.php');
        $con = connect();

        if(isset($_POST)){
            $categoryId = htmlspecialchars($_POST['category_id']);
            $newCategory = htmlspecialchars($_POST['new_category']);
            $query = $con->prepare('UPDATE disaster_categories SET category = ? WHERE id = ?');
            $query->bind_param('si', $newCategory, $categoryId);
            $query->execute();
            echo 'ok';
        }
    }else{
        showError();
    }
?>