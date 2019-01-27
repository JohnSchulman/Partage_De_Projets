<?php

class TotoController extends Controller {
	/**
	 * @return array
	 * @throws Exception
	 */
	protected function test() {
		// Méthode qui vérifie si la classe existe,
		//si c'est le cas
		//elle renvoie une instance de cette classe 
		//et si non elle lance une exception.
		$model = $this->get_model('toto');
		$result = $model->my_test();
		$conf = new Conf('mysql');
		$host = $conf->get('host');

		if($this->get('nom')) {
			return [
				'salut' => $this->get('nom'),
				'host' => $host,
			];
		}
		else {
			return [];
		}
	}
}