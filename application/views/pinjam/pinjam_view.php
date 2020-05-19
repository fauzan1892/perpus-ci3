<?php if(! defined('BASEPATH')) exit('No direct script acess allowed');?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-edit" style="color:green"> </i>  <?= $title_web;?>
    </h1>
    <ol class="breadcrumb">
			<li><a href="<?php echo base_url('dashboard');?>"><i class="fa fa-dashboard"></i>&nbsp; Dashboard</a></li>
			<li class="active"><i class="fa fa-file-text"></i>&nbsp; <?= $title_web;?></li>
    </ol>
  </section>
  <section class="content">
	<?php if(!empty($this->session->flashdata())){ echo $this->session->flashdata('pesan');}?>
	<div class="row">
	    <div class="col-md-12">
	        <div class="box box-primary">
                <div class="box-header with-border"><?php if($this->session->userdata('level') == 'Petugas'){ ?>
                    <a href="transaksi/pinjam"><button class="btn btn-primary">
				<i class="fa fa-plus"> </i> Tambah Pinjam</button></a><?php }?>

                </div>
				<!-- /.box-header -->
				<div class="box-body">
                    <br/>
					<div class="table-responsive">
                    <table id="example1" class="table table-bordered table-striped table" width="100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No Pinjam</th>
                                <th>ID Anggota</th>
                                <th>Nama</th>
                                <th>Tanggal Pinjam</th>
                                <th>Tanggal Balik</th>
                                <th style="width:10%">Status</th>
                                <th>Tanggal Kembali</th>
                                <th>Denda</th>
                                <th>Aksi</th>
                            </tr>
						</thead>
						<?php if($this->session->userdata('level') == 'Petugas'){ ?>
						<tbody>
						<?php 
							$no=1;
							foreach($pinjam->result_array() as $isi){
									$anggota_id = $isi['anggota_id'];
									$ang = $this->db->query("SELECT * FROM tbl_login WHERE anggota_id = '$anggota_id'")->row();

									$pinjam_id = $isi['pinjam_id'];
									$denda = $this->db->query("SELECT * FROM tbl_denda WHERE pinjam_id = '$pinjam_id'");
									$total_denda = $denda->row();
						?>
                            <tr>
                                <td><?= $no;?></td>
                                <td><?= $isi['pinjam_id'];?></td>
                                <td><?= $isi['anggota_id'];?></td>
                                <td><?= $ang->nama;?></td>
                                <td><?= $isi['tgl_pinjam'];?></td>
                                <td><?= $isi['tgl_balik'];?></td>
                                <td><center><?= $isi['status'];?></center></td>
								<td>
									<?php 
										if($isi['tgl_kembali'] == '0')
										{
											echo '<p style="color:red;text-align:center;">belum dikembalikan</p>';
										}else{
											echo $isi['tgl_kembali'];
										}
									
									?>
								</td>
                                <td>
									<center>
									<?php 
										if($isi['status'] == 'Di Kembalikan')
										{
											echo $this->M_Admin->rp($total_denda->denda);
										}else{
											$jml = $this->db->query("SELECT * FROM tbl_pinjam WHERE pinjam_id = '$pinjam_id'")->num_rows();			
											$date1 = date('Ymd');
											$date2 = preg_replace('/[^0-9]/','',$isi['tgl_balik']);
											$diff = $date1 - $date2;
											/*	$datetime1 = new DateTime($date1);
												$datetime2 = new DateTime($date2);
												$difference = $datetime1->diff($datetime2); */
											// echo $difference->days;
											if($diff > 0 )
											{
												echo $diff.' hari';
												$dd = $this->M_Admin->get_tableid_edit('tbl_biaya_denda','stat','Aktif'); 
												echo '<p style="color:red;font-size:18px;">
												'.$this->M_Admin->rp($jml*($dd->harga_denda*$difference->days)).' 
												</p><small style="color:#333;">* Untuk '.$jml.' Buku</small>';
											}else{
												echo '<p style="color:green;text-align:center;">
												Tidak Ada Denda</p>';
											}
												
										}
									?>
									</center>
								</td>
									<td <?php if($this->session->userdata('level') == 'Petugas'){ ?>style="width:22%;"<?php }?>>
									<center>
									<?php if($this->session->userdata('level') == 'Petugas'){ ?>
									<a href="<?= base_url('transaksi/detailpinjam/'.$isi['pinjam_id']);?>">
									<button class="btn btn-primary" title="detail pinjam"><i class="fa fa-eye"></i></button></a>
									
									<?php 
										if($isi['tgl_kembali'] == '0')
										{
									?>
										<a href="<?= base_url('transaksi/kembalipinjam/'.$isi['pinjam_id']);?>">
										<button class="btn btn-warning" title="pengembalian buku">
											<i class="fa fa-sign-out"></i> Kembalikan</button></a>
										<?php
										}else{
									?>
										<a href="javascript:void(0)">
										<button class="btn btn-success" title="pengembalian buku">
											<i class="fa fa-check"></i> Dikembalikan</button></a>
									<?php
										}
									
									?>
									<a href="<?= base_url('transaksi/prosespinjam?pinjam_id='.$isi['pinjam_id']);?>" 
									 onclick="return confirm('Anda yakin Peminjaman Ini akan dihapus ?');">
									<button class="btn btn-danger" title="hapus pinjam"><i class="fa fa-trash"></i></button></a>
									<?php }else{?>
										<a href="<?= base_url('transaksi/detailpinjam/'.$isi['pinjam_id']);?>">
										<button class="btn btn-primary" title="detail pinjam"><i class="fa fa-eye"></i> Detail Pinjam</button></a>
									<?php }?>
									</center>
                                </td>
                            </tr>
                        <?php $no++;}?>
						</tbody>
					<?php }elseif($this->session->userdata('level') == 'Anggota'){?>
                        <tbody>
						<?php $no=1;
							foreach($pinjam->result_array() as $isi){
									$anggota_id = $isi['anggota_id'];
									$ang = $this->db->query("SELECT * FROM tbl_login WHERE anggota_id = '$anggota_id'")->row();

									$pinjam_id = $isi['pinjam_id'];
									$denda = $this->db->query("SELECT * FROM tbl_denda WHERE pinjam_id = '$pinjam_id'");			
								
									if($this->session->userdata('ses_id') == $ang->id_login){
						?>
                            <tr>
                                <td><?= $no;?></td>
                                <td><?= $isi['pinjam_id'];?></td>
                                <td><?= $isi['anggota_id'];?></td>
                                <td><?= $ang->nama;?></td>
                                <td><?= $isi['tgl_pinjam'];?></td>
                                <td><?= $isi['tgl_balik'];?></td>
                                <td><center><?= $isi['status'];?></center></td>
								<td>
									<?php 
										if($isi['tgl_kembali'] == '0')
										{
											echo '<p style="color:red;text-align:center;">belum dikembalikan</p>';
										}else{
											echo $isi['tgl_kembali'];
										}
									
									?>
								</td>
                                <td>
									<center>
									<?php 

										$jml = $this->db->query("SELECT * FROM tbl_pinjam WHERE pinjam_id = '$pinjam_id'")->num_rows();			
										if($denda->num_rows() > 0){
											$s = $denda->row();
											echo $this->M_Admin->rp($s->denda);
										}else{
											$date1 = date('Ymd');
											$date2 = preg_replace('/[^0-9]/','',$isi['tgl_balik']);
											$diff = $date2 - $date1;

											if($diff >= 0 )
											{
												echo '<p style="color:green;text-align:center;">
												Tidak Ada Denda</p>';
											}else{
												$dd = $this->M_Admin->get_tableid_edit('tbl_biaya_denda','stat','Aktif'); 
												echo '<p style="color:red;font-size:18px;">'.$this->M_Admin->rp($jml*($dd->harga_denda*abs($diff))).' 
												</p><small style="color:#333;">* Untuk '.$jml.' Buku</small>';
											}
										}
									?>
									</center>
								</td>
									<td <?php if($this->session->userdata('level') == 'Petugas'){ ?>style="width:22%;"<?php }?>>
									<center>
									<?php if($this->session->userdata('level') == 'Petugas'){ ?>
									<a href="<?= base_url('transaksi/detailpinjam/'.$isi['pinjam_id']);?>">
									<button class="btn btn-primary" title="detail pinjam"><i class="fa fa-eye"></i></button></a>
									
									<?php 
										if($isi['tgl_kembali'] == '0')
										{
									?>
										<a href="<?= base_url('transaksi/kembalipinjam/'.$isi['pinjam_id']);?>">
										<button class="btn btn-warning" title="pengembalian buku">
											<i class="fa fa-sign-out"></i> Kembalikan</button></a>
										<?php
										}else{
									?>
										<a href="javascript:void(0)">
										<button class="btn btn-success" title="pengembalian buku">
											<i class="fa fa-check"></i> Dikembalikan</button></a>
									<?php
										}
									
									?>
									<a href="<?= base_url('transaksi/prosespinjam?pinjam_id='.$isi['pinjam_id']);?>" 
									 onclick="return confirm('Anda yakin Peminjaman Ini akan dihapus ?');">
									<button class="btn btn-danger" title="hapus pinjam"><i class="fa fa-trash"></i></button></a>
									<?php }else{?>
										<a href="<?= base_url('transaksi/detailpinjam/'.$isi['pinjam_id']);?>">
										<button class="btn btn-primary" title="detail pinjam"><i class="fa fa-eye"></i> Detail Pinjam</button></a>
									<?php }?>
									</center>
                                </td>
                            </tr>
                        <?php $no++;}}?>
                        </tbody>
					<?php }?>
					</table>
			    </div>
			    </div>
	        </div>
    	</div>
    </div>
</section>
</div>
