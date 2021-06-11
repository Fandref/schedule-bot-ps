<?php

class Schedule{
    public $url;
    public $edu;
    protected $show_empty;
    protected $get_link;
    private $parser;
    protected $load;
    protected const days_week = array(
        1 => "понеділок",
        2 => "вівторок",
        3 => "середа",
        4 => "четверг",
        5 => "п'ятниця",
        6 => "субота",
        7 => "неділя"
    );
    public function __construct($edu = "NPUD", $url = 'http://nmu.npu.edu.ua/cgi-bin/timetable.cgi?n=700', $show_empty = false,  $link = false){
        $this->url = $url;
        $this->edu = $edu;
        $this->show_empty = $show_empty;
        $this->get_link = $link;
        $this->load = new Loader();
        $this->parser = $this->load->component('parser', [$edu, $url]);
    }

    public function groupDay(string $group, string $date = "now"){
        $data = array(
            'group' => $group
        );
        return $this->day($data, $date);
    }
    public function groupWeek(string $group, string $date = "now"){
        $data = array(
            'group' => $group
        );
        return $this->week($data, $date);
    }

    public function groupPeriod(string $group, string $edate, string $sdate = "now"){
        $data = array(
            'group' => $group
        );
        return $this->period($data, $edate, $sdate);
    }
    
    public function filter(string $filter_string, array $data, string $by = 'teacher'){
        $filtred_data = array();
        foreach($data as $day){
            $filtred = array(
                'date' => $day['date'],
                'day_week' => $day['day_week']
            );

            $filtred_couples = array();
            foreach($day['couples'] as $schedule){
                if(strpos($schedule["detail"][$by], $filter_string) !== false){
                    $filtred_couples[] = $schedule;
                }
            }
            if(count($filtred_couples) != 0){
                $filtred['couples'] = $filtred_couples;
                $filtred_data[] = $filtred;
            }
           

        }
        return $filtred_data;
    }

    public function teacherDay(string $teacher, string $date = "now"){
        $data = array(
            'teacher' => $teacher
        );
        return $this->day($data, $date);
    }
    public function teacherWeek(string $teacher, string $date = "now"){
        $data = array(
            'teacher' => $teacher
        );
        return $this->week($data, $date);
    }

    public function teacherPeriod(string $teacher, string $edate, string $sdate = "now"){
        $data = array(
            'teacher' => $teacher
        );
        return $this->period($data, $edate, $sdate);
    }

    private function period(array $data, string $edate, string $sdate = "now"){

        list($data["sdate"], $data["edate"]) = $this->getPeriod($edate, $sdate); 

        $schedule = $this->parser->getData($data, $this->show_empty, $this->get_link);
        return $schedule;
    }
    private function week(array $data, string $date = "now"){
        if($date == "now")
            list($data["sdate"], $data["edate"]) = $this->getPeriod(7); 
        else
            list($data["sdate"], $data["edate"]) = $this->getPeriod(6, $date); 

        $schedule = $this->parser->getData($data, $this->show_empty, $this->get_link);
        if($date == "now"){
            $now_time = new DateTime('NOW');
            $last_couple = end($schedule[0]['couples']);
            $time_couple = new DateTime($last_couple['period'][1]);
            
            if($now_time > $time_couple)
                $schedule = array_filter($schedule, function ($day_schedule) use ($data){
                    return $day_schedule["date"] != $data["sdate"];
                });
            
            else
                $schedule = array_filter($schedule, function ($day_schedule) use ($data){
                    return $day_schedule["date"] != $data["edate"];
                });

        }
        return $schedule;
    }
    private function day(array $data, string $date = "now"){
        $insurance_day = 5;
        if($date == "now"){
            list($data["sdate"], $data["edate"]) = $this->getPeriod($insurance_day); 
        }
            
        else
            $data["sdate"] = $date;
        try {
            $schedule = $this->parser->getData($data, $this->show_empty, $this->get_link);
            if($date == "now"){
                $now_time = new DateTime('now');
                $last_couple = end($schedule[0]['couples']);
                $time_couple = new DateTime($last_couple['period'][1]);
                
                $evening = new DateTime("18:20");

                if(($now_time > $time_couple && $schedule[0]['date'] == $now_time->format('d.m.Y')) || $now_time >= $evening){
                    
                    $day_week = intval($now_time->format('N'));
                    $now_time->modify("+1 day");
                    
                    
                    if($schedule[0]['date'] != $now_time->format('d.m.Y') && $day_week<6)
                        $schedule[0] = $schedule[1];
                    
                    
                }
                
                $schedule = $this->check_range($schedule[0], $now_time, $insurance_day);
              

            }
            return $schedule;
        } catch (\Exception $th) {
            throw $date != "now" ? new \Exception("На цей день пар немає", 1): $th;
        }
        

    }

    private function getPeriod(string $end, string $start = "now"){
        $now_date = new DateTime($start);
        $sdate = $now_date->format('d.m.Y');

        if(preg_match('/\d{2}\.\d{2}\.\d{4}/', $end)){
            $end_date = new DateTime($end);
            $edate = $end_date->format('d.m.Y');
        }
        else{
            $day = intval($end);
            $now_date->modify('+'.$day.' day');
            $edate = $now_date->format('d.m.Y');
        }
        return [$sdate, $edate];
    }
    private function check_range(array $schedule, DateTime $start_time, int $insurance_day){
         
        $start_range = $start_time->format("Y-m-d");
        $period_str = 'R'.$insurance_day.'/'.$start_range.'T00:00:00Z/P1D';
        $period = new DatePeriod($period_str);
        $start_day = array();
        $end_day = array();
        foreach ($period as $p_date) {
            $p_date_str = $p_date->format('d.m.Y');
            if(in_array($p_date_str, $schedule)){
                $schedule = [$schedule];
                break;
            }
            else{
                $index_day = intval($p_date->format('N'));
                if(count($start_day) == 0 && $index_day < 6){
                    $start_day["date"] = $p_date_str;
                    $start_day["day_week"] = self::days_week[$index_day];
                    
                    
                }
                else if($index_day < 6){
                    $end_day['date'] = $p_date_str;
                    $end_day['day_week'] = self::days_week[$index_day];
                }
            }
        }
        if(count($start_day)>0){
            $empty_days = array(
                "date" => $start_day['date'],
                "day_week" =>  $start_day['day_week'],
                "message" => "Пар немає"
            );
            if(count($end_day)>0){
                $empty_days["date"] = [
                    'start' => $start_day['date'], 
                    'end' => $end_day['date']
                ];
                
                $empty_days["day_week"] = [
                    'start' => $start_day['day_week'], 
                    'end' => $end_day['day_week']
                ];
            }
                
            array_unshift($schedule, $empty_days);
        }

        
        return $schedule;
    }

    
    
    


}