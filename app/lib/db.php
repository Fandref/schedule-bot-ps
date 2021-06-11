<?php

class Db{
	private $session;
	public function __construct($db_system, $hostname, $username, $password, $database)
	{
		if(PDOCLIENT){
			require_once 'db/pdo_client.php';
			if (class_exists('PDOClient')) {
				$this->session = new PDOClient($db_system, $hostname, $username, $password, $database, $port);
			} else {
				throw new \Exception('Error: Could not load database !');
			}
		}
		else{
			require_once 'db/'.$db_system;
			$class = ucfirst($db_system)."Db";
			if (class_exists($class)) {
				$this->session = new $db_system($hostname, $username, $password, $database, $port);
			} else {
				throw new \Exception('Error: Could not load database !');
			}
		}
		
	}

	public function query($sql, $params = [], $index = -1) {
		return $this->session->query($sql, $params, $index);
	}

	public function countAffected(){
		return $this->session->countAffected();
	}

	public function lastInsertId()
	{
		return $this->session->lastInsertId();
	}

	public function connected() {
		return $this->session->connected();
	}

}