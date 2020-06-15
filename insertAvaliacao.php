<?php 
	require_once __DIR__ . '\Conexao.php';
	$avaliacoes	 		= $_POST['avaliacoes'];

	$resultado = array(
		"resultado" => "successo"
	);

	for($i = 0; $i < count($avaliacoes);$i++){
		$query = Conexao::getInstance()->prepare("INSERT INTO avaliacao (local,usuario,nota,categoria) VALUES (:local,1,:nota,:categoria);");
		echo $avaliacoes[$i]['id_local'];
		$query->bindValue("local"		,	$avaliacoes[$i]['id_local']			, PDO::PARAM_INT);
		$query->bindValue("nota"		,	$avaliacoes[$i]['estrelas']			, PDO::PARAM_INT);
		$query->bindValue("categoria"	,	$avaliacoes[$i]['categoria']		, PDO::PARAM_INT);
		$query->execute();
	}
	echo json_encode($resultado);


