<center>
<div class="acontent">
 
 <h3><span class="glyphicon glyphicon-list-alt"></span> Управление активными страницами</h3>
 <hr class="ahr"/>
 
 <?php if(is_array($listPages['data'])):?>
 
 <?php if($listPages['paginate'] !== false):?>
 
  <ul class="pagination pagination-sm">
 
  <?php foreach($listPages['paginate'] as $i=>$n):?>
       
    <?php if($i == 'active') :?> 
    
     <li class="active"><a href=""><?=$n;?></a></li>

   <?php else:?>
  
    <li><a href="<?=Route::getUrl('?mode=admin&page='.$n)?>"><?=$n;?></a></li>
    
   <?php endif;?>

  <?php endforeach;?>
 
  </ul> 
 
 <?php endif;?>

 <table  class="table table-hover table-striped">
 
 <tr>
  <th>#PID</th>
  <th>#Псевдоним</th>
  <th>#Title</th>
  <th>#Раздел</th>
  <th>#Доступ</th>
  <th></th>
  <th></th>
  <th></th>
  <th></th>
  <th></th>
 </tr>
 
 <?php foreach($listPages['data'] as $v=>$page): ?>
 
 <tr> 
  <td width="3%"><a name="line<?=($v);?>"><?=$page['pid'];?></a></td>
  <td width="250px"><?=$page['name'];?></td>
  <td><a href="<?=ROUTE::getUrl('?mode=admin&route=editpage&pid='.(int)$page['pid']);?>">
      
       <?=$page['title'];?>
      
      </a>
  </td>
  <td width="200px" align="left">
  <small class="text-muted">
  <?=(!empty($page['sname']) ? $page['sname'] : '<span class="text-danger"><b>удалён</b></span>')?></small>
  </td>
  <td width="150px">
   <select name="access" class="form-control input-sm" 
   onchange="location.href='<?=Route::getUrl('?mode=admin&route=pages')?>&updateAccess=<?=$page['pid'];?>,'+this.value">
    <option value="0">Все</option>
   <?php foreach($accessList as $k=>$v):?>
      
     <?php if(!in_array($v['acid'], array(1,2))):?> 
      
     <option value="<?=$v['acid'];?>" <?=$page['acid']==$v['acid'] ? 'selected="selected"' : '';?>>
     <?=$v['aname'];?></option>
   
    <?php endif;?>
   
   <?php endforeach;?>
   
   </select>    
  </td>
  <td  width="30px" align="center">

      <a  target="_blank"  href="<?=ROUTE::getUrl('?sections='.$page['sindex'].'&page='.$page['name']);?>" title="Смотреть">
        <span class="glyphicon glyphicon-eye-open"></span>
      </a>
  </td>
  <td  width="30px" align="center">
      <a href="<?=ROUTE::getUrl('?mode=admin&route=editpage&pid='.(int)$page['pid']);?>" title="Редактировать">
        <span class="glyphicon glyphicon-pencil"></span>
      </a>
  </td>  

  <td width="30px">
  
   <?php if($page['display'] == 1):?>
    
    <span class="glyphicon glyphicon-ok-circle"></span>
       
   <?php else:?>    
      
       <i class="fa fa-lock"></i>
   
   <?php endif;?>
   
  </td>
  <td  width="30px">
      <a title="Скопировать ссылку на страницу в буфер обмена" 
      onclick="copy_clip('<?=ROUTE::getUrl('/'.PATH.'?sections='.$page['sindex'].'&page='.$page['name']);?>');return false" href="#">
        <span class="glyphicon glyphicon-globe"></span>
      </a>
  </td>
  <td width="40px">
  <a onclick="return confirm('Удалить страницу?')" 
  href="<?=Route::getUrl('?mode=admin&route=pages&deletepage='.(int)$page['pid']);?>" title="Удалить">
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

 <?php if($listPages['paginate'] !== false):?>
 
  <ul class="pagination pagination-sm">
 
  <?php foreach($listPages['paginate'] as $i=>$n):?>
    
    <?php if($i == 'active') :?> 
    
     <li class="active"><a href=""><?=$n;?></a></li>

   <?php else:?>
  
    <li><a href="<?=Route::getUrl('?mode=admin&page='.$n)?>"><?=$n;?></a></li>
    
   <?php endif;?>

  <?php endforeach;?>
 
  </ul> 
 
 <?php endif;?>
 
 <br/>
 <a href="<?=Route::getUrl('?mode=admin&route=addpage');?>">
 <button type="button" class="btn btn-primary">Создать новую страницу</button>
 </a>

 </div>
</center>