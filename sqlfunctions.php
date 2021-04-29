 <?php
 // Grab our server information
include_once 'tablemodel.php';
include_once 'connection.php';

function validateArgs(...$params) {
    foreach($params as $arg) {
        if (!isset($arg) OR $arg == "" OR str_contains($arg, ";")
    		OR str_contains($arg, "\"") OR str_contains($arg, "'")) {
            return false;
        }
    }
    return true;
}

function executeStatement($SQLStmt) {
	$answer = sqlsrv_execute($SQLStmt);
	if(!$answer) {
		echo("Executing preparedStatementUserEntry isn't working! Execution aborted! ");
		die(print_r(sqlsrv_errors(), true));
	}
	return $answer;
}

function createUser($username, $password, $fname, $lname, $card, $phone, $email, $is_Admin) {
	global $conn;
	$sql = "EXEC InsertUser ?, ?, ?, ?, ?, ?, ?, ?";
	$params = array( &$username, &$password, &$fname,
 &$lname, &$card, &$phone, &$email, &$is_Admin);

	return executeStatement(sqlsrv_prepare($conn, $sql, $params));
}

function createAuthor($fname, $lname) {
	global $conn;
	$sql = "EXEC InsertAuthor ?, ?";
	$params = array( &$fname, &$lname);

	return executeStatement(sqlsrv_prepare($conn, $sql, $params));
}

function createBook($ISBN, $Book_Title, $AuthorID, $Genre, $Pyear, $Country, $Page_Count) {
	global $conn;
	$sql = "EXEC InsertBook ?, ?, ?, ?, ?, ?, ?";
	$params = array( &$ISBN, &$Book_Title, &$AuthorID, &$Genre, 
		&$Pyear, &$Country, &$Page_Count);

	return executeStatement(sqlsrv_prepare($conn, $sql, $params));
}

function createBookStock(String $ISBN) {
	global $conn;
	$sql = "EXEC InsertBookStock ?";
	$params = array( &$ISBN);

	return executeStatement(sqlsrv_prepare($conn, $sql, $params));
}

function createCheckout($Username, $Book_ID, $Checkout_Date, $Return_Date) {
	global $conn;
	$sql = "EXEC InsertCheckout ?, ?, ?, ?";
	$params = array( &$Username, &$Book_ID, &$Checkout_Date, &$Return_Date);

	return executeStatement(sqlsrv_prepare($conn, $sql, $params));
}


function createTransaction($Username, $Amount) {
	global $conn;
	$sql = "EXEC InsertTransaction ?, ?";
	$params = array( &$Username, &$Amount);

	return executeStatement(sqlsrv_prepare($conn, $sql, $params));
}


function grabUserFromLogin($Username, $password) {
    global $conn;
    $sql = "EXEC GetUser ?, ?";
    $params = array( &$Username, &$password);
    $results = sqlsrv_query($conn, $sql, $params);
    $row = sqlsrv_fetch_array( $results, SQLSRV_FETCH_ASSOC);
    if (!$row) {
          return false;
    }
    return User::parseUser($row);
}

function grabAuthors() {
    global $conn;
    $sql = "SELECT * FROM Author";
    $params = array();

    $results = sqlsrv_query($conn, $sql, $params);
    $authors = array();
    if (!$results) {
        return array();
    }
    while($row = sqlsrv_fetch_array( $results, SQLSRV_FETCH_ASSOC)) {
        $author = Author::parseAuthor($row);
        $authors[$author->authorID] = $author;
    }
    return $authors; 
}

function grabBooks() {
    global $conn;
    $sql = "SELECT * FROM Book";
    $params = array();

    $results = sqlsrv_query($conn, $sql, $params);
    $books = array();
    if (!$results) {
        return array();
    }
    while($row = sqlsrv_fetch_array( $results, SQLSRV_FETCH_ASSOC)) {
        $book = Book::parseBook($row);
        $books[$book->ISBN] = $book;
    }
    return $books; 
}

function grabAvailableBooks() {
    global $conn;
    $sql = "SELECT * FROM Book WHERE EXISTS(SELECT * FROM BookStock WHERE Book.ISBN = BookStock.ISBN AND In_Stock = 1)";
    $params = array();

    $results = sqlsrv_query($conn, $sql, $params);
    $books = array();
    if (!$results) {
        return array();
    }
    while($row = sqlsrv_fetch_array( $results, SQLSRV_FETCH_ASSOC)) {
        $book = Book::parseBook($row);
        $books[$book->ISBN] = $book;
    }
    return $books; 
}

