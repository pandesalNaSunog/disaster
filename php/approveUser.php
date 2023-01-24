<?php
    require 'vendor/PHPMailer/PHPMailer/src/Exception.php';
    require 'vendor/PHPMailer/PHPMailer/src/PHPMailer.php';
    require 'vendor/PHPMailer/PHPMailer/src/SMTP.php';
    require 'vendor/autoload.php';


    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    
    include('server.php');
    if(secured()){
        function sendMail($con, $email){

            $query = "SELECT * FROM creds";
            $result = $con->query($query);
            $creds = $result->fetch_assoc();
            $mail = new PHPMailer(true);
    
            $mail->SMTPDebug = 2;
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = $creds['email']; 
            $mail->Password = $creds['key']; 
            $mail->SMTPSecure = $creds['secure']; 
            $mail->Port = $creds['port'] ;
    
            $mail->setFrom($creds['email'], 'Exodus Search and Rescue, Inc.');
            $mail->addAddress($email);
            $mail->isHTML(true);
    
            $mail->Subject = 'Account Approved';
            $mail->Body = 'Your account has been approved by the administrator. You may now proceed to log in.';
            $mail->send();
        }

        include('connection.php');
        $con = connect();
        if(isset($_POST['user_id'])){
            $userId = htmlspecialchars($_POST['user_id']);
            $query = "UPDATE users SET approved = 'yes' WHERE id = '$userId'";
            $con->query($query) or die($con->error);

            $query = "SELECT * FROM users WHERE id = '$userId'";
            $result = $con->query($query);
            $user = $result->fetch_assoc();
            $email = $user['email'];
            sendMail($con, $email);
            
            echo 'ok';
        }
    }else{
        showError();
    }

    
?>