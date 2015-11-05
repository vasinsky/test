<?php
    /**
     * Класс хранилища данных
     * @author Vasinsky Igor
     * @email igor.vasinsky@gmail.com
     * @copyright 2013
     */
     
     class Bufer{
        protected static $bufer = array();
        
        public function __construct(){
            
        }
        /**
         *  Запись в хранилище
         *  @param array
         *   Bufer::set(array('key'=>$data))
         */ 
        static public function set($data){
            self::$bufer = $data;            
        }
        /**
         * Чтение из хранилища конкретного подмассива
         *  @param string
         *  Bufer::get('key')
         */ 
        static public function get($key){
            return self::$bufer[$key];
        }
        /**
         * Очистка хранилища от данных
         */ 
        static public function clear(){
            self::$bufer = null;
        }
        /**
         * Чтение всего хранилища
         */ 
        static public function getData(){
            return self::$bufer;
        }
        
        /**
         *  Дополнение хранилища данными 
         */ 
        static function add($data){
            foreach($data as $k=>$v)
                self::$bufer[$k] = $v;
        } 
     }
?>