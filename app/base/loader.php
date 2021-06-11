<?php
final class Loader{
	// protected $registry;
	private $lpath = '/lib';
	private $cppath = '/component';
	private $apath  = '/action';

	private function get_file($dir, $route){
		try {
			$file = DIR_APP.$dir."/".$route.".php";

			if(file_exists($file)){
				require_once $file;
				preg_match('/[^\/]+$/', $route, $match_name);
				$filename = $match_name ? $match_name[0] : $route;
				return preg_replace('/[^a-zA-Z0-9]/', '', $filename);
			}
			else{
				return null;
			}

		} catch (\Exception $e) {
			throw new \Exception("Direction no found", 1);
			
		}
	}
	public function component($route, $params = array()){
		$c_name = $this->get_file($this->cppath, $route);
		if($c_name){
			$class_name = $c_name;
				if(is_array($params))
					$obj = $params == false ? new $class_name() : new $class_name(...$params);
				else
					$obj = new $class_name($params);
		
				return $obj;
		}
		else{
			return $c_name;
		}	
	}
	
	public function library($route, $params = array()){
		$c_name = $this->get_file($this->lpath, $route);
		if($c_name){
			$class_name = $c_name;
				if(is_array($params))
					$obj = $params == false ? new $class_name() : new $class_name(...$params);
				else
					$obj = new $class_name($params);
				return $obj;
		}
		else{
			return $c_name;
		}
	}

	public function action($route, $params = array()){
		$c_name = $this->get_file($this->apath, $route);
		if($c_name){
			$class_name = $c_name."Action";
				if(is_array($params))
					$obj = $params == false ? new $class_name() : new $class_name(...$params);
				else
					$obj = new $class_name($params);
				return $obj;
		}
		else{
			return $c_name;
		}
	}
}