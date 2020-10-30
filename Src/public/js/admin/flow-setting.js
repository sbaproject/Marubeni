$( document ).ready(function() {

   $('.select2').select2();

   $("#cbxForm").change(function(){
   	 
   	    var form = parseInt($(this).val());

	   	if (form === 2){   
	   	 	$(".form-trip").fadeIn();
	   	 	$(".form-entertaiment").fadeOut();

	   	}else if (form  === 3){
	   	 	$(".form-entertaiment").fadeIn();
	   	 	$(".form-trip").fadeOut();

	   	}else{
	        $(".form-entertaiment").fadeOut();
	        $(".form-trip").fadeOut();
	   	}
   });

   $( document ).on( "click", ".btn-add-step", function() {	
   	    var step = $(this).data("step")	;
        var index = 1;
        step++;       
   	    var html = '<div class="section-step-'+step+'">';
           html+=  '<div class="d-flex justify-between mt-5">';
           html+=  '<h5>STEP '+step+' <span>(Budget for per person : 2000.000)</span></h5>';
           html+=  '<div><button type="button" data-step="'+step+'" class="btn-del-step btn btn-danger pt-1 pb-1 pl-3 pr-3 mb-1">Delete</button></div>';
           html+=  '</div>';
   		   html+=  '<div class="approver-'+step+'-'+index+'">';
   		   html+=  '<div class="border border-secondary p-3 wrap-step-1">';
	       html += '<div class="d-flex justify-between">';
	       html += '<div class="text-muted">Approver</div>';	       
	       html += '</div>';
	       html += '<table class="table table-bordered table-sm">';
	       html += '<tr>';
	       html += '<td style="width: 20%;">Destination</td>';
	       html += '<td class="text-left">';
	       html += '<div class="form-check-inline">';
	       html += '<label class="form-check-label">';
	       html += '<input type="radio" checked="checked" name="step-'+step+'-dest-'+index+'">To';
	       html += '</label>';
	       html += '</div>';
	       html += '<div class="form-check-inline">';
	       html += '<label class="form-check-label">';
	       html += '<input type="radio" name="step-'+step+'-dest-'+index+'">CC';
	       html += '</label>';
	       html += '</div>';
	       html += '</td>';
	       html += '</tr>';
	       html += '<tr>';
	       html += '<td class="align-middle">Approver</td>';
	       html += '<td class="p-0"><input type="text" class="form-control" placeholder="Approver"></td>';
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
   });
   
   $( document ).on( "click", ".btn-add-approver", function() {	
        var step = $(this).data("step")	;
        var index = $(this).data("index");
        index++;
	   	var html =  '<div class="approver-'+step+'-'+index+'">';
	       html += '<div class="d-flex justify-between">';
	       html += '<div class="text-muted">Approver</div>';
	       html += '<div><button type="button" data-step="'+step+'" data-index="'+index+'" class="btn-del-approver btn btn-danger btn-sm pt-0 pb-0 pl-3 pr-3 mb-1">Delete</button></div>';
	       html += '</div>';
	       html += '<table class="table table-bordered table-sm">';
	       html += '<tr>';
	       html += '<td style="width: 20%;">Destination</td>';
	       html += '<td class="text-left">';
	       html += '<div class="form-check-inline">';
	       html += '<label class="form-check-label">';
	       html += '<input type="radio" checked="checked" name="step-'+step+'-dest-'+index+'">To';
	       html += '</label>';
	       html += '</div>';
	       html += '<div class="form-check-inline">';
	       html += '<label class="form-check-label">';
	       html += '<input type="radio" name="step-'+step+'-dest-'+index+'">CC';
	       html += '</label>';
	       html += '</div>';
	       html += '</td>';
	       html += '</tr>';
	       html += '<tr>';
	       html += '<td class="align-middle">Approver</td>';
	       html += '<td class="p-0"><input type="text" class="form-control" placeholder="Approver"></td>';
	       html += '</tr>';
	       html += '</table>';
	       html += '</div>';
	    $( ".block-add-approver-" +step).before( html );
	    $(this).data("index", index);
   });

   

   $( document ).on( "click", ".btn-del-step", function() {
        var step = $(this).data("step")	;       
	    $( ".section-step-" + step).remove();
   });	
 
   $( document ).on( "click", ".btn-del-approver", function() {
   	    var step = $(this).data("step")	;
        var index = $(this).data("index");
	    $( ".approver-" + step + "-" +  index ).remove();
   });


});