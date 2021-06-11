<?php


class Formatter{
    private const mounth_name = array(
        1  => 'січня',
        2  => 'лютого',
        3  => 'березня',
        4  => 'квітня',
        5  => 'травня',
        6  => 'червня',
        7  => 'липня',
        8  => 'серпня',
        9  => 'вересня',
        10 => 'жовтня',
        11 => 'листопада',
        12 => 'грудня'

    );
    private const day_week_name = array(
        "середа" => "середу",
        "п'ятниця" => "п'ятницю",
        "субота" => "суботу",
        "неділя" => "неділю"
    );
    private const icon_for_couples = array(
        1 => '1️⃣',
        2 => '2️⃣',
        3 => '3️⃣',
        4 => '4️⃣',
        5 => '5️⃣',
        6 => '6️⃣',
        7 => '7️⃣',
        8 => '8️⃣',
        9 => '9️⃣'

    );
    public const COUPLE_DATA_TOGETHER = 0;
    public const DAY_SCHEDULE_TOGETHER = 1;
    public const SCHEDULE_SEPARATE = 2;

    public function __construct(array $schedule = null, int $flag = 2){
        if(isset($schedule))
            return $this->formatting_schedule($schedule);
    }

    public function formattingDataSchedule(array $schedule, int $flag = 2){
        $formatted_schedule = array();

        foreach($schedule as $schedule_day){

            $formatted_schedule_day = array();

            if(!is_array($schedule_day['date'])){

                $date = new DateTime($schedule_day['date']);
                $n_mounth = intval($date->format("n"));
                $formatted_date = $date->format("d")." ".self::mounth_name[$n_mounth];  
                $case_day = $schedule_day['day_week'];
             
                $case_day = self::day_week_name[$case_day] ?? $case_day;
                $day_title = "Розклад на ".$case_day."  ";   
            }
            else{
                $start_date = new DateTime($schedule_day['date']['start']);
                $start_n_mounth = intval($start_date->format("n"));
                $start_formatted_date = $start_date->format("d")." ".self::mounth_name[$start_n_mounth];

                $start_case_day = $schedule_day['day_week']['start'];
                $start_case_day = self::day_week_name[$case_day] ?? $start_case_day;

                $end_date = new DateTime($schedule_day['date']['end']);
                $end_n_mounth = intval($end_date->format("n"));
                $end_formatted_date = $end_date->format("d")." ".self::mounth_name[$end_n_mounth];
                
                $end_case_day = $schedule_day['day_week']['end'];
                $end_case_day = self::day_week_name[$case_day] ?? $end_case_day;

                $day_title = $start_case_day." - ".$end_case_day; 

                $formatted_date = $start_formatted_date." - ".$end_formatted_date;
            }
            
            $formatted_schedule_day['title'] = "🧾 <b>{$day_title}</b>\n";
            $formatted_schedule_day['date'] = "📅 <code>{$formatted_date}</code>  \n\n\n";
            
            if(array_key_exists('couples', $schedule_day)){
                $formatted_schedule_day['couples'] = array();
                foreach($schedule_day['couples'] as $couple){
                    $couple_data = array();
                    $couple_n = intval($couple['number']);
                    $couple_data['number'] = self::icon_for_couples[$couple_n];
                    $couple_data['period'] = "[ ".$couple['period'][0]." - ".$couple['period'][1]." ]";
                    $couple_data['detail']['subject'] = $couple['detail']['subject'];
                    $couple_data['detail']['subject_type'] = "<code>(".$couple['detail']['subject_type'].")</code>";
                    unset($couple['detail']['subject']);
                    unset($couple['detail']['subject_type']);
                    foreach($couple['detail'] as $key => $detail){
                        $couple_data['detail'][$key] = "<code>{$detail}</code>";
                    }
                    if(array_key_exists('link', $couple)){
                        $couple_data['link'] = "<u>".$couple['link']."</u>";
                    }
                    
                    $formatted_schedule_day['couples'][] = $couple_data;
                }
            }
            else{
                $formatted_schedule_day['message'] = $schedule_day['message'];
            }
            $formatted_schedule[] = $formatted_schedule_day;
        }
        if($flag < 2){
            for($i = 0; $i < count($formatted_schedule); $i++){
                $schedule_day = $formatted_schedule[$i];
                if(array_key_exists('couples', $schedule_day)){
                    $couples = array();
                    foreach($schedule_day['couples'] as $couple){
                        $formatted_couple = "";
                        $formatted_couple .= $couple['number']."  ";
                        $formatted_couple .= $couple['period']."  ";
                        $formatted_couple .= $couple['detail']['subject']." ";
                        unset($couple['detail']['subject']);
                        $formatted_couple .= $couple['detail']['subject_type']."\n";
                        unset($couple['detail']['subject_type']);
                        if(array_key_exists('teacher', $couple['detail'])){
                            $formatted_couple .= $couple['detail']['teacher']."  ";
                            unset($couple['detail']['teacher']);
                        }
                        else if(array_key_exists('group', $couple['detail'])){
                            $formatted_couple .= $couple['detail']['group']."  ";
                            unset($couple['detail']['group']);
                        }
                        $formatted_couple .= ($couple['detail']['audience'] ?? "")."\n";
                        unset($couple['detail']['audience']);
                        $str_end = "";
                        foreach($couple['detail'] as $detail){
                            $formatted_couple .= $detail."  ";
                            $str_end = "\n";
                        }
                        if(array_key_exists('link', $couple))
                            $formatted_couple .= "\n".$couple['link'];
                        $formatted_couple .= $str_end;
                        $couples[] = $formatted_couple;
                    
                    }
                    $schedule_day['couples'] = $couples;
                }
                
                
                $formatted_schedule[$i] = $schedule_day;
            }
        }
        if($flag == 1){
            for($i = 0; $i < count($formatted_schedule); $i++){
                $schedule_day = $formatted_schedule[$i];
     
                $formatted_schedule_day = $schedule_day['title'];
                $formatted_schedule_day .= $schedule_day['date'];
                if(array_key_exists('couples', $schedule_day))
                    $formatted_schedule_day .= implode("\n", $schedule_day['couples']);
                if(array_key_exists('message', $schedule_day))
                    $formatted_schedule_day .= $schedule_day['message'];
               

                $formatted_schedule[$i] = $formatted_schedule_day;
            }
            
            
        }
        return $formatted_schedule;
    }
}