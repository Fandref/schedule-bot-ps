<?php


class Parser{
    
    private $load;
    private $url;
    protected $edu;
    private $structurizer;
    public function __construct($edu = "NPUD", $url = 'http://nmu.npu.edu.ua/cgi-bin/timetable.cgi?n=700'){
        $this->url = $url;
        $this->edu = $edu;
        $this->load = new Loader();
        $this->structurizer = $this->load->component('structurizer');
        
    }
    private function getDOM(array $data){
        if(array_key_exists("group", $data) || array_key_exists("teacher", $data)){
            try {
                $options = array(
                    'http' => array(
                        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                        'method'  => 'POST',
                        'content' => http_build_query(mb_convert_encoding($data, "windows-1251", "utf-8"))
            
                    )
                );
                $context  = stream_context_create($options);
                $html = file_get_contents($this->url, false, $context);
                
                $html =  mb_convert_encoding($html, "utf-8", "windows-1251");
                
                $html = str_replace('charset=windows-1251', 'charset=utf-8', $html);
                
                $html = str_replace('</tr></div><div class="row">', '</tr>', $html);
                
                $dom = $this->load->library('simple_html_dom', [$html]);

                return $dom;
            } catch (\Throwable $th) {
                if($th->getCode() == 0)
                    throw new \Exception("Не можу отримати доступ до сайту", 1);
                else
                    throw $th;
            }
            
           
        }
        else
            throw new \Exception("Якась халепа, не знаю для кого шукати розклад", 1);
            
        
    }

    public function parsing(array $data, bool $show_empty = false, bool $link = false){
        try {
            $dom = $this->getDOM($data);
        
            $tables = $dom->find('.jumbotron .container .col-md-6');

            $parse_data = array();
            
            foreach ($tables as $table) {
                $head_table = $table->find("h4", 0)->plaintext;
                $day = array();
                preg_match('/\d{2}\.\d{2}\.\d{4,}/', $head_table, $date);
                $day["date"] = $date[0];
                $day["day_week"] = str_replace($day["date"], "", $head_table);
                $day["day_week"] = mb_strtolower(str_replace(" ", "", $day["day_week"]));
                $couples_data = array();
                $couples  = $table->find("table tr");
                foreach ($couples as $couple) {
                    if($couple->find("td", 2)->innertext != " " && !$show_empty){
                        $delete_element = "/(<div class='link'>.*<\/div>)|(<span.[^>]*>.*<\/span>)|(<img.[^>]*>)/";
                        $couple_data = array(
                            "number" => $couple->find("td", 0)->plaintext,
                            "period" => explode("<br>", $couple->find("td", 1)->innertext),
                            "detail" => preg_replace($delete_element, "", $couple->find("td", 2)->innertext)        
                        );
                        if($link){
                           $couple_data['link'] = $couple->find("td .link", 0)->innertext != " " ? $couple->find("td .link", 0)->innertext : NULL;
                        }
                        $couples_data[] = $couple_data;
                    }
                    
                }
                $day["couples"] = $couples_data;
                
                $parse_data[] = $day;
        
            }
            return $parse_data;
        } catch (\Throwable $th) {
            if($th->getCode() == 0){
                throw new \Exception("Схоже, сервер університету не витримав лихої долі 😅", 2);
            }
            else{
                throw $th;
            }
            
            
        }
            
        
    }

    // returned structured data of parsing
    public function getData(array $data, bool $show_empty = false, bool $link = false){
        
        $parsed_data = $this->parsing($data, $show_empty = false,  $link = false);
        if(is_array($parsed_data) && count($parsed_data) > 0){
            $type = array_key_exists("teacher", $data) ? "teacher" : "group";
            $structuring_data = $this->structurizer->structuring($parsed_data, $type);
            
            return $structuring_data;
        }
            
        else
            throw new \Exception("Найближчим часом пар немає", 1);
            
        
        
    
    }



}
