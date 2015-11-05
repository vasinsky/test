<center>
<div class="acontent">
 
 <h3><span class="glyphicon glyphicon-th-list"></span> Управление пользователями</h3>
 <hr class="ahr"/>
 
 <?php if(is_array($usersList['data'])):?>
 
 <?php if($usersList['paginate'] !== false):?>
 
  <ul class="pagination pagination-sm">
 
  <?php foreach($usersList['paginate'] as $i=>$n):?>
       
    <?php if($i == 'active') :?> 
    
     <li class="active"><a href=""><?=$n;?></a></li>

   <?php else:?>
  
    <li><a href="<?=Route::getUrl('?mode=admin&route=users&page='.$n)?>"><?=$n;?></a></li>
    
   <?php endif;?>

  <?php endforeach;?>
 
  </ul> 
 
 <?php endif;?>

 <table  class="table table-hover table-striped">
 
 <tr>
  <th>#UID</th>
  <th>#Логин</th>
  <th>#Хэш пароля</th>
  <th></th>
  <th>#Email</th>
  <th>#Права</th> 
  <th width="20px" title="Уровень доступа" >#LA</th> 
  <th></th>
  <th></th>
 </tr>
 
 <?php foreach($usersList['data'] as $v=>$user): ?>
 <tr> 
  <td width="3%"><a name="line<?=($v);?>"><?=$user['uid'];?></a></td>
  <td width="150px"><?=$user['login'];?></td>
  <td width="150px"><small class="text-muted"><?=$user['password'];?></small></td>
  <td></td>
  <td width="100px"><?=$user['email'];?></td>
  <td width="200px">
   <select name="access" class="form-control input-sm" 
   onchange="location.href='<?=Route::getUrl('?mode=admin&route=users')?>&updateAccess=<?=$user['uid'];?>,'+this.value">
   
   <?php foreach($accessList as $k=>$v):?>
    
    <?php if(!in_array($v['acid'],array(2))):?>
    
     <option value="<?=$v['acid'];?>" <?=$user['isadmin']==$v['acid'] ? 'selected="selected"' : '';?>>
     <?=$v['aname'];?></option>
   
   <?php endif;?>
   
   <?php endforeach;?>
   
   </select>  
  </td>
  
  <td align="center"><?=$user['isadmin'];?></td>
  
  <td  width="30px" align="center">
      <a href="<?=ROUTE::getUrl('?mode=admin&route=edituser&uid='.(int)$user['uid']);?>" title="Редактировать">
        <button type="button" class="btn btn-default  btn-xs">
        <span class="glyphicon glyphicon-pencil"></span> Редактировать
        </button>
      </a>
  </td>  
  
  <td width="40px">
  <a onclick="return confirm('Удалить пользователя?')" 
  href="<?=Route::getUrl('?mode=admin&route=users&deleteuser='.(int)$user['uid']);?>" title="Удалить">
  <button type="button" class="btn btn-danger  btn-xs"><span class="glyphicon glyphicon-trash"></span>
  Удалить</button>
  </a>
  </td>
 </tr>
 <?php endforeach;?>
 
 
 <?php else:?>
  
  Страниц нет
 
 <?php endif;?>

 </table>

 <?php if($usersList['paginate'] !== false):?>
 
  <ul class="pagination pagination-sm">
 
  <?php foreach($usersList['paginate'] as $i=>$n):?>
    
    <?php if($i == 'active') :?> 
    
     <li class="active"><a href=""><?=$n;?></a></li>

   <?php else:?>
  
    <li><a href="<?=Route::getUrl('?mode=admin&route=users&page='.$n)?>"><?=$n;?></a></li>
    
   <?php endif;?>

  <?php endforeach;?>
 
  </ul> 
 
 <?php endif;?>
 
 <br/>
 <!--
 <a href="<?=Route::getUrl('?mode=admin&route=adduser');?>">
 <button type="button" class="btn btn-primary">Добавить пользователя</button>
 </a>
 -->
 </div>
</center>