<center>
<div class="acontent">
<h3><i class="fa fa-user"></i> Группы пользователей</h3>
<hr class="ahr"/>

<?php if(is_array($groupsUsersList)):?>

 <table class="table table-hover table-striped">
 
 <tr>
  <th>#ACID</th>
  <th>#Name</th>
  <th></th>
  <th></th>
  <th></th>
 </tr>
 
 <?php foreach($groupsUsersList as $v=>$group): ?>
 
  <tr>
   <td width="50px"><?=$group['acid'];?></td>
   <td width="300px"><?=$group['aname'];?></td>
   <td></td>
   <td  width="60px">
  <a  
  href="<?=Route::getUrl('?mode=admin&route=editgroupusers&acid='.(int)$group['acid']);?>" title="Редактировать">
  <button type="button" <?=in_array($group['acid'], array(1,2,3)) ? 'disabled' : '';?> class="btn btn-default  btn-xs"><span class="glyphicon glyphicon-pencil"></span>
  Редактировать</button>
  </a>      
   </td>
   <td  width="60px">
  <a onclick="return confirm('Удалить группу? Все участники группы будут перемещены в группу Гость (acid=2)')" 
  href="<?=Route::getUrl('?mode=admin&route=groupsusers&deletegroupusers='.(int)$group['acid']);?>" title="Удалить">
  <button type="button" <?=in_array($group['acid'], array(1,2,3)) ? 'disabled' : '';?> class="btn btn-danger  btn-xs"><span class="glyphicon glyphicon-trash"></span>
  Удалить</button>
  </a>   
   </td>
  </tr>
 
 <?php endforeach;?>

 </table>

<?php else :?>

  Список групп пуст

<?php endif;?>

 <br/>
  <br/>
 <a href="<?=Route::getUrl('?mode=admin&route=addgroupusers');?>">
 <button type="button" class="btn btn-primary">Создать новую группу</button>
 </a>
 
 <br/>
<br/>
</div>
</center>