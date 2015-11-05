function ge(id){
    return document.getElementById(id);
}

function show(id, capcha, block){
    ge(id).style.display = 'block';
    ge(id).style.opacity = 0;
    
    if(ge(id).style.opacity < 1){
        setInterval(function(){
                ge(id).style.opacity = parseFloat(ge(id).style.opacity)+0.2;
                if(block){
                    ge(block).style.display = 'block';
                }    
        },60);
    }
    
    
    ge(id).style.top = ge(id).style.top+document.body.scrollTop+'px';
    document.body.style.overflow = 'hidden';        
}

function hide(id, capcha, block){

   if(block){ 
      ge(block).style.display = 'none';  
   }
   ge(id).style.display = 'none'; 

    if(capcha){
        capcha.innerHtml = '';
    }
}