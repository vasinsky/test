<center>
<div class="acontent">
<h3><i class="fa fa-user"></i> Создание группы пользователей</h3>
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

<div style="width:500px">

<?=$formAddGroupUsers['begin'];?>

<div class="form-group">
 <label for="display">Наименование</label> 
 <?=$formAddGroupUsers['aname'];?>
</div>

<?=$formAddGroupUsers['addgroupusers'];?>

<?=$formAddGroupUsers['end'];?>

</div>

<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</div>
</center>