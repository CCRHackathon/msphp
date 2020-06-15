<?php 
	require_once __DIR__ . '\Conexao.php';
	$limite_km 		= $_REQUEST['limite_km'];
	$lat 			= $_REQUEST['latitude'];
	$long 			= $_REQUEST['longitude'];

	header('Content-Type: application/json');
	$query = Conexao::getInstance()->prepare("
		SELECT local.id,
			(6371 * acos(
			 cos( radians(:latitude) )
			 * cos( radians( local.lat ) )
			 * cos( radians( local.long ) - radians(:longitude) )
			 + sin( radians(:latitude) )
			 * sin( radians( local.lat ) ) 
			 )
			) AS distancia,
            local.nome,
            local.descricao,
            categoria.titulo as categoria,
            local.lat,
            local.long
		FROM local,categoria
        WHERE categoria.id = local.categoria
		HAVING distancia < :limite_km
		ORDER BY distancia ASC
		LIMIT 30;
	");
	$query->bindValue("limite_km"	,	$limite_km		, PDO::PARAM_INT);
	$query->bindValue("latitude"	,	$lat							);
	$query->bindValue("longitude"	,	$long  							);
	$query->setFetchMode(PDO::FETCH_ASSOC);
	$query->execute();
	$vetor_local = array();
	foreach ($query->fetchAll() as $local) {
		$get_avaliacoes = Conexao::getInstance()->prepare("SELECT categoria.titulo,categoria.icon,categoria.descricao,
										(SELECT round(avg(nota),1) FROM avaliacao WHERE avaliacao.local = :local AND avaliacao.categoria = servicos.categoria) as nota
										FROM servicos,categoria WHERE servicos.local = :local AND categoria.id = servicos.categoria ");
		$get_avaliacoes->bindValue("local",$local['id']);
		$get_avaliacoes->execute();
		$local['avaliacao'] = $get_avaliacoes->fetchAll();
		array_push($vetor_local, $local);
	}
	echo json_encode($vetor_local);
	

?>
