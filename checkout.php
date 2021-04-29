<?php
// Imports
include_once 'connection.php';
include_once 'tablemodel.php';
include_once 'sqlfunctions.php';	

$user = getCookieUser();
if (!$user) {
	header("Location: login.php");
	die();
} else {
	$user = User::getUser($user);

}

function getCookieUser() {
	return isset($_COOKIE['user']) ? $_COOKIE['user'] : false;
}
	if (isset($_POST['checkoutBook'])) {
		$Username = $_POST['checkoutUserName'];
		$checkoutBook = $_POST['CheckoutBookList'];
		$bookID = getBookStock($checkoutBook);
		$Checkout_Date = $_POST['Checkout_Date'];
		$Return_Date = $_POST['Return_Date'];
		if (!validateArgs($Username, $checkoutBook, $bookID, $Checkout_Date, $Return_Date)) {
			echo "<script>alert(\"Must fill all fields correctly!\")</script>";
        }
        else {
            if (createCheckout($Username, $bookID->BookID, $Checkout_Date, $Return_Date)) {
            	$bookID->checkoutBook();
            }
        }
	}

	if (isset($_POST['checkInBook'])) {
		if (isset($_POST['CheckinList'])) {
			$stock = getBookStockFromCheckout($_POST['CheckinList']);
        	checkinBook($_POST['CheckinList'], $stock->BookID);
		}
	}
		
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Checkout Page</title>
	<link href="primary.css" rel="stylesheet" type="text/css"/>
</head>
<body>
	<div id="da_content">
	<nav id="navigator">
            <ul>
                <li><a href="index.php">Home/Logout</a></li>
                <li><a href="registration.php">Register user</a></li>
                <li><a href="add.php">Add Page</a></li>
                <li><span class="nav_selected">Checkout Book</span></li>
            </ul>
        </nav>
	<section id="checkoutBook">
		<h2>Checkout Book:</h2>
		<form action="checkout.php" method="POST">
			<label><b>User Name: </b></label>
			<input type="text" name="checkoutUserName" placeholder="Enter User Name"><br>
			<label><b>ISBN/ Book Title:</b></label>
			<select name="CheckoutBookList" id="CheckoutBookList">
        		<?php 
		            $books = grabAvailableBooks();
		            foreach ($books as $book) {
		            	$Amount = getBookStockCount($book->ISBN);

		                echo "<option value=\"" . $book->ISBN . "\">" . $book->Book_Title . $Amount . "</option>";
		            }
        		?>
        	</select><br>
        	<label><b>Checkout Date:</b></label>
        	<input type="date" name="Checkout_Date"><br>
        	<label><b>Return Date:</b></label>
        	<input type="date" name="Return_Date"><br>
        	<input type="submit" name="checkoutBook" value="Checkout book">
		</form>
	</section>

	<br>
	<hr>
	<br>	

    <section id="checkInBook">
    <h2>Check in Book:</h2>
    <form action="checkout.php" method="POST">
        <label><b>User Name:</b></label>
        <input type="text" name="checkinUserName" placeholder="Enter Username" value=
        <?php 
        	if (isset($_POST['checkinUserName'])) {
        		echo $_POST['checkinUserName'];
        	}
        ?>
        ><br>
        <select name="CheckinList" id="CheckinList">
    		<?php 
    			if (isset($_POST['checkinUserName'])) {
		            $checkouts = grabCheckoutsFromUser($_POST['checkinUserName']);
		            foreach ($checkouts as $checkout) {
		            	$Book = getBookFromCheckout($checkout->Checkout_ID);
		                echo "<option value=\"" . $checkout->Checkout_ID . "\">" . $Book->Book_Title . "</option>";
		            }
	        	}
    		?>
        </select><br>	
        <label id="checkinAmt"><b>Amount to Pay: 
        	<?php  
        		if (isset($_POST['CheckinList'])) {
        			echo getLateDebt($_POST['CheckinList']);
        		}
        	?>
        </b></label><br>
        <input type="submit" name="checkInBook" value="Check in book">
        <input type="submit" name="grabEntries" value="Get List">
    </form>
    </section>
</div>
</body>
</html>