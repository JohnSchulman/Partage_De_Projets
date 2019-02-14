<?php

require_once __DIR__.'/autoload.php';
// format de données du document
if(isset($_GET['return_type']) && $_GET['return_type'] === 'html') {
	header('Content-Type: text/html');
}
else {
	header('Content-Type: application/json');
}
define('__ROOT__', __DIR__);

try {
	if(isset($_GET['controller'])) {
		$setup = new Setup($_GET['controller']);
	}
	else {
		throw new Exception('Vous devez définir un controlleur !');
	}
	echo $setup->run();
} catch (Exception $e) {
	exit(
		json_encode(
			[
				'error' => 500,
				'message' => $e->getMessage()
			]
		)
	);
}