<?php require_once('includes/init.php'); ?>
<?php cek_login($role = array(1)); ?>

<?php
$page = "Penilaian";
require_once('template/header.php');

if(isset($_POST['tambah'])):	
	$id_alternatif = $_POST['id_alternatif'];
	$id_kriteria = $_POST['id_kriteria'];
	$nilai = $_POST['nilai'];

	if(!$id_kriteria) {
		$errors[] = 'ID kriteria tidak boleh kosong';
	}
	if(!$id_alternatif) {
		$errors[] = 'ID Alternatif kriteria tidak boleh kosong';
	}		
	if(!$nilai) {
		$errors[] = 'Nilai kriteria tidak boleh kosong';
	}	
	
	if(empty($errors)):
		$i = 0;
		foreach ($nilai as $key) {
			$simpan = mysqli_query($koneksi,"INSERT INTO penilaian (id_penilaian, id_alternatif, id_kriteria, nilai) VALUES ('', '$id_alternatif', '$id_kriteria[$i]', '$key')");
			$i++;
		}
		if($simpan) {
			$sts[] = 'Data berhasil disimpan';
		}else{
			$sts[] = 'Data gagal disimpan';
		}
	endif;
endif;

if(isset($_POST['edit'])):	
	$id_alternatif = $_POST['id_alternatif'];
	$id_kriteria = $_POST['id_kriteria'];
	$nilai = $_POST['nilai'];

	if(!$id_kriteria) {
		$errors[] = 'ID kriteria tidak boleh kosong';
	}
	if(!$id_alternatif) {
		$errors[] = 'ID Alternatif kriteria tidak boleh kosong';
	}		
	if(!$nilai) {
		$errors[] = 'Nilai kriteria tidak boleh kosong';
	}	
	
	if(empty($errors)):
		$i = 0;
		mysqli_query($koneksi,"DELETE FROM penilaian WHERE id_alternatif = '$id_alternatif';");
		foreach ($nilai as $key) {
			$simpan = mysqli_query($koneksi,"INSERT INTO penilaian (id_penilaian, id_alternatif, id_kriteria, nilai) VALUES ('', '$id_alternatif', '$id_kriteria[$i]', '$key')");
			$i++;
		}
		if($simpan) {
			$sts[] = 'Data berhasil diupdate';
		}else{
			$sts[] = 'Data gagal diupdate';
		}
	endif;
endif;
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-edit"></i> Data Penilaian</h1>
</div>

<?php if(!empty($sts)): ?>
	<div class="alert alert-info">
		<?php foreach($sts as $st): ?>
			<?php echo $st; ?>
		<?php endforeach; ?>
	</div>
<?php
endif;
?>

