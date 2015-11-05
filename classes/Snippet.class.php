<?php
    /**
     * Класс для быстрого вызова HTML втавок в шаблоны
     * @author Vasinsky Igor
     * @email igor.vasinsky@gmail.com
     * @copyright 2013
     * 
     *  Snippet::snippet_name()
     */
     
    class Snippet{
       /**
        * Вспомогательный метод для получения кода HTML сниппета из БД
        * @param string   name html snippet
        * @return string HTML код сниппета
        */ 
       private static function get($nameSnippet){
            $mysqli = Fw_mysqli::connect(); 
    
            $sql = "select * from htmlsnippets where hsname='".Fw_mysqli::escape($nameSnippet)."' limit 1";
            
            $result = $mysqli->query($sql);
            
            if($result->num_rows == 0){
                return " <span class='text-danger'>HTML snippet <code>#".$nameSnippet."</code> не найден</span> ";
            } 
            else{
                $snippet = $result->fetch_assoc();
                return $snippet['code'];
            }
       }
        /**
         *  Динамическое создание метода по имени сниппета в БД
         *  @return string HTML код
         */ 
        public static function __callStatic($sMethod, $rgArgs) {
            $snippet_code = self::get($sMethod);
            
            return $snippet_code;
        }
        
        /**
         *  Парсинг html сниппетов на публичной части сайта
         *  BB code сниппетов  snippet=[name_snippet], 
         *  например: snippet[leftMenu]
         *  @param string - html код страницы
         *  @param string  - admin|public  - MODE
         *  @return string - html код страницы после парсинга и замены
         */ 
         public static function parseSnippet($html, $mode){
         
            if($mode == 'public'){
                preg_match_all("#\*snippet=\[(.*)\]\*#", $html, $snippets);
                
                if(!empty($snippets)){
                    
                    $bb_snippets = $snippets[0];
                    $name_snippets = $snippets[1];
                    
                    foreach($bb_snippets as $k=>$snpt){
                        
                        if(!empty($snpt)){
                            
                            $bb_snippet = $snpt;
                            $name_snippet = $name_snippets[$k];
                            $code_snippet = Snippet::$name_snippet();
                            $html = strtr($html, array($bb_snippet=>$code_snippet));
                        }
                    }
                
                }
            }
            
            return eval('?>'.$html);            
         }
                   
    }
?>