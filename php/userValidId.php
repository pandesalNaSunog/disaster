<?php
    include('server.php');
    if(secured()){
        include('connection.php');
        $con = connect();

        if(isset($_POST['user_id'])){
            $userId = htmlspecialchars($_POST['user_id']);
            $query = $con->prepare('SELECT valid_id, name FROM users WHERE id = ?');
            $query->bind_param('i', $userId);
            $query->execute();
            $result = $query->get_result();

            $userRow = $result->fetch_assoc();
            echo json_encode($userRow);
        }
    }else{
        showError();
    }
?>