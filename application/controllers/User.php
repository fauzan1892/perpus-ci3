<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
	function __construct(){
	 parent::__construct();
	 	//validasi jika user belum login
     $this->data['CI'] =& get_instance();
     $this->load->helper(array('form', 'url'));
     $this->load->model('M_Admin');
     	if($this->session->userdata('masuk_perpus') != TRUE){
			$url=base_url('login');
			redirect($url);
		}
     }
     
    public function index()
    {	
        $this->data['idbo'] = $this->session->userdata('ses_id');
        $this->data['user'] = $this->M_Admin->get_table('tbl_login');

        $this->data['title_web'] = 'Data User ';
        $this->load->view('header_view',$this->data);
        $this->load->view('sidebar_view',$this->data);
        $this->load->view('user/user_view',$this->data);
        $this->load->view('footer_view',$this->data);
    }

    public function tambah()
    {	
        $this->data['idbo'] = $this->session->userdata('ses_id');
        $this->data['user'] = $this->M_Admin->get_table('tbl_login');
        
        $this->data['title_web'] = 'Tambah User ';
        $this->load->view('header_view',$this->data);
        $this->load->view('sidebar_view',$this->data);
        $this->load->view('user/tambah_view',$this->data);
        $this->load->view('footer_view',$this->data);
    }

    public function add()
    {
		// format tabel / kode baru 3 hurup / id tabel / order by limit ngambil data terakhir
		$id = $this->M_Admin->buat_kode('tbl_login','AG','id_login','ORDER BY id_login DESC LIMIT 1'); 
        $nama = htmlentities($this->input->post('nama',TRUE));
        $user = htmlentities($this->input->post('user',TRUE));
        $pass = md5(htmlentities($this->input->post('pass',TRUE)));
        $level = htmlentities($this->input->post('level',TRUE));
        $jenkel = htmlentities($this->input->post('jenkel',TRUE));
        $telepon = htmlentities($this->input->post('telepon',TRUE));
        $status = htmlentities($this->input->post('status',TRUE));
        $alamat = htmlentities($this->input->post('alamat',TRUE));
		$email = $_POST['email'];
		
		$dd = $this->db->query("SELECT * FROM tbl_login WHERE user = '$user' OR email = '$email'");
		if($dd->num_rows() > 0)
		{
			$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-warning">
			<p> Gagal Update User : '.$nama.' !, Username / Email Anda Sudah Terpakai</p>
			</div></div>');
			redirect(base_url('user/tambah')); 
		}else{
            // setting konfigurasi upload
            $nmfile = "user_".time();
            $config['upload_path'] = './assets_style/image/';
            $config['allowed_types'] = 'gif|jpg|jpeg|png';
            $config['file_name'] = $nmfile;
            // load library upload
            $this->load->library('upload', $config);
            // upload gambar 1
            $this->upload->do_upload('gambar');
            $result1 = $this->upload->data();
            $result = array('gambar'=>$result1);
            $data1 = array('upload_data' => $this->upload->data());
            $data = array(
				'anggota_id' => $id,
                'nama'=>$nama,
                'user'=>$user,
                'pass'=>$pass,
                'level'=>$level,
                'tempat_lahir'=>$_POST['lahir'],
                'tgl_lahir'=>$_POST['tgl_lahir'],
                'level'=>$level,
                'email'=>$_POST['email'],
                'telepon'=>$telepon,
                'foto'=>$data1['upload_data']['file_name'],
                'jenkel'=>$jenkel,
                'alamat'=>$alamat,
                'tgl_bergabung'=>date('Y-m-d')
            );
			$this->db->insert('tbl_login',$data);
			
            $this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-success">
            <p> Daftar User telah berhasil !</p>
            </div></div>');
			redirect(base_url('user'));
		}    
      
    }

    public function edit()
    {	
		if($this->session->userdata('level') == 'Petugas'){
			if($this->uri->segment('3') == ''){ echo '<script>alert("halaman tidak ditemukan");window.location="'.base_url('user').'";</script>';}
			$this->data['idbo'] = $this->session->userdata('ses_id');
			$count = $this->M_Admin->CountTableId('tbl_login','id_login',$this->uri->segment('3'));
			if($count > 0)
			{			
				$this->data['user'] = $this->M_Admin->get_tableid_edit('tbl_login','id_login',$this->uri->segment('3'));
			}else{
				echo '<script>alert("USER TIDAK DITEMUKAN");window.location="'.base_url('user').'"</script>';
			}
			
		}elseif($this->session->userdata('level') == 'Anggota'){
			$this->data['idbo'] = $this->session->userdata('ses_id');
			$count = $this->M_Admin->CountTableId('tbl_login','id_login',$this->uri->segment('3'));
			if($count > 0)
			{			
				$this->data['user'] = $this->M_Admin->get_tableid_edit('tbl_login','id_login',$this->session->userdata('ses_id'));
			}else{
				echo '<script>alert("USER TIDAK DITEMUKAN");window.location="'.base_url('user').'"</script>';
			}
		}
        $this->data['title_web'] = 'Edit User ';
        $this->load->view('header_view',$this->data);
        $this->load->view('sidebar_view',$this->data);
        $this->load->view('user/edit_view',$this->data);
        $this->load->view('footer_view',$this->data);
	}
	
	public function detail()
    {	
		if($this->session->userdata('level') == 'Petugas'){
			if($this->uri->segment('3') == ''){ echo '<script>alert("halaman tidak ditemukan");window.location="'.base_url('user').'";</script>';}
			$this->data['idbo'] = $this->session->userdata('ses_id');
			$count = $this->M_Admin->CountTableId('tbl_login','id_login',$this->uri->segment('3'));
			if($count > 0)
			{			
				$this->data['user'] = $this->M_Admin->get_tableid_edit('tbl_login','id_login',$this->uri->segment('3'));
			}else{
				echo '<script>alert("USER TIDAK DITEMUKAN");window.location="'.base_url('user').'"</script>';
			}		
		}elseif($this->session->userdata('level') == 'Anggota'){
			$this->data['idbo'] = $this->session->userdata('ses_id');
			$count = $this->M_Admin->CountTableId('tbl_login','id_login',$this->session->userdata('ses_id'));
			if($count > 0)
			{			
				$this->data['user'] = $this->M_Admin->get_tableid_edit('tbl_login','id_login',$this->session->userdata('ses_id'));
			}else{
				echo '<script>alert("USER TIDAK DITEMUKAN");window.location="'.base_url('user').'"</script>';
			}
		}
        $this->data['title_web'] = 'Print Kartu Anggota ';
        $this->load->view('user/detail',$this->data);
    }

    public function upd()
    {
        $nama = htmlentities($this->input->post('nama',TRUE));
        $user = htmlentities($this->input->post('user',TRUE));
        $pass = htmlentities($this->input->post('pass'));
        $level = htmlentities($this->input->post('level',TRUE));
        $jenkel = htmlentities($this->input->post('jenkel',TRUE));
        $telepon = htmlentities($this->input->post('telepon',TRUE));
        $status = htmlentities($this->input->post('status',TRUE));
        $alamat = htmlentities($this->input->post('alamat',TRUE));
        $id_login = htmlentities($this->input->post('id_login',TRUE));

        // setting konfigurasi upload
        $nmfile = "user_".time();
        $config['upload_path'] = './assets_style/image/';
        $config['allowed_types'] = 'gif|jpg|jpeg|png';
        $config['file_name'] = $nmfile;
        // load library upload
        $this->load->library('upload', $config);
		// upload gambar 1
		
        
		if(!$this->upload->do_upload('gambar'))
		{
			if($this->input->post('pass') !== ''){
				$data = array(
					'nama'=>$nama,
					'user'=>$user,
					'pass'=>md5($pass),
					'tempat_lahir'=>$_POST['lahir'],
					'tgl_lahir'=>$_POST['tgl_lahir'],
					'level'=>$level,
					'email'=>$_POST['email'],
					'telepon'=>$telepon,
					'jenkel'=>$jenkel,
					'alamat'=>$alamat,
				);
				$this->M_Admin->update_table('tbl_login','id_login',$id_login,$data);
				if($this->session->userdata('level') == 'Petugas')
				{

					$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-success">
					<p> Berhasil Update User : '.$nama.' !</p>
					</div></div>');
					redirect(base_url('user'));  
				}elseif($this->session->userdata('level') == 'Anggota'){

					$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-success">
					<p> Berhasil Update User : '.$nama.' !</p>
					</div></div>');
					redirect(base_url('user/edit/'.$id_login)); 
				}
			}else{
				$data = array(
					'nama'=>$nama,
					'user'=>$user,
					'tempat_lahir'=>$_POST['lahir'],
					'tgl_lahir'=>$_POST['tgl_lahir'],
					'level'=>$level,
					'email'=>$_POST['email'],
					'telepon'=>$telepon,
					'jenkel'=>$jenkel,
					'alamat'=>$alamat,
				);
				$this->M_Admin->update_table('tbl_login','id_login',$id_login,$data);
			
				if($this->session->userdata('level') == 'Petugas')
				{

					$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-success">
					<p> Berhasil Update User : '.$nama.' !</p>
					</div></div>');
					redirect(base_url('user'));  
				}elseif($this->session->userdata('level') == 'Anggota'){

					$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-success">
					<p> Berhasil Update User : '.$nama.' !</p>
					</div></div>');
					redirect(base_url('user/edit/'.$id_login)); 
				} 
			
			}
		}else{
			$result1 = $this->upload->data();
			$result = array('gambar'=>$result1);
			$data1 = array('upload_data' => $this->upload->data());
			unlink('./assets_style/image/'.$this->input->post('foto'));
			if($this->input->post('pass') !== ''){
				$data = array(
					'nama'=>$nama,
					'user'=>$user,
					'tempat_lahir'=>$_POST['lahir'],
					'tgl_lahir'=>$_POST['tgl_lahir'],
					'pass'=>md5($pass),
					'level'=>$level,
					'email'=>$_POST['email'],
					'telepon'=>$telepon,
					'foto'=>$data1['upload_data']['file_name'],
					'jenkel'=>$jenkel,
					'alamat'=>$alamat
				);
				$this->M_Admin->update_table('tbl_login','id_login',$id_login,$data);
			
				if($this->session->userdata('level') == 'Petugas')
				{

					$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-success">
					<p> Berhasil Update User : '.$nama.' !</p>
					</div></div>');
					redirect(base_url('user'));  
				}elseif($this->session->userdata('level') == 'Anggota'){

					$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-success">
					<p> Berhasil Update User : '.$nama.' !</p>
					</div></div>');
					redirect(base_url('user/edit/'.$id_login)); 
				} 
		
			}else{
				$data = array(
					'nama'=>$nama,
					'user'=>$user,
					'tempat_lahir'=>$_POST['lahir'],
					'tgl_lahir'=>$_POST['tgl_lahir'],
					'level'=>$level,
					'email'=>$_POST['email'],
					'telepon'=>$telepon,
					'foto'=>$data1['upload_data']['file_name'],
					'jenkel'=>$jenkel,
					'alamat'=>$alamat
				);
				$this->M_Admin->update_table('tbl_login','id_login',$id_login,$data);
			
				if($this->session->userdata('level') == 'Petugas')
				{

					$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-success">
					<p> Berhasil Update User : '.$nama.' !</p>
					</div></div>');
					redirect(base_url('user'));  
				}elseif($this->session->userdata('level') == 'Anggota'){

					$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-success">
					<p> Berhasil Update User : '.$nama.' !</p>
					</div></div>');
					redirect(base_url('user/edit/'.$id_login)); 
				}
			}
		}
    }
    public function del()
    {
        if($this->uri->segment('3') == ''){ echo '<script>alert("halaman tidak ditemukan");window.location="'.base_url('user').'";</script>';}
        
        $user = $this->M_Admin->get_tableid_edit('tbl_login','id_login',$this->uri->segment('3'));
        unlink('./assets_style/image/'.$user->foto);
		$this->M_Admin->delete_table('tbl_login','id_login',$this->uri->segment('3'));
		
		$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-warning">
		<p> Berhasil Hapus User !</p>
		</div></div>');
		redirect(base_url('user'));  
    }
}
