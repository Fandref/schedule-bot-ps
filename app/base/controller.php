<?php
	abstract class Controller
	{
		protected $registry;
		public function __construct($registry = false){
			if(!$registry){
				$registry = new Registry();
				$registry->set("load", new Loader());
			}
			$this->registry =  $registry;
			$this->model = $this->load->model($this->route);
			$this->request = $this->load->library('request');
		}

		public function __get($key){
			return $this->registry->get($key);
		}
		public function __set($key, $name){
			return $this->registry->set($key, $name);
		}
		public function send_answer(int $code, array $answer = array()){
			http_response_code($code);
			
			if($code >= 400 && !array_key_exists("error", $answer)){
				$answer['error'] = "bad request";
			}
			$full_answer = array("code" => $code);
			if(!array_key_exists("error", $answer))
				$full_answer["result"] = $answer ? $answer : null;
			else
				$full_answer['error'] = $answer['error'];
			http_response_code($code);
   	 	echo json_encode($full_answer);
   	 	exit();
		}
	}