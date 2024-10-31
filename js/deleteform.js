 jQuery(document).ready(function($){


 // alert("hello here");
			 $('.del_form').click(function(){
				
				  var currentElement = $(this);
				 
				  var id = currentElement.attr('id');
				//alert(value );
				
				
				 jQuery.ajax({
        	type:'POST',
        	data:{action:'delete_form',
			form_id:id
			
			},
                url: "admin-ajax.php",
				
                success: function(r) {                                                     
                  console.log(r);
                window.location.href = window.location.href;
						

					}

			});//ajax
				});
		
		
			 });