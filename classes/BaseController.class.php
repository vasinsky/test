<?php
    /**
     * Класс базовой контроллера
     * @author Vasinsky Igor
     * @email igor.vasinsky@gmail.com
     * @copyright 2013
     */

    class BaseController{
        const DEBUG = DEBUG;
        public $model;
        public $data = array();
        
        /**
         * Пагинация
         */ 
        public $paginateNumberPages = 10;
        public $paginateButtonNext = true; 
        public $paginateButtonPrev = true;
        public $paginateButtonNextText = 'дальше';
        public $paginateButtonPrevText = 'назад';
        public $paginateCurPage = 1;

        public function __construct(){

        }
        
        public function setModel($route){
            $class = $route.'Model'; 
            $this->model = new $class;
            
            return $this->model;
        }

        public function returnData($sql){
            return $this->model->returnData($sql);
        }

        public function updateData($sql){
            return $this->model->updateData($sql);
        }        
        
        public function deleteData($sql){
            return $this->model->deleteData($sql);
        }        

        public function query($sql){
            return $this->model->query($sql);
        }
        
        public function getPaginateData($sql, $pageSize, $curPage){
            return $this->model->getPaginateData($sql, $pageSize, $curPage);
        }   
        
        public function view($pathtotpl){
            
            ob_start();
                Files::load($pathtotpl);
                $html = ob_get_contents();
            ob_clean(); 
                        
            echo Snippet::parseSnippet($html, MODE);
        }
        /**
         *  ДОПИЛИТЬ
         */ 
        public function paginate(){
            return $this->model->paginate();
        }     
    }
?>