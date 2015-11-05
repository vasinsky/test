<?php
    /**
     * Предопределённые константы, необходимые для работы с БД MySQLi
     * @author Vasinsky Igor
     * @email igor.vasinsky@gmail.com
     * @copyright 2013
     */    
     
    class Validate{
        protected $rules;
        private $errors = array();
        private $data = array();
        public $error_text;
        protected $errorData = array();

        //Манипуляция с данными
        // float - к дробному числу
        // string - к строке
        // sha1 - сделает hash алгоритмом sha1
        // trim - утсечёт пробелы
        // html - аналог  strip_tags()
        
        //Примечание
        // notempty не пусто
        // regexp[шаблон с делиметерами]
        // length[мин,макс] 
        // code[index эл-та в $_SESSION]  - для капчи
        // confirm[имя поля с которым сравнивается]
        //
        
        protected $text_error = array(
                                        'notempty'  => 'Поле не может быть пустым',
                                        'length' => 'Поле содержит неверное кол-во символов',
                                        'email'  => 'Поле заполнено не корректно',
                                        'regexp' => 'Поле заполнено не корректно',
                                        'code'   => 'Введён не правильный защитный код',
                                        'confirm'=> 'Введённые пароли не совпадают',
                                        'id'=>'Поле заполнено не верно, ожидаются только цифры',
                                        'getName'=>'Произошла ошибка присвоения текста ошибки для поля, возможно вы пропустили установку правил для поля'
                                      );
        
        public function __construct(){
            
        }
        
        /**
         * Установка возвращаемого текста ошибки
         * @param string
         */ 
        public function setErrorText($error_text){
           $this->error_text = $error_text;
           $dtrace = debug_backtrace();

           $index =  count($dtrace[0]['object']->rules)-1;
           
           if(array_keys($dtrace[0]['object']->rules[$index])){
               
               $t_arr = array_keys($dtrace[0]['object']->rules[$index]);
           }
    
           if(isset($t_arr[0])){ 
               $this->errorData[$t_arr[0]] = $this->error_text;
           }
           else 
               $this->text_error['getName']; 
    
           return $this;
        }
        
        public function addRules($rules){
            $this->rules[] = $rules;
            return $this;
        }
        
        protected function _empty($nameTag){
            $_REQUEST[$nameTag] = trim($_REQUEST[$nameTag]);
            
            if(empty($_REQUEST[$nameTag]))
                $this->errors[$nameTag]['notempty'] = isset($this->errorData[$nameTag]) 
                                                      ? $this->errorData[$nameTag] 
                                                      :  $this->text_error['notempty'];   
            else
                $this->insertData($nameTag);              
        }
    
        protected function _id($nameTag){
            if(!is_numeric($_REQUEST[$nameTag])){
                $this->errors[$nameTag]['id'] = isset($this->errorData[$nameTag]) ? $this->errorData[$nameTag] :  $this->text_error['id'];    
            }
            else
             $this->insertData($nameTag);  
        }    
    
        protected function _float($nameTag){
            $_REQUEST[$nameTag] = (float)$_REQUEST[$nameTag];
            $this->insertData($nameTag);  
        }    
 
        protected function _int($nameTag){
            $_REQUEST[$nameTag] = (int)$_REQUEST[$nameTag];
            $this->insertData($nameTag);  
        }    
    
        protected function _sha1($nameTag){
            $_REQUEST[$nameTag] = sha1($_REQUEST[$nameTag]);
            $this->insertData($nameTag);  
        }   
    
        protected function _string($nameTag){
            $_REQUEST[$nameTag] = (string)$_REQUEST[$nameTag];
            $this->insertData($nameTag);  
        }        
            
        protected function _length($nameTag, $tv){       
            $limit = rtrim($tv, ']');
    
            if(empty($limit[0])) return;
            
            $t = explode(',',$limit[0]);
            $min = (int)$t[0];
            $max = isset($t[1]) ? (int)$t[1] : 2000;
            $length = mb_strlen(trim($_REQUEST[$nameTag]), 'utf-8');
           
            if($length<$min || $length>$max){
                $this->errors[$nameTag]['length'] = isset($this->errorData[$nameTag]) ? $this->errorData[$nameTag] : $this->text_error['length'];  
                
            }
            else
                $this->insertData($nameTag);                                            
        }    
        
        protected function _trim($nameTag){
            $_REQUEST[$nameTag] = trim($_REQUEST[$nameTag]);
            $this->insertData($nameTag);  
        }    
    
        protected function _html($nameTag){
           $_REQUEST[$nameTag] = strip_tags($_REQUEST[$nameTag]); 
           $this->insertData($nameTag);  
        }
        
        protected function _email($nameTag){
           if(!filter_var($_REQUEST[$nameTag], FILTER_VALIDATE_EMAIL))
               $this->errors[$nameTag]['email'] = isset($this->errorData[$nameTag]) ? $this->errorData[$nameTag] :  $this->text_error['email'];  
           else
                $this->insertData($nameTag);    
    
        }
    
        protected function _url($nameTag){
           if(!filter_var($_REQUEST[$nameTag],  FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED))
               $this->errors[$nameTag]['url'] = isset($this->errorData[$nameTag]) ? $this->errorData[$nameTag] :  $this->text_error['url'];      
           else
                $this->insertData($nameTag);        
        }
        
        protected function _confirm($nameTag, $tv){
            $confirm = rtrim($tv, ']');
    
            if(!isset($this->errors[$tv])){
                    if($_REQUEST[$nameTag] != $_REQUEST[$confirm]){
                        unset($this->errors[$nameTag]['errors']);
                        $this->errors[$nameTag]['confirm'] = isset($this->errorData[$nameTag]) ? $this->errorData[$nameTag] :  $this->text_error['confirm'];      
                    }
            }
        }
        
        protected function _regexp($nameTag, $tv){
            if(!preg_match($tv, $_REQUEST[$nameTag])){
                 $this->errors[$nameTag]['regexp'] = isset($this->errorData[$nameTag]) ? $this->errorData[$nameTag] :  $this->text_error['regexp'];             
            }
            else
                $this->insertData($nameTag);    
        }
        
        protected function _code($nameTag, $tv){
           if(!isset($_REQUEST[$nameTag])){
               $this->errors[$nameTag]['code'] = 'В форме отсутствует поле: '.$nameTag;
           }
           else{
              if($_SESSION[$tv] != $_REQUEST[$nameTag])
                  $this->errors[$nameTag]['code'] = isset($this->errorData[$nameTag]) 
                  ? $this->errorData[$nameTag] 
                  :  $this->text_error['code']; 
              else
                  $this->insertData($nameTag);        
           }
        }
        
        private function parseRules($target){
            $this->target = $target;

            foreach($target as $nameTag=>$l){
                
                $tempRules = explode('|',$l);
                
                foreach($tempRules as $k=>$v){
                     if(preg_match("#code#", $v)){
                        $tv = rtrim(strtr($v, array('code['=>'')),']');
                        
                        $v = 'code';
                    }               
                    elseif(preg_match("#regexp#", $v)){
                        $tv = rtrim(strtr($v, array('regexp['=>'')),']');
                        $v = 'regexp';
                    }
                    else{
                        $v = explode("[", $v);
                        $tv = isset($v[1]) ? $v[1] : null;
                        $v = $v[0];   
                    }
                    switch($v){
                        case 'notempty' : 
                            $this->_empty($nameTag);
                            break;    
                        case 'length':
                            if($tv != null)
                                $this->_length($nameTag, $tv);
                            break;
                        case 'trim' :
                                $this->_trim($nameTag);
                            break;    
                        case 'html' :
                                $this->_html($nameTag);
                            break;    
                        case 'email' :
                                $this->_email($nameTag);
                            break;   
                        case 'confirm' :
                                $this->_confirm($nameTag,$tv);
                            break; 
                        case 'regexp' :
                                $this->_regexp($nameTag,$tv);
                            break;   
                        case 'code' :
                                $this->_code($nameTag,$tv);
                            break;    
                        case 'id' :
                                $this->_id($nameTag);
                            break; 
                        case 'float' :
                                $this->_float($nameTag);
                            break; 
                        case 'string' :
                                $this->_int($nameTag);
                            break;     
                        case 'sha1' :
                                $this->_int($nameTag);
                            break;                                                                                                                                                                                                                        
                    }
                }
            } 
               
        }
        
        private function insertData($nameTag){
            $this->data[$nameTag] = $_REQUEST[$nameTag];
        }
        
        public function getData(){
            return $this->data;
        }

        public function getAllErrors(){
            return $this->errors;
        }
        
        public function getErrors(){
            $firstErrors = array();
            
            foreach($this->errors as $k=>$err){
                $firstErrors[$k] = array_shift($err);    
            }
            
            return $firstErrors;
        }    
        
        protected function validate(){
            if(is_array($this->rules)){

                foreach($this->rules as $k=>$v){
                    $this->parseRules($v);
                }
                
                if(count($this->errors)>0){
                    $success = false;
                }
                else{
                    $success = true;
                }
                return $success;
            }
        }
    }

