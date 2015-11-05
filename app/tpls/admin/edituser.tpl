<center>
<div class="acontent">
<h3><i class="fa fa-user"></i> Редактирование пользователя</h3>
<hr class="ahr"/>
<br/>
<?php if(isset($errors) && !empty($errors)) :?>
 
 <div class="alert alert-danger fade in"> 
 <ul class="text-danger">
 
 <?php foreach($errors as $error):?>

  <li><?=$error?></li>

 <?php endforeach;?>
 
 </ul>
 </div> 

<?php endif;?>

<?=$formUserData['begin'];?>

<div style="width:600px">

<div class="form-group">
 <label for="login">Логин</label>
 <?=$formUserData['login'];?>
 <?=$formUserData['uid'];?>
</div>

<div class="form-group">
 <label for="password">Hash пароля</label>
 <?=$formUserData['password'];?>
</div>

<div class="form-group">
 <label for="email">Email</label>
 <?=$formUserData['email'];?>
</div>

<div class="form-group">
 <label for="access"></label> 
 <?=$formUserData['access'];?>
</div>

 <?=$formUserData['edituser'];?>

</div>

<?=$formUserData['end'];?>

<br/><br/>

<h3>Генерация hash нового пароля</h3>
<hr class="ahr"/>
<br/>
Введите новый пароль: <input id="newpassword" style="width:290px" type="text"> 
<input type="button" value="Генерировать hash" onclick="genHash()"/>
<input type="hidden" value="<?=SALT;?>" id="salt">

<br/><br/>
</div>
</center>