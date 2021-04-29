<?php
	setcookie("user", $userLoginVal->Username, time()- 3600, "/");
	header("Location: login.php");
	die();
?>