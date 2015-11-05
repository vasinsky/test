<center>
<div class="acontent">
<h3><span class="glyphicon glyphicon-pencil"></span> Редактирование страницы<br/>
<small><?=isset($titlePage) 
            ? '<span class="glyphicon glyphicon-refresh"></span>&nbsp;&nbsp;' . htmlspecialchars($titlePage)
            : '';?></small>
</h3>
<hr class="ahr"/>
<?=$formEditpage['begin'];?>


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
 <?=$formEditpage['title'];?>
 <?=$formEditpage['pid'];?>
</div>
<div class="form-group">
 <label for="description">Описание</label>
 <?=$formEditpage['description'];?>
</div>
<div class="form-group">
 <label for="keywords">Ключевые слова</label>
 <?=$formEditpage['keywords'];?>
</div>
<div class="form-group">
 <label for="index">Псевдоним (только латинские буквы)</label> 
 <?=$formEditpage['index'];?>
</div>

<div class="form-group">
 <label for="section">Раздел</label> 
 <?=$formEditpage['section'];?>
</div>

<div class="form-group">
 <label for="display">Включена</label> 
 <?=$formEditpage['display'];?>
</div>

<div class="form-group">
 <label for="content">Анонс контента</label> 
 <br/>
<span class="text-success"><b>* Для вставки HTML сниппета использовать bb-код:</b> <i>*snippet=[nameSnippet]*</i></span><br/><br/>
 
 <?=$formEditpage['preview'];?>

</div>

<div class="form-group">
 <label for="content">Содержание страницы</label> 
 <br/>
 
 <?=$formEditpage['content'];?>

</div>

 <table class="buttons">
  <tr>
   <td><input onclick="popupWindow(tinyMCE.get('content').getContent())" type="button" class="btn btn-primary btn-lg addpage" value="Предварительный просмотр"/></td>
   <td><?=$formEditpage['editpage'];?></td>
  </tr>
 </table>



<?=$formEditpage['end'];?>

</div>
</center>