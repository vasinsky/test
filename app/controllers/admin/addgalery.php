<?php
  class AddgaleryController extends BaseController{
      public function __construct(){
          parent::__construct();
          $this->setModel('Addgalery');
      }
      
      public function renderForm(){
          $form = new HTMLForm;
          
          $form->open('images','','POST', 'enctype|multipart/form-data');
          
          $form->setInput('name_galery')
               ->setAttr('type|text')
               ->setAttr('onkeyup|document.getElementById(\'child\').value=translit(this.value)')
               ->setAttr('class|form-control input-lg')
               ->setRules('notempty|trim')
               ->setAttr('value|'.(isset($_POST['name_galery']) ? $_POST['name_galery'] : ''))
               ->setErrorText('Укажите название новой галереи (3-30 знаков)')     
               ->addInput();
               
          $form->setInput('sinonim_galery')
               ->setAttr('type|text')
               ->setAttr('id|child')
               ->setAttr('class|form-control input-lg')
               ->setAttr('value|'.(isset($_POST['sinonim_galery']) ? $_POST['sinonim_galery'] : ''))
               ->setRules('notempty|trim|regexp[#[a-z0-9\-_\.]#]')
               ->setErrorText('Укажите синоним галереи')     
               ->addInput();               
               
          $form->setTextarea('gdescription')
               ->setAttr('class|form-control')
               ->setText((isset($_POST['gdescription']) ? $_POST['gdescription'] : ''))
               ->setRules('notempty|trim')
               ->setErrorText('Укажите описание галереи') 
               ->addTextarea();     
               
          $form->setInput('images[]')
               ->setAttr('type|file')
               ->setAttr('multiple|multiple')
               ->addInput();               
               
          $form->setInput('resize')
               ->setAttr('type|checkbox')
               ->setAttr(isset($_POST['resize']) ? 'checked|checked' : '')
               ->addInput();     
               
          $form->setInput('resize_w')
               ->setAttr('type|text')
               ->setAttr('value|'.(isset($_POST['resize_w']) ? (int)$_POST['resize_w'] : 800))
               ->setRules('regexp[#[0-9]*#]')
               ->setErrorText('Укажите ширину картинки для ресайза')                
               ->setAttr('class|form-control input-lg notlong')  
               ->addInput();               

          $form->setInput('resize_h')
               ->setAttr('type|text')
               ->setAttr('value|'.(isset($_POST['resize_h']) ? (int)$_POST['resize_h'] : 0))
               ->setRules('regexp[#[0-9]*#]')
               ->setErrorText('Укажите высоту картинки для ресайза')                
               ->setAttr('class|form-control input-lg notlong') 
               ->addInput();  

          $form->setInput('preview')
               ->setAttr('type|checkbox')
               ->setAttr('checked')
               ->setAttr('disabled|disabled')
               ->addInput();  
                    
          $form->setInput('preview_w')
               ->setAttr('type|text')
               ->setAttr('value|'.(isset($_POST['preview_w']) ? (int)$_POST['preview_w'] : 150))
               ->setAttr('class|form-control input-lg notlong')
               ->setRules('regexp[#[0-9]*#]')
               ->setErrorText('Укажите ширину картинки для превью')     
               ->addInput();               

          $form->setInput('preview_h')
               ->setAttr('type|text')
               ->setAttr('value|'.(isset($_POST['preview_h']) ? (int)$_POST['preview_h'] : 0))
               ->setAttr('class|form-control input-lg notlong')
               ->setRules('regexp[#[0-9]*#]')
               ->setErrorText('Укажите высоту картинки для preview')     
               ->addInput();                        

          $form->setInput('watermark')
               ->setAttr('type|checkbox')
               ->setAttr(isset($_POST['watermark']) ? 'checked|checked' : '')
               ->addInput();   
          
          $form->setInput('watermarkfile')
               ->setAttr('type|file')
               ->addInput();
               
          $form->setSelect('wm_position')
               ->setAttr('class|form-control')  
               ->setAttr('style|width:300px')   
               ->setRules('notempty') 
               ->addSelect(); 
               
          $form->setOption('Слева наверху')
               ->setAttr('value|top_left')
               ->addOption('wm_position');          

          $form->setOption('Справа наверху')
               ->setAttr('value|top_right')
               ->addOption('wm_position');             
                         
          $form->setOption('Внизу слева')
               ->setAttr('value|bottom_left')
               ->addOption('wm_position');  
               
          $form->setOption('Внизу справа')
               ->setAttr('value|bottom_right')
               ->setAttr('selected|selected')
               ->addOption('wm_position');                                          
            
            
          $form->setSelect('image_quality')
               ->setAttr('class|form-control')  
               ->setAttr('style|width:300px')   
               ->setRules('notempty') 
               ->addSelect();                  

          $form->setOption('Исходное качество')
               ->setAttr('value|100')
               ->addOption('image_quality');  
               
          $form->setOption('90%')
               ->setAttr('value|90')
               ->addOption('image_quality');  
               
          $form->setOption('80%')
               ->setAttr('value|80')
               ->addOption('image_quality');                                              
            
          $form->setOption('70%')
               ->setAttr('value|70')
               ->addOption('image_quality');  
               
          $form->setOption('60%')
               ->setAttr('value|60')
               ->addOption('image_quality');                             
            
          $form->setInput('wm_text')
               ->setAttr('type|text')
               ->setAttr('class|form-control input-lg small')
               ->setAttr('value|'.$_SERVER['SERVER_NAME'])
               ->setAttr('style|width:200px!important')
               ->addInput();            
            
          $form->setInput('color')
               ->setAttr('type|text')
               ->setAttr('class|form-control input-lg small')
               ->setAttr('value|'.(isset($_POST['color']) ? $_POST['color'] : 'ffffff'))
               ->setAttr('onblur|setColor(this)')
               ->setAttr('maxlength|6')
               ->setAttr('size|6')
               ->setAttr('id|colorpickerField')
               ->setAttr('style|text-align:center;width:200px')
               ->addInput();      
         
          $form->setSelect('size')
               ->setAttr('class|form-control')  
               ->setAttr('style|width:200px')   
               ->setRules('notempty') 
               ->addSelect(); 
               
          $form->setOption('9 px')
               ->setAttr('value|9')
               ->addOption('size');                 
               
          $form->setOption('10 px')
               ->setAttr('value|10')
               ->addOption('size');          

          $form->setOption('12 px')
               ->setAttr('value|12')
               ->addOption('size');  
               
          $form->setOption('15 px')
               ->setAttr('value|15')
               ->setAttr('selected|selected')
               ->addOption('size');  
               
          $form->setOption('20 px')
               ->setAttr('value|20')
               ->addOption('size');           
       
          $form->setSelect('alfa')
               ->setAttr('class|form-control')  
               ->setAttr('style|width:300px')   
               ->setRules('notempty') 
               ->addSelect(); 

          $form->setOption('Не прозрачный')
               ->setAttr('value|1')
               ->addOption('alfa');                            

          $form->setOption('50%')
               ->setAttr('value|50')
               ->addOption('alfa');  
               
          $form->setOption('60%')
               ->setAttr('value|60')
               ->addOption('alfa');  
               
          $form->setOption('70%')
               ->setAttr('value|70')
               ->addOption('alfa');                                              
            
          $form->setOption('80%')
               ->setAttr('value|80')
               ->addOption('alfa');  
               
          $form->setOption('90%')
               ->setAttr('value|90')
               ->addOption('alfa');          
                                  
            
          $form->setInput('addimages')
               ->setAttr('type|submit')
               ->setAttr('class|btn btn-primary btn-lg')
               ->setAttr('value|Создать галерею')
               ->addInput();  
               
                                                                      
                              
          $imagesForm = $form->close();     
          

          Bufer::set(array('imagesForm'=>$imagesForm));

          if(!$form->sendForm('addimages')){

              $errors = $form->getErrors();  

              Bufer::add(array('errors'=>$errors));
          }
          else{
                $settings_upload = $this->getSettingsUploads();
                $bufer = Bufer::getData();
                
                 //Ошибок нет, создадим строку в БД
                 if(!isset($bufer['errors']))               
                    $gid = $this->addGaleryToDb(); 



                 if(isset($gid) && $gid !== false){
                     if(!isset($bufer['errors'])){ 
                        $result = $this->creatFolders($gid);               
                        
                        if($result === true){
                           $result = $this->copyFiles($settings_upload, $gid);
                        }
                     }
                     else{
                        $this->model->deleteGalery($gid);
                     }   
                 }
                
          }  
     }
     
     public function addGaleryToDb(){
         $data = array(
                      'gname'=>$this->model->escape($_POST['name_galery']),
                      'gindex'=>$this->model->escape($_POST['sinonim_galery']),
                      'gdescription'=>$this->model->escape($_POST['gdescription']),
                      'image'=>null
         );
         
         $result = $this->model->addGalery($data);
         
         //Запись в БД прошла
         if($result['success'] === true){
            $gid = $result['gid'];  
            return $gid;                    
         }
         else{
            Bufer::add(array('errors'=>array('Ошибка создания галереи')));
            return false;
         }
                             
     }
     
     public function copyFiles($settings, $gid){
          //Допустимые типы
          $validTypes = array('image/jpg','image/jpeg','image/gif','image/wbmp'); 
          //Поле с которого происходит выбор файлов
          Upload::$index = 'images';
          //Максимальный размер в кб
          Upload::$size = 15000;
          //Передача типов в класс
          Upload::validType($validTypes);                    
          //Проверка валидности файлов
          $files = Upload::validate();
          //Загрузка во временную директорию
          $result = Upload::uploadFiles($files, 'tmp', true);
        
          Bufer::add(array('result'=>$result));   
          
          $dir_galery_pic = 'uploads/images/galery/'.$gid.'/pic';
          $dir_galery_thumb = 'uploads/images/galery/'.$gid.'/thumb';
          
          //Если есть файлы, прошедшие проверку
          if(!empty($result['valid'])){
              foreach($result['valid'] as $file){
                 $image = $file['hashname'].'.'.$file['ext'];
                 
                 $preview_w = $settings['preview_w'];
                 $preview_h = $settings['preview_h'];
                 
                 $quality = isset($settings['quality']) ? $settings['quality'] : 100;
                 
                 $imageInfo = getimagesize($file['fullpath'], $quality);
                 
                 $img = new Images($file['fullpath']);
                 $resizeThumb = $img->resize($preview_w, $preview_h, $dir_galery_thumb, $image);

                 $width = isset($settings['resize_w']) ? $settings['resize_w'] :  $imageInfo[0];      
                 $height = isset($settings['resize_h']) ? $settings['resize_h'] :  $imageInfo[1];  

                 $img = new Images($file['fullpath']);                
                 $resizeBig = $img->resize($width,$height, $dir_galery_pic, $image);   

                 if(isset($settings['watermark_text'])){
                     $alfa = $settings['water_set']['fontAlpha'];
                     $position = $settings['water_set']['position'];
                     $align = $settings['water_set']['align'];
                     $font = $settings['water_set']['fontFamily'];
                     $size = $settings['water_set']['fontSize'];
                     $color = $settings['water_set']['fontColor'];
                     $margin = $settings['water_set']['margin']; 
                     $text = $settings['watermark_text']; 
                     
                     $img = new Images($dir_galery_pic.'/'.$image);  
                     
                     $img->waterSettings(array(
                    	'fontAlpha' => $alfa, // Прозрачность от 0 до 100
                    	'fontSize' => $size, // Размер текста
                    	'fontFamily' => $font, // Шрифт
                    	'fontColor' => $color, // Цветовая гамма RGB
                    	'position' => $position, // top - вверху, bottom - снизу
                    	'align' => $align, // left - слева, right - справо
                    	'margin' => 10 // Отступ от границы
                     ));                     
                     
                     $arrInfo = $img->waterMarkText($text, $dir_galery_pic,false);    
                 }
 
                 if(isset($settings['watermark_image'])){
                     $alfa = $settings['water_set']['imgAlpha'];
                     $position = $settings['water_set']['position'];
                     $align = $settings['water_set']['align'];
                     $margin = $settings['water_set']['margin']; 
                     $image = $settings['watermark_image']; 
                     
                     $img = new Images($dir_galery_pic.'/'.$image);
                     
                     $img->waterSettings(array(
                    	'imgAlpha' => $alfa, // Прозрачность от 100 до 0
                    	'position' => $position, // top - вверху, bottom - снизу
                    	'align' => $align, // left - слева, right - справо
                    	'margin' => 10 // Отступ от границы
                     ));               
                             
                     $arrInfo = $img->waterMarkImg($image, $dir_galery, false);  
                 }        
                 
                 $images[] = array(
                                    'pic'=>$dir_galery_pic.'/'.$image,
                                    'thumb'=>$dir_galery_thumb.'/'.$image
                 );
                      
                 Upload::deleteFile($file['fullpath']);
              }
          }  
          
           if(isset($images) && isset($gid)){
                $result = $this->addImagesOnDb($gid, $images);
           }
              
     }
     
     public function creatFolders($gid){
         if(!file_exists('uploads/images/galery/'.$gid)){
             if(!mkdir('uploads/images/galery/'.$gid)){
                 Bufer::add(array('errors'=>array('Ошибка создания папки для галереи: uploads/images/galery/'.$gid)));    
             }
             else{
                $dir_galery = 'uploads/images/galery/'.$gid;

                file_put_contents($dir_galery.'/index.html', 'access denied');
                 
                if(!file_exists('uploads/images/galery/'.$gid.'/pic')){
                    if(!mkdir('uploads/images/galery/'.$gid.'/pic')){
                        Bufer::add(array('errors'=>
                        array('Ошибка создания папки для превью картинок галереи: uploads/images/galery/'.$gid.'/pic')));
                    }
                    else{
                       $dir_galery_pic = 'uploads/images/galery/'.$gid.'/pic';
                       file_put_contents($dir_galery_pic.'/index.html', 'access denied');
                    }
                }   
             }
         }
         if(!file_exists('uploads/images/galery/'.$gid.'/thumb')){
             if(!mkdir('uploads/images/galery/'.$gid.'/thumb')){
                 Bufer::add(array('errors'=>
                 array('Ошибка создания папки для превью картинок галереи: uploads/images/galery/'.$gid.'/thumb')));
             }
             else{
                $dir_galery_thumb = 'uploads/images/galery/'.$gid.'/thumb';
                file_put_contents($dir_galery_thumb.'/index.html', 'access denied');
             }
         }   
         
         $bufer = Bufer::getData();
         
         return  isset($bufer['errors']) ? false : true;    
     } 
      
      public function getSettingsUploads(){
         $settings = array();
                
         $settings['preview_w'] = (int)($_POST['preview_w']);
         $settings['preview_h'] = (int)($_POST['preview_h']);
                 
         //Если картинок для загрузки не выбрано
         if(sizeof($_FILES['images']['name']) == 1 and $_FILES['images']['name'][0] == ''){
             Bufer::add(array('errors'=>array('Вы не выбрали изображения для загрузки')));
             return false;
         }else{
            //Выбран ли ресайз картинок, если - да, то проверить значения полей ресайза
            if(isset($_POST['resize'])){
                $settings['resize'] = true;
                $settings['resize_w'] = (int)($_POST['resize_w']);
                $settings['resize_h'] = (int)($_POST['resize_h']);

                $settings['quality'] = $_POST['image_quality'];
                
                if($settings['resize_w']== 0 and  $settings['resize_h'] == 0){
                    Bufer::add(array('errors'=>array('Укажите ширину или высоту картинки после ресайза > 50px')));
                    return false;
                }
                elseif(($settings['resize_w'] < 50 and $settings['resize_w'] != 0) 
                        or ($settings['resize_h'] < 50 and $settings['resize_h'] !=0)){
                    Bufer::add(array('errors'=>array('Высота или ширина изображения указанная вами при ресайзе 
                                                      менее 50 px')));
                    return false;
                }
                
                if($settings['preview_w'] == 0 and  $settings['preview_h'] == 0){
                    Bufer::add(array('errors'=>array('Укажите ширину или высоту картинки превью > 25px')));
                    return false;
                }
                elseif(($settings['preview_w'] < 25 and $settings['preview_w'] != 0) 
                        or ($settings['preview_h'] < 25 and $settings['preview_h'] !=0)){
                    Bufer::add(array('errors'=>array('Высота или ширина изображения указанная вами для превью 
                                                      менее 50 px')));
                    return false;
                }                    

            }
            //Водяной знак на картинку
            if(isset($_POST['watermark'])){
                $settings['watermark'] = true;

                switch($_POST['wm_position']){
                    case 'bottom_right': $settings['position'] = 'bottom'; $settings['align'] = 'right'; break;
                    case 'bottom_left': $settings['position'] = 'bottom'; $settings['align'] = 'left'; break;
                    case 'top_right': $settings['position'] = 'top'; $settings['align'] = 'right'; break;
                    case 'top_left': $settings['position'] = 'top'; $settings['align'] = 'left'; break;                      
                }                
                
                //Если картинка
                if($_POST['wm_type'] == 'wm_image'){
                    $settings['wm_type'] = 'image';
                    
                    if(isset($_FILES['watermarkfile']) and $_FILES['watermarkfile']['error'] == 0){
                        $wm_info = pathinfo($_FILES['watermarkfile']['name']);
                        
                        if(!move_uploaded_file($_FILES['watermarkfile']['tmp_name'], 'tmp/wm.'.$wm_info['extension'])){
                            Bufer::add(array('errors'=>array('Не получилось скопировать водяной знак в tmp папку')));
                        } 
                        else{
                            $settings['watermark_image'] = 'tmp/wm.'.$wm_info['extension'];
                           
                        }
                        $settings['alfa'] = $_POST['alfa'];
                        
                        $settings['water_set'] = array(
                                        	'imgAlpha' => $settings['alfa'], // Прозрачность от 100 до 0
                                        	'position' => $settings['position'], // top - вверху, bottom - снизу
                                        	'align' => $settings['align'], // left - слева, right - справо
                                        	'margin' => 10 // Отступ от границы
                        );
                    }
                    else{
                        Bufer::add(array('errors'=>array('Не выбрана картинка для водяного знака')));
                    }
                }
                elseif($_POST['wm_type'] == 'wm_text'){
                    $settings['wm_type'] = 'text';
                    $settings['color'] = array($_POST['red'], $_POST['green'], $_POST['blue']);
                    $settings['alfa'] = $_POST['alfa'];
                    $settings['size'] = $_POST['size'];
                    $settings['watermark_text'] = $_POST['wm_text'];
                    
                    $settings['water_set'] = array(
                                    	'fontAlpha' => $settings['alfa'], // Прозрачность от 0 до 100
                                    	'fontSize' => $settings['size'], // Размер текста
                                    	'fontFamily' => './fonts/tahoma.ttf', // Шрифт
                                    	'fontColor' => $settings['color'], // Цветовая гамма RGB
                                    	'position' => $settings['position'], // top - вверху, bottom - снизу
                                    	'align' => $settings['align'], // left - слева, right - справо
                                    	'margin' => 10 // Отступ от границы                    
                    );                    
                    
                    if(empty($_POST['wm_text'])){
                        Bufer::add(array('errors'=>array('Введите текст для подяного знака')));    
                    }     
                    elseif(!preg_match("#([a-z0-9]{6})#i", $_POST['color'])){
                        Bufer::add(array('errors'=>array('Не корректный hex код цвета текста подяного знака')));       
                    }
                }
            } 
          } 
          
          return $settings;       
      }  
      
      public function addImagesOnDb($gid, $images){
         $result = $this->model->addImagesOnDb($gid, $images);
         
         if($result['success'] === false){
             $this->model->deleteGalery($gid);
             @rmdir('uploads/images/galery/'.$gid);
             
             Bufer::add(array('errors'=>array($result['error'])));
         }
         else{
            Route::go('?mode=admin&route=galery');
         }
      }    

  
  }
  
  $controller = new AddgaleryController;
  $controller->renderForm();
  
  $controller->view(ADMIN_TPLS_DIR.'/header.tpl');
  $controller->view(ADMIN_TPLS_DIR.'/addgalery.tpl');
  $controller->view(ADMIN_TPLS_DIR.'/footer.tpl');   
?>