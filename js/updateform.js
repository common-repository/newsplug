 
 jQuery(document).ready(function($){

var count_submit=0;
var count=parseInt($('.update_button').attr('last_order'));
count=count+1;
var text_count=1;
var email_count=1;
$('.update_button').click(function(){
	
	var input_text_values = [];
	var input_email_values = [];
	var input_submit_values=[];

	var basicArray = new Array();
    basicArray = [] ;

	
	var form_name = '';
	var text_id="";
	var email_id="";
	form_name =$( ".form_name" ).val();
	
	var id 	=	$(this).attr('form-id');
	
	$('.text_class').each(function(i, e){
      var currentElement = $(this);

    var value = currentElement.val();
	text_type= currentElement.attr("type");
	text_id= currentElement.attr("unique-data");

	text_order= currentElement.attr("order");
		

	text_name=$(".text_class").attr("name");
	basicArray.push(text_type+'_'+value+'_'+text_id+'_'+text_order);
	
	


 });
	$('.email_class').each(function(){
      var currentElement = $(this);
     email_type=email_id=value='';
    var value = currentElement.val();
	email_type= currentElement.attr("type");
	email_id= currentElement.attr("unique-data");

	email_order= currentElement.attr("order");
	
	email_name= currentElement.attr("name");
	basicArray.push(email_type+'_'+value+'_'+email_id+'_'+email_order);
	

 });
 
 	$('.submit_class').each(function(){
      var currentElement = $(this);

    var value = currentElement.val();
	
	submit_type=$(".submit_class").attr("type");
	submit_id=$(".submit_class").attr("unique-data");

	submit_order=$(".submit_class").attr("order");
	basicArray.push(submit_type+'_'+value+'_'+submit_id+'_'+submit_order);
	
 });

 jQuery.ajax({
        	type:'POST',
        	data:{action:'updateform_request',
			form_name:form_name,
			data:basicArray,
			form_id:id,
			},
                url: "admin-ajax.php",
				
                success: function(r) {                                                     
                  console.log(r);
				  alert("Form Saved Successfully");
              window.location.href = window.location.href;
						

					}

			});//ajax
			
 

});


$('.draggable').draggable({
    revert: true,
    revertDuration: 0,
    stack: ".draggable"
    //helper: 'clone'

});
$('.droppable').droppable({
	
    accept: ".draggable",
    drop: function( event, ui ) {
    var droppable = $(this);
    var draggable = ui.draggable;
    var clone = draggable.clone();  
    
    // Move draggable into droppable
    if(clone.find('input').attr('unique-data')=='1' && count_submit>0)
	{
		alert("You can only add one submit button");
		return false;
	}
	$(this).append(clone);
    clone.css({top: '20px', left: '5px',float: 'left'});
	clone.find('input').removeAttr('readonly');
	if(clone.find('input').attr('unique-data')=='1')
	{
		
		clone.find('input').attr('class', 'submit_class');
		clone.find('input').attr('value', 'submit');
		clone.find('input').attr('order', count);
		count=count+1;
		count_submit=count_submit+1;
	}
	if(clone.find('input').attr('unique-data')=='2')
	{
		clone.find('input').attr('class', 'text_class');
		//clone.find('input').attr('name', 'text-'+text_count);
		clone.find('input').attr('order', count);
		count=count+1;
		text_count=text_count+1;
	}
	if(clone.find('input').attr('unique-data')=='3')
	{
		clone.find('input').attr('class', 'email_class');
		//clone.find('input').attr('name', 'email-'+email_count);
		clone.find('input').attr('order', count);
		count=count+1;
		email_count=email_count+1;
	}
	
}   
	
});
 });