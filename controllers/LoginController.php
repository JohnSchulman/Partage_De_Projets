<?php

class LoginController extends Controller {
	/** @var LoginModel $model */
	private $model;

	/**
	 * LoginController constructor.
	 *
	 * @param $action
	 * @param $params
	 * @throws Exception
	 */
	public function __construct($action, $params) {
		parent::__construct($action, $params);
		$this->model = $this->get_model('login');
	}

	public function index() {
		return $this->login();
	}

	public function login() {
		if($this->model->user_exists($this->get('email'), $this->get('password'))) {
			$user = $this->model->get_current_user($this->get('email'), $this->get('password'));
			$this->model->set_session($user);
			return $user;
		}
		return [];
	}

	public function disconnect() {
		return [
			'success' => $this->model->delete_session(),
		];
	}

	public function is_logged() {
		return [
			'result' => $this->model->get_logged_user(),
		];
	}

	public function delete_user() {
		if($this->get('id')) {
			if ($this->model->delete_user($this->get('id'))) {
				return [
					'status' => 'success'
				];
			}
			return [
				'status' => 'error'
			];
		}
		return [
			'status' => 'error',
			'message' => 'id parameter is requires',
		];
	}
}