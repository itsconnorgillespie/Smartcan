<?php

// Session start
session_start();

// Include file auth
const api = true;
require_once("database.php");
require_once("config.php");

// Header
require_once("header.php");

// Check session
if(isset($_SESSION['token'])){
    header("Location: dashboard.php");
}

// Handle POST request
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recaptcha
    if(isset($_POST['g-recaptcha-response']))
    {
        $captcha = $_POST['g-recaptcha-response'];
        $ip = $_SERVER['REMOTE_ADDR'];
        $captchaurl = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $private . '&response=' . $captcha;
        $response = file_get_contents($captchaurl);
        $responsekeys = json_decode($response, true);

        if($responsekeys['success']){
            // Check for auth token
            if(isset($_POST['token']) && $_POST['token'] != ""){
                // Validate auth token
                $token = $_POST['token'];
                $stmt = $mysqli->prepare("SELECT count, total, timestamp, email, tip, lid, threshold FROM tokens WHERE token = ?");
                $stmt->bind_param("s", $token);
                $stmt->execute();
                $arr = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                $stmt->close();
                if(!$arr){
                    ?>

                    <!-- Alert -->
                    <div class="alert alert-danger d-flex align-items-center alert-dismissible fade show" role="alert">
                        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                        <div>
                            The provided token could not be found.
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <svg>
                        <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                        </symbol>
                    </svg>

                    <?php
                }
                else {
                    $_SESSION['token'] = $token;
                    $_SESSION['email'] = $arr[0]['email'];
                    $_SESSION['count'] = $arr[0]['count'];
                    $_SESSION['total'] = $arr[0]['total'];
                    $_SESSION['tip'] = $arr[0]['tip'];
                    $_SESSION['lid'] = $arr[0]['lid'];
                    $_SESSION['threshold'] = $arr[0]['threshold'];
                    $_SESSION['timestamp'] = $arr[0]['timestamp'];
                    header("Location: dashboard.php");
                }
            }
        }
        else {
            ?>

            <!-- Alert -->
            <div class="alert alert-danger d-flex align-items-center alert-dismissible fade show" role="alert">
                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                <div>
                    The reCAPTCHA was failed.
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <svg>
                <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                </symbol>
            </svg>

            <?php
        }
    }
}

?>
    <!-- Title -->
    <section class="py-5 text-center container">
        <div class="row py-lg-5">
            <div class="col-lg-6 col-md-8 mx-auto">
                <h1 class="fw-light">Dashboard</h1>
                <p class="lead text-muted">Using a valid token, you can view all statistics and edit settings for your Smartcan&trade; remotely via our web dashboard.</p>
            </div>
        </div>
    </section>

    <!-- Form -->
    <div class="container d-flex justify-content-center">
        <form class="needs-validation bg-light p-5 rounded col-sm-8 col-md-7 p-5"  method="post">
            <input type="text" class="form-control" name="token" placeholder="Token" required autofocus>
            <div class="g-recaptcha pt-3" data-sitekey="<?=$public?>" style="transform:scale(0.77);-webkit-transform:scale(0.77);transform-origin:0 0;-webkit-transform-origin:0 0;"></div>
            <div class="checkbox mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="terms" required>
                    <label class="form-check-label" for="terms">
                        Agree to Terms and Conditions
                    </label>
                </div>
            </div>
            <button class="btn btn-primary" type="submit" data-bs-toggle="modal">Submit</button>
        </form>
    </div>

<?php

// Footer
require_once("footer.html");


