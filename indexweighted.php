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
	<h1>Our Restaurant Reviews</h1>
    </div>
    <div id="content">
<a href="newreview.php">Time for another review</a><br><br>
<a href="changeweights.php">Change score weights</a><br><br>
<?php
include 'config/config.php';

$tblArray = array();
$avgArray = array();


$restaurants = getRestaurants();
$us = getUs();

//echo names for table

echo "<table><thead><tr><th></th><th></th>";

foreach ($us as $name){
	echo "<th>" . $name['usName'] . "</th><th></th><th></th><th></th><th></th><th></th>";

}
echo "<th></th></tr>";

echo "<tr><th>Restaurant</th><th>Visits</th><th>Taste</th><th>Price/Value</th><th>Convenience/<br>Location</th><th>Description to<br>Product</th><th>Service</th><th>Average</th><th>Taste</th><th>Price/Value</th><th>Convenience/<br>Location</th><th>Description to<br>Product</th><th>Service</th><th>Average</th><th>Total Score</th></tr></thead><tbody>";



foreach ($restaurants as $restaurant){
	$i = 0;
	$cumAvg = 0;



	foreach ($us as $person){
		$restId = $restaurant['restID'];
		$persId = $person['usID'];
		$reviewItems = getReviews($restId, $persId);
		$reviewCount = getReviewCount($restId);
		
		//echo $reviewCount . " ";

		//create person total/average
		$weights = getWeights();
		$taste = $reviewItems[0]['avg(taste)'] * ($weights[0]['tasteweight']/100);
		$taste = round($taste, 3);

		$priceVal = $reviewItems[0]['avg(priceValue)'] * ($weights[0]['priceweight']/100);
		$priceVal = round($priceVal, 3);

		$convLoc = $reviewItems[0]['avg(convenienceLocation)'] * ($weights[0]['locationweight']/100);
		$convLoc = round($convLoc, 3);

		$descToProd = $reviewItems[0]['avg(descToProduct)'] * ($weights[0]['descriptionweight']/100);
		$descToProd = round($descToProd, 3);

		$service = $reviewItems[0]['avg(service)'] * ($weights[0]['serviceweight']/100);
		$service = round($service, 3);

		$name = $reviewItems[0]['restName'];
		
		$persTot = ($taste + $priceVal + $convLoc + $descToProd + $service);
		if ($persTot != 0){
			$persAvg = $persTot/5;
		} else {
			$persAvg = 0;
		}
		$i++;
		//add avg to cumulative avg
		$cumAvg += $persAvg;


		//create table string
		if ($i == 1){
			//echo $reviewCount . " ";
			$tblString = "<td><a href='singlerestaurant.php?id=" . $restId . "'>" . $name . "</a></td><td>" . $reviewCount . "</td>";
		}

		$tblString .= "<td>" . $taste . "</td><td>" . $priceVal . "</td><td>" . $convLoc . "</td><td>" . $descToProd . "</td><td>" . $service . "</td><td>" . round($persAvg, 3) . "</td>";



	}

	$avg = $cumAvg / $i;

	$tblString .= "<td>" . round($avg, 3) . "</td></tr>";


	//add table string to table array
	array_push($tblArray, $tblString);

	//add cum avg to avg array

	array_push($avgArray, $avg);

}


//sort arrays

array_multisort($avgArray, SORT_DESC, $tblArray);


//display each in array
$rowOdd = true;


for ($j=0; $j<count($tblArray); $j++){
	echo ("<tr ");

	if ($rowOdd == true){
		echo ("class='odd'");
	} else {
		echo ("class='even'");
	}
	echo (">");


	echo $tblArray[$j];

	if($rowOdd == true){
		$rowOdd = false;
	} else {
		$rowOdd = true;
	}


}

function getReviewCount($restId){
	try{
		$pdo = new PDO(PDO_DSN, PDO_USERNAME, PDO_PASSWORD);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$stmt = $pdo->prepare("select count(reviewID) from review where restID = :restId");

		$stmt->bindParam(':restId', $restId);


		$stmt->execute();

		$revCount = $stmt->fetchColumn();

		$pdo = null;

		}
		catch(PDOException $e){
	       		echo $e;
	}
//print_r($revCount);
//echo " ";
//	$count = $revCount['count(reviewID)'];

	return $revCount;
}


function getReviews($restId, $persId){

	try{
		$pdo = new PDO(PDO_DSN, PDO_USERNAME, PDO_PASSWORD);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		//$stmt = $pdo->prepare("select * from scores join review on scores.reviewID = review.reviewID where review.restID = :restId and scores.usID = :person");

		$stmt = $pdo->prepare("select avg(taste), avg(priceValue), avg(convenienceLocation), avg(descToProduct), avg(service), restName from scores join review on scores.reviewID = review.reviewID join restaurants on restaurants.restID = review.restID where review.restID = :restId and scores.usID = :person");


		$stmt->bindParam(':restId', $restId);
		$stmt->bindParam(':person', $persId);

		$stmt->execute();

		$restaurants = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$pdo = null;

		}
		catch(PDOException $e){
	       		echo $e;
	}

	return $restaurants;
}

function getRestaurants(){

	try{
		$pdo = new PDO(PDO_DSN, PDO_USERNAME, PDO_PASSWORD);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$stmt = $pdo->prepare("select * from restaurants");

		$stmt->execute();

		$restaurants = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$pdo = null;

		}
		catch(PDOException $e){
	       		echo $e;
	}

	return $restaurants;
}




function getUs(){

	try{
		$pdo = new PDO(PDO_DSN, PDO_USERNAME, PDO_PASSWORD);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$stmt = $pdo->prepare("select * from us");

		$stmt->execute();

		$us = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$pdo = null;

		}
		catch(PDOException $e){
	       		echo $e;
	}

	return $us;
}


function getWeights(){

	try{
		$pdo = new PDO(PDO_DSN, PDO_USERNAME, PDO_PASSWORD);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$stmt = $pdo->prepare("select * from weight where userid1 = 1");

		$stmt->execute();

		$weights = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$pdo = null;

		}
		catch(PDOException $e){
	       		echo $e;
	}

	return $weights;
}

?>


          </tbody>
        </table>
    </div>
</div>
</body>
</html>
