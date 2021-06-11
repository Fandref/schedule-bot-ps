<?php
class Request {
	public $get = array();
	public $post = array();
	public $put = array();
	public $patch = array();
	public $delete = array();

	public function __construct() {
		$this->get_request_params();
	}
	private function get_request_params(){
		if(isset($_POST)){
			$this->post = $this->migration($_POST);
		}
		if(isset($_GET)){
			$this->get = $this->migration($_GET);
		}
		if (isset($_SERVER['REQUEST_METHOD'])){
			switch ($_SERVER['REQUEST_METHOD']){
				case 'POST':
					if(isset($_POST)){
						$this->post = $this->migration($_POST);
					}
					break;
				case 'PUT':
					parse_str(file_get_contents("php://input"), $put);
					
					if($put){
						$this->put = $this->migration($put);
					}
					break;
				case 'PATCH':
					parse_str(file_get_contents("php://input"), $patch);
					
					if($patch){
						$this->patch = $this->migration($patch);
					}
					break;
				case 'DELETE':
					parse_str(file_get_contents("php://input"), $delete);
					if($delete){
						$this->delete = $this->migration($delete);
					}
					break;
			}

		}
	}
	private function migration($data){
		$to_registry = array();
		foreach($data as $key=>$val){
			$to_registry[$key] = $val;
		}
		unset($data);
		return $to_registry;
	}
}