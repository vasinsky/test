<?php
    /**
     * Класс рендеринга html форм
     * @author Vasinsky Igor
     * @email igor.vasinsky@gmail.com
     * @copyright 2013
     */    
     
    class HTMLForm extends Validate{
        public $action;
        public $method;
        public $nameForm;
        public $paramsForm;      
        
        const EndTagInput        = ' rel="i"/>';
        const EndTagSelectInside = ' rel="s"';
        const EndTagSelect       = '</select>';
        const EndTagOption       = ' rel="o">';
        const EndTagTextarea     = ' rel="t">';
            
        protected $elems = array(
                                  'input'=>'<input rel="i"/>',
                                  'select'=>'<select rel="s"></select>',
                                  'option'=>'<option rel="o"></option>',
                                  'textarea'=>'<textarea rel="t"></textarea>'
                                );
    
        public function __construct(){
            return parent::__construct();
        }
        
        public function setRules($rules){
            parent::addRules(array($this->name=>$rules));
            return $this;
        }
        
        //создание свойства класса динамически
        public function createProperty($name, $value){
           if(!empty($value))
              $this->$name = $value;
        }
        //проверка наличия свойства у класса
        private function checkProperty($name){
            if(!property_exists(__CLASS__, $name)){
                $this->createProperty($name,$name);
                $this->name = $name;
            }    
            else
                $this->name = $name;        
        }
        //начало рисования формы
        //Самые распространнённые у формы 3 мараметра, буду задавать  обязательными аргументами
        public function open($nameForm, $action, $method, $arg = null){
            $this->action = $action;
            $this->method = $method;
            
            $this->arg = explode(',', $arg);       
           
            //если был 3й аргумент - соберём все параметры в стрку вида arg1="param1" arg2="param2"
            if(is_array($this->arg)){
                                
                $this->paramsForm = null;

                foreach($this->arg as $val){
                    
                    if($val != null){
                        $temp = explode("|", $val);
                        $this->paramsForm .= ' '.$temp[0].'="'.$temp[1].'" ';    
                    }
                }
            }
            $this->form[$this->nameForm] = array(); 
            $this->nameForm = $nameForm; 
            $this->form[$this->nameForm]['begin'] = '<form name="'.$this->nameForm.'" method="'
           .$this->method.'" action="'.$this->action.'" '.$this->paramsForm.'>';
        }
        //Закрывает формы (чисто для солидарности:))
        public function close(){
            $this->form[$this->nameForm]['end'] = '</form>';
            return $this->form[$this->nameForm];
        }
        //Метод парсит тег и производит вставки атрибутов и т.д.
        private function collectElem($name, $elem, $endTag){
            $this->elem = $elem;
            $this->checkProperty($name);   
            $this->parentMethodName = __CLASS__.'::set'.ucfirst($this->elem);
            $this->parentMethod = strtr($this->elems[$this->elem], array($endTag=>' name="'.$this->$name.'"'.$endTag));  
        }
        //Собирает textarea
        public function setTextarea($name){
            $this->collectElem($name, 'textarea', self::EndTagTextarea);
            return $this;
        }    
        //собирает input всех типов
        public function setInput($name){
            $this->collectElem($name, 'input', self::EndTagInput); 
            return $this;
        }
        //инициализирует тег select
        public function setSelect($name){
            $this->collectElem($name, 'select', self::EndTagSelectInside);    
            return $this; 
        }
        //собирает теги option
        public function setOption($name){
            $this->checkProperty($name);  
            $this->parentMethodName = __METHOD__;
            $this->parentMethod = strtr($this->elems['option'], array(self::EndTagOption =>self::EndTagOption.$this->$name));         
            
            return $this;
        }    
        //устанавливает атрибуты тегам
        public function setAttr($attr){
            $this->attr = explode('|', $attr);
            
            if(is_array($this->attr)){
                if(isset($this->attr[1])){
                    $this->parentMethod = strtr($this->parentMethod,  
                     array($this->getEndTag() => ' '.$this->attr[0].'="'.$this->attr[1].'"'.$this->getEndTag()));
                } 
                else{
                    $this->parentMethod = strtr($this->parentMethod,  
                         array($this->getEndTag() => ' '.$this->attr[0].' '.$this->getEndTag())); 
                }                  
            }

            
            return $this; 
        }    
        
        //дописывает текст в option,checkbox, radio в поле textarea
        public function setText($text){
            if(!property_exists(__CLASS__, $text)){
                $this->createProperty($text,$text);
                $this->text = $text;
            }    
            else
                $this->text = $text;  
                      
            if(is_array($this->attr) && !empty($this->$text)){
                $this->parentMethod = strtr($this->parentMethod,  
                                            array($this->getEndTag() => $this->getEndTag().$this->$text));
            }    
            return $this;
        }
       
        //промежуточное сохранение в ассоц массив
        private function saveInArray($sub = false,$innerNameSub = null){
            if($sub)
                 $this->form[$this->nameForm][$this->name][] = $this->parentMethod;
            else if(!$sub)
                 $this->form[$this->nameForm][$this->name] = $this->parentMethod;    
            else if($innerNameSub != null){
                 $this->form[$this->nameForm][$innerNameSub.'_options'][$this->name] = $this->parentMethod;  
                 return $this->form[$this->nameForm][$innerNameSub.'_options'];
            }      
        }
        //формирует Input
        public function addInput(){
            if(preg_match('#\[\]#', $this->parentMethod))
                $this->saveInArray();
            else
                $this->saveInArray();
                
            $this->parentMethod = '<input rel="i"/>';
        } 
        //формирует select
        public function addSelect(){
            $this->saveInArray();
            $this->parentMethod = '<select rel="s"></select>';
        } 
        //формирует textarea
        public function addTextarea(){
            $this->saveInArray();
            $this->parentMethod = '<textarea rel="t"></textarea>';
        }     
        //формирует список option и собирает вместе с указанным select
        public function addOption($select){
            //ПРомежуточное сохранение вида тега option, далее он храниться в подмассиве select
            $this->form[$this->nameForm][$select.'_options'][$this->name] = $this->parentMethod;
            $temp = $this->form[$this->nameForm][$select.'_options'];
            $this->form[$this->nameForm][$select] = strtr($this->form[$this->nameForm][$select],
                                                          array(self::EndTagSelect => 
                                                          $temp[$this->name].PHP_EOL.self::EndTagSelect)
                                                          );
            $this->parentMethod = '<option rel="o"></option>';
            return $this;
        }     
        //ничё интересного
        private function getEndTag(){
            switch($this->parentMethodName){
                case __CLASS__.'::setTextarea' : $this->endTag = self::EndTagTextarea; break;
                case __CLASS__.'::setInput'    : $this->endTag = self::EndTagInput; break;
                case __CLASS__.'::setSelect'   : $this->endTag = self::EndTagSelectInside; break;
                case __CLASS__.'::setOption'   : $this->endTag = self::EndTagOption; break;
            }
            return $this->endTag;
        } 
        
        public function sendForm($submit){
            foreach($this->form[$this->nameForm] as $k=>$elem){
                if(!is_array($elem)){
                    if(strpos($elem, 'type="submit"') !== false){
                        $t = preg_match('#name="(.*)"#uUs', $elem, $s);
                        if(isset($_REQUEST[$submit])){
                            return $this->validate();
                        }
                    }
                }
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


