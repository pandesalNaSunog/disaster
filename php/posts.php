<?php
    include('server.php');
    if(secured()){
        include('connection.php');
        $con = connect();

        if($_SERVER['REQUEST_METHOD'] == 'GET'){
            $query = "SELECT * FROM posts ORDER BY created_at DESC";
        }else{
            $barangayId = htmlspecialchars($_POST['barangay_id']);
            $query = "SELECT * FROM posts WHERE barangay_id = '$barangayId' ORDER BY created_at DESC";
        }
        

        $posts = array();
        $post = $con->query($query) or die($con->error);
        while($postRow = $post->fetch_assoc()){
            $userId = $postRow['user_id'];

            $query = "SELECT name FROM users WHERE id = '$userId'";
            $user = $con->query($query) or die($con->error);
            $userRow = $user->fetch_assoc();
            $barangayId = $postRow['barangay_id'];

            $query = "SELECT * FROM barangays WHERE id = '$barangayId'";
            $barangay = $con->query($query) or die($con->error);
            $barangayRow = $barangay->fetch_assoc();

            $barangayName = $barangayRow['barangay'];
            $posts[] = array(
                'user' => $userRow,
                'caption' => $postRow['caption'],
                'image' => $postRow['image'],
                'response' => $postRow['response'],
                'date' => date_format(date_create($postRow['created_at']), "M d, Y h:i A"),
                'id' => $postRow['id'],
                'barangay' => $barangayName
            );
        }

        echo json_encode($posts);
    }else{
        showError();
    }
?>