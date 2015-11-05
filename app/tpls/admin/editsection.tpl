<center>
<div class="acontent">
<h3><span class="glyphicon glyphicon-pencil"></span> Редактирование раздела</h3>
<hr class="ahr"/>
<?=$formEditsection['begin'];?>


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
 <label for="sname">Наименование</label>
 <?=$formEditsection['sname'];?>
</div>

<div class="form-group">
 <label for="sindex">Псевдоним (только латинские буквы)</label> 
 <?=$formEditsection['sindex'];?>
</div>

<div class="form-group">
 <label for="sdescription">Описание раздела</label> 
 <br/>
 
 <?=$formEditsection['sdescription'];?>

</div>

 <table class="buttons">
  <tr>
   <td><?=$formEditsection['savesection'];?></td>
  </tr>
 </table>



<?=$formEditsection['end'];?>

</div>
</center>