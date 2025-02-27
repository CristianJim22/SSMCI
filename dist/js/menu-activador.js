$(document).ready(function(){
  $('.nav-link').each(function() {
    //alert($(this).attr("data-value"));
    if($(this).attr("data-value") != $("#pagina").attr("data-value")){
      $(this).removeClass("active");
    }
  }); 
});
