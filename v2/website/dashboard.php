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
if(!isset($_SESSION['token'])){
    header("Location: login.php");
} else {
    $stmt = $mysqli->prepare("SELECT count, total, timestamp, email, tip, lid, threshold FROM tokens WHERE token = ?");
    $stmt->bind_param("s", $_SESSION['token']);
    $stmt->execute();
    $arr = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    $_SESSION['email'] = $arr[0]['email'];
    $_SESSION['count'] = $arr[0]['count'];
    $_SESSION['total'] = $arr[0]['total'];
    $_SESSION['tip'] = $arr[0]['tip'];
    $_SESSION['lid'] = $arr[0]['lid'];
    $_SESSION['threshold'] = $arr[0]['threshold'];
    $_SESSION['timestamp'] = $arr[0]['timestamp'];
}

// Handle POST request
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['current'])){
        $stmt = $mysqli->prepare("UPDATE tokens SET count = ? WHERE token = ?");
        $value = 0;
        $stmt->bind_param("is", $value, $_SESSION['token']);
        $stmt->execute();
        $stmt->close();
        $_SESSION['count'] = 0;
        ?>

        <!-- Alert -->
        <div class="alert alert-success d-flex align-items-center alert-dismissible fade show" role="alert">
            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
            <div>
                The current bottle count has been cleared.
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <svg>
            <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
                <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
            </symbol>
        </svg>

        <?php
    } else if(isset($_POST['total'])){
        $stmt = $mysqli->prepare("UPDATE tokens SET total = ? WHERE token = ?");
        $value = 0;
        $stmt->bind_param("is", $value, $_SESSION['token']);
        $stmt->execute();
        $stmt->close();
        $_SESSION['total'] = 0;
        ?>

        <!-- Alert -->
        <div class="alert alert-success d-flex align-items-center alert-dismissible fade show" role="alert">
            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
            <div>
                The total bottle count has been cleared.
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <svg>
            <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
                <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
            </symbol>
        </svg>

        <?php
    } else if(isset($_POST['save'])){
        $stmt = $mysqli->prepare("UPDATE tokens SET tip = ?, lid = ?, threshold = ? WHERE token = ?");

        $tip = 0;
        if(isset($_POST['tip']) && $_POST['tip'] == "1"){
            $tip = 1;
        }

        $lid = 0;
        if(isset($_POST['lid']) && $_POST['lid'] == "1"){
            $lid = 1;
        }

        $threshold = $_POST['threshold'];
        $stmt->bind_param("iiis", $tip, $lid, $threshold, $_SESSION['token']);
        $stmt->execute();
        $stmt->close();
        $_SESSION['tip'] = $tip;
        $_SESSION['lid'] = $lid;
        $_SESSION['threshold'] = $threshold;
        ?>

        <!-- Alert -->
        <div class="alert alert-success d-flex align-items-center alert-dismissible fade show" role="alert">
            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
            <div>
                Your current settings have been saved.
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
?>

    <!-- Title -->
    <section class="text-center container">
        <div class="row py-lg-5">
            <div class="col-lg-6 col-md-8 mx-auto">
                <h1 class="fw-light">Dashboard</h1>
                <p class="lead text-muted">Remotely edit and configure Smartcan&trade; settings and notifications.</p>
            </div>
        </div>
    </section>

    <!-- Dashboard -->
    <div class="container col-xxl-8 px-4">
        <div class="card mb-3 bg-light border rounded-3">
            <div class="row g-0">
                <div class="col-md-4 d-flex align-items-center justify-content-center">
                    <figure>
                        <img src="src/profile.png" class="img-fluid img-sm rounded-circle" alt="avatar" width="200px;">
                        <figcaption class="card-text text-center pt-1"><?=$_SESSION['email']?></figcaption>
                    </figure>
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <form class="form-inline form-group" method="post">
                            <ul class="list-group list-group-flush pt-3 pb-3">
                                <li class="list-group-item active">Settings</li>
                                <li class="list-group-item">Token <span class="float-end"><?=$_SESSION['token']?></span></li>
                                <li class="list-group-item">Tip Notifications
                                    <span class="float-end">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="tip" id="tip" value="1" <?=($_SESSION['tip'] == 1) ? "checked" : ""?>>
                                        </div>
                                    </span>
                                </li>
                                <li class="list-group-item">Lid Notifications
                                    <span class="float-end">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="lid" id="lid" value="1" <?=($_SESSION['lid'] == 1) ? "checked" : ""?>>
                                        </div>
                                    </span>
                                </li>
                                <li class="list-group-item">Bottle Threshold
                                    <span class="float-end">
                                        <input value=<?=$_SESSION['threshold']?> type="range" class="form-range" name="threshold" id="threshold" oninput="this.nextElementSibling.value = this.value">
                                        <output><?=$_SESSION['threshold']?></output>
                                    </span>
                                </li>
                                <li class="list-group-item">
                                    <button type="submit" name="save" class="btn btn-primary">Save</button>
                                </li>
                            </ul>
                        </form>
                        <form method="post">
                            <ul class="list-group list-group-flush pt-3 pb-3">
                                <li class="list-group-item active">Statistics</li>
                                <li class="list-group-item">Current Count <span class="float-end"><?=$_SESSION['count']?></span></li>
                                <li class="list-group-item">Total Count <span class="float-end"><?=$_SESSION['total']?></span></li>
                                <li class="list-group-item">
                                    <button type="submit" name="current" class="btn btn-danger">Reset Current</button>
                                    <button type="submit" name="total" class="btn btn-danger">Reset Total</button>
                                </li>
                            </ul>
                        </form>
                        <p class="card-text"><small class="text-muted d-flex align-items-center justify-content-center">Last Updated <?=$_SESSION['timestamp']?></small></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php

// Footer
require_once("footer.html");
