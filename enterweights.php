<?php
include 'config/config.php';

$tasteWeight = getPostData('tasteWeight');
$priceValWeight = getPostData('priceValWeight');
$convLocWeight = getPostData('convLocWeight');
$descToProdWeight = getPostData('descToProdWeight');
$serviceWeight = getPostData('serviceWeight');

//echo $tasteWeight . ' ' . $priceValWeight . ' ' . $convLocWeight . ' ' . $descToProdWeight . ' ' . $serviceWeight;


updateWeights($tasteWeight, $priceValWeight, $convLocWeight, $descToProdWeight, $serviceWeight);

header("Location: http://localhost/reviews/indexweighted.php");

exit;







function getPostData($name){
	if (!isset($_POST[$name])){
		$postVal = 'undefined';
	} else {
		$postVal = $_POST[$name];
		if (!(($postVal >= 0) && ($postVal <= 200))){
			$postVal = 100;
		}
	
	}
	return $postVal;
}




function updateWeights($tasteWeight, $priceValWeight, $convLocWeight, $descToProdWeight, $serviceWeight){
//echo $tasteWeight . ' ' . $priceValWeight . ' ' . $convLocWeight . ' ' . $descToProdWeight . ' ' . $serviceWeight;
	try{
		$pdo = new PDO(PDO_DSN, PDO_USERNAME, PDO_PASSWORD);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$stmt = $pdo->prepare("update weight set tasteweight=:tasteweight, priceweight=:priceValweight, locationweight=:convLocweight, descriptionweight=:descToProdweight, serviceweight=:serviceweight where userid1 = 1");
		$stmt->bindValue(':tasteweight', $tasteWeight);
		$stmt->bindParam(':priceValweight', $priceValWeight);
		$stmt->bindParam(':convLocweight', $convLocWeight);
		$stmt->bindParam(':descToProdweight', $descToProdWeight);
		$stmt->bindParam(':serviceweight', $serviceWeight);

		$stmt->execute();

		$pdo = null;

		}
		catch(PDOException $e){
	       		echo $e;
	}

}



?>