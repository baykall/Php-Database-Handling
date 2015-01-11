<?php
include_once 'database.php';

// define your host name, username, password, and database name here
$dHost = 'MYHOSTNAME.com';
$dUser = 'MYDatabaseUserName';
$dPassword = 'MyDatabasePassword';
$dBase = 'MyDatabaseName';

//connect to database in the beginning
$db = new DataBase;
$db->connect();

//To insert a row into a database table
$db->InsertInto('Employees',array('Name','ID','Age'),array('Jack',23,45)); 

//To update a row in a database table
$db->Update('Employees',array('Name','ID'),array('Jack',23),'Age',47); 

//To delete rows in a database table
$db->Delete('Employees',array('Name','ID'),array('Jack',23));

//To query a database table and return single result
$myresult = $db->Get('Employees',array('Name','ID'),array('Jack',23),'Age','Single');

//To query a database table and return all results in an array
$myresults = $$db->Get('Employees',array('Name','ID'),array('Jack',23),'Age','All');


//To insert a row into a database table with column values ordered
$db->InsertIntoSmart('Employees',1,array('Column#1 Value','Column#2 Value')); 

//To query a row into a database table with column values ordered
$myresult = $db->GetSmart('Employees',1,array('Column#1 Value','Column#2 Value'),'Age');


//disconnect in the end
$db->disconnect();
?>


