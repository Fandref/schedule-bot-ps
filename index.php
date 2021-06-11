<?php
header("Access-Control-Allow-Origin: *");
// header("Content-Type: application/json; charset=UTF-8");
header("Content-Type: text/html; charset=UTF-8");


require_once 'config.php';
require_once DIR_BASE_APP.'/registry.php';
require DIR_BASE_APP.'/model.php';
require DIR_BASE_APP.'/loader.php';
require DIR_BASE_APP.'/action.php';

require_once 'main.php';
// session_start();

$load = new Loader();

$advisor = $load->component("advisor");


var_dump($advisor->verifyTeacher('Іван'));

$main =  new Main();



$main->main();







































// $showScheduleWeekCertainDate = function() use ($bot, $id, $schedule, $storage){
//     try{
//         $message = $bot->getMessage()['text'];
//         if(preg_match('/^(\d{2})\.(\d{2})\.(\d{4})$/', $message, $date_parts)){
//             $date = new DateTime($date_parts[3].'-'.$date_parts[2].'-'.$date_parts[1]) ?? false;
//             $couples = $schedule->groupWeek($storage->getGroup($id), date('d.m.Y', strtotime($message)));
//             pr($couples);
//             $bot->history_command->clear();
//         }
//         else{
//             $date_now = date('d.m.Y');
//             $bot->sendMessage($id, [
//                 'text' => "Здається, це не дата 😧\nДата повинна бути в форматі: <b>{$date_now}</b>"
//             ]);
//         }
       
        
//     } catch (\Exception $th) {

//         if($th->getCode() == 0){
//             $bot->sendMessage($id, [
//                 'text' => "Ой 😳 Я не знаю такої дати"
//             ]);
//         }
//         else 
//             throw $th;
        
//     }
    
// };



















// // $showScheduleTodayToCertainDate = function() use ($bot, $id, $storage, $schedule){
    
// // };

// $bot->waitAnswer("Від сьогодні до дати", $showScheduleTodayToCertainDate);


// $startScheduleDateToCertainDate = function () use ($bot, $id){
//     $date_now = date('d.m.Y');
//     $bot->sendMessage($id, [
//         'text' => "Від якої дати потрібний розклад?\n\n<code>В форматі: {$date_now}</code>"
//     ]);
// };

// $bot->onMessage('Від дати до дати', $startScheduleDateToCertainDate);









// $s = "";
// $k = 190;
// for ($i = 1; $i <= $k; $i++)
// {
//     $s .= $i;
// }
// for($i = 0; $i<$k; $i++){
//     if($i==$k-1){
//         echo "<b>".$s[$i]."</b>";

//     }
//     else
//         echo $s[$i];
// }
// // var_dump($s[$k-1]);

// function cls($k){
//     if($k < 10)
//         return $k;
//     else if($k>=10 && $k<=90*2+9){
//         $a = ($k-10)/2;
//         $n = 9;
//         for($i = 0; $i<$a; $i++){
//             $n++;
//         }
//         if($a*2%2 == 0){
//             return $n/10;
//         }
//         else {
//             return $n%10;
//         }
//     }
//     else if($k>90*2+9 && $k<=900*3+(90*2+9)){
//         $a = ($k-190)/3;
//         $n = 99;
//         for($i = 0; $i<$a; $i++){
//             $n++;
//         }
//         if($a*3%2 == 0){
//             return $n/100;
//         }
//         else {
//             return $n%100;
//         }
//     }
    
    
// }

// var_dump(cls($k));
// $node = array();
// var_dump(8 & 1);

try {
    $storage = new Storage();
    // var_dump($storage->getNameTeacher('124124214214'));
    // $storage->addUser('124124214213', 1);
    
    
    function pra($a){
        foreach($a as $aa){
        
            echo "<b>Розклад на {$aa['day_week']}</b><br>";
            echo "<code>{$aa['date']}</code><br><br>";
            if(!array_key_exists("message", $aa)){
                foreach($aa['couples'] as $b){
                    echo "<b>{$b['number']}</b>&nbsp;&nbsp;[ {$b['period'][0]} - {$b['period'][1]} ]&nbsp;&nbsp;{$b['detail']['subject']} <code>({$b['detail']['subject_type']})<br>{$b['detail']['teacher']}&nbsp;&nbsp;{$b['detail']['audience']}</code><br><br>";
                }
            }
            else{
                echo $aa['message'];
            }
            
            echo "<br><br><br>";
        }
    }
    $a = $d->groupWeek('22ІПЗ');
    pra($a);
    echo "<br><hr><br>";
    $a = $d->teacherWeek('Малежик Петро Михайлович');

    pra($a);
    echo "<br><hr><br>";

    $a  = $d->filter("22ІПЗ", $a, "group");
   
    pra($a);
    echo "<br><hr><br>";
    
    $a = $d->groupWeek('22ІПЗ', "24.03.2021");
    pra($a);
    echo "<br><hr><br>";
    $a = $d->groupPeriod('22ІПЗ', '23.03.2021');
    pra($a);
//     array(
//     'group' => '22ІПЗ',
//     // 'sdate' => '9.03.2021',
//     // 'edate' => '10.03.2021',
// ));

} catch (\Throwable $th) {
    // if($th->getCode > 0)
    // $bot->sendMessage($id, [
    //     'text' => $th->getMessage()

    // ]);
}




// require_once 'config.php';
// require_once DIR_CORE_APP.'/registry.php';
// require_once DIR_CORE_APP.'/loader.php';
// require_once DIR_CORE_APP.'/controller.php';
// require_once DIR_CORE_APP.'/model.php';
// require_once DIR_CORE_APP.'/app.php';

// $registry = new Registry();

// // $url = substr($_SERVER['REQUEST_URI'], 1);
// // preg_match('/^v\d+/', $url, $v);
// // $registry->set("version", $v ? $v[0] : 'v1');

// // $registry->set("url", isset($v) && $v != NULL ? str_replace($v[0]."/", "", $url) : $url);



// $router = new Router();

// $router->start();





