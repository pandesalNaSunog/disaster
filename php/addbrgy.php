<?php
    include('server.php');
    if(secured()){
        include('connection.php');
        $con = connect();
        $today = currentDate();
        if(isset($_POST)){
            $barangay = htmlspecialchars($_POST['barangay']);

            $query = "SELECT * FROM barangays WHERE barangay = '$barangay'";
            $barangayQuery = $con->query($query) or die($con->error);
            if($barangayRow = $barangayQuery->fetch_assoc()){
                echo 'exists';
            }else{
                $query = "INSERT INTO barangays(`barangay`,`created_at`,`updated_at`)VALUES('$barangay','$today','$today')";
                $con->query($query) or die($con->error);

                $query = "SELECT * FROM barangays WHERE id = LAST_INSERT_ID()";
                $barangayQuery = $con->query($query) or die($con->error);
                $barangayRow = $barangayQuery->fetch_assoc();

                echo json_encode($barangayRow);
            }
        }
    }else{
        showError();
    }
?>