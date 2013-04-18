<?php
include 'config/config.php';

$tblArray = array();
$avgArray = array();


$restaurants = getRestaurants();
$us = getUs();


$returnstring = "<a href='newreview.html'>Time for another review</a><br><br>";



//echo names for table

$returnstring .= "<table><thead><tr><th></th><th></th>";

foreach ($us as $name){
	$returnstring .= "<th>" . $name['usName'] . "</th><th></th><th></th><th></th><th></th><th></th>";

}
$returnstring .= "<th></th></tr>";

$returnstring .= "<tr><th>Restaurant</th><th>Visits</th><th>Taste</th><th>Price/Value</th><th>Convenience/<br>Location</th><th>Description to<br>Product</th><th>Service</th><th>Average</th><th>Taste</th><th>Price/Value</th><th>Convenience/<br>Location</th><th>Description to<br>Product</th><th>Service</th><th>Average</th><th>Total Score</th></tr></thead><tbody>";



foreach ($restaurants as $restaurant){
	$i = 0;
	$cumAvg = 0;



	foreach ($us as $person){
		$restId = $restaurant['restID'];
		$persId = $person['usID'];
		$reviewItems = getReviews($restId, $persId);
		$reviewCount = getReviewCount($restId);

		//create person total/average

		$taste = round($reviewItems[0]['avg(taste)'], 3);
		$priceVal = round($reviewItems[0]['avg(priceValue)'], 3);
		$convLoc = round($reviewItems[0]['avg(convenienceLocation)'], 3);
		$descToProd = round($reviewItems[0]['avg(descToProduct)'], 3);
		$service = round($reviewItems[0]['avg(service)'], 3);
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
	$returnstring .= "<tr ";

	if ($rowOdd == true){
		$returnstring .= "class='odd'";
	} else {
		$returnstring .= "class='even'";
	}
	$returnstring .= ">";


	$returnstring .= $tblArray[$j];

	if($rowOdd == true){
		$rowOdd = false;
	} else {
		$rowOdd = true;
	}


}

$returnstring .= "</tbody></table>";
echo $returnstring;

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

	$count = $revCount['count(reviewID)'];

	return $count;
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




?>