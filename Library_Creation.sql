USE master;
go

DROP DATABASE IF EXISTS Library_Database;
go

CREATE DATABASE Library_Database;
go

USE Library_Database;
go

CREATE TABLE Users(
Username NVARCHAR(40),
User_Password NVARCHAR(40),
User_Fname NVARCHAR(40),
User_Lname NVARCHAR(40),
Library_Card NVARCHAR(40),
Phone INT,
Email NVARCHAR(40),
is_Admin INT,
PRIMARY KEY(Username)
)
go

CREATE TABLE Author(
Author_ID INT,
Auth_Fname NVARCHAR(40),
Auth_Lname NVARCHAR(40),
PRIMARY KEY(Author_ID)
)
go

CREATE TABLE Book(
ISBN INT,
Book_Title NVARCHAR(40),
Author_ID INT, -- Foreign Key
Genre NVARCHAR(40),
Publication_Year INT,
Country NVARCHAR(40),
Page_Count INT,
PRIMARY KEY(ISBN)
)
go

CREATE TABLE Transactions(
Checkout_ID INT,
Amount_Paid INT, -- Foreign Key
PRIMARY KEY(Checkout_ID)
)
go

CREATE TABLE BookStock(
Book_ID INT,
ISBN INT, -- Foreign Key
In_Stock INT,
PRIMARY KEY(Book_ID)
)
go 
CREATE TABLE Checkouts(
Checkout_ID INT,
Username NVARCHAR(40), -- Foreign Key
Book_ID INT, -- Foreign Key
Checkout_Date DATE,
Return_Date DATE,
Actual_Return_Date DATE,
PRIMARY KEY (Checkout_ID)
)
go

ALTER TABLE Book
ADD CONSTRAINT FK_Checkout
FOREIGN KEY (Checkout_ID) REFERENCES Checkouts(Checkout_ID)
go

ALTER TABLE Transactions
ADD CONSTRAINT FK_TransactionsUsername
FOREIGN KEY (Username) REFERENCES Users(Username)
go

ALTER TABLE BookStock
ADD CONSTRAINT FK_BookStockISBN
FOREIGN KEY (ISBN) REFERENCES Book(ISBN)
go

ALTER TABLE Checkouts
ADD CONSTRAINT FK_CheckoutsUsername
FOREIGN KEY (Username) REFERENCES Users(Username)
go

ALTER TABLE Checkouts
ADD CONSTRAINT FK_CheckoutsBook
FOREIGN KEY (Book_ID) REFERENCES BookStock(Book_ID)
go
