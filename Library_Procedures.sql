USE Library_Database;
go

CREATE PROCEDURE InsertAuthor @Fname NVARCHAR(40), @Lname NVARCHAR(40)
AS
BEGIN
DECLARE @ID AS INT
SELECT TOP 1 @ID = Author_ID FROM Author ORDER BY Author_ID DESC 
DECLARE @AUTH_ID AS INT = COALESCE(@ID, -1)
INSERT INTO Author VALUES (@AUTH_ID + 1, @Fname, @Lname)
END
GO


CREATE PROCEDURE InsertBook @ISBN INT, @Book_Title NVARCHAR(40), @Author_ID INT, @Genre NVARCHAR(40), @Pyear INT, @Country NVARCHAR(40), @Page_Count INT
AS
BEGIN
INSERT INTO Book VALUES (@ISBN, @Book_Title, @Author_ID, @Genre, @Pyear, @Country, @Page_Count)
END
GO


CREATE PROCEDURE InsertBookStock @ISBN INT
AS
BEGIN
DECLARE @ID AS INT
SELECT TOP 1 @ID = Book_ID FROM BookStock ORDER BY Book_ID DESC 
DECLARE @BOOKID AS INT = COALESCE(@ID, -1)
INSERT INTO BookStock VALUES (@BOOKID + 1, @ISBN, 1)
END
GO

GO
CREATE PROCEDURE InsertTransaction @Username NVARCHAR(40), @Amount INT
AS
BEGIN
DECLARE @ID AS INT
SELECT TOP 1 @ID = Transaction_ID FROM Transactions ORDER BY Transaction_ID DESC 
DECLARE @TRANSID AS INT = COALESCE(@ID, -1)
INSERT INTO Transactions VALUES (@TRANSID + 1, @Username, @Amount)
END
GO

CREATE PROCEDURE InsertUser 

	@Username NVARCHAR(40),
	@User_Password NVARCHAR(40),
	@User_Fname NVARCHAR(40),
	@User_Lname NVARCHAR(40),
	@Library_Card NVARCHAR(40),
	@Phone INT,
	@Email NVARCHAR(40),
	@is_Admin INT
AS
BEGIN
DECLARE @HPassword AS NVARCHAR(100) = HASHBYTES('SHA2_256', CONCAT('saltierthansalt',@User_Password))
INSERT INTO Users VALUES (@Username, @HPassword, @User_Fname, @User_Lname, @Library_Card, @Phone, @Email, @is_Admin, 0)
END
GO


CREATE PROCEDURE InsertCheckout
	@Username NVARCHAR(40),
	@Book_ID INT,
	@Checkout_Date DATE,
	@Return_Date DATE
AS
BEGIN
DECLARE @ID AS INT
SELECT TOP 1 @ID = Checkout_ID FROM Checkouts ORDER BY Checkout_ID DESC 
DECLARE @CHECKINGOUT_ID AS INT = COALESCE(@ID, -1)
INSERT INTO Checkouts VALUES (@ID + 1,@Username,@Book_ID, @Checkout_Date, @Return_Date, NULL)
END
GO

CREATE PROCEDURE GetAuthor @AuthorID INT
AS
BEGIN
SELECT * FROM Author WHERE Author_ID = @AuthorID
END
GO

CREATE PROCEDURE GetUser @Username NVARCHAR(50),
@User_Password NVARCHAR(50)
AS
BEGIN
DECLARE @HPassword AS NVARCHAR(100) = HASHBYTES('SHA2_256', CONCAT('saltierthansalt',@User_Password))
SELECT * FROM Users WHERE Username = @Username AND User_Password = @HPassword
RETURN
END
GO

--added code necessary for access
USE Library_Database;
go

EXEC InsertUser 'thejoek','password1,','Joseph','Kelly', 'abc123456', 3184709931, 'kellyj91@lsus.edu', 1;
go

select *
from Users;

Delete FROM Users WHERE Username = 'test'
GO

EXEC GetUser 'thejoek', 'password1,';
go

select *
from Author;

select *
from Book;

select *
from BookStock;


--delete 
--from Author
--where Author_ID = 0;

EXEC InsertAuthor 'Jim', 'Butcher'; 
EXEC InsertAuthor 'Stephen','King';
EXEC InsertAuthor 'H. P.','Lovecraft';
EXEC InsertAuthor 'Ryan','Riordan';
EXEC InsertAuthor 'George R. R.','Martin';
EXEC InsertAuthor 'Alan','Moore';
go