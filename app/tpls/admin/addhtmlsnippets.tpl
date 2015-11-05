<center>
<div class="acontent">
<h3><span class="glyphicon glyphicon-plus-sign"></span> Создать HTML сниппет</h3>
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

<?=$formAddSnippet['begin'];?>

<div style="width:600px">

<div class="form-group">
 <label for="hsname">Название</label>
 <?=$formAddSnippet['hsname'];?>
</div>

<div class="form-group">
 <label for="hsdescription">Описание</label>
 <?=$formAddSnippet['hsdescription'];?>
</div>

<div class="form-group">
 <label for="hscode">Содержимое или код сниппета</label>
 <?=$formAddSnippet['hscode'];?>
</div>



</div>
<?=$formAddSnippet['addsnippet'];?>
<?=$formAddSnippet['end'];?>

</div>
</center>