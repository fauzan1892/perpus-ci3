/*
 *
 * Costom jQuery
 *
 */
/*-- loading page --*/

var myVar;
function myFunctionLoader() {
    myVar = setTimeout(showPage, 200);
}
function showPage() {
  $("#loader").fadeOut("slow");
}

  $(function () {
    $('#example1').DataTable()
    $('#example2').DataTable()
    $('#example3').DataTable()
    $('#example4').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    })
  });

/*-- append to footer bottom --*/

$("<p>Made with <i class='fa fa-heart'></i> Codekop</p>").appendTo("#made_with"); //appendTo: Append at inside bottom

/*-- date picker --*/
$( document ).ready(function() {
    $("#datepicker").datepicker({
        format: 'dd-mm-yyyy'
    });
    $("#datepicker").on("change", function () {
        var fromdate = $(this).val();
    });
});

$(function () {
	//Timepicker
	$('.timepicker').timepicker({
	  showInputs: false,
	  showMeridian: false
	})
});

$(function() {
  $('html, body, .wrapper').css('height', '100%');
})
