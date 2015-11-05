<center>
<div class="acontent">
<h3><span class="glyphicon glyphicon-plus-sign"></span> Создание нового раздела</h3>
<hr class="ahr"/>
<?=$formAddsection['begin'];?>


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
 <?=$formAddsection['sname'];?>
</div>

<div class="form-group">
 <label for="sindex">Псевдоним (только латинские буквы)</label> 
 <?=$formAddsection['sindex'];?>
</div>

<div class="form-group">
 <label for="sdescription">Описание раздела</label> 
 <br/>
 
 <?=$formAddsection['sdescription'];?>

</div>

 <table class="buttons">
  <tr>
   <td><?=$formAddsection['createsection'];?></td>
  </tr>
 </table>



<?=$formAddsection['end'];?>

</div>
</center>