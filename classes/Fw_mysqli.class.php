<?php
    /**
     * Класс для работы с БД mysqli
     * @author Vasinsky Igor
     * @email igor.vasinsky@gmail.com
     * @copyright 2013
     */
     
    class Fw_mysqli{
        
        const DEBUG = DEBUG;
        /**
         * Подключение к сереверу БД 
         */ 
        public static function connect(){
            
            $mysqli = new mysqli(MYSQLI_HOST, MYSQLI_USER, MYSQLI_PASS, MYSQLI_DB);
            
            if(self::DEBUG){
    
                    if ($mysqli->connect_error) 
                            throw new Exception($mysqli->connect_error);  
                    else{ 
                        $mysqli->query('set names "'.MYSQLI_CHARSET.'"');
                        
                        return $mysqli;        
                    }
            }        
        }
        
        public static function escape($data){
            return Fw_mysqli::connect()->real_escape_string($data);
        }
        
        
    }
?>
