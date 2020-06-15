<?php 
	require_once __DIR__ . '\Conexao.php';
	$local	 		= $_REQUEST['local'];
	header('Content-Type: application/json');

	$query = Conexao::getInstance()->prepare("SELECT * FROM avaliacao WHERE local = :local");
	$query->bindValue("local"		,	$local			, PDO::PARAM_INT);
	$query->execute();
	$array_avaliacao = array();
	while($local = $query->fetch()){
		$vetor = array();
		$vetor['id'] = $local['id'];
		$vetor['nota'] = $local['nota'];
		$vetor['comentario'] = $local['comentario'];
		$vetor['local'] = $local['local'];
		$vetor['usuario'] = $local['usuario'];
		array_push($array_avaliacao,$vetor);
	}
	echo json_encode($array_avaliacao);

