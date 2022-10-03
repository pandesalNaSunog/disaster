<?php
    include('server.php');
    if(secured()){
        include('connection.php');
        $con = connect();
        $query = "SELECT * FROM barangays ORDER BY barangay ASC";
        $barangays = array();
        $barangayQuery = $con->query($query) or die($con->error);
        while($barangayRow = $barangayQuery->fetch_assoc()){
            $barangays[] = $barangayRow;
        }

        echo json_encode($barangays);
    }else{
        showError();
    }
?>