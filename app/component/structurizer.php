<?php




class Structurizer{
    private $edu;
    private $structuring_template;
    public function __construct($edu = "NPUD"){
        $this->edu = $edu;
        $this->structuring_template = require 'structuring_template.php';
    }

    public function structuring(array $data, $type){
        $structured_data = array();
        if(array_key_exists("couples", $data[0])){
            $structur_template = $this->structuring_template[$this->edu][$type];
            foreach ($data as $schedule) {
                $couples = array();
                foreach ($schedule['couples'] as $couple) {
                    
                    $w_structuring = $couple['detail'];
                    $structurted_detail = array();
                    foreach ($structur_template as $key => $value) {
                        preg_match($value, $w_structuring, $match_str);
                       
                        $structured_detail[$key] = $match_str[1];
                        
                        
                        
                    }
                    $couple['detail'] = $structured_detail;
                
                    $couples[] = $couple;
                    
                }
                $schedule['couples'] = $couples;
                $structured_data[] = $schedule;
            }
        }
        else{
            foreach ($data as $couple) {
                $structur_template = $this->structuring_template[$this->edu];
                $w_structuring = $couple['detail'];
                $structured_detail = array();
                foreach ($structur_template as $key => $value) {
                    preg_match($value, $w_structuring, $match_str);
                   
                    $structured_detail[$key] = $match_str[1];
                    
                    
                    
                }
                $couple['detail'] = $structured_detail;
                
                $structured_data[] = $couple;
               
                
            }
        }
        return $structured_data;
        
    }



}
