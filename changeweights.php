<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Our Restaurant Reviews</title>
<link rel="stylesheet" href="extras/reviews.css" type="text/css">


</head>

<body>
<div id="container">
    <div id="heading">
	<h1>Change Weights</h1>
    </div>
    <div id="content">
<form name="newReview" method="post" action="enterweights.php">



<?php
include 'config/config.php';


try{
	$pdo = new PDO(PDO_DSN, PDO_USERNAME, PDO_PASSWORD);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$stmt = $pdo->prepare("select * from weight");

	$stmt->execute();

	$weights = $stmt->fetchAll(PDO::FETCH_ASSOC);

	foreach($weights as $row){

		echo "<table class='newReview'><tbody>";
		echo "<tr><td class='taste'>Taste</td><td class='price'>Price/Value</td><td class='convloc'>Convenience/Location</td><td class='descprod'>Description To Product</td><td class='service'>Service</td></tr>";


		echo "<tr>";

		echo "<td class='taste'><input type='text' name='tasteWeight' id='tasteWeightWeight' value='" . $row['tasteweight'] . "' size='4'></td>";
		echo "<td class='price'><input type='text' name='priceValWeight' id='priceValWeight' value='" . $row['priceweight'] . "' size='4'></td>";
		echo "<td class='convloc'><input type='text' name='convLocWeight' id='convLocWeight' value='" . $row['locationweight'] . "' size='4'></td>";
		echo "<td class='descprod'><input type='text' name='descToProdWeight' id='descToProdWeight'value='" . $row['descriptionweight'] . "'  size='4'></td>";
		echo "<td class='service'><input type='text' name='serviceWeight' id='serviceWeight'value='" . $row['serviceweight'] . "'  size='4'></td>";
		echo "</tr>";
		echo "</tbody></table>";

	}






		$pdo = null;

	}
	catch(PDOException $e){
       		echo $e;
}




echo "<br><br><input style='color:blue' type='submit' value='Donate Now'>";



echo "</form>";


?>


  
</div>
</body>
</html>