function getBookStockCount($ISBN) {
	global $conn;
    $sql = "SELECT COUNT(ISBN) AS Amount FROM BookStock WHERE ISBN = ? AND In_Stock = 1 GROUP BY ISBN";
    $params = array(&$ISBN);

    $results = sqlsrv_query($conn, $sql, $params);
    $books = array();
    if (!$results) {
        return 0;
    }
    while($row = sqlsrv_fetch_array( $results, SQLSRV_FETCH_ASSOC)) {
        return $row['Amount'];
    }
}

function grabCheckoutsFromUser($Username) {
	global $conn;
    $sql = "SELECT * FROM Checkouts WHERE Username = ?";
    $params = array(&$Username);

    $results = sqlsrv_query($conn, $sql, $params);
    $checkouts = array();
    if (!$results) {
        return array();
    }
    while($row = sqlsrv_fetch_array( $results, SQLSRV_FETCH_ASSOC)) {
        $checkout = Checkout::parseCheckout($row);
        $checkouts[$checkout->Checkout_ID] = $checkout;
    }
    return $checkouts; 
}

function getBookFromCheckout($CheckoutID) {
	global $conn;
    $sql = "SELECT * FROM Book WHERE EXISTS(SELECT * FROM Checkouts WHERE Checkout_ID = ? AND 
    	EXISTS(SELECT * FROM BookStock WHERE BookStock.Book_ID = Checkouts.Book_ID AND BookStock.ISBN = Book.ISBN))";
    $params = array(&$CheckoutID);

    $results = sqlsrv_query($conn, $sql, $params);
    if (!$results) {
        return null;
    }
    $row = sqlsrv_fetch_array( $results, SQLSRV_FETCH_ASSOC);
  	return Book::parseBook($row);
}


function getBookStock($ISBN) {
    global $conn;
    $sql = "SELECT TOP 1 * FROM BookStock WHERE ISBN = ? AND In_Stock = 1";
    $params = array(&$ISBN);

    $results = sqlsrv_query($conn, $sql, $params);
    $books = array();
    if (!$results) {
        return null;
    }
    while($row = sqlsrv_fetch_array( $results, SQLSRV_FETCH_ASSOC)) {
        return BookStock::parseBookStock($row);
    } 
}

function getBookStockFromCheckout($Checkout_ID) {
    global $conn;
    $sql = "SELECT * FROM BookStock WHERE EXISTS (SELECT * FROM Checkouts WHERE Checkout_ID = ? AND Checkouts.Book_ID = BookStock.Book_ID)";
    $params = array(&$Checkout_ID);

    $results = sqlsrv_query($conn, $sql, $params);
    $books = array();
    if (!$results) {
        return null;
    }
    while($row = sqlsrv_fetch_array( $results, SQLSRV_FETCH_ASSOC)) {
        return BookStock::parseBookStock($row);
    } 
}

function checkoutBookID($Book_ID) {
	global $conn;
	$sql = "UPDATE BookStock SET In_Stock = 0 WHERE Book_ID = ?";
	$params = array(&$Book_ID);

	return sqlsrv_execute(sqlsrv_prepare($conn, $sql, $params));
}

function getLateDebt($CheckoutID) {
	global $conn;
	$sql = "EXEC GetAmount ?";
	$params = array(&$CheckoutID);

	$results = sqlsrv_query($conn, $sql, $params);
	if (!$results) {
		return 0.00;
	} 
	$row = sqlsrv_fetch_array( $results, SQLSRV_FETCH_ASSOC);
	return $row['Days'] * 0.05;
}

function checkinBook($CheckoutID, $Book_ID) {
	global $conn;
	$sql = "UPDATE Checkouts
		SET Actual_Return_Date = ?
		WHERE Checkout_ID = ?";
	$params = array(date("Y-m-d"), &$CheckoutID);

	$results = sqlsrv_execute(sqlsrv_prepare($conn, $sql, $params));
	$sql = "UPDATE BookStock
		SET In_Stock = 1
		WHERE Book_ID = ?";
	$params = array(&$Book_ID);
	return sqlsrv_execute(sqlsrv_prepare($conn, $sql, $params));
}

?>
