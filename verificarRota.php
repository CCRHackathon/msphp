<?php 
	require_once __DIR__ . '\Conexao.php';

	function distancia($lat1, $lon1, $lat2, $lon2) {
	    $theta = $lon1 - $lon2;
	    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
	    $dist = acos($dist);
	    $dist = rad2deg($dist);
	    $miles = $dist * 60 * 1.1515;
	    return ($miles * 1.609344);
	}

	header('Content-Type: application/json');

	$distancia = $_REQUEST['limite_km'];
	$array_latitude = $_REQUEST['latitude'];
	$array_longitude = $_REQUEST['longitude'];
	

	sort($array_latitude);
	sort($array_longitude);
	$vetor_local = array();
	$vetor_indices = array();
	$round = ceil($array_latitude[0]);
	array_push($vetor_indices,array("round" => $round, "indice_vetor" => 0));
	for($i = 0; $i < count($array_latitude); $i++){
		if($round != ceil($array_latitude[$i])){
			$round = ceil($array_latitude[$i]);
			array_push($vetor_indices,array("round" => $round, "indice_vetor" => $i));
		}
	}

	for($i = 0;$i < count($vetor_indices);$i++){
		$min = $vetor_indices[$i]['round'] - 1;
		$max = $vetor_indices[$i]['round'] + 1;
		$query = Conexao::getInstance()->prepare("SELECT * FROM `local` WHERE lat >= :min and lat <= :max ");
		$query->bindValue("min",$min);
		$query->bindValue("max",$max);
		$query->setFetchMode(PDO::FETCH_ASSOC);
		$query->execute();
		$posicao_vetor_latitude = $i == (count($vetor_indices)-1) ? count($array_latitude) : $vetor_indices[$i+1]['indice_vetor'];
		while($local = $query->fetch()){
			for($i_latLong = $vetor_indices[$i]['indice_vetor']; $i_latLong < $posicao_vetor_latitude; $i_latLong++){
				$distancia_local = distancia($array_latitude[$i_latLong],$array_longitude[$i_latLong],$local['lat'],$local['long']);
				if($distancia_local <= $distancia){
					$local['distancia'] = $distancia_local;
					array_push($vetor_local,$local);
					$i_latLong = $posicao_vetor_latitude;
				}
			}
		}
	}

	$vetor_local_final = array();
	for($i = 0;$i < count($vetor_local); $i++){
		$get_avaliacoes = Conexao::getInstance()->prepare("SELECT categoria.titulo,categoria.icon,categoria.descricao,
										(SELECT round(avg(nota),1) FROM avaliacao WHERE avaliacao.local = :local AND avaliacao.categoria = servicos.categoria) as nota
										FROM servicos,categoria WHERE servicos.local = :local AND categoria.id = servicos.categoria ");
		$get_avaliacoes->bindValue("local",$vetor_local[$i]['id']);
		$get_avaliacoes->execute();
		$vetor_local[$i]['avaliacao'] = $get_avaliacoes->fetchAll();
		array_push($vetor_local_final, $vetor_local[$i]);
	}

	echo json_encode($vetor_local_final);

