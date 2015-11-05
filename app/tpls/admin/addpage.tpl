<center>
<div class="acontent">
<h3><span class="glyphicon glyphicon-plus-sign"></span> Создание новой страницы</h3>
<hr class="ahr"/>
<?=$formAddpage['begin'];?>


<?php if(isset($errors) && !empty($errors)) :?>
 
 <div class="alert alert-danger fade in"> 
 <ul class="text-danger">
 
 <?php foreach($errors as $error):?>

  <li><?=$error?></li>

 <?php endforeach;?>
 
 </ul>
 </div> 

<?php endif;?>

<div class="form-group">
 <label for="title">Title</label>
 <?=$formAddpage['title'];?>
</div>
<div class="form-group">
 <label for="description">Описание</label>
 <?=$formAddpage['description'];?>
</div>
<div class="form-group">
 <label for="keywords">Ключевые слова</label>
 <?=$formAddpage['keywords'];?>
</div>
<div class="form-group">
 <label for="index">Псевдоним (только латинские буквы)</label> 
 <?=$formAddpage['index'];?>
</div>

<div class="form-group">
 <label for="section">Раздел</label> 
 <?=$formAddpage['section'];?>
</div>

<div class="form-group">
 <label for="display">Включена</label> 
 <?=$formAddpage['display'];?>
</div>

<div class="form-group">
 <label for="content">Анонс контента</label> 
 <br/>
<span class="text-success"><b>* Для вставки HTML сниппета использовать bb-код:</b> <i>*snippet=[nameSnippet]*</i></span><br/><br/>
 
 <?=$formAddpage['preview'];?>

</div>

<div class="form-group">
 <label for="content">Содержание страницы</label> 
 <br/>
 
 <?=$formAddpage['content'];?>

</div>

 <table class="buttons">
  <tr>
   <td><input onclick="popupWindow(tinyMCE.get('content').getContent())" type="button" class="btn btn-primary btn-lg addpage" value="Предварительный просмотр"/></td>
   <td><?=$formAddpage['addpage'];?></td>
  </tr>
 </table>



<?=$formAddpage['end'];?>

</div>
</center>