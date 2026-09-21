<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="clearfix"></div>
<footer class="main-footer">
    <div id="mycredit"><strong> Copyright &copy; <?php echo date('Y');?> Sistem Informasi Perpustakaan Codekop 
    </strong> All rights | Page rendered in <strong>{elapsed_time}</strong> seconds. 
    <div class="pull-right">
     <span id="made_with"></span>
    </div></div>
</footer>

<div id="logout"></div>
<style>
    #notifikasi {
        margin-bottom: 15px;
    }

    #notifikasi .alert {
        position: relative;
        margin: 0;
        padding: 13px 38px 13px 16px;
        border: 0;
        border-left: 4px solid rgba(0, 0, 0, .18);
        border-radius: 4px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .08);
    }

    #notifikasi .alert p {
        margin: 0;
    }

    #notifikasi .close {
        position: absolute;
        top: 8px;
        right: 12px;
        color: inherit;
        opacity: .65;
    }
</style>
<!-- ./wrapper -->
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url();?>assets/adminlte/bower_components/bootstrap/dist/js/bootstrap.js"></script>
<script src="<?php echo base_url();?>assets/adminlte/plugins/summernote/summernote-lite.js"></script>

<script>
    $('#summernotehal').summernote({
        height: 150,
        tabsize: 1,
        direction: 'rtl',
        toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
								['view', ['fullscreen', 'help']],
								['table', ['table']],
                ],
	});
</script>
<!-- Select2 -->
<script src="<?php echo base_url();?>assets/adminlte/bower_components/select2/dist/js/select2.full.min.js"></script>
<script>

$(function() {
    //Initialize Select2 Elements
    $('.select2').select2();
});
// Restricts input for each element in the set of matched elements to the given inputFilter.
(function($) {
  $.fn.inputFilter = function(inputFilter) {
    return this.on("input keydown keyup mousedown mouseup select contextmenu drop", function() {
      if (inputFilter(this.value)) {
        this.oldValue = this.value;
        this.oldSelectionStart = this.selectionStart;
        this.oldSelectionEnd = this.selectionEnd;
      } else if (this.hasOwnProperty("oldValue")) {
        this.value = this.oldValue;
        this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
      }
    });
  };
}(jQuery));
// Install input filters.
$("#uintTextBox").inputFilter(function(value) {
  return /^\d*$/.test(value); });
// Install input filters.
$("#uintTextBox2").inputFilter(function(value) {
  return /^\d*$/.test(value); });
$("#uintTextBox3").inputFilter(function(value) {
  return /^\d*$/.test(value); });
</script>
<script>
    $(function () {
        $('#notifikasi .alert').each(function () {
            var $alert = $(this);
            if (!$alert.find('.close').length) {
                $alert.prepend('<button type="button" class="close" aria-label="Tutup">&times;</button>');
            }
            $alert.find('.close').on('click', function () {
                $alert.stop(true, true).slideUp(180, function () { $(this).remove(); });
            });
        });

        setTimeout(function () {
            $('#notifikasi .alert').stop(true, true).slideUp(250, function () { $(this).remove(); });
        }, 5000);
    });
</script>

<!-- custom jQuery -->
<script src="<?php echo base_url();?>assets/adminlte/dist/js/custom.js"></script>

<!-- Logout Ajax -->
<!-- AdminLTE App -->
<script src="<?php echo base_url();?>assets/adminlte/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url();?>assets/adminlte/dist/js/demo.js"></script>
<!-- PACE -->
<script src="<?php echo base_url();?>assets/adminlte/bower_components/PACE/pace.min.js"></script>
<!-- DataTables -->
<script src="<?php echo base_url();?>assets/adminlte/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url();?>assets/adminlte/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- bootstrap datepicker -->
<script src="<?php echo base_url();?>assets/adminlte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- bootstrap time picker -->
<script src="<?php echo base_url();?>assets/adminlte/plugins/timepicker/bootstrap-timepicker.min.js"></script>
</body>
</html>
