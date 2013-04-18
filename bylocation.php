<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Our Restaurant Reviews</title>
<link rel="stylesheet" href="extras/reviews.css" type="text/css">
<script src="extras/ajaxbylocation.js"></script>


</head>

<body>
<div id="container">
    <div id="heading">
	<h1>Reviews By Location</h1>
    </div>
    <div id="content">
<form name="locations"">
<select id="locations" name="location" onChange="ajaxFunction()">
<option value="" selected="selected"></option>
<?php
include 'config/config.php';



try{
	$pdo = new PDO(PDO_DSN, PDO_USERNAME, PDO_PASSWORD);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$stmt = $pdo->prepare("select * from locations order by location");

	$stmt->execute();

	$locations = $stmt->fetchAll(PDO::FETCH_ASSOC);

	foreach($locations as $row){

		$id=$row['locationID'];
		$name=$row['location'];
		echo "<option value='". $id . "'>" . $name . "</option>";
	}

		$pdo = null;

	}
	catch(PDOException $e){
       		echo $e;
}

echo "</select>";
echo "</form>";


?>
<div id="result" name="result">

</div>  
</div>
</body>
</html>