<div class="card shadow mb-4">
    <!-- /.card-header -->
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-info"><i class="fa fa-table"></i> Daftar Data Penilaian</h6>
    </div>
	
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
				<thead class="bg-info text-white">
					<tr align="center">
						<th width="5%">No</th>
						<th>Alternatif</th>
						<th width="15%">Aksi</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$no=1;
					$query = mysqli_query($koneksi,"SELECT * FROM alternatif");			
					while($data = mysqli_fetch_array($query)){
					?>
					<tr align="center">
						<td><?=$no ?></td>
						<td align="left"><?= $data['nama'] ?></td>
						<?php
						$id_alternatif = $data['id_alternatif'];
						$q = mysqli_query($koneksi,"SELECT * FROM penilaian WHERE id_alternatif='$id_alternatif'");
						$cek_tombol = mysqli_num_rows($q);
						?>
						<td>
						<?php if ($cek_tombol==0) { ?>
						<a data-toggle="modal" href="#set<?= $data['id_alternatif'] ?>" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Input</a>
						<?php } else { ?>
						<a data-toggle="modal" href="#edit<?= $data['id_alternatif'] ?>" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i> Edit</a>
						<?php } ?>
						</td>
					</tr>
				
					<!-- Modal -->
					<div class="modal fade" id="set<?= $data['id_alternatif'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="myModalLabel"><i class="fa fa-plus"></i> Input Penilaian</h5>
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								</div>
								<form action="" method="post">
									<div class="modal-body">
										<?php
										$q2 = mysqli_query($koneksi,"SELECT * FROM kriteria ORDER BY kode_kriteria ASC");			
										while($d = mysqli_fetch_array($q2)){
										?>
										<input type="text" name="id_alternatif" value="<?= $data['id_alternatif'] ?>" hidden>
										<input type="text" name="id_kriteria[]" value="<?= $d['id_kriteria'] ?>" hidden>
										<div class="form-group">
											<label class="font-weight-bold">(<?= $d['kode_kriteria'] ?>) <?= $d['nama'] ?></label>
											<?php
											if($d['ada_pilihan']==1){
											?>
											<select name="nilai[]" class="form-control" required>
												<option value="">--Pilih--</option>
												<?php
												$id_kriteria = $d['id_kriteria'];
												$q3 = mysqli_query($koneksi,"SELECT * FROM sub_kriteria WHERE id_kriteria = '$id_kriteria' ORDER BY nilai ASC");			
												while($d3 = mysqli_fetch_array($q3)){
												?>
												<option value="<?= $d3['id_sub_kriteria'] ?>"><?= $d3['nama'] ?> </option>
												<?php } ?>
											</select>
											<?php
											}else{
											?>
											<input type="number" name="nilai[]" class="form-control" step="0.001" required autocomplete="off">
											<?php
											}
											?>
										</div>
										<?php } ?>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
										<button type="submit" name="tambah" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
									</div>
								</form>
							</div>
						</div>
					</div>
					
					<!-- Modal -->
					<div class="modal fade" id="edit<?= $data['id_alternatif'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="myModalLabel"><i class="fa fa-edit"></i> Edit Penilaian</h5>
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								</div>
								<form action="" method="post">
									<div class="modal-body">
										<?php
										$q2 = mysqli_query($koneksi,"SELECT * FROM kriteria ORDER BY kode_kriteria ASC");			
										while($d = mysqli_fetch_array($q2)){
										$id_kriteria = $d['id_kriteria'];
										$id_alternatif = $data['id_alternatif'];
										$q4 = mysqli_query($koneksi,"SELECT * FROM penilaian WHERE id_alternatif='$id_alternatif' AND id_kriteria='$id_kriteria'");			
										$d4 = mysqli_fetch_array($q4);
										?>
										<input type="text" name="id_alternatif" value="<?= $data['id_alternatif'] ?>" hidden>
										<input type="text" name="id_kriteria[]" value="<?= $d['id_kriteria'] ?>" hidden>
										<div class="form-group">
											<label class="font-weight-bold">(<?= $d['kode_kriteria'] ?>) <?= $d['nama'] ?></label>
											<?php
											if($d['ada_pilihan']==1){
											?>
											<select name="nilai[]" class="form-control" required>
												<option value="">--Pilih--</option>
												<?php
												$q3 = mysqli_query($koneksi,"SELECT * FROM sub_kriteria WHERE id_kriteria = '$id_kriteria' ORDER BY nilai ASC");			
												while($d3 = mysqli_fetch_array($q3)){
												?>
												<option value="<?= $d3['id_sub_kriteria'] ?>" <?php if($d3['id_sub_kriteria']==$d4['nilai']){echo "selected";} ?>><?= $d3['nama'] ?> </option>
												<?php } ?>
											</select>
											<?php
											}else{
											?>
											<input type="number" name="nilai[]" class="form-control" step="0.001" value="<?= $d4['nilai'] ?>" required autocomplete="off">
											<?php
											}
											?>
										</div>
										<?php } ?>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
										<button type="submit" name="edit" class="btn btn-success"><i class="fa fa-save"></i> Update</button>
									</div>
								</form>
							</div>
						</div>
					</div>
					<?php 
					$no++;
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<?php
require_once('template/footer.php');
?>