<?php
    /**
     * @author Vasinsky Igor
     * @email igor.vasinsky@gmail.com
     * @copyright 2013
     * Класс проверки доступа посетителя сайта к определённому контенту
     */
     
    class Access{
        /**
         *  Получает AccessLevel посетителя сайта
         *  @return int
         */ 
        public static function getLevel(){
            if(Route::isAdmin()){
                    
                $la = 1; //администратор
            } 
            elseif(!isset($_SEESION['fw']['user']['login'])){
                 $la = 2; //Гость (неавторизированный)    
            }       
            elseif(isset($_SEESION['fw']['user']['login'])){
                $mysqli = Fw_mysqli::connect();
                
                $sql = "select * from access 
                        where login = '".$mysqli->escape($_SEESION['fw']['user']['login'])."'";
                
                $result = $mysqli->query($sql);
                
                $accessData = $result->fetch_assoc();
                
                $a = $accessData['isadmin'];
                
            }
            else{
                $a = 0;
            }
            
            return $la; 
        }
        /**
         *  Сравнивает AccessLevel посетителя и страниц сайта (открывает/закрывает доступ)
         *  @return bool
         * 
         *  Пример: за levelAcces взято значение поля acid таблицы pages
         *  ---------------
         *  if(!Access::validate()){
         *      exit(MESSAGE_ACCESS_DENIED);
         *  }
         */ 
        public static function validate(){
            //Проверка админки
            if(MODE == 'admin' && self::getLevel() !=1)
                exit(MESSAGE_ACCESS_DENIED);
            else{                
                if(isset($_SESSION['fw']['pageAccess'])){
                    if($_SESSION['fw']['pageAccess']['acid'] == 0) //Общий доступ
                        return true;
                    elseif(Route::isAdmin()){ //Администратору всегда открыто
                        return true;
                    } 
                    else{
                        if(self::getLevel() != $_SESSION['fw']['pageAccess']['acid']){
                            return false;
                        }
                    }
                }
            }
        } 
        /**
         * Возможность поставить доступ на любой раздел сайта
         * @param int - AccessLevel раздела
         * @return bool 
         *
         *  Пример:  Только авторизированным пользователям
         *  ---------------
         *  if(!Access:setlevelAccess(3)){
         *      exit(MESSAGE_ACCESS_DENIED);
         *  }
         */ 
        public static function setlevelAccess($accessLevel){
            if(self::getLevel() == 1){ //Администратору всегда открыто
                return true;
            }
            elseif(self::getLevel() != $accessLevel){
                return false;
            }
        }
    }
?>