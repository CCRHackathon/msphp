<?php 
	require_once __DIR__ . '\Conexao.php';
	$limite_km 		= $_REQUEST['limite_km'];
	$lat 			= $_REQUEST['latitude'];
	$long 			= $_REQUEST['longitude'];

	header('Content-Type: application/json');

	$query = Conexao::getInstance()->prepare("
		SELECT transmissao.*,
			(6371 * acos(
			 cos( radians(:latitude) )
			 * cos( radians( transmissao.lat ) )
			 * cos( radians( transmissao.long ) - radians(:longitude) )
			 + sin( radians(:latitude) )
			 * sin( radians( transmissao.lat ) ) 
			 )
			) AS distancia
		FROM transmissao
		HAVING distancia < :limite_km
		ORDER BY distancia ASC
		LIMIT 30;
	");
	$query->bindValue("limite_km"	,	$limite_km		, PDO::PARAM_INT);
	$query->bindValue("latitude"	,	$lat							);
	$query->bindValue("longitude"	,	$long  							);
	$query->setFetchMode(PDO::FETCH_ASSOC);
	$query->execute();
	echo json_encode($query->fetchAll());


