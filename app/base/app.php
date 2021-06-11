<?php

final class App{
	protected $registry;
	protected $params = array();
	public function __construct($registry = false){
		if(!$registry)
			$registry = new Registry();
		$this->registry = $registry;
		$this->register_routs();
	}
	
}