<?php
   /**
    * @author Vasinsky Igor
    * @email igor.vasinsky@gmail.com
    * @copyright 2013
    *  
    * Класс загрузки файлов
    *
    */    
    /**
     * ---------------------------------------------------------------------------
      if(isset($_POST['send'])){
          $validTypes = array('image/jpg','image/jpeg','image/gif','image/bmp'); 
          Upload::$index = 'images';
          Upload::$size = 15000;
          Upload::validType($validTypes);
          
          $files = Upload::validate();
          $result = Upload::uploadFiles($files, 'tmp',1);
          
          echo '<pre>' . print_r($result, 1) . '</pre>'; 
      }      
      
      $result - Двумерный массив с эл-ми valid (загруженнык) и error (не загруженные)
    * ---------------------------------------------------------------------------
    */

   class Upload{
        /**
         * name поля type=file
         */ 
        static $index = 'images';
        /**
         * максимально допустимый размер файла 
         */ 
        static $size = 600;
        /**
         * Внутренняя переменная для хранения допустипых mime типов
         */ 
        static $validType = array();
        
        public function __construct(){
            
        }
        /**
         * Установка mime типов файлов
         * http://www.spravkaweb.ru/php/pril/mime 
         * @param array  
         *  array('mime/type1', 'mime/type1')
         *  пустой массив - нет ограничений
         */ 
        static public function validType($type){
            self::$validType = $type;
        }
        
        /**
         * Получение данных загружаемых файлов
         * @return array
         */  
        static public function getFiles(){
             if(empty($_FILES)){
                
               return false;
             }
             else{
                foreach($_FILES[self::$index]['name'] as $key=>$name){
                    $pathinfo = pathinfo($name);
                    
                    
                    
                    if($pathinfo['basename'] !=''){
                        $filename = $pathinfo['filename'];
                        $ext = $pathinfo['extension'];
                  
                        $hashname = sha1($_FILES[self::$index]['tmp_name'][$key].$pathinfo['basename'].microtime());
                        
                        $errors = array(
                            0=>'',
                            1=>'Размер принятого файла превысил максимально допустимый размер, который задан 
                            директивой upload_max_filesize конфигурационного файла',
                            2=>'Размер загружаемого файла превысил значение MAX_FILE_SIZE, указанное в HTML-форме',
                            3=>'Загружаемый файл был получен только частично',
                            4=>'Файл не был загружен',
                            6=>'Отсутствует временная папка',
                            7=>'Не удалось записать файл на диск',
                            8=>'PHP-расширение остановило загрузку файла. PHP не предоставляет способа 
                            определить какое расширение остановило загрузку файла'
                        );
                        
                        $filesdata[] = array(
                                'name'=>$filename,
                                'ext'=>isset($ext) ? $ext : '-',
                                'type'=>$_FILES[self::$index]['type'][$key],
                                'hashname'=>$hashname,
                                'tmpname'=>isset($_FILES[self::$index]['tmp_name'][$key])
                                           ? $_FILES[self::$index]['tmp_name'][$key]
                                           : '-',
                                'error'=>$errors[$_FILES[self::$index]['error'][$key]],
                                'size'=>ceil($_FILES[self::$index]['size'][$key]/1024)
                        );
                    }
                }
                return isset($filesdata) ? $filesdata : false;
             } 
        }
        
        /**
         * Проверка валидности загружаемых файлов
         * @return array/bool
         */ 
        public static function validate(){
            if(self::getFiles() === false){
                return false;
            }
            else{
                if(empty(self::$validType)){
                    return self::getFiles();
                }
                else{
                    
                    foreach(self::getFiles() as $k=>$v){
                       if(!empty($v['error'])){
                           if($returnOnlyValidFiles = 1)
                               $files[] = $v; 
                       } 
                       elseif(!in_array($v['type'], self::$validType)){
                           $v['error'] = 'Не допустимо загружать файл типа: '.$v['type'];
                           $files[] = $v;
                       }
                       elseif($v['size']>self::$size){
                           $v['error'] = 'Файл превышает размер '.self::$size .' кб';
                           $files[] = $v;                
                       }
                       else{
                           $files[] = $v;
                       }
                    } 
                    return empty($files) ? false : $files;                 
                }
            }
        }
        
        /**
         * Функция загрузки файлов на сервер
         * @param array - то что вернула Upload::validate()
         * @param string директория загрузки
         * @param bool
         * false - использовать оригинальные имена файлов
         * true - использовать hash имени файла из getFiles() эл-т hashname
         * использовать только после этапа Upload::validate())
         * 
         * @return array/bool
         */ 
        public static function uploadFiles($validate_files, $dir, $rename=false, $prefix=false){

            if(!is_array($validate_files)){
                return false;
            }
            
            if($prefix !== false){
                $validate_files = self::setPrefix($validate_files, $prefix);
            }
            
            
            
            $files['valid'] = array();
            $files['error'] = array();
                
            foreach($validate_files as $k=>$file){
                
                $name = ($rename === false) ? $file['name'] : $file['hashname'];

                if($file['error'] == ''){
                     
                    $file['uploaddir'] = $dir;
                    
                    if(move_uploaded_file($file['tmpname'], $dir.'/'.$name.'.'.$file['ext'])){
                        $file['fullpath'] = $dir.'/'.$name.'.'.$file['ext'];
                        $files['valid'][] = $file;
                    }    
                    else{
                        $file['error'] = 'Не получилось скопировать файл';
                        $files['error'][] = $file;
                    }
                } 
                else{
                    $files['error'][] = $file;
                }
            }
            
            return isset($files) ? $files : false;
        }
        
        /**
         *  Метод модификации имени файла - добавление префикса
         *  @param array
         *  @param string
         *  return array
         */ 
         public function setPrefix($files, $prefix){
              foreach($files as $k=>$f){
                  $mod_files[] = array(
                                     'name'=>$prefix.'_'.$f['name'],
                                     'ext'=>$f['ext'],
                                     'type'=>$f['type'],
                                     'hashname'=>$f['hashname'],
                                     'tmpname'=>$f['tmpname'],
                                     'error'=>$f['error'],
                                     'size'=>$f['size'],
                                        
                                     );
              }
            
             return $mod_files;
         }
        
        /**
         * Удаление файла из директории
         * @param string
         * @return bool
         */ 
        static public function deleteFile($pathtofile){
            return (!unlink($pathtofile)) ? false : true;
        }
        
        static public function move_file($pathtofile, $dir, $del = true){
            $pathinfo = pathinfo($pathtofile);
            
            $result = copy($pathtofile, $dir.'/'.$pathinfo['basename']);

            if($del === true)
                Upload::deleteFile($pathtofile);
            
            return $result;
        }
        
        
   }
?>