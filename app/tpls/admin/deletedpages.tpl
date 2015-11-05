<center>
<div class="acontent">
 
 <h3><span class="glyphicon glyphicon-list-alt"></span> Удалённые страницы</h3>
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
  <th>PID</th>
  <th>Псевдоним</th>
  <th>Title</th>
  <th>Раздел</th>
  <th></th>
  <th></th>
  <th></th>
  <th></th>
 </tr>
 
 <?php foreach($listPages['data'] as $v=>$page): ?>
 
 <tr> 
  <td width="3%"><a name="line<?=($v);?>"><?=$page['pid'];?></a></td>
  <td width="120px"><?=$page['name'];?></td>
  <td><span class="text-muted" href="<?=ROUTE::getUrl('?mode=admin&route=editpage&pid='.(int)$page['pid']);?>">
      
       <?=$page['title'];?>
      
      </span>
  </td>
  <td width="200px" align="left">
  <small class="text-muted">
    <?=(!empty($page['sname']) ? $page['sname'] : '<span class="text-danger"><b>удалён</b></span>')?></small>
  </td>
  <td  width="30px" align="center">
      <a class="text-muted"  target="_blank"  href="" title="Смотреть">
        <span class="glyphicon glyphicon-eye-open"></span>
      </a>
  </td>

  <td width="30px">
  
   <?php if($page['display'] == 1):?>
    
    <span class="glyphicon glyphicon-ok-circle"></span>
       
   <?php endif;?>
   
  </td>

  <td width="40px">
  <a  href="<?=Route::getUrl('?mode=admin&route=deletedpages&recoverypage='.(int)$page['pid']);?>" title="Восстановить">
  <button type="button" class="btn btn-success  btn-xs"><i class="fa fa-refresh"></i>
  Восстановить</button>
  </a>
  </td>
  <td width="40px">
  <a onclick="return confirm('Вы действительно хотите удалить страницу? Восстановление будет не возможно.')" 
  href="<?=Route::getUrl('?mode=admin&route=deletedpages&killpage='.(int)$page['pid']);?>" title="Удалить">
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

 </div>
</center>