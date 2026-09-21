<?php
if(! defined('BASEPATH')) exit('No direct script acess allowed');

class M_Login extends CI_Model
{

  function GET_LOGIN($user,$pass)
  {
      $row = $this->db
        ->where('user', $user)
        ->where('pass', $pass)
        ->where('deleted_at IS NULL', NULL, FALSE)
        ->limit(1)
        ->get('tbl_login');
      return $row;
  }

  function insertTable($table_name,$data)
  {
   $tambah = $this->db->insert($table_name,$data);
   return $tambah;
  }

}
?>
