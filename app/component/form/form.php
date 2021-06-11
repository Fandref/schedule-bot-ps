<?php

class Form{
    private $form;
    private $db;
    protected $user_id;
    function __construct($user_id){
        $load = new Loader();
        $this->db = $load->library("db", [DB_DRIVER, DB_HOSTNAME, DB_USERNAME, PASSWORD, DB_DATABASE]);
        $this->user_id = strval($user_id);
        $this->form = $this->getFormFromStorage();
    }
    private function getFormFromStorage(){
        $user_id = $this->user_id;
        $params = ['user_id' => $user_id];
        $form =  $this->db->query("SELECT form_data FROM forms WHERE user_id = :user_id", $params, 0);
        $form = $form['form_data'];
        $pre_form = isset($form) ? unserialize($form) : NULL;
        if(!isset($form)){
            $this->db->query("INSERT INTO forms (user_id, form_data) VALUES (:user_id, '')", $params);
        }
        return $pre_form ?? array();
    }

    public function init(array $fields){
        foreach($fields as $field)
            $this->initField($field);
    }

    public function initField(string $name){
        $this->$name = ''; 
    }
    public function isFieldInit($name){
        return array_key_exists($name, $this->form);
    }

    public function refreshField(string $name){
        if($this->isFieldInit($name)){
            $this->form[$name] = '';
        }
    }
    public function isInit(){
        
        return count($this->form) > 0 && isset($this->form) ? true : false;
    }

    public function __set($name, $value){
        $this->form[$name] = $value;
    }

    public function __get($name){
        if(isset($name)){
            $value = $this->form[$name];
            return $value;
        }
        else 
            return null;
        
    }
    public function __isset($name){
        $value = $this->form[$name] ?? "";
        $value = str_replace(' ', '', $value);
        return strlen($value) > 0 ? true : false;
    }
    public function popForm(){
        $form_data = $this->form;
        $this->deleteForm();
        return $form_data;
    }
    public function deleteForm(){
        $this->form = array();
    }

    private function saveToStorage(){
        $form_data = serialize($this->form);
        $params = [
            'user_id' => $this->user_id,
            'form_data' => $form_data
        ];
      
        $this->db->query("UPDATE forms SET form_data = :form_data WHERE user_id = :user_id", $params);

    }
    public function __destruct(){
        $this->saveToStorage();
    }
    

    


}