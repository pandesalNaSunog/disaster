<?php
    include('server.php');
    if(secured()){
        session_start();
        session_unset();
        session_destroy();
        echo 'ok';
    }else{
        showError();
    }
?>