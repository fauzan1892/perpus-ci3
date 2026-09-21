<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
	function __construct(){
	 parent::__construct();
	 	//validasi jika user belum login
     $this->data['CI'] =& get_instance();
     $this->load->helper(array('form', 'url', 'upload'));
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
        $pass = password_hash((string) $this->input->post('pass', FALSE), PASSWORD_BCRYPT);
        $level = htmlentities($this->input->post('level',TRUE));
        $jenkel = htmlentities($this->input->post('jenkel',TRUE));
        $telepon = htmlentities($this->input->post('telepon',TRUE));
        $status = htmlentities($this->input->post('status',TRUE));
        $alamat = htmlentities($this->input->post('alamat',TRUE));
		$email = trim((string) $this->input->post('email', TRUE));

		$dd = $this->db
			->group_start()
			->where('user', $user)
			->or_where('email', $email)
			->group_end()
			->where('deleted_at IS NULL', NULL, FALSE)
			->get('tbl_login');
		if($dd->num_rows() > 0)
		{
			$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-warning">
			<p> Gagal Update User : '.$nama.' !, Username / Email Anda Sudah Terpakai</p>
			</div></div>');
			redirect(base_url('user/tambah')); 
		}else{
            // setting konfigurasi upload
            $config = perpus_upload_config('assets/image', 'gif|jpg|jpeg|png', 2048);
            // load library upload
            $this->load->library('upload', $config);
            // upload gambar 1
            if (!$this->upload->do_upload('gambar'))
            {
                $this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-danger"><p>File foto tidak valid atau gagal diupload.</p></div></div>');
                redirect(base_url('user/tambah'));
            }
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
	        $pass = (string) $this->input->post('pass', FALSE);
        $level = htmlentities($this->input->post('level',TRUE));
        $jenkel = htmlentities($this->input->post('jenkel',TRUE));
        $telepon = htmlentities($this->input->post('telepon',TRUE));
        $status = htmlentities($this->input->post('status',TRUE));
        $alamat = htmlentities($this->input->post('alamat',TRUE));
        $id_login = htmlentities($this->input->post('id_login',TRUE));

        // setting konfigurasi upload
        $config = perpus_upload_config('assets/image', 'gif|jpg|jpeg|png', 2048);
        // load library upload
        $this->load->library('upload', $config);
		// upload gambar 1
		
        
		if(!$this->upload->do_upload('gambar'))
		{
			if($this->input->post('pass') !== ''){
				$data = array(
					'nama'=>$nama,
					'user'=>$user,
					'pass'=>password_hash($pass, PASSWORD_BCRYPT),
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
				$old_photo = basename((string) $this->input->post('foto', TRUE));
				if ($old_photo !== '' && is_file(FCPATH.'assets/image/'.$old_photo))
				{
					unlink(FCPATH.'assets/image/'.$old_photo);
				}
			if($this->input->post('pass') !== ''){
				$data = array(
					'nama'=>$nama,
					'user'=>$user,
					'tempat_lahir'=>$_POST['lahir'],
					'tgl_lahir'=>$_POST['tgl_lahir'],
						'pass'=>password_hash($pass, PASSWORD_BCRYPT),
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
		$this->M_Admin->delete_table('tbl_login','id_login',$this->uri->segment('3'));
		
		$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-warning">
		<p> Berhasil Hapus User !</p>
		</div></div>');
		redirect(base_url('user'));  
    }
}
