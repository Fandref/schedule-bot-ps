<?php

interface ActionInterface{
    public function handler();
}

abstract class Action{
    const main_menu = [
        'keyboard' => [
            [
                'Швидкий розклад 🚀',
                'Розклад  🗒'
            ],
            [
                'Підтримати 💰',
                'Допомога ℹ️'
            ]
        ],
        'resize_keyboard' => true
    ];

    const main_menu_button = [
        'Головне меню 📚'
    ];

    const diff_in_menu_schedule = [
        1 => [
                'Студенський 👨‍🎓'
             ],
        // for student 
        2 => [
                'Викладацький 🧑‍🏫'
             ]
         
    ];

    

    protected $registry;
    abstract public function handler();

    public function __construct(Registry $registry  = null){
        if(!isset($registry)){
            $this->registry = new Registry();
            $this->load = new Loader();
            $this->schedule = $this->load->component('schedule');
            $this->bot = $this->load->component('bot/bot');
            $this->storage = $this->load->component('storage'); 
            $this->advisor = $this->load->component('advisor');
        }
        else{
            $this->registry = $registry;
            // var_dump($this->bot->history_command->get());
            if(!isset($this->load))
                $this->load = new Loader();
            
            if(!isset($this->schedule))
                $this->schedule = $this->load->component('schedule');
            if(!isset($this->bot))
                $this->bot = $this->load->component('bot/bot');
            
            if(!isset($this->storage))
                $this->storage = $this->load->component('storage');
            
            if(!isset($this->advisor))
                $this->advisor = $this->load->component('advisor');
            
            
        }

        $this->id = $this->bot->getUserId();
        $this->date_now = date('d.m.Y');
        if($this->storage->existUser($this->id))
            $this->role = $this->storage->getRole($this->id);
        else 
            $this->role = 0;
        
        if($this->role == 1){
            $this->method_name = 'Teacher';
            $this->name = $this->storage->getNameTeacher($this->id);   
        }
        else if($this->role == 2){
            $this->method_name = 'Group';
            $this->group = $this->storage->getGroup($this->id);
        }
        
        
    }

    protected function printSchedule($schedule){
        $formatter = $this->load->component('formatter');
        $formatted_schedule = $formatter->formattingDataSchedule($schedule, Formatter::DAY_SCHEDULE_TOGETHER);
        $last_element_schedule = array_pop($formatted_schedule);
        foreach($formatted_schedule as $schedule){     
            $this->bot->sendMessage($this->id, [
                'text' => $schedule,
                'disable_notification' => true
            ]);
        }
        $this->bot->sendAnswer($this->id, [
            'text' => $last_element_schedule,
            'disable_notification' => true,
            'reply_markup' => self::main_menu
        ]);
    }

    function formatingOtherVariable(array $variables, string $callback_data = null){
        $formated_variable = array();
        foreach($variables as $variable){
            $formated_variable[] = [[
                'text' => $variable,
                'callback_data' => $variable
            ]];
        }
        return $formated_variable;
    }

    function isDate($str_check){
        try{
            if(preg_match('/^(\d{2})\.(\d{2})\.(\d{4})$/', $str_check, $date_parts)){
                $date = new DateTime($date_parts[3].'-'.$date_parts[2].'-'.$date_parts[1]);
                
                return true;
            }
            else{
             
                $date_now = date('d.m.Y');
                $this->bot->sendMessage($this->id, [
                    'text' => "Здається, це не дата 😧\nДата повинна бути в форматі: <b>{$date_now}</b>"
                ]);
             
            }
               
        } catch (\Exception $th) {
            if($th->getCode() == 0){
                $this->bot->sendMessage($this->id, [
                    'text' => "Ой 😳 \nЯ не знаю такої дати \n Можливо, написано з помилкою"
                ]);
            }
            else 
                throw $th;
            
        }
        return false;
    }

    protected function renderScheduleMenu(bool $nested = false){
        $menu_schedule = [
            'keyboard' => [
                [
                    'На день', 'На тиждень'
                ],
                [
                    'За певний період'
                ],   
                
            ]
        ];
        if(!$nested)
            $menu_schedule['keyboard'][] = self::diff_in_menu_schedule[$this->role];
        
        $menu_schedule['keyboard'][] = self::main_menu_button;
        return $menu_schedule;
    
    }


    protected function getTeacherName(){
    
        $callback = $this->bot->getCallback();
        $teacher = $this->bot->getMessage()['text'] ?? $callback['data'];
    
        $is_teacher = $this->advisor->verifyTeacher($teacher) ?? "foo";

        if($is_teacher && !is_array($is_teacher)){
            if($callback){
                $message_id = $callback['message']['message_id'];
                $chat_id = $callback['message']['chat']['id'];
                $this->bot->editMessage($chat_id, $message_id, 
                [
                    'text' => $teacher,
                    'reply_markup' => null
                    ]
                );

            }
            return $teacher;
        }
        else if(!$is_teacher){
            $this->bot->sendMessage($this->id, [
                'text' => "Напевно, ти не знаєш як звати викладача 😱"
            ]);
        }
        else if(is_array($is_teacher)){
            
            if(count($is_teacher) > 50){
                $this->bot->sendMessage($this->id, [
                    'text' => "Ой, 😳 можна трішке точніше? А то я розгубився.."
                ]);
            }
            else {
                $prepareted_teachers = $this->formatingOtherVariable($is_teacher); 
  
                $this->bot->sendMessage($this->id, [
                    'text' => "Можливо це хтось із них",
                    'reply_markup' => [
                        'inline_keyboard' => $prepareted_teachers
                    ]                
                ]);
                
            }
            
        }
            
        
        return false;
    }

    protected function getGroup(){
        $callback = $this->bot->getCallback();
        $group = $this->bot->getMessage()['text'] ?? $callback['data'];
        $is_group = $this->advisor->verifyGroup($group);
        if($is_group && !is_array($is_group)){
            if($callback){
                $message_id = $callback['message']['message_id'];
                $chat_id = $callback['message']['chat']['id'];
                $this->bot->editMessage($chat_id, $message_id, 
                [
                    'text' => $group,
                    'reply_markup' => null
                    ]
                );

            }
            return $group;
        }
        else if(!$is_group){
            $this->bot->sendMessage($this->id, [
                'text' => "Здається, такої групи немає"
            ]);
        }
        else if(is_array($is_group)){
            if(count($is_teacher) > 50){
                $this->bot->sendMessage($this->id, [
                    'text' => "Ой, 😳 можна трішке точніше? А то я розгубився.."
                ]);
            }
            else {
                $prepareted_groups = $this->formatingOtherVariable($is_group); 
                $this->bot->sendMessage($this->id, [
                    'text' => "Можливо якась із цих груп?",
                    'reply_markup' => [
                        'inline_keyboard' => $prepareted_groups
                    ]                
                ]);
            }
        }
        
        

        return false;
    }


    public function __get($key) {
		return $this->registry->get($key);
	}

	public function __set($key, $value) {
		$this->registry->set($key, $value);
	}
    public function __isset($key){
        return $this->registry->has($key);
    }

    

}