<!DOCTYPE html>

<html lang="en">
  <?php
	$serverName = "DESKTOP-53TFF9O"; //tcp:myserver.database.windows.net,1433
	$uid = "CSC625P";   
	$pwd = "CSC625test";  
	$databaseName = "Library_Database";
	$connectionOptions = array("Database"=>$databaseName,  
	"Uid"=>$uid, "PWD"=>$pwd);  
	$conn = sqlsrv_connect($serverName, $connectionOptions); 
   ?>
</html>
