<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $title_web;?></title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="shortcut icon" href="" />
  <link rel="stylesheet" href="<?php echo base_url('assets/adminlte/bower_components/bootstrap/dist/css/bootstrap.min.css');?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/adminlte/bower_components/font-awesome/css/font-awesome.min.css');?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/adminlte/bower_components/Ionicons/css/ionicons.min.css');?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/adminlte/dist/css/AdminLTE.min.css');?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/adminlte/dist/css/responsivelogin.css');?>">

  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <style type="text/css">
    .navbar-inverse { background-color: #333; }
    .navbar-color { color: #fff; }
    blink, .blink { animation: blinker 3s linear infinite; }
    @keyframes blinker { 50% { opacity: 0; } }
  </style>
</head>
<body class="hold-transition login-page" style="overflow-y: hidden; background: url('<?php echo base_url('assets/image/Buku-2.jpg');?>') no-repeat; background-size: 100%;">
  <div class="login-box">
    <br>
    <div class="login-logo" style="margin-top:4pc;">
      <a href="<?php echo base_url(); ?>" style="color: yellow;">Sistem Informasi <br><b>Perpustakaan</b></a>
    </div>

    <div id="tampilalert"></div>
    <?php if ($login_error = $this->session->flashdata('login_error')): ?>
      <div id="notifikasi" class="alert alert-danger" role="alert">
        <?php echo htmlspecialchars($login_error, ENT_QUOTES, 'UTF-8'); ?>
      </div>
    <?php endif; ?>

    <div class="login-box-body" style="border: 2px solid #226bbf;">
      <p class="login-box-msg" style="font-size: 16px;"></p>
      <form action="<?php echo base_url('login/auth');?>" method="post">
        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
        <div class="form-group has-feedback">
          <label class="sr-only" for="user">Username</label>
          <input type="text" class="form-control" placeholder="Username" id="user" name="user" required autocomplete="username">
          <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
          <label class="sr-only" for="pass">Password</label>
          <input type="password" class="form-control" placeholder="Password" id="pass" name="pass" required autocomplete="current-password">
          <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <button type="submit" id="loding" class="btn btn-primary btn-block btn-flat">Sign In</button>
        <div id="loadingcuy"></div>
      </form>
    </div>

    <br>
    <footer>
      <div class="login-box-body text-center bg-blue">
        <a style="color: yellow;">Copyright &copy; Sistem Perpustakaan Codekop - <?php echo date('Y');?></a>
      </div>
    </footer>
  </div>

  <div id="tampilkan"></div>

  <script src="<?php echo base_url('assets/adminlte/bower_components/jquery/dist/jquery.min.js');?>"></script>
  <script src="<?php echo base_url('assets/adminlte/bower_components/bootstrap/dist/js/bootstrap.min.js');?>"></script>
  <script src="<?php echo base_url('assets/adminlte/plugins/iCheck/icheck.min.js');?>"></script>
</body>
</html>
