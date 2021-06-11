

<?php

class DateToCertainDateScheduleAction extends Action implements ActionInterface{
    public function handler(){
        $form = $this->load->component("form/form", [$this->id]);
        $this->form = $form;
        if(!$form->isInit()){
            $this->bot->sendMessage($this->id, [
                'text' => "Так-так 🤖 \nЗараз тобі потрібно ввести першу та останню дату періоду ✏️",
                'reply_markup' => [
                    'keyboard' =>[
                        self::main_menu_button      
                    ],
                    'resize_keyboard' => true
                ]
            ]);
            $this->bot->sendMessage($this->id, [
                'text' => "З якої дати починати? 🤓 \n\n<code>В форматі: {$this->date_now}</code>"
            ]);

            $form->init(['start_date', 'end_date']);
        } 
        else{
        
            $this->getTodayToCertainDateSchedule($this->group);
        } 
    }

    protected function getTodayToCertainDateSchedule(string $group_name){
        if($this->fillPeriodDates()){
            $form = $this->form;

            $couples = $this->schedule->groupPeriod($group_name, $form->end_date, $form->start_date);
            $this->printSchedule($couples); 

            $this->form->deleteForm();
        } 
    }

    protected function fillPeriodDates(){
         
        $form = $this->form;

        if(!$form->isFieldInit('start_date') && !$form->isFieldInit('end_date')){
            $form->init('start_date');
            $form->init('end_date');
        }



        $catch_data = $this->bot->getMessage()['text'];
        if(!$form->start_date){
            if($this->isDate($catch_data)){
                $date_now = date('d.m.Y');
                $form->start_date = $catch_data;
                $this->bot->sendMessage($this->id, [
                    'text' => "Tепер потрібно ввести кінцеву дату періоду ✍️ \n\n<code>В форматі: {$date_now}</code>"
                ]);
            }
        }
        else if(!$form->end_date){
            if($this->isDate($catch_data)){
                $form->end_date = $catch_data;
                $sdate = new DateTime($form->start_date);
                $edate = new DateTime($form->end_date);
                if($sdate < $edate){
                    return true;
                }
                else{
                    $this->bot->sendAnswer($this->id,[
                        'text' => "Здається, період заданний невірно 🙁 \nПотрібно, щоб кінцева дата була більшою"
                    ]);
                    $form->formDelete();
                }
                
            }
        }

        return false;
    }

    
}



    