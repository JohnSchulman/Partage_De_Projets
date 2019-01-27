<?php

class Model {
	/**
	 * @param $model
	 * @return mixed
	 * @throws Exception
	 */
	protected function get_model($model) {
		$model = ucfirst($model);
		$model = $model.'Model';
		if(file_exists(__DIR__.'/../models/'.$model.'.php')) {
			require_once __DIR__.'/../models/'.$model.'.php';
			return new $model();
		}
		else {
			throw new Exception('La classe '.$model.' n\'existe pas !');
		}
	}
}