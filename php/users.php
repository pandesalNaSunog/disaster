<?php
    
    include('server.php');

    if(secured()){
        include('connection.php');
        $con = connect();

        $query = "SELECT * FROM users WHERE user_type != 'admin'";
        $user = $con->query($query) or die($con->error);
        $users = array();
        while($userRow = $user->fetch_assoc()){
            $users[] = array(
                'name' => $userRow['name'],
                'email' => $userRow['email'],
                'id' => $userRow['id'],
                'address' => $userRow['address'],
                'contact' => $userRow['contact'],
            );
        }
        echo json_encode($users);
    }else{
        echo header('HTTP/1.1 403 Forbidden');
    }
?>