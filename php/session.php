<?php
    session_start();

    if(isset($_SESSION['admin_id'])){
        echo 'ok';
    }else{
        echo 'index.html';
    }
?>