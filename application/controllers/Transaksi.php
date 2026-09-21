<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi extends CI_Controller {
	function __construct(){
	 parent::__construct();
	 	//validasi jika user belum login
		$this->data['CI'] =& get_instance();
		$this->load->helper(array('form', 'url', 'perpus'));
		$this->load->model('M_Admin');
		$this->load->library(array('cart'));
		if($this->session->userdata('masuk_perpus') != TRUE){
			$url=base_url('login');
			redirect($url);
		}
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
		$this->data['title_web'] = 'Data Pinjam Buku ';
		$this->data['idbo'] = $this->session->userdata('ses_id');
		
		if($this->session->userdata('level') == 'Anggota'){
			$this->data['pinjam'] = $this->db->query("SELECT DISTINCT `pinjam_id`, `anggota_id`, 
				`status`, `tgl_pinjam`, `lama_pinjam`, `tgl_balik`, `tgl_kembali` 
				FROM tbl_pinjam WHERE deleted_at IS NULL AND status = 'Dipinjam'
				AND anggota_id = ? ORDER BY pinjam_id DESC", 
				array($this->session->userdata('anggota_id')));
		}else{
			$this->data['pinjam'] = $this->db->query("SELECT DISTINCT `pinjam_id`, `anggota_id`, 
				`status`, `tgl_pinjam`, `lama_pinjam`, `tgl_balik`, `tgl_kembali` 
				FROM tbl_pinjam WHERE deleted_at IS NULL AND status = 'Dipinjam' ORDER BY pinjam_id DESC");
		}
		
		$this->load->view('header_view',$this->data);
		$this->load->view('sidebar_view',$this->data);
		$this->load->view('pinjam/pinjam_view',$this->data);
		$this->load->view('footer_view',$this->data);
	}

	public function kembali()
	{	
		$this->data['title_web'] = 'Data Pengembalian Buku ';
		$this->data['idbo'] = $this->session->userdata('ses_id');

		if($this->session->userdata('level') == 'Anggota'){
			$this->data['pinjam'] = $this->db->query("SELECT DISTINCT `pinjam_id`, `anggota_id`, 
				`status`, `tgl_pinjam`, `lama_pinjam`, `tgl_balik`, `tgl_kembali` 
				FROM tbl_pinjam WHERE deleted_at IS NULL AND anggota_id = ? AND status = 'Di Kembalikan'
				ORDER BY id_pinjam DESC",array($this->session->userdata('anggota_id')));
		}else{
			$this->data['pinjam'] = $this->db->query("SELECT DISTINCT `pinjam_id`, `anggota_id`, 
				`status`, `tgl_pinjam`, `lama_pinjam`, `tgl_balik`, `tgl_kembali` 
				FROM tbl_pinjam WHERE deleted_at IS NULL AND status = 'Di Kembalikan' ORDER BY id_pinjam DESC");
		}
		
		$this->load->view('header_view',$this->data);
		$this->load->view('sidebar_view',$this->data);
		$this->load->view('kembali/home',$this->data);
		$this->load->view('footer_view',$this->data);
	}


	public function pinjam()
	{	

		$this->data['nop'] = $this->M_Admin->buat_kode('tbl_pinjam','PJ','id_pinjam','ORDER BY id_pinjam DESC LIMIT 1'); 
		$this->data['idbo'] = $this->session->userdata('ses_id');
        $this->data['user'] = $this->M_Admin->get_table('tbl_login');
		$this->data['buku'] = $this->db->where('deleted_at IS NULL', NULL, FALSE)->order_by('id_buku', 'DESC')->get('tbl_buku');

		$this->data['title_web'] = 'Tambah Pinjam Buku ';

		$this->load->view('header_view',$this->data);
		$this->load->view('sidebar_view',$this->data);
		$this->load->view('pinjam/tambah_view',$this->data);
		$this->load->view('footer_view',$this->data);
	}

	public function detailpinjam()
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');		
		$id = $this->uri->segment('3');
		if($this->session->userdata('level') == 'Anggota'){
			$count = $this->db->where('deleted_at IS NULL', NULL, FALSE)->get_where('tbl_pinjam',[
				'pinjam_id' => $id, 
				'anggota_id' => $this->session->userdata('anggota_id')
			])->num_rows();
			if($count > 0)
			{
				$this->data['pinjam'] = $this->db->query("SELECT DISTINCT `pinjam_id`, 
				`anggota_id`, `status`, 
				`tgl_pinjam`, `lama_pinjam`, 
				`tgl_balik`, `tgl_kembali` 
				FROM tbl_pinjam WHERE deleted_at IS NULL AND pinjam_id = ?
				AND anggota_id =?", 
				array($id,$this->session->userdata('anggota_id')))->row();
			}else{
				echo '<script>alert("DETAIL TIDAK DITEMUKAN");window.location="'.base_url('transaksi').'"</script>';
			}
		}else{
			$count = $this->M_Admin->CountTableId('tbl_pinjam','pinjam_id',$id);
			if($count > 0)
			{
				$this->data['pinjam'] = $this->db->query("SELECT DISTINCT `pinjam_id`, 
				`anggota_id`, `status`, 
				`tgl_pinjam`, `lama_pinjam`, 
				`tgl_balik`, `tgl_kembali` 
				FROM tbl_pinjam WHERE deleted_at IS NULL AND pinjam_id = ?", array($id))->row();
			}else{
				echo '<script>alert("DETAIL TIDAK DITEMUKAN");window.location="'.base_url('transaksi').'"</script>';
			}
		}
		$this->data['sidebar'] = 'kembali';
		$this->data['title_web'] = 'Detail Pinjam Buku ';
		$this->load->view('header_view',$this->data);
		$this->load->view('sidebar_view',$this->data);
		$this->load->view('pinjam/detail',$this->data);
		$this->load->view('footer_view',$this->data);
	}

	public function kembalipinjam()
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');		
		$id = $this->uri->segment('3');
		$count = $this->M_Admin->CountTableId('tbl_pinjam','pinjam_id',$id);
		if($count > 0)
		{
			$this->data['pinjam'] = $this->db->query("SELECT DISTINCT `pinjam_id`, 
			`anggota_id`, `status`, 
			`tgl_pinjam`, `lama_pinjam`, 
			`tgl_balik`, `tgl_kembali` 
			FROM tbl_pinjam WHERE deleted_at IS NULL AND pinjam_id = ?", array($id))->row();
		}else{
			echo '<script>alert("DETAIL TIDAK DITEMUKAN");window.location="'.base_url('transaksi').'"</script>';
		}


		$this->data['title_web'] = 'Kembali Pinjam Buku ';
		$this->load->view('header_view',$this->data);
		$this->load->view('sidebar_view',$this->data);
		$this->load->view('pinjam/kembali',$this->data);
		$this->load->view('footer_view',$this->data);
	}

	public function prosespinjam()
	{
		$post = $this->input->post();

		if(!empty($post['tambah']))
		{

			$tgl = trim((string) ($post['tgl'] ?? ''));
			$lama_pinjam = filter_var($post['lama'] ?? null, FILTER_VALIDATE_INT);
			$tgl2 = perpus_tanggal_jatuh_tempo($tgl, $lama_pinjam);
			if ($tgl2 === false)
			{
				$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-danger"><p>Tanggal dan lama pinjam tidak valid.</p></div></div>');
				redirect(base_url('transaksi/pinjam'));
			}

			$hasil_cart = array_values(unserialize($this->session->userdata('cart')));
			foreach($hasil_cart as $isi)
			{
				$data[] = array(
					'pinjam_id'=>htmlentities($post['nopinjam']), 
					'anggota_id'=>htmlentities($post['anggota_id']), 
					'buku_id' => $isi['id'], 
					'status' => 'Dipinjam', 
					'tgl_pinjam' => htmlentities($post['tgl']), 
					'lama_pinjam' => $lama_pinjam,
					'tgl_balik'  => $tgl2, 
					'tgl_kembali'  => '0',
				);
			}
			$total_array = count($data);
			if($total_array != 0)
			{
				$this->db->insert_batch('tbl_pinjam',$data);

				$cart = array_values(unserialize($this->session->userdata('cart')));
				for ($i = 0; $i < count($cart); $i ++){
				  unset($cart[$i]);
				  // $this->session->unset_userdata($cart[$i]);
				  // $this->session->unset_userdata('cart');
				}
			}

			$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-success">
			<p> Tambah Pinjam Buku Sukses !</p>
			</div></div>');
			redirect(base_url('transaksi')); 
		}

		if($this->input->get('pinjam_id'))
		{
			$this->M_Admin->delete_table('tbl_pinjam','pinjam_id',$this->input->get('pinjam_id'));
			$this->M_Admin->delete_table('tbl_denda','pinjam_id',$this->input->get('pinjam_id'));

			$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-warning">
			<p>  Hapus Transaksi Pinjam Buku Sukses !</p>
			</div></div>');
			redirect(base_url('transaksi')); 
		}

		if($this->input->get('kembali'))
		{
			$id = trim((string) $this->input->get('kembali', TRUE));
			$pinjam = $this->db->where('pinjam_id', $id)->where('deleted_at IS NULL', NULL, FALSE)->get('tbl_pinjam')->result_array();
			if (empty($pinjam))
			{
				$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-danger"><p>Transaksi peminjaman tidak ditemukan.</p></div></div>');
				redirect(base_url('transaksi'));
			}

			if ((string) $pinjam[0]['tgl_kembali'] !== '0' || $pinjam[0]['status'] === 'Di Kembalikan')
			{
				$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-warning"><p>Transaksi ini sudah dikembalikan.</p></div></div>');
				redirect(base_url('transaksi'));
			}

			$jumlah_buku = count($pinjam);
			$lama_waktu = perpus_hari_terlambat($pinjam[0]['tgl_balik']);
			$dd = $this->db->where('stat', 'Aktif')->where('deleted_at IS NULL', NULL, FALSE)->order_by('id_biaya_denda', 'DESC')->limit(1)->get('tbl_biaya_denda')->row();
			$harga_denda = perpus_hitung_denda($lama_waktu, $jumlah_buku, $dd ? $dd->harga_denda : 0);
			$tanggal_kembali = date('Y-m-d');

			$data = array(
				'status' => 'Di Kembalikan', 
				'tgl_kembali'  => $tanggal_kembali,
			);
			$this->db->where('pinjam_id', $id)->where('status', 'Dipinjam')->where('deleted_at IS NULL', NULL, FALSE)->update('tbl_pinjam', $data);

			$data_denda = array(
				'pinjam_id' => $id,
				'denda' => $harga_denda, 
				'lama_waktu'=>$lama_waktu, 
				'tgl_denda'=> $tanggal_kembali,
			);
			$existing_denda = $this->db->where('pinjam_id', $id)->where('deleted_at IS NULL', NULL, FALSE)->order_by('id_denda', 'ASC')->limit(1)->get('tbl_denda')->row();
			if ($existing_denda)
			{
				$this->db->where('id_denda', $existing_denda->id_denda)->where('deleted_at IS NULL', NULL, FALSE)->update('tbl_denda', $data_denda);
			}
			else
			{
				$this->db->insert('tbl_denda', $data_denda);
			}

			$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-success">
			<p> Pengembalian Pinjam Buku Sukses !</p>
			</div></div>');
			redirect(base_url('transaksi')); 

		}
	}

	public function denda()
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');	

		$this->data['denda'] = $this->db->where('deleted_at IS NULL', NULL, FALSE)->order_by('id_biaya_denda', 'DESC')->get('tbl_biaya_denda');

		if(!empty($this->input->get('id'))){
			$id = $this->input->get('id');
			$count = $this->M_Admin->CountTableId('tbl_biaya_denda','id_biaya_denda',$id);
			if($count > 0)
			{			
				$this->data['den'] = $this->db->where('id_biaya_denda', $id)->where('deleted_at IS NULL', NULL, FALSE)->get('tbl_biaya_denda')->row();
			}else{
				echo '<script>alert("KATEGORI TIDAK DITEMUKAN");window.location="'.base_url('transaksi/denda').'"</script>';
			}
		}

		$this->data['title_web'] = ' Denda ';
		$this->load->view('header_view',$this->data);
		$this->load->view('sidebar_view',$this->data);
		$this->load->view('denda/denda_view',$this->data);
		$this->load->view('footer_view',$this->data);
	}

	public function dendaproses()
	{
		if(!empty($this->input->post('tambah')))
		{
			$post= $this->input->post();
			$data = array(
				'harga_denda'=>$post['harga'],
				'stat'=>'Tidak Aktif',
				'tgl_tetap' => date('Y-m-d')
			);

			$this->db->insert('tbl_biaya_denda', $data);
			
			$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-success">
			<p> Tambah  Harga Denda  Sukses !</p>
			</div></div>');
			redirect(base_url('transaksi/denda')); 
		}

		if(!empty($this->input->post('edit')))
		{
			$dd = $this->M_Admin->get_tableid('tbl_biaya_denda','stat','Aktif');
			foreach($dd as $isi)
			{
				$data1 = array(
					'stat'=>'Tidak Aktif',
				);
				$this->db->where('id_biaya_denda',$isi['id_biaya_denda'])->where('deleted_at IS NULL', NULL, FALSE);
				$this->db->update('tbl_biaya_denda', $data1);
			}

			$post= $this->input->post();
			$data = array(
				'harga_denda'=>$post['harga'],
				'stat'=>$post['status'],
				'tgl_tetap' => date('Y-m-d')
			);

			$this->db->where('id_biaya_denda',$post['edit'])->where('deleted_at IS NULL', NULL, FALSE);
			$this->db->update('tbl_biaya_denda', $data);


			$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-success">
			<p> Edit Harga Denda  Sukses !</p>
			</div></div>');
			redirect(base_url('transaksi/denda')); 	
		}

		if(!empty($this->input->get('denda_id')))
		{
			$this->M_Admin->delete_table('tbl_biaya_denda', 'id_biaya_denda', $this->input->get('denda_id'));

			$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-warning">
			<p> Hapus Harga Denda Sukses !</p>
			</div></div>');
			redirect(base_url('transaksi/denda')); 
		}
	}


	public function result()
    {	
		
		$user = $this->M_Admin->get_tableid_edit('tbl_login','anggota_id',$this->input->post('kode_anggota'));
		error_reporting(0);
		if($user->nama != null)
		{
			echo '<table class="table table-striped">
						<tr>
							<td>Nama Anggota</td>
							<td>:</td>
							<td>'.$user->nama.'</td>
						</tr>
						<tr>
							<td>Telepon</td>
							<td>:</td>
							<td>'.$user->telepon.'</td>
						</tr>
						<tr>
							<td>E-mail</td>
							<td>:</td>
							<td>'.$user->email.'</td>
						</tr>
						<tr>
							<td>Alamat</td>
							<td>:</td>
							<td>'.$user->alamat.'</td>
						</tr>
						<tr>
							<td>Level</td>
							<td>:</td>
							<td>'.$user->level.'</td>
						</tr>
					</table>';
		}else{
			echo 'Anggota Tidak Ditemukan !';
		}
        
	}

	public function buku()
    {	
		$id = trim((string) $this->input->post('kode_buku', TRUE));
		if ($id === '')
		{
			return;
		}
		$row = $this->db->where('buku_id', $id)->where('deleted_at IS NULL', NULL, FALSE)->get('tbl_buku');
		
		if($row->num_rows() > 0)
		{
			$tes = $row->row();
			$item = array(
				'id'      => $id,
				'qty'     => 1,
                'price'   => '1000',
				'name'    => $tes->title,
				'options' => array('isbn' => $tes->isbn,'thn' => $tes->thn_buku,'penerbit' => $tes->penerbit)
			);
			$cart = $this->cart_items();
			if ($this->exists($id, $cart) === -1)
			{
				$cart[] = $item;
				$this->session->set_userdata('cart', serialize($cart));
			}
		}else{

		}
        
	}

	public function buku_list()
	{
		$cart = $this->cart_items();
	?>
		<table class="table table-striped">
			<thead>
				<tr>
					<th>No</th>
					<th>Title</th>
					<th>Penerbit</th>
					<th>Tahun</th>
					<th>Aksi</th>
				</tr>
			</thead>
			<tbody>
			<?php $no=1;
				foreach($cart as $items){?>
				<tr>
					<td><?= $no;?></td>
					<td><?= $items['name'];?></td>
					<td><?= $items['options']['penerbit'];?></td>
					<td><?= $items['options']['thn'];?></td>
					<td style="width:17%">
					<a href="javascript:void(0)" id="delete_buku<?=$no;?>" data_<?=$no;?>="<?= $items['id'];?>" class="btn btn-danger btn-sm">
						<i class="fa fa-trash"></i></a>
					</td>
				</tr>
				<script>
					$(document).ready(function(){
						$("#delete_buku<?=$no;?>").click(function (e) {
							$.ajax({
								type: "POST",
								url: "<?php echo base_url('transaksi/del_cart');?>",
								data:'buku_id='+$(this).attr("data_<?=$no;?>"),
								beforeSend: function(){
								},
								success: function(html){
									$("#tampil").html(html);
								}
							});
						});
					});
				</script>
			<?php $no++;}?>
			</tbody>
		</table>
		<?php foreach($cart as $items){?>
			<input type="hidden" value="<?= $items['id'];?>" name="idbuku[]">
		<?php }?>
		<div id="tampil"></div>
	<?php
	}

	public function del_cart()
    {
		$id = trim((string) $this->input->post('buku_id', TRUE));
		$cart = $this->cart_items();
		$index = $this->exists($id, $cart);

		if ($index !== -1)
		{
			unset($cart[$index]);
			$cart = array_values($cart);
		}

		if (empty($cart))
		{
			$this->session->unset_userdata('cart');
		}
		else
		{
			$this->session->set_userdata('cart', serialize($cart));
		}
		echo '<script>$("#result_buku").load("'.base_url('transaksi/buku_list').'");</script>';
    }

    private function cart_items()
    {
        $stored_cart = $this->session->userdata('cart');
        if (!is_string($stored_cart))
        {
            return array();
        }

        $cart = unserialize($stored_cart, array('allowed_classes' => FALSE));
        return is_array($cart) ? array_values($cart) : array();
    }

    private function exists($id, $cart = NULL)
    {
        $cart = $cart === NULL ? $this->cart_items() : $cart;
        for ($i = 0; $i < count($cart); $i ++) {
            if (isset($cart[$i]['id']) && (string) $cart[$i]['id'] === (string) $id) {
                return $i;
            }
        }
        return -1;
    }

}
