<?php

class RolesController extends Controller {
	/** @var RolesModel $model */
	private $model;
	public function __construct($action, $params) {
		parent::__construct($action, $params);
		$this->model = $this->get_model('roles');
	}

	public function add() {
		$result = $this->model->add($this->get('role'));
		$status = $result ? self::SUCCESS : self::ERROR;
		return [
			'status' => $status
		];
	}

	public function delete() {
		$result = $this->model->delete((int)$this->get('id'));
		$status = $result ? self::SUCCESS : self::ERROR;
		return [
			'status' => $status
		];
	}
}