
$( document ).ready(function() {
	
	$(function() {
	  $(".datepicker").datepicker({ dateFormat: 'yy-mm-dd' });
	});

	$(".modal-form").submit(function(e) {
    var url = $(this).attr('action');
    e.preventDefault();
    $.ajax({
	    type: "POST",
	    url: url,
	    data: $(this).serialize(),
	    success: function(data) {
				$('.modal-body').html($(data).find('#resultTable').html());
	    }
    }); 
	});

  $(".btn-danger").click(function(){
    if (!confirm("Do you really want to delete?")){
      return false;
    }
  });

});



