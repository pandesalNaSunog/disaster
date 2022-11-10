<?php
    include('server.php');
    if(secured()){
        include('connection.php');
        $con = connect();
        $query = "SELECT * FROM barangays";
        $barangay = $con->query($query) or die($con->error);
        $response = array();
        while($barangayRow = $barangay->fetch_assoc()){
            $barangayId = $barangayRow['id'];
            $barangayName = $barangayRow['barangay'];
            

            $query = "SELECT * FROM posts WHERE barangay_id = '$barangayId'";
            $postQuery = $con->query($query) or die($con->error);
            $numberOfPosts = 0;

            $caseClosed = 0;
            $crewDispatched = 0;
            $pending = 0;
            while($postRow = $postQuery->fetch_assoc()){
                $numberOfPosts++;

                $status = $postRow['response'];
                if($status == 'case closed'){
                    $caseClosed++;
                }else if($status == 'crew dispatched'){
                    $crewDispatched++;
                }else{
                    $pending++;
                }
            }

            $response[] = array(
                'barangay_name' => $barangayName,
                'number_of_posts' => $numberOfPosts,
                'case_closed' => $caseClosed,
                'crew_dispatched' => $crewDispatched,
                'pending' => $pending
            );
        }

        $query = "SELECT * FROM posts";
        $postQuery = $con->query($query) or die($con->error);
        $totalPosts = 0;
        while($postRow = $postQuery->fetch_assoc()){
            $totalPosts++;
        }
        echo json_encode(
            array(
                'total_posts' => $totalPosts,
                'total_posts_for_each_barangay' => $response
            )
        );
    }else{
        showError();
    }
?>