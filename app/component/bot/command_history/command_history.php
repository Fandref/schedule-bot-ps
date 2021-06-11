<?php



class CommandHistory{
    private $user_id;
    private $db;
    private $command_history;
    public function __construct($user_id){
        $load = new Loader();
        $this->db = $load->library("db", [DB_DRIVER, DB_HOSTNAME, DB_USERNAME, PASSWORD, DB_DATABASE]);
        $this->user_id = strval($user_id);
        $this->command_history = $this->getCommandFromStorage();
    }
    private function getCommandFromStorage(){
        $user_id = $this->user_id;
        $params = ['user_id' => $user_id];
        $command =  $this->db->query("SELECT last_command FROM history_command WHERE user_id = :user_id", $params, 0);
        $command = $command['last_command'];
        if(!isset($command)){
            $this->db->query("INSERT INTO history_command (user_id) VALUES (:user_id)", $params);
        }
        return $command;
    }
    public function get(){
        return $this->command_history ?? false;
    }

    public function set(string $command){
        $this->command_history = $command;
        
    }

    
    public function clear(){

        $this->command_history = NULL;

    }

    private function saveToStorage(){
        $command = $this->command_history;
        $params = [
            'user_id' => $this->user_id,
            'command' => $command
        ];
 
        $this->db->query("UPDATE history_command SET last_command = :command WHERE user_id = :user_id", $params);

    }

    function __destruct(){
        $this->saveToStorage();
    }

}
