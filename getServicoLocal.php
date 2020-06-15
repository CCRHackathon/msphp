<?php 
	require_once __DIR__ . '\Conexao.php';
	$local 		= $_REQUEST['local'];
	header('Content-Type: application/json');
	$query = Conexao::getInstance()->prepare("
		SELECT categoria.*
		FROM categoria,servicos
		WHERE categoria.id = servicos.categoria AND servicos.local = :local
	");
	$query->bindValue("local"	,	$local  							);
	$query->setFetchMode(PDO::FETCH_ASSOC);
	$query->execute();
	echo json_encode($query->fetchAll());


