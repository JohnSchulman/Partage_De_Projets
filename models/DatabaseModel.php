<?php

class DatabaseModel extends Model {
	private $mysql_conf;
	private $mysql;

	public function __construct() {
		// Récupère les données du fichier de configuration conf/mysql.php dans la propriété mysql_conf
		$this->mysql_conf = new Conf('mysql');
		$this->mysql = new mysqli(
			$this->mysql_conf->get('host'),
			$this->mysql_conf->get('user'),
			$this->mysql_conf->get('password'),
			$this->mysql_conf->get('database')
		);
	}

	public function get_mysql_connector() {
		return $this->mysql;
	}
}