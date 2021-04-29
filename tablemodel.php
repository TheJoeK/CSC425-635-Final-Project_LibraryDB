<?php


class User {
	public $Username;
	public $Password;
	public $Fname;
	public $Lname;
	public $Library_Card;
	public $Phone;
	public $Email;
	public $is_Admin;
	public function __construct(String $Username, STring $Password, String $Fname, String $Lname,
		String $Library_Card, String $Phone, String $Email, int $is_Admin) { 
      $this->Username = $Username;
      $this->Password = $Password;
      $this->Fname = $Fname;
      $this->Lname = $Lname; 
      $this->Library_Card = $Library_Card;
      $this->Phone = $Phone;
      $this->Email = $Email;
      $this->is_Admin = $is_Admin;
  	}

  	public static function parseUser($row) {
  		$Username = $row['Username'];
  		$Password = $row['User_Password'];
  		$FName = $row['User_Fname'];
  		$LName = $row['User_Lname'];
  		$Library_Card = $row['Library_Card'];
  		$Phone = $row['Phone'];
  		$Email = $row['Email'];
  		$is_Admin = $row['is_Admin'];
  		return new User($Username, $Password, $FName, $LName, $Library_Card, $Phone, $Email, $is_Admin);
  	}

    public static function getUser($Username) {
      global $conn;
      $sql = "SELECT * FROM Users WHERE Username = ?";
      $params = array( &$Username);
      $results = sqlsrv_query($conn, $sql, $params);
      $row = sqlsrv_fetch_array( $results, SQLSRV_FETCH_ASSOC);
      if ($row) {
        return User::parseUser($row);
      }
      return null;
    }
}

class Author {
	public $authorID;
	public $authorFName;
	public $authorLName;

	public function __construct(int $authorID, String $authorFName, String $authorLName) {
      $this->authorID = $authorID; 
      $this->authorFName = $authorFName;
      $this->authorLName = $authorLName;
  	}

	public static function parseAuthor($row) {
		$authorID = $row['Author_ID'];
		$FName = $row['Auth_Fname'];
		$LName = $row['Auth_Lname'];
		return new Author($authorID, $FName, $LName);
	}

  public static function getAuthor($authorID) {
    global $conn;
    $sql = "SELECT * FROM Author WHERE Author_ID = ?";
    $params = array( &$authorID);
    $results = sqlsrv_query($conn, $sql, $params);
    $row = sqlsrv_fetch_array( $results, SQLSRV_FETCH_ASSOC);
    if ($row) {
      return Author::parseAuthor($row);
    }
    return null;
  }

	public function __toString()
  {
      return strval($this->authorID) . ":" . $this->authorFName . ":" . $this->authorLName;
  }
}

class Book {
	public $ISBN;
  public $Book_Title;
	public $authorID;
	public $genre;
	public $pYear;
	public $country; 
	public $pageCount;

	public function __construct(int $ISBN, String $Book_Title, int $authorID, String $genre, int $pYear, String $country, int $pageCount) {
      $this->ISBN = $ISBN; 
      $this->Book_Title = $Book_Title;
      $this->authorID = $authorID;
      $this->genre = $genre;
      $this->pYear = $pYear; 
      $this->country = $country;
      $this->pageCount = $pageCount;
  	}

  	public static function parseBook($row) {
  		$ISBN = $row['ISBN'];
      $Book_Title = $row['Book_Title'];
  		$authorID = $row['Author_ID'];
  		$genre = $row['Genre'];
  		$pYear = $row['Publication_Year'];
  		$country = $row['Country'];
		$pageCount = $row['Page_Count'];
  		return new Book($ISBN, $Book_Title, $authorID, $genre, $pYear, $country, $pageCount);
  	}

    public static function getBook($ISBN) {
      global $conn;
      $sql = "SELECT * FROM Book WHERE ISBN = ?";
      $params = array( &$ISBN);
      $results = sqlsrv_query($conn, $sql, $params);
      if (!$results) {
        return null;
      }
      if ($row = sqlsrv_fetch_array( $results, SQLSRV_FETCH_ASSOC)) {
        return Book::parseBook($row);
      }
      return null;
    }

  	public function Author() {
      return Author::getAuthor($this->authorID);  		
  	}

    public function __toString()
    {
      return strval($this->ISBN) . ":" .
      $this->Book_Title . ":" .
      strval($this->authorID) . ":" .
      $this->genre . ":" .
      strval($this->pYear) . ":" .
      $this->country . ":" .
      strval($this->pageCount);
    }
}

class BookStock {
	public $BookID;
	public $ISBN;
	public $inStock;

	public function __construct(int $BookID, int $ISBN, int $inStock) {
      $this->BookID = $BookID; 
      $this->ISBN = $ISBN;
      $this->inStock = $inStock;
  	}

  	public static function parseBookStock($row) {
  		$BookID = $row['Book_ID'];
  		$ISBN = $row['ISBN'];
  		$inStock = $row['In_Stock'];

  		return new BookStock($BookID, $ISBN, $inStock);
  	}

    public function checkoutBook() {
      return checkoutBookID($this->BookID);
    }
  	public function Book() {
  		return Book::getBook($this->ISBN);
  	}

    public function __toString()
    {
      return strval($this->BookID) . ":" .
      strval($this->ISBN) . ":" .
      strval($this->inStock);
    }
}

class Checkout {
	public $Checkout_ID;
	public $Username;
	public $Book_ID;
	public $Checkout_Date;
	public $Return_Date;
	public $Actual_Return_Date; // This can be null

	public function __construct(int $Checkout_ID, String $Username, int $Book_ID,
		$Checkout_Date, $Return_Date, $Actual_Return_Date) {
      $this->Checkout_ID = $Checkout_ID; 
      $this->Username = $Username;
      $this->Book_ID = $Book_ID;
      $this->Checkout_Date = $Checkout_Date; 
      $this->Return_Date = $Return_Date;
      $this->Actual_Return_Date = $Actual_Return_Date;
  	}

  	public static function parseCheckout($row) {
  		$Checkout_ID = $row['Checkout_ID'];
  		$Username = $row['Username'];
  		$Book_ID = $row['Book_ID'];
  		$Checkout_Date = $row['Checkout_ID'];
  		$Return_Date = $row['Return_Date'];
  		$Actual_Return_Date = $row['Actual_Return_Date'];

  		return new Checkout($Checkout_ID, $Username, $Book_ID, $Checkout_Date, $Return_Date, $Actual_Return_Date);
  	}

  	public function User() {
  		return User::getUser($Username);
  	}

    public function __toString()
    {
      return strval($this->Checkout_ID) . ":" .
      $this->Username . ":" .
      strval($this->Book_ID) . ":" .
      $this->Checkout_Date . ":" .
      $this->Return_Date . ":" .
      $this->Actual_Return_Date;
    }
}

class Transaction {
	public $Checkout_ID;
	public $Amount_Paid;


	public function __construct(int $Checkout_ID, float $Amount_Paid) {
      $this->Checkout_ID = $Checkout_ID; 
      $this->Amount_Paid = $Amount_Paid;
  	}

  	public static function parseTransaction($row) {
  		$Checkout_ID = $row['Checkout_ID'];
  		$Amount_Paid = $row['Amount_Paid'];

  		return new Transaction($Checkout_ID, $Amount_Paid);
  	}

    public function __toString()
    {
      return strval($this->Checkout_ID) . ":" . 
      strval($this->Amount_Paid);
    }
}
?>
