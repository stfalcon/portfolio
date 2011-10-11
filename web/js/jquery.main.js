jQuery(document).ready(function(){


jQuery(".content input[type=submit]").hover (
function()
{
	jQuery(this).addClass("sbntHover");
},
function()
{
	jQuery(".sbntHover").removeClass("sbntHover");
	jQuery(this).removeClass("sbntClicking");
	
});	


jQuery(".content input[type=submit]").mouseup(function(){
      jQuery(this).removeClass("sbntClicking");
    }).mousedown(function(){
      jQuery(this).addClass("sbntClicking");
    });





});