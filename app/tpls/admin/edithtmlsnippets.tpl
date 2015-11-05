<center>
<div class="acontent">
<h3><span class="glyphicon glyphicon-plus-sign"></span> Редактирование HTML сниппета</h3>
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

<?=$formEditSnippet['begin'];?>

<div style="width:600px">

<div class="form-group">
 <label for="hsname">Название</label>
 <?=$formEditSnippet['hsname'];?>
</div>

<div class="form-group">
 <label for="hsdescription">Описание</label>
 <?=$formEditSnippet['hsdescription'];?>
</div>

<div class="form-group">
 <label for="hscode">Содержимое или код сниппета</label>
 <?=$formEditSnippet['code'];?>
</div>



</div>
<?=$formEditSnippet['editsnippet'];?>
<?=$formEditSnippet['end'];?>

</div>
</center>