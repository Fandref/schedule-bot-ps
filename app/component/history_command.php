<?php



class HistoryCommand{
    private $history;
    public function __construct(){
        session_start();
        $this->history = $_SESSION; 
    }
    public function getLastCommand(){

    }
}