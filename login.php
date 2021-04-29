<?php
// Imports
include_once 'connection.php';
include_once 'tablemodel.php';
include_once 'sqlfunctions.php';	

if (isset($_POST['login'])) {
	$Username = $_POST['userName'];
	$User_Password = $_POST['password'];
	$userLoginVal = grabUserFromLogin($Username, $User_Password);
	if (!$userLoginVal) {
		echo "<script>alert(\"User name or password is incorrect!\")</script>";
	}
	else {
		setcookie("user", $userLoginVal->Username, time()+(86400 * 3), "/");
		header("Location: index.php");
		die();
	}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Library Login</title>
	<link href="primary.css" rel="stylesheet" type="text/css"/>
</head>
<body>
	<h2>Library access</h1>
	<hr>
	
	<section id="login">
		<h2>LOGIN:</h2>
		<form action="login.php" method="POST">
			<label><b>USER NAME: </b></label>
			<input type="text" name="userName" placeholder="Username"><br>
			<label><b>PASSWORD:</b></label>
			<input type="password" name="password" placeholder="Password"><br>
			<input type="submit" name="login" value="LOGIN">
		</form>
	</section>
	<br>

	
</body>
</html>