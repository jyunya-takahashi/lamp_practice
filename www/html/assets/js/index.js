$(function(){
  $("#submit_select").change(function(){
    console.log('fired');
    $("#submit_form").submit();
  });
});