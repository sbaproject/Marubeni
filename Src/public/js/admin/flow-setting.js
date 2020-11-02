$( document ).ready(function() {

   $('.select2').select2();
 
   $("#cbxForm").change(function(){
   	 
   	    var form = parseInt($(this).val());

	   	if (form === 2){   
	   	 	$(".form-trip").fadeIn();
	   	 	$(".form-entertaiment").fadeOut();

	   	}else if (form  === 3){	   		
	   	 	$(".form-entertaiment").fadeIn();
	   	 	$(".form-not-po").fadeOut();
	   	 	$(".form-trip").fadeOut();	   	 	

	   	}else{
	        $(".form-entertaiment").fadeOut();
	        $(".form-trip").fadeOut();
	        $(".form-not-po").fadeOut();
	   	}
   });


   $(".typePosition").change(function(){
   	  if($('#positionPO').is(':checked')) { 
   	      $(".form-not-po").fadeOut();
   	  	  $(".form-po").fadeIn();
   	  }else{
          $(".form-not-po").fadeIn();
   	  	  $(".form-po").fadeOut();
   	  }
   });

   $( document ).on( "click", ".btn-add-step", function() {	
   	    var index_idx = parseInt($("#index-idx").val());
   	    var step = $(this).data("step")	;
        var index = 1;
        step++;
        $('#cbxApprover-0').select2("destroy");
	   	var clonedCbx = $('.cbx-approver').first().clone(true);	   
	   	clonedCbx.attr('id', 'cbxApprover-' + index_idx);
	   	clonedCbx.attr('name', 'approver['+step+']['+index_idx+']');
   	    var html = '<div class="section-step section-step-'+step+'">';
            html+=  '<div class="d-flex justify-between mt-5">';
            html+=  '<h5>STEP <span class="title-step">'+step+'</span></h5>';
            html+=  '<div><button type="button" data-step="'+step+'" class="btn-del-step btn btn-danger pt-1 pb-1 pl-3 pr-3 mb-1">Delete</button></div>';
            html+=  '</div>';
   		    html+=  '<div class="approver-'+step+'-'+index+'">';
   		    html+=  '<div class="border border-secondary p-3 wrap-step-1">';
	        html += '<div class="d-flex justify-between">';
	        html += '<div class="text-muted">Approver 1</div>';	       
	        html += '</div>';
	        html += '<table class="table table-bordered table-sm">';
	        html += '<tr>';
	        html += '<td style="width: 20%;">Destination</td>';
	        html += '<td class="text-left">';
	        html += '<div class="form-check-inline">';
	        html += '<label class="form-check-label">';
	        html += '<input type="radio" checked="checked" value="0" name="destination['+step+']['+index_idx+']">To';
	        html += '</label>';
	        html += '</div>';
	        html += '<div class="form-check-inline">';
	        html += '<label class="form-check-label">';
	        html += '<input type="radio" value="1" name="destination['+step+']['+index_idx+']">CC';
	        html += '</label>';
	        html += '</div>';
	        html += '</td>';
	        html += '</tr>';
	        html += '<tr>';
	        html += '<td class="align-middle">Approver</td>';
	        html += '<td class="p-0 append-approver-'+index_idx+' text-left"></td>';
	        html += '</tr>';
	        html += '</table>';
	        html += '<div class="block-add-approver-'+step+'">';
            html += '<button type="button" data-step="'+step+'" data-index="1" class="btn-add-approver btn btn-outline-dark pt-0 pb-0 pl-3 pr-3">+ Add</button>';
            html += '</div>';
	        html += '</div>';
	        html += '</div>';
	        html += '</div>';

	    $( ".block-add-step" ).before( html );
	    $(this).data("step", step);
	    $( ".append-approver-" +index_idx).append(clonedCbx);
	    $('.cbx-approver').select2();

	    index_idx++;
	    $("#index-idx").val(index_idx);

	    $(this).fadeOut();
   });
   
   $( document ).on( "click", ".btn-add-approver", function() {	
   	    var index_idx = parseInt($("#index-idx").val());
   	    var step = $(this).data("step")	;
        var index = $(this).data("index");        
        $('#cbxApprover-0').select2("destroy");
	   	var clonedCbx = $('.cbx-approver').first().clone(true);
	   	clonedCbx.attr('id', 'cbxApprover-' + index_idx);
	    clonedCbx.attr('name', 'approver['+step+']['+index_idx+']');
	   	var noOfDivs = $('.section-step-'+ step +' .section-approver').length;	   	  
	   	index =  noOfDivs > 0 ? (noOfDivs + 1) : index;
	    index++;	   	   
	   	var html =  '<div class="section-approver approver-'+step+'-'+index+'">';
	        html += '<div class="d-flex justify-between">';
	        html += '<div class="text-muted">Approver <span class="title-approver">'+index+'</span></div>';
	        html += '<div><button type="button" data-step="'+step+'" data-index="'+index+'" class="btn-del-approver btn btn-danger btn-sm pt-0 pb-0 pl-3 pr-3 mb-1">Delete</button></div>';
	        html += '</div>';
	        html += '<table class="table table-bordered table-sm">';
	        html += '<tr>';
	        html += '<td style="width: 20%;">Destination</td>';
	        html += '<td class="text-left">';
	        html += '<div class="form-check-inline">';
	        html += '<label class="form-check-label">';
	        html += '<input type="radio" checked="checked" value="0" name="destination['+step+']['+index_idx+']">To';
	        html += '</label>';
	        html += '</div>';
	        html += '<div class="form-check-inline">';
	        html += '<label class="form-check-label">';
	        html += '<input type="radio" value="1" name="destination['+step+']['+index_idx+']">CC';
	        html += '</label>';
	        html += '</div>';
	        html += '</td>';
	        html += '</tr>';
	        html += '<tr>';
	        html += '<td class="align-middle">Approver</td>';
	        html += '<td class="p-0 append-approver-'+index_idx+' text-left"></td>';
	        html += '</tr>';
	        html += '</table>';
	        html += '</div>';
	    $( ".block-add-approver-" +step).before( html );	   
	    $( ".append-approver-" +index_idx).append(clonedCbx);
	    $('.cbx-approver').select2();
	    index_idx++;
	    $("#index-idx").val(index_idx);
   });

   

   $( document ).on( "click", ".btn-del-step", function() {    
        $( this ).parent().parent().parent().remove();  	    
	    var step = 1;    
	    $( ".section-step" ).each(function( index ) {
	        step = index+2;
		    $(this).find(".title-step").html(step);		   
		});
		$(".btn-add-step").fadeIn();
		$(".btn-add-step").data("step", step);

		
   });	
 
   $( document ).on( "click", ".btn-del-approver", function() {
   	    var step = $(this).data("step")	;
   	    $( this ).parent().parent().parent().remove();
   	    var pos = 1;
        $( ".section-step-"+step+" .section-approver" ).each(function( index ) {        	
	        pos = index+2;	      
		    $(this).find(".title-approver").html(pos);		   
		});          	    
   });
});