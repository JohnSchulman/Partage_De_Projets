<?php

class BaseModel extends Model {
	protected $mysql;

	/**
	 * BaseModel constructor.
	 *
	 * @throws Exception
	 */
	public function __construct() {
		/** @var DatabaseModel $database */
		$database = $this->get_model('database');
		$this->mysql = $database->get_mysql_connector();
	}

}