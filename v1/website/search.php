<?php

	include('backend/db/mysql.php');
	
?>

<!DOCTYPE>
<html lang="en">
<head>
	<!--================ Title ================-->
	<title>Valencia - Recycling</title>
	
	<!--================ Stylehseet ================-->
	<link rel="stylesheet" href="assets/css/style.css">
	<link href="https://fonts.googleapis.com/css?family=Gochi+Hand|Josefin+Sans&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
</head>
<body>

<!--================ NavBar ================-->
<div class="topnav" id="myTopnav">
  <a href="index.php" class="active">Home</a>
  <a href="https://www.vhstigers.org/">VHS Website</a>
  <a href="contact.php">Contact</a>
  <a href="javascript:void(0);" style="font-size:15px;" class="icon" onclick="myFunction()">&#9776;</a>
</div>

<!--================ Top Page Button ================-->
<button onclick="topFunction()" id="myBtn" title="Go to top"><i class="fas fa-arrow-up"></i></button>

<!--================ Snowflake Background ================-->
<div id="snowflakeContainer">
  <span class="snowflake"></span>
</div>

<!--================ Title ================-->
<div class="title">
  <h2>Valencia Recycling</h2>
  <p>Our mission is to increase the amount of recycling at Valencia High School. We discovered our idea when we tried to incoperate technology and sustainablility together.</p>
  <p></p>
</div>

<!--================ Leaderboards ================-->
<div class="table container-table">
	<table>
		<h2>Leaderboards</h2>
		<tr>
			<th>Student ID</th>
			<th>Bottles Collected</th>
		</tr>
		<tr>
<?php 
	
    $result = $mysqli->query("SELECT * FROM bioproject ORDER BY collected DESC LIMIT 10");
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>".$row['studentid']."</td><td>".$row['collected']."</td><tr>";
    }
    
?>
		</tr>
	<table>
</div>

<!--================ NavBar Script ================-->
<div class="table container-table" action="search.php">
	<h2>Search</h2>
	<form action="search.php" formmethod="get">
		<p>Don't see your Student Id on the leaderboard? Search below!</p>
		<input class="searchBar" type="text" name="search" placeholder="Student Id...">
	</form><br />
	<?php
	
	$studentid = $_GET['search'];
	
	?>
	<table>
		<tr>
			<th>Student Id</th>
			<th>Bottles Collected</th>
		</tr>
		<tr>
	<?php
	
	$result = $mysqli->query("SELECT * FROM bioproject WHERE studentid = $studentid");
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>".$row['studentid']."</td><td>".$row['collected']."</td><tr>";
    }
	
	?>
</div>

<!--================ NavBar Script ================-->
<script>
function myFunction() {
  var x = document.getElementById("myTopnav");
  if (x.className === "topnav") {
    x.className += " responsive";
  } else {
    x.className = "topnav";
  }
}
</script>

<!--================ Snowflakes Script ================-->
<script>
  var snowflakes = [];
  var browserWidth;
  var browserHeight;
  var numberOfSnowflakes = 50;
  var resetPosition = false;
  var enableAnimations = false;
  var reduceMotionQuery = matchMedia("(prefers-reduced-motion)");


  setAccessibilityState();
  setup();

  reduceMotionQuery.addListener(setAccessibilityState);

  function setAccessibilityState() {
    if (reduceMotionQuery.matches) {
      enableAnimations = false;
    } else {
      enableAnimations = true;
    }
  }

  function setup() {
    if (enableAnimations) {
      window.addEventListener("DOMContentLoaded", generateSnowflakes, false);
      window.addEventListener("resize", setResetFlag, false);
    }
  }

  function Snowflake(element, speed, xPos, yPos) {
    // set initial snowflake properties
    this.element = element;
    this.speed = speed;
    this.xPos = xPos;
    this.yPos = yPos;
    this.scale = 1;

    // declare variables used for snowflake's motion
    this.counter = 0;
    this.sign = Math.random() < 0.5 ? 1 : -1;

    // setting an initial opacity and size for our snowflake
    this.element.style.opacity = (.1 + Math.random()) / 3;
  }

  Snowflake.prototype.update = function () {
    this.counter += this.speed / 5000;
    this.xPos += this.sign * this.speed * Math.cos(this.counter) / 40;
    this.yPos += Math.sin(this.counter) / 40 + this.speed / 30;
    this.scale = .5 + Math.abs(10 * Math.cos(this.counter) / 20);

    setTransform(Math.round(this.xPos), Math.round(this.yPos), this.scale, this.element);

    if (this.yPos > browserHeight) {
      this.yPos = -50;
    }
  }

  function setTransform(xPos, yPos, scale, el) {
    el.style.transform = `translate3d(${xPos}px, ${yPos}px, 0) scale(${scale}, ${scale})`;
  }

  function generateSnowflakes() {
    var originalSnowflake = document.querySelector(".snowflake");
    var snowflakeContainer = originalSnowflake.parentNode;

    snowflakeContainer.style.display = "block";

    browserWidth = document.documentElement.clientWidth;
    browserHeight = document.documentElement.clientHeight;

    for (var i = 0; i < numberOfSnowflakes; i++) {

      var snowflakeClone = originalSnowflake.cloneNode(true);
      snowflakeContainer.appendChild(snowflakeClone);

      var initialXPos = getPosition(50, browserWidth);
      var initialYPos = getPosition(50, browserHeight);
      var speed = 5 + Math.random() * 40;

      var snowflakeObject = new Snowflake(snowflakeClone,
        speed,
        initialXPos,
        initialYPos);
      snowflakes.push(snowflakeObject);
    }

    snowflakeContainer.removeChild(originalSnowflake);

    moveSnowflakes();
  }

  function moveSnowflakes() {

    if (enableAnimations) {
      for (var i = 0; i < snowflakes.length; i++) {
        var snowflake = snowflakes[i];
        snowflake.update();
      }
    }

    if (resetPosition) {
      browserWidth = document.documentElement.clientWidth;
      browserHeight = document.documentElement.clientHeight;

      for (var i = 0; i < snowflakes.length; i++) {
        var snowflake = snowflakes[i];

        snowflake.xPos = getPosition(50, browserWidth);
        snowflake.yPos = getPosition(50, browserHeight);
      }

      resetPosition = false;
    }

    requestAnimationFrame(moveSnowflakes);
  }

  function getPosition(offset, size) {
    return Math.round(-1 * offset + Math.random() * (size + 2 * offset));
  }

  function setResetFlag(e) {
    resetPosition = true;
  }
</script>

<!--================ TopButton Script ================-->
<script>
// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function() {scrollFunction()};

function scrollFunction() {
  if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
    document.getElementById("myBtn").style.display = "block";
  } else {
    document.getElementById("myBtn").style.display = "none";
  }
}

// When the user clicks on the button, scroll to the top of the document
function topFunction() {
  document.body.scrollTop = 0;
  document.documentElement.scrollTop = 0;
}
</script>
</body>
</html>