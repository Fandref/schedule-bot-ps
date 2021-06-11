<?php
	abstract class Model
	{
		protected $registry;
		public function __construct($registry = false){
			if(!$registry){
				$registry = new Registry();
				$registry->set("load", new Loader());
			}
			$this->registry = $registry;
			$this->db = $this->load->library("db", [DB_DRIVER, DB_HOSTNAME, DB_USERNAME, PASSWORD, DB_DATABASE]);
		}
		public function __get($key){
			return $this->registry->get($key);
		}
		public function __set($key, $name){
			return $this->registry->set($key, $name);
		}
	}