<center>
<div class="acontent">
<h3><i class="fa fa-clipboard"></i> Редактирование галереи 
"<?=isset($data['meta']['gname']) ? $data['meta']['gname'] : null;?>"</h3>
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
    
    <?php if(isset($data)):?>

            <?=$editForm['begin'];?>
            
            
             <table width="100%" class="meta">
              <tr>
               <td width="250px" valign="top">
                 <h3>Обложка альбома</h3>

                 <?php if(isset($data['meta']['image']) && $data['meta']['image'] != null) :?>
                   
                   <?php if(file_exists($data['meta']['image'])):?>
                   
                  <img class="circle" width="420px" src="/<?=$data['meta']['image'];?>"/>
                  
                  <?php else:?>
                  
                   <div class="alert alert-danger fade in"> 
                   <ul class="text-danger">
                    <li>Обложка указана, но файл был удалён из папки. Замените обложку</li> 
                   </ul>
                  </div>                 
                  
                  <?php endif;?>
                  
                  
                 <?php else: ?> 
                  
                  <div class="alert alert-danger fade in"> 
                   <ul class="text-danger">
                    <li>Обложка не указана</li> 
                   </ul>
                  </div>
                   
                 <?php endif;?>            
               </td>
               <td>
                <div class="form-group">
                 <label for="name_galery">Наименование</label> 
                    <?=$editForm['name_galery'];?>
                </div>    
                <br/>
                    
                <div class="form-group">
                 <label for="sinonim_galery">Синоним для url</label> 
                    <?=$editForm['sinonim_galery'];?>
                </div>    
                <br/>
                
                <div class="form-group">
                 <label for="gdescription">Описание</label> 
                <?=$editForm['gdescription'];?>
                </div>               
               </td>
              </tr>
                         
            </table>
            
            
            <h3>Изображения</h3>
            <div class="images_new_galery">
            
            <?php if(isset($data['gimages']) and !empty($data['gimages'][0]['giid'])) :?>     
            
             <?php foreach($data['gimages'] as $k=>$i):?>
            
             <?php if(!empty($i['pic'])) :?>
            
             <table width="90%" class="itable">
              <tr>
               <td class="itd" width="200px" align="center">
                
                 <?=($i['pic'] == $data['meta']['image']) ? '<b><code>О Б Л О Ж К А</code></b>' : null;?>
               
                 <a href="/<?=$i['pic'];?>" data-lightbox="image-<?=$i['giid'];?>" 
                 title="<b><?=$i['giname'];?></b><br/><?=$i['gidescription'];?>">
                 <img class="circle" src="/<?=$i['thumb'];?>" border="0"/></a>
                
                <br/>
                 <center>

                 <a onclick="return confirm('Удалить картинку?')" 
                 href="<?=Route::getUrl('?mode=admin&route=editgalery&glid='.$data['meta']['glid'].'&delete_giid='.$i['giid']);?>" title="Удалить">
                
                 <button type="button" class="btn btn-danger  btn-xs">
                 <span class="glyphicon glyphicon-trash"></span>
                 Удалить изображение</button>
                 
                 </a>
                 <!--
                 <a href="<?=Route::getUrl('?mode=admin&route=editimage&giid='.$i['giid']);?>" title="Редактировать">
               
                 <button type="button" class="btn btn-default  btn-xs">
                 <span class="glyphicon glyphicon-pencil"></span>
                 Редактор</button>
                 
                 </a>
                 -->
                 
                 </center>
               </td>
               <td valign="top" class="metaimages">
                 <table width="100%">
                  <tr>
                   <td width="80px"><label>Название:</label></td>
                   <td><input class="form-control input-sm" type="text" 
                   value="<?=$i['giname'];?>" name="giname[<?=$i['giid']?>]"/></td>
                  </tr>
                  <tr>
                   <td><label>Описание:</label></td>
                   <td><input class="form-control input-sm" type="text" 
                   value="<?=$i['gidescription'];?>" name="gidescription[<?=$i['giid']?>]"/></td>
                  </tr>
                  <tr>
                  <tr>
                   <td width="80px"><label>Адрес:</label></td>
                   <td><input class="form-control input-sm" disabled="disabled" type="text" 
                   value="<?='/'.$i['pic'];;?>"/></td>
                  </tr>
                  <tr>
                   <td width="80px"><label>Preview:</label></td>
                   <td><input class="form-control input-sm" disabled="disabled" type="text" 
                   value="<?='/'.$i['thumb'];?>"/></td>
                  </tr>                                    
                  <td></td> 
                   <td>
                   <input name="cover" type="radio" 
                   <?=($i['pic'] == $data['meta']['image']) ? 'checked="checked"' : null;?> 
                   value="<?=$i['giid']?>"> Обложка галереи</td>
                  </tr>
                 </table>
               </td>
              </tr>
             </table>
             
             <?php endif;?>
             <br/>
            
            <?php endforeach;?>
            
            </div>
            
            <?php else: ?>
            
             <div class="alert alert-danger fade in"> 
             <ul class="text-danger">
              <li>Не обнаружено ни одного изображения в этой галереи</li>
             </ul>
             </div>            
             
            <?php endif;?>

    <?php else:?>
    
     <div class="alert alert-danger fade in"> 
     <ul class="text-danger">
      <li>Ошибка получения данных галереи</li>
     </ul>
     </div>
    
    <?php endif;?>            
            
        <br/>    
        
        <div class="images_new_galery">    
        <div class="form-group">
         <label for="images">Выберите картинки для загрузки</label>     
            <?=$editForm['images[]'];?>
        </div>    
        </div>
        <table>
        <tr>
         <td valign="top">
        <div class="form-group">
        <?=$editForm['resize'];?> <label for="resize">Резайз картинок</label> <br/> 
        
        <div class="images_new_galery"> 
        
            <table>
            <tr>
             <td>
             width:<?=$editForm['resize_w'];?>
             </td>
             <td width="30px"></td>
             <td>
             height:<?=$editForm['resize_h'];?>
             </td>
            </tr>
            </table>
               
                  <br/>  
                 <span class="text-muted">* - автоматический ресайз:<br/>
                  <b>по ширине:</b> указать width, height=0<br/>
                  <b>по высоте:</b> указать height, width=0
                  </span>
            <br/>      
            <br/> 
            <div class="form-group">
            <label for="image_quality">Качество изображений</label>
            <?=$editForm['image_quality'];?>
            </div>
        </div>    
            </div>   
             </td>
             <td width="30px"></td>
             <td valign="top">
            <div class="form-group">
             <?=$editForm['preview'];?> <label for="preview">Превью картинок</label><br/> 
        <div class="images_new_galery"> 
            <table>
            <tr>
             <td>
             width:<?=$editForm['preview_w'];?> 
             </td>
             <td width="30px"></td>
             <td>
             height:<?=$editForm['preview_h'];?>  
             </td>
            </tr>
            </table> 
            
        </div>   
             </td>
            </tr>
            </table>
        
        <br/>
        
        <div class="form-group">
         <?=$editForm['watermark'];?> <label for="watermarfile">Водяной знак</label>  
        <br/>
        <div class="images_new_galery"> 
        <table>
         <tr>
          <td>Расположение водяного знака на картинке</td>
          <td></td>
          <td><input type="radio" name="wm_type" value="wm_image" 
              <?=isset($_POST['wm_type']) && $_POST['wm_type'] == 'wm_image' ? 'checked="checked"' : ''?>>
              &nbsp;&nbsp;Картинка</td>
          <td></td>
          <td><input type="radio" name="wm_type" value="wm_text"
              <?=(isset($_POST['wm_type']) && $_POST['wm_type'] == 'wm_text') || !isset($_POST['wm_type']) ? 'checked="checked"' : ''?>>
              &nbsp;&nbsp;Текст</td>
         </tr>
         <tr>
          <td rospan="5" height="10px"></td>
         </tr>
         <tr>
          <td valign="top">
          <?=$editForm['wm_position'];?><br/>
          Прозрачность водяного знака
          <?=$editForm['alfa'];?>
          </td>
          <td width="30px"></td>
          <td valign="top"><?=$editForm['watermarkfile'];?></td>
          <td></td>
          <td><?=$editForm['wm_text'];?> цвет: 
          <?=$editForm['color'];?>
          размер:
          <?=$editForm['size'];?>
          <br/>
        
          </p>
          </td>
         </tr>
        </table>    
        </input>    
            <br/>
            
        
        </div>  
        
        <!-- color picker + hex to rgb converter -->
        
        <input type="hidden" name="red" value="0" id="r"/>
        <input type="hidden" name="green" value="0" id="g"/>
        <input type="hidden" name="blue" value="0" id="b"/>
        
        <!-- end -->
        
            <br/><br/>
           
            
            <?php #echo '<pre>' . print_r($data, 1) . '</pre>';?>
            


            
             <?=$editForm['savegalery'];?>
            
            <?=$editForm['end'];?>
   
    
</div>



</center>