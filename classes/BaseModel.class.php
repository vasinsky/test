<?php
    /**
     * Класс базовой модели
     * @author Vasinsky Igor
     * @email igor.vasinsky@gmail.com
     * @copyright 2013
     */

    class BaseModel{
        const DEBUG = DEBUG;
        protected $mysqli;
        protected $start;
        protected $count;
        protected $pageSize;
        protected $thisPage;
        private $paginate = array();
        
        public function __construct(){
            return $this->mysqli = Fw_mysqli::connect(); 
        }   
        
        /**
         *  Запрос в БД
         *  @param string - sql запрос
         */ 
         public function sqlQuery($sql){
            $result = $this->mysqli->query($sql);
 
            if(!$result){
              Files::addtolog(LOG_MYSQLI, $sql.'--->>>'.$this->mysqli->error);
              
              throw new Exception($this->mysqli->error);
            }            
            else{
                return $result;
            }      
         }
         
        /**
         *  Мульти запрос в БД
         *  @param string - sql запрос
         */ 
         public function sqlMultyQuery($sql){
            $result = $this->mysqli->multi_query($sql);
            
            if(!$result){
              Files::addtolog(LOG_MYSQLI, $sql.'--->>>'.$this->mysqli->error);
              
              throw new Exception($this->mysqli->error);
            }            
            else{
                while($this->mysqli->next_result()) $this->mysqli->store_result($linkId);
                return $result;
            }      
         }         
        
        /**
         * Возвращает ассоц масив по sql запросу
         * @param string - sql запрос
         */ 
        public function returnData($sql){
            $result = $this->mysqli->query($sql);

            if(!$result){
              Files::addtolog(LOG_MYSQLI, $sql.'--->>>'.$this->mysqli->error);
              
              throw new Exception($this->mysqli->error);
            }            
            else{
                if($result->num_rows>0){
                    while($row = $result->fetch_assoc()){
                        $data[] = $row;
                    }
                    return $data;
                }    
                else{
                    return false;
                }
            }    
        }
        /**
         *  Обновление данных
         *  @param string - sql запрос
         */ 
         public function updateData($sql){
            $result = $this->mysqli->query($sql);

            if(!$result){
              Files::addtolog(LOG_MYSQLI, $sql.'--->>>'.$this->mysqli->error);
              
              throw new Exception($this->mysqli->error);
            }            
            else{
                
                return $this->mysqli->affected_rows;
            }     
         }
         
        /**
         *  Удаление данных
         *  @param string - sql запрос
         *  @return object
         */ 
         public function deleteData($sql){
            $result = $this->mysqli->query($sql);

            if(!$result){
              Files::addtolog(LOG_MYSQLI, $sql.'--->>>'.$this->mysqli->error);
              
              throw new Exception($this->mysqli->error);
            }            
            else{
                return $this->mysqli->affected_rows;
            }     
         }         
         
        
        /**
         * Возвращает постраницную выборку
         * @param string - sql запрос без LIMIT
         * @param int - кол-во строк на одну страницу
         * @param int >0 текущая страница
         * @return array
         */ 
        public function getPaginateData($sql, $pageSize, $curPage){
            $result = $this->mysqli->query($sql);
            $this->thisPage = $curPage;
            $data = false;

            if(!$result){
              Files::addtolog(LOG_MYSQLI, $sql.'>>>'.$this->mysqli->error);
                
              throw new Exception($this->mysqli->error);
            }            
            else{
                $totalData = $result->num_rows;
                
                if($totalData <= $pageSize){
                    while($row = $result->fetch_assoc()){
          
                        $data[] = $row;
                    }
                    return $data;
                }
                else{
                    $result = null;
                    
                    if($this->thisPage == 1)
                        $this->start = 0;

                    $this->start = ($this->thisPage*$pageSize)-$pageSize;
                    $pages = ceil($totalData/$pageSize); 
                    
                    $result = $this->mysqli->query($sql.' limit '.$this->start.','.$pageSize);
                    
                    if(!$result){
                      Files::addtolog(LOG_MYSQLI, $sql.'--->>>'.$this->mysqli->error);
                
                      throw new Exception($this->mysqli->error);
                    }            
                    else{  
                        if($result->num_rows>0){
                            while($row = $result->fetch_assoc()){
                                $data[] = $row;
                            }
                            /**
                             * 2й массив - номера страниц и активная страница - url прописывается ручками
                             */ 
                            $padinate = array(); 
                             
                            for($i=0;$i<=$pages;$i++){
                                if($i == $curPage)
                                    $paginate['active'] = $i;
                                else{
                                    $paginate[] = $i;
                                }     
                            }
                            
                            unset($paginate[0]);
                            $this->paginate = $paginate;
                        }
                            
                        return $data;
                    }
                    
                }
            }
        }
        
        public function paginate(){
            return $this->paginate;
        }
        
        public function escape($data){
            return $this->mysqli->real_escape_string($data);
        }
        
        public function multiQuery($sqls){
            $result = $this->mysqli->multi_query($sqls);
            
            if($result === false){
                return false;
            }
            else{

                while($this->mysqli->more_results() && $this->mysqli->next_result()) 
                        $this->mysqli->store_result();
                

                return true;
            }
            
        }
        
        public function last_id(){
            return $this->mysqli->insert_id;
        }
        
        /**
         * Возвращает данные страницы из таблицы pages указывая name
         * @param string - sql запрос
         */ 
        public function returnPageData($name){
            $sql = "select * from pages where name = '".$this->escape($name)."' LIMIT 1";
            
            $result = $this->mysqli->query($sql);

            if(!$result){
              Files::addtolog(LOG_MYSQLI, $sql.'--->>>'.$this->mysqli->error);
              
              throw new Exception($this->mysqli->error);
            }            
            else{
                if($result->num_rows>0){
                    while($row = $result->fetch_assoc()){
                        $data[] = $row;
                    }
                    return $data;
                }    
                else{
                    return false;
                }
            }    
        }        
    }
?>
