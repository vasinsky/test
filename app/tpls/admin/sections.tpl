<center>
<div class="acontent">
 
 <h3><span class="glyphicon glyphicon-th-list"></span> Управление разделами</h3>
 <hr class="ahr"/>
 
 <?php if(is_array($sectionsList['data'])):?>
 
 <?php if($sectionsList['paginate'] !== false):?>
 
  <ul class="pagination pagination-sm">
 
  <?php foreach($sectionsList['paginate'] as $i=>$n):?>
       
    <?php if($i == 'active') :?> 
    
     <li class="active"><a href=""><?=$n;?></a></li>

   <?php else:?>
  
    <li><a href="<?=Route::getUrl('?mode=admin&route=sections&page='.$n)?>"><?=$n;?></a></li>
    
   <?php endif;?>

  <?php endforeach;?>
 
  </ul> 
 
 <?php endif;?>

 <table  class="table table-hover table-striped">
 
 <tr>
  <th>SID</th>
  <th>Псевдоним</th>
  <th>Наименование</th>
  <th>Страниц</th>
  <th>Описание</th>
  <th></th>
  <th></th>
 </tr>
 
 <?php foreach($sectionsList['data'] as $v=>$sect): ?>
 
 <tr> 
  <td width="3%"><a name="line<?=($v);?>"><?=$sect['sid'];?></a></td>
  <td width="120px"><?=$sect['sindex'];?></td>
  <td><a target="_blank" href="<?=Route::getUrl('?route='.$sect['sindex']);?>">
        <?=$sect['sname'];?>
      </a>
  </td>
  <td width="30px"><span class="badge pull-center"><?=$sect['countpages']?></span></td>
  <td width="400px" align="left">
  <small class="text-muted"><?=$sect['sdescription']?></small>
  </td>

  <td  width="30px" align="center">
      <a href="<?=ROUTE::getUrl('?mode=admin&route=editsection&sid='.(int)$sect['sid']);?>" title="Редактировать">
        <button type="button" class="btn btn-default  btn-xs">
        <span class="glyphicon glyphicon-pencil"></span> Редактировать
        </button>
      </a>
  </td>  

  <td width="40px">
  <a onclick="return confirm('Удалить раздел? Так же будут удалены (перемещены в удалённые) все страницы этого раздела')" 
  href="<?=Route::getUrl('?mode=admin&route=sections&deletesection='.(int)$sect['sid']);?>" title="Удалить">
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

 <?php if($sectionsList['paginate'] !== false):?>
 
  <ul class="pagination pagination-sm">
 
  <?php foreach($sectionsList['paginate'] as $i=>$n):?>
    
    <?php if($i == 'active') :?> 
    
     <li class="active"><a href=""><?=$n;?></a></li>

   <?php else:?>
  
    <li><a href="<?=Route::getUrl('?mode=admin&route=sections&page='.$n)?>"><?=$n;?></a></li>
    
   <?php endif;?>

  <?php endforeach;?>
 
  </ul> 
 
 <?php endif;?>
 
 <br/>
 <a href="<?=Route::getUrl('?mode=admin&route=addsection');?>">
 <button type="button" class="btn btn-primary">Создать новый раздел</button>
 </a>

 </div>
</center>