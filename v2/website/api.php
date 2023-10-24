<?php

// Include file auth
const api = true;
require_once("database.php");


// Handle GET request
if($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check for auth token
    if(isset($_GET['token'])){
        // Validate auth token
        $token = $_GET['token'];
        $stmt = $mysqli->prepare("SELECT count, timestamp, email, tip, lid, threshold FROM tokens WHERE token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $arr = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        if(!$arr){
            header('HTTP/1.0 403 Forbidden');
            header('Location: login.php');
        }
        $stmt->close();

        // Validate rate limit
        if((strtotime(gmdate("Y-m-d H:i:s")) - strtotime($arr[0]['timestamp'])) < $ratelimit){
            header('HTTP/1.0 429 Too Many Requests');
            header('Location: login.php');
        }

        // Update access timestamp
        $stmt = $mysqli->prepare("UPDATE tokens SET timestamp = now() WHERE token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $stmt->close();

        // Notifications
        $_tip = (bool) $arr[0]['tip'];
        $_lid = (bool) $arr[0]['lid'];
        $_threshold = $arr[0]['threshold'];

        // Parse 'tip' data
        if(isset($_GET['tip'])){
            $tip = (bool) $_GET['tip'];
            if($tip === true && $_tip === true){
                // Alert Email
                require_once("src/phpmailer/PHPMailerAutoload.php");
                $mail = new PHPMailer;
                $mail->isSMTP();
                $mail->Host = "smtp.gmail.com";
                $mail->SMTPAuth = true;
                $mail->Username = "ltk.watermelon@gmail.com";
                $mail->Password = "festknpgoagfdhet";
                $mail->SMTPSecure = "tsl";
                $mail->Port = "587";
                $mail->setFrom("noreply@smartcan.tech", "Smartcan");
                $mail->addAddress($arr[0]['email']);
                $mail->isHTML(true);
                $mail->Subject = "Smartcan Automated Alert";
                $mail->Body = "Smartcan tipped over!";
                $mail->send();
            }
        }

        // Parse 'lid' data
        if(isset($_GET['lid'])){
            $lid = (bool) $_GET['lid'];
            if($lid === true && $_lid === true){
                // Alert Email
                require_once("src/phpmailer/PHPMailerAutoload.php");
                $mail = new PHPMailer;
                $mail->isSMTP();
                $mail->Host = "smtp.gmail.com";
                $mail->SMTPAuth = true;
                $mail->Username = "ltk.watermelon@gmail.com";
                $mail->Password = "festknpgoagfdhet";
                $mail->SMTPSecure = "tsl";
                $mail->Port = "587";
                $mail->setFrom("noreply@smartcan.tech", "Smartcan");
                $mail->addAddress($arr[0]['email']);
                $mail->isHTML(true);
                $mail->Subject = "Smartcan Automated Alert";
                $mail->Body = "Smartcan lid removed!";
                $mail->send();
            }
        }

        // Parse 'count' data
        if(isset($_GET['count'])){
            if(is_numeric($_GET['count'])){
                // Update values
                $count = (int) $_GET['count'];
                $stmt = $mysqli->prepare("UPDATE tokens SET count = ?, total = ? WHERE token = ?");
                $_count = (int) $arr[0]['count'] + $count;
                $_total = (int) $arr[0]['total'] + $count;
                $stmt->bind_param("iis", $_count, $_total, $token);
                $stmt->execute();
                $stmt->close();

                if($_count >= $_threshold){
                    // Alert Email
                    require_once("src/phpmailer/PHPMailerAutoload.php");
                    $mail = new PHPMailer;
                    $mail->isSMTP();
                    $mail->Host = "smtp.gmail.com";
                    $mail->SMTPAuth = true;
                    $mail->Username = "ltk.watermelon@gmail.com";
                    $mail->Password = "festknpgoagfdhet";
                    $mail->SMTPSecure = "tsl";
                    $mail->Port = "587";
                    $mail->setFrom("noreply@smartcan.tech", "Smartcan");
                    $mail->addAddress($arr[0]['email']);
                    $mail->isHTML(true);
                    $mail->Subject = "Smartcan Threshold Alert";
                    $mail->Body = "Smartcan threshold alert! There current count is " . $_count . " bottles, and the current total is " . $_total . " bottles.";
                    $mail->send();
                }
            }
        }

        header('Location: login.php');
    }
    else {
        header('HTTP/1.0 403 Forbidden');
        header('Location: login.php');
    }
}
else {
    header('HTTP/1.0 403 Forbidden');
    header('Location: login.php');
}