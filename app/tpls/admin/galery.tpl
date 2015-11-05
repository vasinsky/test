
<center>
<div class="acontent">
<h3><i class="fa fa-camera"></i> Галереи (Фотоальбомы)</h3>
<hr class="ahr"/>
<br/><br/>

    <?php if(isset($errors) && !empty($errors)) :?>
    
     <div class="alert alert-danger fade in"> 
     <ul class="text-danger">
     
     <?php foreach($errors as $error):?>
    
    <li><?=$error?></li>
    
    
     <?php endforeach;?>
     
     </ul>
     </div>
    <?php endif;?>

    

 <?php if(isset($galeryList)):?>
 
 
 
 
 <?php if(isset($paginate) && $paginate !== false):?>
 
  <ul class="pagination pagination-sm">
 
  <?php foreach($paginate as $i=>$n):?>
       
    <?php if($i == 'active') :?> 
    
     <li class="active"><a href=""><?=$n;?></a></li>

   <?php else:?>
  
    <li><a href="<?=Route::getUrl('?mode=admin&route=galery&page='.$n)?>"><?=$n;?></a></li>
    
   <?php endif;?>

  <?php endforeach;?>
 
  </ul> 
 
 <br/>
 
 <?php endif;?>
 
 <table  class="table table-hover table-striped">
 
 <tr>
  <th>#GLID</th>
  <th>#Наименование</th>
  <th>#Кол-во фото</th>
  <th>#Описание</th>
  <th></th>
 </tr> 
 
  <?php foreach($galeryList as $v=>$gal): ?>
 
 <tr>
  <td width="3%"><?=$gal['glid'];?></td>
  <td width="150px"><a href="<?=Route::getUrl('?mode=admin&route=editgalery&glid='.$gal['glid'])?>">
                     <?=$gal['gname'];?>
                    </a>
  </td>
  <td align="center" width="10%"><?=$gal['count'];?></td>
  <td><?=$gal['gdescription'];?></td>
  <td width="25%">
      <a href="<?=ROUTE::getUrl('?mode=admin&route=editgalery&glid='.(int)$gal['glid']);?>" title="Редактировать">
         <button type="button" class="btn btn-default  btn-xs"><span class="glyphicon glyphicon-pencil"></span> 
         Редактировать
         </button>
      </a>
      
  <a onclick="return confirm('Удалить галерею? Все фото этой галереи так же будут удалены!')" 
  href="<?=Route::getUrl('?mode=admin&route=galery&deletegalery='.(int)$gal['glid']);?>" title="Удалить">
  <button type="button" class="btn btn-danger  btn-xs"><span class="glyphicon glyphicon-trash"></span>
  Удалить</button>
  </a>        
  </td>
 </tr>
 
  <?php endforeach;?>
 
 </table>

 
 <?php endif;?>

 </table>

 <?php if(isset($paginate) && $paginate !==false):?>
 
  <ul class="pagination pagination-sm">
 
  <?php foreach($paginate as $i=>$n):?>
    
    <?php if($i == 'active') :?> 
    
     <li class="active"><a href=""><?=$n;?></a></li>

   <?php else:?>
  
    <li><a href="<?=Route::getUrl('?mode=admin&route=galery&page='.$n)?>"><?=$n;?></a></li>
    
   <?php endif;?>

  <?php endforeach;?>
 
  </ul> 
 
 
 
 <?php endif;?>
 
 
 
 <br/>
 <a href="<?=Route::getUrl('?mode=admin&route=addgalery');?>">
 <button type="button" class="btn btn-primary">Создать новую галерею</button>
 </a>


    
</div>



</center>