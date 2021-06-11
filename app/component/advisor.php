<?php


class Advisor{
    private $url_query;
    public function __construct(){
        // $this->$url_teacher_query = 'http://www.nmu.npu.edu.ua/cgi-bin/timetable.cgi?n=701&lev=141&faculty=0&query=';
        // $this->$url_query_group = 'http://www.nmu.npu.edu.ua/cgi-bin/timetable.cgi?n=701&lev=142&faculty=0&query=';
        $this->$url_query = 'http://www.nmu.npu.edu.ua/cgi-bin/timetable.cgi?';
    }

    public function verifyGroup(string $query){
        $data_answer = $this->getListGroup($query);

        if(!is_array($data_answer) || count($data_answer)==0){
            return false;
        }
        else if(count($data_answer)==1){
            return true;
        }
        else {
            foreach($data_answer as $group){
                if($query == $group)
                    return true;
            }
            return $data_answer;
        }
    }

    public function getListGroup(string $query){
        $query_params = [
            'n' => 701,
            'lev' => 142,
            'query' => $query
        ];
        return $this->searchQuery($query_params);
    }

    public function verifyTeacher(string $query){
        $data_answer = $this->getListTeachers($query);

        if(!is_array($data_answer) || count($data_answer)==0){
            return false;
        }
        else if(count($data_answer)==1){
            return true;
        }
        else {
            
            return $data_answer;
        }
    }

    public function getListTeachers(string $query){
        $query_params = [
            'n' => 701,
            'lev' => 141,
            'query' => $query
        ];

        return $this->searchQuery($query_params);
        
    }
    public function searchQuery($query_params){
        // $query_params = mb_convert_encoding($query_params, "windows-1251", "utf-8");
        $prepared_query = http_build_query($query_params, null, '&', PHP_QUERY_RFC3986);
        // var_dump($prepared_query == 'n=701&lev=141&query=%D0%86%D0%B2%D0%B0%D0%BD');
        

        $answer_query = file_get_contents($this->$url_query.$prepared_query);
        // throw new Exception(serialize($answer_query), 0);
        $answer_query = mb_convert_encoding($answer_query, "utf-8", "windows-1251");
        
        // throw new Exception($prepared_query, 0);
        

        $data_answer = json_decode($answer_query, true);

        // throw new Exception(serialize($data_answer), 0);

        

        return $data_answer['suggestions'];
    }
}