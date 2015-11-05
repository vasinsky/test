<center>
<div class="acontent">
<h3><span class="glyphicon glyphicon-plus-sign"></span> Добавить модуль</h3>
<hr class="ahr"/>

<?php if(isset($errors) && !empty($errors)) :?>
 
 <div class="alert alert-danger fade in"> 
 <ul class="text-danger">
 
 <?php foreach($errors as $error):?>

  <li><?=$error?></li>

 <?php endforeach;?>
 
 </ul>
 </div> 

<?php endif;?>


<?=$formAddModule['begin'];?>

<table class="addmoduleformtable" width="500px">
 <tr>
  <td><label for="name">Наименование</label> </td>
  <td><?=$formAddModule['name'];?>
  <small>только латинские буквы</small>
  </td>
 </tr>
 <tr>
  <td><label for="name">Режим<br/></label></td>
  <td><?=$formAddModule['mode'];?></td>
 </tr>
 <tr>
  <td colspan="2"><?=$formAddModule['createmodule'];?></td>
 </tr>
</table>

<?=$formAddModule['end'];?>

<div class="bs-callout bs-callout-warning">
<span class="help-block">При создании нового модуля будет создано 3 файла (по наименованию модуля).<br/><br/>
Например вы назвали будующий модуль "articles", следовательно в директории фреймворка
<ul>
 <li>/app/tpls/admin/articles.tpl</li>
 <li>/app/controllers/admin/articles.tpl</li>
 <li>/app/models/admin/articles.tpl</li>
</ul><br/>
или (если для публичной части)
<ul>
 <li>/app/tpls/articles.tpl</li>
 <li>/app/controllers/articles.tpl</li>
 <li>/app/models/articles.tpl</li>
</ul>
В данные файлы будет предварительно помещён дефолтовый код.
<br/>
<br/>
<b>Новый модуль будет доступен по следующим адресам:</b><br/><br/>
Если админ часть:<br/>
<i>
<?=Route::getUrl('domain.com/pathtosite/index.php?mode=admin&route=namemodule');?><br/>
<?=Route::getUrl('domain.com/pathtosite/?mode=admin&route=namemodule');?><br/>
</i>
<br/>
Если публичная часть:<br/>
<i>
<?=Route::getUrl('domain.com/pathtosite/index.php?mode=public&route=namemodule');?><br/>
<?=Route::getUrl('domain.com/pathtosite/?mode=public&route=namemodule');?><br/>
<?=Route::getUrl('domain.com/pathtosite/?route=namemodule');?><br/>
</i>
</span>
</div>



 
 




<?=$formAddModule['end'];?>

</div>