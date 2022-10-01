<?php
    function secured(){
        if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'){
            return true;
        }else{
            return false;
        }
    }

    function showError(){
        echo header('HTTP/1.1 403 Forbidden');
    }
?>