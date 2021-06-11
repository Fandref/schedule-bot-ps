<?php


class Main{
    protected $registry;
    private $role_name;
    public function __construct(){
        $this->registry = new Registry();

        $this->load = new Loader();
        $this->bot = $this->load->component('bot/bot');
        $this->storage = $this->load->component('storage');
        $this->id = $this->bot->getUserId();
        $form = $this->load->component('form/form', $this->id);
        
        if($this->storage->existUser($this->id)){
            $role = $this->storage->getRole($this->id);

            $this->role_name = $role == 1 ? "teacher/" : "group/";

            if($form->isInit()){
                if($form->isFieldInit('name')){
                    $this->role_name .= "teacher/";
                    $this->expansion = true;
                }
                else if($form->isFieldInit('group')){
                    $this->role_name .= "group/";
                    $this->expansion = true;
                }
                
            }
            else{
                unset($this->expansion);
            }
        }
        else{
            $this->role_name =  "";
        }

        


        
    }
    public function main(){
        $this->bot->onCommand('start', $this->runHandler('wellcome'));
        if(!$this->storage->existUser($this->id)){
            $this->bot->onCommand('start', $this->runHandler('registration'));
            $this->bot->onCommand('registration', $this->runHandler('registration'));
        }
            
        else{
    
            $this->tools();
        }
        
    }



    private function tools(){
        try {
            // $up = $this->bot->getUpdate();
            
            //menus
            $this->bot->onMessage("Головне меню\s?|\s📚?", $this->runHandler('mainMenu'));

            $this->bot->onMessage("Розклад\s?|\s🗒?", $this->runHandler('menuSchedule'));

            $this->bot->onMessage("Викладацький\s?|\s🧑‍🏫?", $this->runHandler('menuScheduleTeacher'));

            $this->bot->onMessage("Студенський\s?|\s👨‍🎓?", $this->runHandler('menuScheduleGroup'));
            
            $this->bot->onMessage("На день", $this->runHandler('dayScheduleMenu'));
            
            $this->bot->onMessage("На тиждень", $this->runHandler('weekScheduleMenu'));
            
            $this->bot->onMessage("За певний період", $this->runHandler('menuPeriodSchedule'));


            
            //show schedule
            $this->bot->onMessage("Швидкий розклад\s?|\s🚀?", $this->runHandler('quickSchedule'));

            $this->bot->onMessage("Сьогодні", $this->runHandler('scheduleToday'));

            $this->bot->onMessage("Завтра", $this->runHandler('scheduleTomorrow'));
                
            $this->bot->onMessage("Певна дата", $this->runHandler('scheduleCertainDate'));                

            $this->bot->onMessage("Поточний ⌛️", $this->runHandler('currentWeekSchedule'));
                
            $this->bot->onMessage("Від сьогодні", $this->runHandler('todayWeekSchedule'));
                
            $this->bot->onMessage("Від певної дати", $this->runHandler('certainDateWeekSchedule'));

            $this->bot->onMessage('Поточний до дати ⌛️', $this->runHandler('currentToCertainDateSchedule'));

            $this->bot->onMessage('Від сьогодні до дати', $this->runHandler('todayToCertainDateSchedule'));

            $this->bot->onMessage('Від дати до дати', $this->runHandler('dateToCertainDateSchedule'));
            

            // //donate
            $this->bot->onMessage('Підтримати\s?|\s💰?', $this->runHandler('donate'));

            $this->bot->onMessage('Допомога\s?|\sℹ️?', $this->runHandler('help'));

            $this->bot->onMessage('😘|😍|💕|🍬|🤗|😻|❤️|🧡|💛|💚|💙|💜|💋', $this->runHandler('cute'));

            // $this->bot->sendMessage($this->id, [
            //     'text' => "А я такого не знаю 😥"
            // ]);

        } catch (\Throwable $th) {
           
            if($th->getCode() > 0){
                $this->bot->sendMessage($this->id, [
                    'text' => $th->getMessage()
                ]);
                $this->runHandler('mainMenu')();
                
            }
            

            else{
                $this->bot->sendMessage($this->id, [
                    'text' => strval($th)
                ]);
                echo $th->getMessage();
            }
                
    
        }
        
        

    }

    

    public function runHandler($name_action){

        return function() use ($name_action){
            $name_class = $this->role_name;
            $expansion = $this->expansion;
            $load_action;
   
            if($expansion){
                unset($this->expansion);
                $load_action .= $name_class.$name_action.'Opposite';
            }
            else
                $load_action = $name_class.$name_action;
            
            
        
            $action = $this->load->action($load_action, [$this->registry]) ?? $this->load->action($name_action, [$this->registry]);
            
            $action->handler();

        };
        

    }
    
    
    

    // private function start(){
    //     if(!$this->storage->existUser($id)){
    //         $this->bot->onCommand("start", function(){
    //             $this->bot->sendMessage($id, [
    //                 'text' => "Привіт! Я <b>NPURozkladBot</b> 🤖. Якщо тобі потрібний розклад пар, я обов'язково з цим допоможу. Все що протрібно - дати відповідь на декілька моїх запитань📝",
    //                 'reply_markup' => [
    //                     'remove_keyboard' => true
    //                 ]
    //             ]);
    //             $this->bot->sendMessage($id, [
    //                 'text' => "Хто ви?",
    //                 'reply_markup' => [
    //                     'inline_keyboard' => [
    //                         [
    //                             ['text' => 'Студент', 'callback_data' => 'role_student']
    //                         ],
    //                         [
    //                             ['text' => 'Викладач', 'callback_data' => 'role_teacher']
    //                         ]
    //                     ]
    //                 ]
                
    //             ]);
    //         });
        
            
        
           
    //     }



        

    // }
    public function __set($name, $value){
        $this->registry->set($name, $value);
    }

    public function __get($name){
        return $this->registry->get($name);
    }

    private function toCallback(string $name, array $params = null, $object = null){
        // var_dump("<br><br>");
        $object = $object ?? $this;

       
        if(isset($params))
            return array(array($object, $name), $params);
        return array($object, $name);
   }

}