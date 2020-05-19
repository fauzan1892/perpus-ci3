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
			<div class="row">
				<div class="col-sm-4">
					<div class="box box-primary">
						<div class="box-header with-border">
							<?php if(!empty($this->input->get('id'))){?>
							<h4> Edit Harga Denda</h4>
							<?php }else{?>
							<h4> Tambah Harga Denda</h4>
							<?php }?>
						</div>
						<!-- /.box-header -->
						<div class="box-body">
							<?php if(!empty($this->input->get('id'))){?>
							<form method="post" action="<?= base_url('transaksi/dendaproses');?>">
								<div class="form-group">
								<label for="">Harga Denda</label>
									<input type="number" name="harga"  value="<?=$den->harga_denda;?>" class="form-control" placeholder="Contoh : 10000" >
								
								</div>
								<div class="form-group">
									<label for="">Status</label>
									<select class="form-control" name="status">
										<option <?php if($den->stat == 'Aktif'){echo 'selected';}?>>Aktif</option>
										<option <?php if($den->stat == 'Tidak Aktif'){echo 'selected';}?>>Tidak Aktif</option>
									</select>
								</div>
								<br/>
								<input type="hidden" name="edit" value="<?=$den->id_biaya_denda;?>">
								<button type="submit" class="btn btn-primary"><i class="fa fa-edit"></i> Edit Harga Denda</button>
							</form>
							<?php }else{?>

							<form method="post" action="<?= base_url('transaksi/dendaproses');?>">
								<div class="form-group">
								<label for="">Harga Denda</label>
									<input type="number" name="harga" class="form-control" placeholder="Contoh : 10000" >
								</div>
								<br/>
								<input type="hidden" name="tambah" value="tambah">
								<button type="submit" class="btn btn-primary"> <i class="fa fa-plus"></i> Tambah Harga Denda</button>
							</form>
							<?php }?>
						</div>
					</div>
				</div>
				<div class="col-sm-8">
					<div class="box box-primary">
						<div class="box-header with-border">
						</div>
						<!-- /.box-header -->
						<div class="box-body">
						<div class="table-responsive">
							<table id="example1" class="table table-bordered table-striped table" width="100%">
								<thead>
									<tr>
										<th>No</th>
										<th>Harga Denda</th>
										<th>Status</th>
										<th>Mulai Tanggal</th>
										<th>Aksi</th>
									</tr>
								</thead>
								<tbody>
								<?php $no=1;foreach($denda->result_array() as $isi){?>
									<tr>
										<td><?= $no;?></td>
										<td><?= $isi['harga_denda'];?></td>
										<td><?= $isi['stat'];?></td>
										<td><?= $isi['tgl_tetap'];?></td>
										<td style="width:20%;">
											<a href="<?= base_url('transaksi/denda?id='.$isi['id_biaya_denda']);?>"><button class="btn btn-success"><i class="fa fa-edit"></i></button></a>
											<?php if($isi['stat'] == 'Aktif'){?>
											<button class="btn btn-warning"><i class="fa fa-ban"></i></button>
											<?php }else{?>
											<a href="<?= base_url('transaksi/dendaproses?denda_id='.$isi['id_biaya_denda']);?>" onclick="return confirm('Anda yakin Biaya denda ini akan dihapus ?');">
											<button class="btn btn-danger"><i class="fa fa-trash"></i></button></a>
											<?php }?>
										</td>
									</tr>
								<?php $no++;}?>
								</tbody>
							</table>
						</div>
						</div>
					</div>
				</div>
			</div>
    	</div>
    </div>
</section>
</div>
