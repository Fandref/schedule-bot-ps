<?php


class RegistrationAction extends Action implements ActionInterface{
    public function handler(){
        $form = $this->load->component('form/form', [$this->id]);
        if(!$form->isInit()){
            $form->init([
                'role'
            ]);
            $this->bot->sendMessage($this->id, [
                'text' => "Хто ви?",
                'reply_markup' => [
                    'inline_keyboard' => [
                        [
                            ['text' => 'Студент', 'callback_data' => 'role_student']
                        ],
                        [
                            ['text' => 'Викладач', 'callback_data' => 'role_teacher']
                        ]
                    ]
                ]
            
            ]);
            $this->bot->history_command->set('registration');
        }
        else if(!$form->role){
            $callback = $this->bot->getCallback();
           
            if($callback){
                $message_id = $callback['message']['message_id'];
                $chat_id = $callback['message']['chat']['id'];

                $role = $callback['data'];
                if($role == 'role_student'){
        
                    $form->role = 2;
                    $this->bot->editMessage($chat_id, $message_id, 
                    [
                        'text' => "Хто ви?\n<code>----------------------</code>\n<code>Ви відповіли: Студент</code>",
                        'reply_markup' => null
                        ]
                    );
                    
                    $this->bot->sendMessage($this->id, [
                        'text' => "Введи назву своєї групи",
                        'allow_sending_without_reply' => true
                    ]);
                    $form->initField('group');
                }
                else if($role == 'role_teacher'){
                    $form->role = 1;

                    $this->bot->editMessage($chat_id, $message_id, 
                    [
                        'text' => "Хто ви?\n<code>------------------------</code>\n<code>Ви відповіли: Викладач</code>
                        ",
                        'reply_markup' => null
                        ]
                    );
                    
                    $this->bot->sendMessage($this->id, [
                        'text' => "Введіть своє повне ім'я",
                        'allow_sending_without_reply' => true
                    ]);
                    $form->initField('name');
                }

                
                }
            }
            else if($form->role){
                $this->storage->addUser($this->id, $form->role);
                if($form->isFieldInit('name') && !isset($form->name)){
                    $teacher_name = $this->bot->getMessage()['text'] ?? $this->bot->getCallback()['data'];
                    $is_teacher = $this->advisor->verifyTeacher($teacher_name);
                    if($is_teacher && !is_array($is_teacher)){
                        $this->bot->sendAnswer($this->id, [
                            'text' => "Дякую вам, {$teacher_name}. \nЯ не маю більше до вас запитань 😇 \nВідкривайте меню і користуйтесь моїми функціями 🤓",
                            'reply_markup' => self::main_menu
                        ]);
                        $this->storage->setNameTeacher($this->id, $teacher_name);
                        $is_callback = $this->bot->getCallback();
                        if($is_callback){
                            $message_id = $is_callback['message']['message_id'];
                
                        }
                        $form->deleteForm();
                    }
                    else if(!$is_teacher){
                        $this->bot->sendMessage($this->id, [
                            'text' => "Такого викладача не знайдено. Перевірте свою інформацію 🤓"
                        ]);
                    }
                    else if(is_array($is_teacher)){
                        $prepareted_teachers = $this->formatingOtherVariable($is_teacher); 
                        $this->bot->sendMessage($this->id, [
                            'text' => "Можливо ви хотіли ввести, щось із цього? 🧐",
                            'reply_markup' => [
                                'inline_keyboard' => $prepareted_teachers
                            ]
                
                        ]);
                    }
                }
                else if($form->isFieldInit('group') && !isset($form->group)){
                    $group = $this->bot->getMessage()['text'] ?? $this->bot->getCallback()['data'];
            
                    $is_group = $this->advisor->verifyGroup($group);
                    if($is_group && !is_array($is_group)){
                        $this->bot->sendAnswer($this->id, [
                            'text' => "Все, я не маю більше до тебе запитань 😇 \nВідкривай меню і користуйся моїми функціями 😏",
                            'reply_markup' => self::main_menu
                        ]);
                        $this->storage->setGroup($this->id, $group);
                        $is_callback = $this->bot->getCallback();
                        if($is_callback){
                            $message_id = $is_callback['message']['message_id'];
                            $chat_id = $this->id;
                
                            $this->bot->deleteMessage($chat_id, $message_id);
                
                        }
                        $form->deleteForm();
                    }
                    else if(!$is_group){
                        $this->bot->sendMessage($this->id, [
                            'text' => "Такої групи немає. Перевірь свою інформацію 🤓"
                        ]);
                    }
                    else if(is_array($is_group)){
                        $prepareted_groups = $this->formatingOtherVariable($is_group); 
                        $this->bot->sendMessage($this->id, [
                            'text' => "Можливо ти хотів ввести, щось із цього? 🧐",
                            'reply_markup' => [
                                'inline_keyboard' => $prepareted_groups
                            ]
                
                        ]);
                    }
                }
            }
        


    }
}