/**
$_SESSION['secret'] = 123;

$f = new HTMLForm;

$f->open('aut','','POST');

$login = isset($_POST['my_login']) ? htmlspecialchars($_POST['my_login']) : '';
$email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';
$capcha = isset($_POST['capcha']) ? htmlspecialchars($_POST['capcha']) : '';
 
$f->setInput('my_login')
  ->setAttr('type|text')
  ->setAttr('value |'.$login)
  ->setAttr('placeholder|Введите логин')
  ->setRules('notempty|regexp[#[a-z0-9\-]{3,10}#i]')  
  ->setErrorText('Логин может содержать от 3 до 10 символов латинского алфавита, цифр, дефиса')
  ->addInput();
  
$f->setInput('email')
  ->setAttr('type|text')
  ->setAttr('value|'.$email)  
  ->setAttr('placeholder|Введите email')
  ->setRules('notempty|email')  
  ->setErrorText('Введён не корректный email')  
  ->addInput();   
  
$f->setInput('my_password')
  ->setAttr('type|password')
  ->setAttr('placeholder|Введите пароль')
  ->setRules('notempty|length[4,16]')
  ->setErrorText('Пароль должен содержать от 4 до 16 символов')  
  ->addInput();  
  
$f->setInput('my_password2')
  ->setAttr('type|password')
  ->setAttr('placeholder|Подтвердите пароль')
  ->setRules('confirm[my_password]')
  ->addInput();  
      
$f->setInput('capcha')
  ->setAttr('type|text')
  ->setAttr('value|'.$capcha)    
  ->setRules('code[secret]')  
  ->addInput();  
  
$f->setInput('send')
  ->setAttr('type|submit')
  ->setAttr('value|Отправить')
  ->addInput();

    
$form = $f->close();
//echo '<pre>' . print_r($form, 1);

if(!$f->sendForm('send')){
    $errors = $f->getErrors();  
    //echo '<pre>' . print_r($errors, 1) . '</pre>';  
}
else{
    $data = $f->getData();
    echo '<pre>' . print_r($data, 1) . '</pre>';     
}    
?>
 

<?=$form['begin'];?> 
<?=$form['my_login'];?> <?=isset($errors['my_login']) ? $errors['my_login'] : null;?><br />
<?=$form['email'];?> <?=isset($errors['email']) ? $errors['email'] : null;?><br />
<?=$form['my_password'];?> <?=isset($errors['my_password']) ? $errors['my_password'] : null;?><br />
<?=$form['my_password2'];?> <?=isset($errors['my_password2']) ? $errors['my_password2'] : null;?><br />
<?=$form['capcha'];?> <?=isset($errors['capcha']) ? $errors['capcha'] : null;?><br/>
<?=$form['send'];?><br />
<?=$form['end'];?>

*/
?>