<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $title_web;?> | Sistem Informasi Perpustakaan Codekop </title>
  <!-- Tell the browser to be responsive to screen width -->


  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/adminlte/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/adminlte/bower_components/font-awesome/css/font-awesome.min.css">
	
	
	<!-- Select2 -->
	<link rel="stylesheet" href="<?php echo base_url();?>assets/adminlte/bower_components/select2/dist/css/select2.min.css">
	
	<!-- Ionicons -->
	<link rel="stylesheet" href="<?php echo base_url();?>assets/adminlte/bower_components/Ionicons/css/ionicons.min.css">
	<!-- Theme style -->  
	
	<link href="<?php echo base_url();?>assets/adminlte/plugins/summernote/summernote-lite.css" rel="stylesheet">

  <link rel="stylesheet" href="<?php echo base_url();?>assets/adminlte/dist/css/AdminLTE.css">
	<link rel="stylesheet" href="<?php echo base_url();?>assets/adminlte/dist/css/responsive.css">
	
  <!-- Bootstrap time Picker -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/adminlte/plugins/timepicker/bootstrap-timepicker.min.css">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/adminlte/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/adminlte/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/adminlte/dist/css/skins/_all-skins.min.css">

  <link rel="stylesheet" href="<?php echo base_url();?>assets/adminlte/plugins/pace/pace.min.css">
  <!-- jQuery 3 -->
  <script src="<?php echo base_url();?>assets/adminlte/bower_components/jquery/dist/jquery.min.js"></script>
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <!-- offline -->
  <script type="text/javascript">
      $(document).ajaxStart(function() {
          if (window.Pace && typeof window.Pace.restart === 'function') {
              window.Pace.restart();
          }
      });

      // Semua request AJAX POST harus membawa token CSRF yang sama dengan cookie.
      // Token tidak diregenerasi setiap request agar beberapa AJAX berurutan tidak
      // saling membatalkan token milik request sebelumnya.
      (function ($) {
          var perpusCsrf = {
              name: <?php echo json_encode($this->security->get_csrf_token_name()); ?>,
              hash: <?php echo json_encode($this->security->get_csrf_hash()); ?>
          };

          $.ajaxPrefilter(function (options) {
              var method = (options.type || options.method || 'GET').toUpperCase();

              if (method !== 'POST' || options.crossDomain === true) {
                  return;
              }

              if (options.data instanceof FormData) {
                  if (!options.data.has(perpusCsrf.name)) {
                      options.data.append(perpusCsrf.name, perpusCsrf.hash);
                  }
                  return;
              }

              if (typeof options.data === 'string') {
                  if (options.data.indexOf(encodeURIComponent(perpusCsrf.name) + '=') === -1) {
                      options.data += (options.data ? '&' : '')
                          + encodeURIComponent(perpusCsrf.name) + '='
                          + encodeURIComponent(perpusCsrf.hash);
                  }
                  return;
              }

              options.data = $.extend({}, options.data || {});
              options.data[perpusCsrf.name] = perpusCsrf.hash;
          });
      }(jQuery));
  </script>
</head>
<body class="hold-transition skin-blue-light sidebar-mini">
<div class="wrapper">
  <header class="main-header">

    <!-- Logo -->
    <a href="<?php echo base_url('index.php/dashboard');?>" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>P</b>C</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg">Perpus Codekop</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <?php
            $d = $this->db->where('id_login', $idbo)->where('deleted_at IS NULL', NULL, FALSE)->get('tbl_login')->row();
            $nama_profil = $d ? (string) $d->nama : 'Profil';
            $level_profil = $d ? (string) $d->level : '';
            $foto_profil = $d ? $d->foto : '';
          ?>
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="<?php echo perpus_user_foto_url($foto_profil);?>" class="user-image" alt="Foto profil">
              <span class="hidden-xs"><?php echo html_escape($nama_profil);?></span>
              <i class="fa fa-angle-down" aria-hidden="true" style="margin-left:5px;"></i>
            </a>
            <ul class="dropdown-menu">
              <li class="user-header">
                <img src="<?php echo perpus_user_foto_url($foto_profil);?>" class="img-circle" alt="Foto profil">
                <p>
                  <?php echo html_escape($nama_profil);?>
                  <?php if ($level_profil !== ''): ?>
                    <small><?php echo html_escape($level_profil);?></small>
                  <?php endif; ?>
                </p>
              </li>
              <li class="user-footer">
                <div class="pull-left">
                  <a href="<?php echo base_url('user/edit/'.$idbo);?>" class="btn btn-default btn-flat">Edit Profil</a>
                </div>
                <div class="pull-right">
                  <a href="<?php echo base_url('login/logout');?>" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button 
          <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>-->
        </ul>
      </div>
    </nav>
  </header>
  <!--loading-->
  <!-- Left side column. contains the logo and sidebar -->
