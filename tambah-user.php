<?php require_once('includes/init.php'); ?>
<?php cek_login($role = array(1)); ?>

<?php
$errors = array();
$sukses = false;

if(isset($_POST['submit'])):			
	$username = $_POST['username'];
	$password = $_POST['password'];
	$password2 = $_POST['password2'];
	$nama = $_POST['nama'];
	$email = $_POST['email'];
	$role = $_POST['role'];
	
	if(!$username) {
		$errors[] = 'Username tidak boleh kosong';
	}		
	
	if(!$password) {
		$errors[] = 'Password tidak boleh kosong';
	}		
	
	if($password != $password2) {
		$errors[] = 'Password harus sama keduanya';
	}		
	
	if(!$nama) {
		$errors[] = 'Nama tidak boleh kosong';
	}		
	
	if(!$email) {
		$errors[] = 'Email tidak boleh kosong';
	}
	
	if(!$role) {
		$errors[] = 'Role tidak boleh kosong';
	}
	
	// Cek Username
	if($username) {
		$query = mysqli_query($koneksi,"SELECT * FROM user WHERE username = '$username'");
		$cek = mysqli_fetch_array($query);
		if(!empty($cek)) {
			$errors[] = 'Username sudah digunakan';
		}
	}
	
	if(empty($errors)):
		$pass = sha1($password);
		$simpan = mysqli_query($koneksi,"INSERT INTO user (id_user, username, password, nama, email, role) VALUES ('', '$username', '$pass', '$nama', '$email', '$role')");
		if($simpan) {
			redirect_to('list-user.php?status=sukses-baru');		
		}else{
			$errors[] = 'Data gagal disimpan';
		}
	endif;

endif;
?>

<?php
$page = "User";
require_once('template/header.php');
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-users-cog"></i> Data User</h1>

	<a href="list-user.php" class="btn btn-secondary btn-icon-split"><span class="icon text-white-50"><i class="fas fa-arrow-left"></i></span>
		<span class="text">Kembali</span>
	</a>
</div>
			
<?php if(!empty($errors)): ?>
	<div class="alert alert-info">
		<?php foreach($errors as $error): ?>
			<?php echo $error; ?>
		<?php endforeach; ?>
	</div>				
<?php endif; ?>

<form action="tambah-user.php" method="post">
	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-info"><i class="fas fa-fw fa-plus"></i> Tambah Data User</h6>
		</div>
		<div class="card-body">
			<div class="row">
				<div class="form-group col-md-6">
					<label class="font-weight-bold">Username</label>
					<input autocomplete="off" type="text" name="username" required class="form-control"/>
				</div>
				
				<div class="form-group col-md-6">
					<label class="font-weight-bold">Password</label>
					<input autocomplete="off" type="password" name="password" required class="form-control"/>
				</div>
				
				<div class="form-group col-md-6">
					<label class="font-weight-bold">Ulangi Password</label>
					<input autocomplete="off" type="password" name="password2" required class="form-control"/>
				</div>
				
				<div class="form-group col-md-6">
					<label class="font-weight-bold">Nama</label>
					<input autocomplete="off" type="text" name="nama" required class="form-control"/>
				</div>
				
				<div class="form-group col-md-6">
					<label class="font-weight-bold">E-Mail</label>
					<input autocomplete="off" type="email" name="email" required class="form-control"/>
				</div>
				
				<div class="form-group col-md-6">
					<label class="font-weight-bold">Level</label>
					<select name="role" required class="form-control">
						<option value="">--Pilih--</option>
						<option value="1">Administrator</option>
						<option value="2">User</option>
					</select>
				</div>
			</div>
		</div>
		<div class="card-footer text-right">
            <button name="submit" value="submit" type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
            <button type="reset" class="btn btn-info"><i class="fa fa-sync-alt"></i> Reset</button>
        </div>
	</div>
</form>
<?php
require_once('template/footer.php');
?>