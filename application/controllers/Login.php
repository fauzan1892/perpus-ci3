<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
	function __construct(){
	 parent::__construct();
	 	//validasi jika user belum login
        $this->data['CI'] =& get_instance();
        $this->load->helper(array('form', 'url'));
        $this->load->model('M_login');
        
	 }
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->data['title_web'] = 'Login | Sistem Informasi Perpustakaan';
		$this->load->view('login_view',$this->data);
	}

    public function auth()
    {
        $user = trim((string) $this->input->post('user', TRUE));
        $pass = (string) $this->input->post('pass', FALSE);

        // Fetch by username using Query Builder so input is safely bound.
        $hasil_login = $this->db
            ->where('user', $user)
            ->where('deleted_at IS NULL', NULL, FALSE)
            ->limit(1)
            ->get('tbl_login')
            ->row_array();

        $is_valid = FALSE;
        if (!empty($hasil_login))
        {
            $stored_password = (string) $hasil_login['pass'];
            $is_valid = password_verify($pass, $stored_password);
        }

        if ($is_valid)
        {
            $this->session->sess_regenerate(TRUE);

            // create session
            $this->session->set_userdata('masuk_perpus',TRUE);
            $this->session->set_userdata('level',$hasil_login['level']);
            $this->session->set_userdata('ses_id',$hasil_login['id_login']);
            $this->session->set_userdata('anggota_id',$hasil_login['anggota_id']);

            redirect('dashboard');
        }else{
            $this->session->set_flashdata('login_error', 'Username atau password salah.');
            redirect('login');
        }
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('login');
    }
}
