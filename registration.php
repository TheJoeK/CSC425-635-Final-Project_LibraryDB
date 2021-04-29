<?php
// Imports
include_once 'connection.php';
include_once 'tablemodel.php';
include_once 'sqlfunctions.php';	

if (isset($_POST['register'])) {
	$Username = $_POST['regUserName'];
	$User_Password = $_POST['regPassword1'];
	if ($User_Password != $_POST['regPassword2']) {
		// fail here
	}
	$FName = $_POST['regFirstName'];
	$LName = $_POST['regLastName'];
	$Email = $_POST['regEmail'];
	$LibraryCard = $_POST['regLibraryCard'];
	$Phone = $_POST['regPhoneNumber'];
	$Admin = isset($_POST['adminChoice']) ? 1 : 0;

	if (!validateArgs($Username, $User_Password, $FName, $LName, $Email, $LibraryCard, $Phone, $Admin)) {
            echo "<script>alert(\"Must fill all fields correctly!\")</script>";
        }
        else {
            $userAccount = createUser($Username, $User_Password, $FName, $LName, $LibraryCard, $Phone, $Email, $Admin);
        }

	

	if (!$userAccount) {
		echo "<script>alert(\"Something went wrong!\")</script>";
	}
	else {
		//setcookie(name)
		//header("Location: index.php");
		//die();
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
	<div id="da_content">
	<nav id="navigator">
            <ul>
                <li><a href="index.php">Home/Logout</a></li>
                <li><span class="nav_selected">Register user</span></li>
                <li><a href="add.php">Add Page</a></li>
                <li><a href="checkout.php">Checkout Book</a></li>
            </ul>
        </nav>
	
	<hr>
	<br>
	<section id="register">
		<h2>REGISTER:</h2>
		<form action="registration.php" method="POST">
			<label><b>FIRST NAME: </b></label>
			<input type="text" name="regFirstName" placeholder="Enter First Name"><br>
			<label><b>LAST NAME: </b></label>
			<input type="text" name="regLastName" placeholder="Enter Last Name"><br>
			<label><b>EMAIL ADDRESS: </b></label>
			<input type="text" name="regEmail" placeholder="Enter Email"><br>
			<label><b>LIBARY CARD: </b></label>
			<input type="text" name="regLibraryCard" placeholder="Enter Library Card"><br>
			<label><b>PHONE NUMBER: </b></label>
			<input type="number" name="regPhoneNumber" placeholder="Enter Phone Number"><br>
			<label><b>ADMIN&#63;: </b></label><br>
			<label>
				<input type="radio" id="admin" name="adminChoice" value="admin">YES
			</label><br>
			<label>
				<input type="radio" id="notAdmin" name="adminChoice" value="notAdmin">NO
			</label><br>

			<label><b>USER NAME: </b></label>
			<input type="text" name="regUserName" placeholder="Create Username"><br>
			<label><b>PASSWORD:</b></label>
			<input type="password" name="regPassword1" placeholder="Create Password"><br>
			<label><b>RE-ENTER PASSWORD:</b></label>
			<input type="password" name="regPassword2" placeholder="Re-enter Password"><br>
			<input type="submit" name="register" value="REGISTER">
		</form>
	</section>

	</div>
</body>
</html>