<?php
    function connect(){
        //return new mysqli("localhost","root","","disaster_db");
        return new mysqli("localhost","u568496919_disaster","DisasterPassword11","u568496919_disaster_db");
    }

    function currentDate(){
        date_default_timezone_set('Asia/Manila');
        return date('Y-m-d H:i:s');
    }
?>