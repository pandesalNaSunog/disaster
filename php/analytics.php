<?php
    include('server.php');
    if(secured()){

        function generateHexColor(){
            $characters = "0123456789ABCDEF";
            $hex = "#";
            for($i = 0; $i < 6; $i++){
                $index = rand(0, strlen($characters) - 1);
                $hex .= $characters[$index];
            }
            return $hex;
        }
        include('connection.php');
        $con = connect();
        date_default_timezone_set('Asia/Manila');
        if($_SERVER['REQUEST_METHOD'] == 'GET'){
            $date = date('Y-m');
        }else{
            $year = htmlspecialchars($_POST['year']);
            $month = htmlspecialchars($_POST['month']);
            $date = $year . '-' . $month;
        }
        $query = "SELECT * FROM barangays";
        $barangay = $con->query($query) or die($con->error);
        $response = array();
        $categoryColors = array();
        while($barangayRow = $barangay->fetch_assoc()){
            $barangayId = $barangayRow['id'];
            $barangayName = $barangayRow['barangay'];
            $categories = array();

            $query = "SELECT * FROM posts WHERE barangay_id = '$barangayId' AND created_at LIKE '$date%'";
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

            $query = "SELECT * FROM disaster_categories";
            $categoryQuery = $con->query($query) or die($con->error);
            $categoryResponse = array();
            while($categoryRow = $categoryQuery->fetch_assoc()){
                $categoryId = $categoryRow['id'];
                $categoryColors[] = generateHexColor();
                $number = 0;
                $query = "SELECT * FROM posts WHERE disaster_category_id = '$categoryId' AND barangay_id = '$barangayId' AND created_at LIKE '$date%'";
                $postQuery = $con->query($query) or die($con->error);
                while($postRow = $postQuery->fetch_assoc()){
                    $number++;
                }
                $color = generateHexColor();
                $categoryResponse[] = array(
                    'category' => $categoryRow['category'],
                    'color' => $color,
                    'data' => $number
                );
            }


            $response[] = array(
                'id' => uniqid(),
                'barangay_name' => $barangayName,
                'number_of_posts' => $numberOfPosts,
                'case_closed' => $caseClosed,
                'crew_dispatched' => $crewDispatched,
                'pending' => $pending,
                'data_for_each_disaster_category' => $categoryResponse
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
                'date' => date_format(date_create($date),"M Y"),
                'total_posts' => $totalPosts,
                'total_posts_for_each_barangay' => $response
            )
        );
    }else{
        showError();
    }
?>