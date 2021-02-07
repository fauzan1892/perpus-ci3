<?php if(! defined('BASEPATH')) exit('No direct script acess allowed');?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-edit" style="color:green"> </i>  <?= $title_web;?>
    </h1>
    <ol class="breadcrumb">
			<li><a href="<?php echo base_url('dashboard');?>"><i class="fa fa-dashboard"></i>&nbsp; Dashboard</a></li>
			<li class="active"><i class="fa fa-edit"></i>&nbsp;  <?= $title_web;?></li>
    </ol>
  </section>
  <section class="content">
	<div class="row">
	    <div class="col-md-12">
	        <div class="box box-primary">
                <div class="box-header with-border">
                </div>
			    <!-- /.box-header -->
			    <div class="box-body">
						<div class="row">
							<div class="col-sm-5">
								<table class="table table-striped">
									<tr style="background:yellowgreen">
										<td colspan="3">Data Transaksi</td>
									</tr>
									<tr>
										<td>No Peminjaman</td>
										<td>:</td>
										<td>
											<?= $pinjam->pinjam_id;?>
										</td>
									</tr>
									<tr>
										<td>Tgl Peminjaman</td>
										<td>:</td>
										<td>
											<?= $pinjam->tgl_pinjam;?>
										</td>
									</tr>
									<tr>
										<td>Tgl pengembalian</td>
										<td>:</td>
										<td>
											<?= $pinjam->tgl_balik;?>
										</td>
									</tr>
									<tr>
										<td>ID Anggota</td>
										<td>:</td>
										<td>
											<?= $pinjam->anggota_id;?>
										</td>
									</tr>
									<tr>
										<td>Biodata</td>
										<td>:</td>
										<td>
											<?php
											$user = $this->M_Admin->get_tableid_edit('tbl_login','anggota_id',$pinjam->anggota_id);
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
											?>
										</td>
									</tr>
									<tr>
										<td>Lama Peminjaman</td>
										<td>:</td>
										<td>
											<?= $pinjam->lama_pinjam;?> Hari
										</td>
									</tr>
								</table>
							</div>
							<div class="col-sm-7">
								<table class="table table-striped">
									<tr style="background:yellowgreen">
										<td colspan="3">Pinjam Buku</td>
									</tr>
									<tr>
										<td>Status</td>
										<td>:</td>
										<td>
											<?= $pinjam->status;?>
										</td>
									</tr>
									<tr>
										<td>Tgl Kembali</td>
										<td>:</td>
										<td>
											<?php 
												if($pinjam->tgl_kembali == '0')
												{
													echo '<p style="color:red;">belum dikembalikan</p>';
												}else{
													echo $pinjam->tgl_kembali;
												}
											
											?>
										</td>
									</tr>
									<tr>
										<td>Denda</td>
										<td>:</td>
										<td>
											
											<?php 
												$pinjam_id = $pinjam->pinjam_id;
												$denda = $this->db->query("SELECT * FROM tbl_denda WHERE pinjam_id = '$pinjam_id'");
												$total_denda = $denda->row();

												if($pinjam->status == 'Di Kembalikan')
												{
													echo $this->M_Admin->rp($total_denda->denda);
													
												}else{
													$jml = $this->db->query("SELECT * FROM tbl_pinjam WHERE pinjam_id = '$pinjam_id'")->num_rows();			
													$date1 = date('Ymd');
													$date2 = preg_replace('/[^0-9]/','',$pinjam->tgl_balik);
													$diff = $date1 - $date2;
													/*	$datetime1 = new DateTime($date1);
														$datetime2 = new DateTime($date2);
														$difference = $datetime1->diff($datetime2); */
													// echo $difference->days;
													if($diff > 0 )
													{
														echo $diff.' hari';
														$dd = $this->M_Admin->get_tableid_edit('tbl_biaya_denda','stat','Aktif'); 
														echo '<p style="color:red;font-size:18px;">'.$this->M_Admin->rp($jml*($dd->harga_denda*$diff)).' 
														</p><small style="color:#333;">* Untuk '.$jml.' Buku</small>';
													}else{
														echo '<p style="color:green;text-align:center;">
														Tidak Ada Denda</p>';
													}
												}
											?>
										</td>
									</tr>
									<tr>
										<td>Kode Buku</td>
										<td>:</td>
										<td>
										<?php
											$pin = $this->M_Admin->get_tableid('tbl_pinjam','pinjam_id',$pinjam->pinjam_id);
											$no =1;
											foreach($pin as $isi)
											{
												$buku = $this->M_Admin->get_tableid_edit('tbl_buku','buku_id',$isi['buku_id']);
												echo $no.'. '.$buku->buku_id.'<br/>';
											$no++;}

										?>
										</td>
									</tr>
									<tr>
										<td>Data Buku</td>
										<td>:</td>
										<td>
											<table class="table table-striped">
												<thead>
													<tr>
														<th>No</th>
														<th>Title</th>
														<th>Penerbit</th>
														<th>Tahun</th>
													</tr>
												</thead>
												<tbody>
												<?php 
													$no=1;
													foreach($pin as $isi)
													{
														$buku = $this->M_Admin->get_tableid_edit('tbl_buku','buku_id',$isi['buku_id']);
												?>
													<tr>
														<td><?= $no;?></td>
														<td><?= $buku->title;?></td>
														<td><?= $buku->penerbit;?></td>
														<td><?= $buku->thn_buku;?></td>
													</tr>
												<?php $no++;}?>
												</tbody>
											</table>
										</td>
									</tr>
								</table>
							</div>
						</div>
                        <div class="pull-right">
							<a href="<?= base_url('transaksi');?>" class="btn btn-danger btn-md">Kembali</a>
						</div>
		        </div>
	        </div>
	    </div>
    </div>
</section>
</div>
