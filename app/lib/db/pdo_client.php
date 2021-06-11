<?php
	final class PDOClient{
		private $link; 
		private $statement;
		public function __construct($db_system, $hostname, $username, $password, $database, $port = 3306){
			try {
				$this->link = new \PDO($db_system.":host=" . $hostname . ";port=" . $port . ";dbname=" . $database, $username, $password, array(\PDO::ATTR_PERSISTENT => true));
			} catch(\PDOException $e) {
				throw new \Exception('Failed to connect to database. Reason: \'' . $e->getMessage() . '\'');
			}
			$this->link->exec("SET GLOBAL connect_timeout=100");
			$this->link->exec("SET NAMES 'utf8'");
			$this->link->exec("SET CHARACTER SET utf8");
			$this->link->exec("SET CHARACTER_SET_CONNECTION=utf8");
		}
		public function query($sql, $params = [], $index = -1) {

		$this->statement = $this->link->prepare($sql);
		if (!empty($params)) {
			foreach ($params as $key => $val) {
				$this->statement->bindValue(':'.$key, $val);
			}
		}
		try {
			if($this->statement && $this->statement->execute()){
			
				if($index >= 0){
					$current_index = 0;
					while (($row = $this->statement->fetch(\PDO::FETCH_ASSOC)) && $current_index <= $index) {
						if($current_index++ == $index){
							return $row;
						}
					}
				}
				else{
					$data = $this->statement->fetchAll(\PDO::FETCH_ASSOC);
					return is_array($data) ? (count($data)>0 ? $data : true) : false;
				}
			}
		} catch (\PDOException $e) {
			throw new \Exception('Error: ' . $e->getMessage() . ' Error code: ' . $e->getCode() . ' <br />' . $sql);
		}
	}
		public function countAffected() {
			if ($this->statement) {
				return $this->statement->rowCount();
			} else {
				return 0;
			}
		}

		public function lastInsertId() {
			return $this->link->lastInsertId();
		}
		
		public function isConnected() {
			if ($this->link) {
				return true;
			} else {
				return false;
			}
		}
		
		public function __destruct() {
			$this->link = null;
		}
	}