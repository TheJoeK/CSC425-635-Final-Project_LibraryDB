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

  //validate author form and submit data to database 
	if (isset($_POST['addAuthor'])) {
		$Fname = $_POST['Fname'];
		$Lname = $_POST['Lname'];

		if (!validateArgs($Fname, $Lname)) {
            echo "<script>alert(\"Must fill all fields correctly!\")</script>";
        }
        else {
            createAuthor($Fname, $Lname);
        }
	}

	// validate author form and submit data to database
	if (isset($_POST['createBook'])) {
		$ISBN = $_POST['ISBN'];
		$Book_Title = $_POST['Book_Title'];
		$Author_ID = $_POST['authorList'];
		$Genre = $_POST['Genre'];
		$Publication_Year = $_POST['Publication_Year'];
		$Country = $_POST['Country'];
		$Page_Count = $_POST['Page_Count'];
		if (!validateArgs($ISBN, $Book_Title, $Author_ID, $Genre, $Publication_Year, $Country, $Page_Count)) {
            echo "<script>alert(\"Must fill all fields correctly!\")</script>";
        }
        else {
            createBook($ISBN, $Book_Title, $Author_ID, $Genre, $Publication_Year, $Country, $Page_Count);
        }		

	}
	//add book to bookStock
	if (isset($_POST['addBookStock'])) {
				$bookQuantityMod = $_POST['bookList'];
				createBookStock($bookQuantityMod);

	}
		
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Add Page</title>
	<link href="primary.css" rel="stylesheet" type="text/css"/>
</head>
<body>
	<div id="da_content">
	<nav id="navigator">
            <ul>
                <li><a href="index.php">Home/Logout</a></li>
                <li><a href="registration.php">Register user</a></li>
                <li><span class="nav_selected">Add Page</span></li>
                <li><a href="checkout.php">Checkout Book</a></li>
            </ul>
        </nav>

	<section id="addBook">
		<h2>Add a Book:</h2>
		<form action="add.php" method="POST">
			<label><b>Book Title</b></label>
			<input type="text" name="Book_Title" placeholder="Book Title"><br>
			<label><b>ISBN</b></label>
			<input type="number" name="ISBN" placeholder="ISBN"><br>
			<label><b>Author:</b></label>
			<select name="authorList" id="authorList">
        		<?php 
		            $authors = grabAuthors();
		            foreach ($authors as $author) {
		                echo "<option value=\"" . $author->authorID . "\">" . $author->authorLName . ", " . $author->authorFName . "</option>";
		            }
        		?>
    		</select><br>
			<label><b>Genre:</b></label>
			<input type="text" name="Genre" placeholder="Genre"><br>
			<label><b>Publication Year:</b></label>
			<input type="number" name="Publication_Year" placeholder="Publication Year"><br>
			<label><b>Country:</b></label>
			<input type="text" name="Country" placeholder="Country"><br>
			<label><b>Page Count:</b></label>
			<input type="number" name="Page_Count" placeholder="Page Count"><br>
			
			<input type="submit" name="createBook" value="Add Book">
		</form>
		
	</section>
	<br>
	<hr>
	<br>
	<section id="addAuthor">
		<h2>Add an Author:</h2>
		

		<form action="add.php" method="POST">
			<label><b>Author First Name: </b></label>
			<input type="text" name="Fname" placeholder="Author First Name"><br>
			<label><b>Author Last Name:</b></label>
			<input type="text" name="Lname" placeholder="Author Last Name"><br>
			<input type="submit" name="addAuthor" value="Add Author">
		</form>


		
	</section>
	<br>
	<hr>
	<br>
	<section id="bookStock">
		<h2>Add Book Quantity:</h2>
		<form action="add.php" method="POST">
			<label><b>ISBN/ Book Title:</b></label>
			<select name="bookList" id="bookList">
        		<?php 
		            $books = grabBooks();
		            foreach ($books as $book) {
		                echo "<option value=\"" . $book->ISBN . "\">" . $book->Book_Title . "</option>";
		            }
        		?>
    		</select><br>
			<input type="submit" name="addBookStock" value="Add Book to shelf">
			
		</form>
				
	</section>
</div>
</body>
</html>