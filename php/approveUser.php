<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    require 'vendor/autoload.php';
    include('server.php');
    if(secured()){
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

    function sendMail($con, $email){

        $query = "SELECT * FROM creds";
        $result = $con->query($query);
        $creds = $result->fetch_assoc();
        $mail = new PHPMailer(true);

        $mail->SMTPDebug = 0;
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

        $mail->Subject = 'One Time Password';
        $mail->Body = 'Your account has been approved by the administrator. You may now proceed to log in.';
    }
?>