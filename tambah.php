<?php
include('config.php');
include('fungsi.php');

// mendapatkan data edit
if (isset($_GET['jenis'])) {
	$jenis = $_GET['jenis'];
}

if (isset($_POST['tambah'])) {
	$jenis = $_POST['jenis'];
	$nama = $_POST['nama'];

	tambahData($jenis, $nama);

	header('Location: ' . $jenis . '.php');
}

include('header.php');
?>

<section class="content">
	<h2>Tambah
		<?php echo $jenis ?> baru
	</h2>
	<br>
	<form class="ui form" method="post" action="tambah.php">
		<div class="inline field">
			<label style="font-size: 16px;">Nama
				<?php echo $jenis ?>
			</label>
			<input type="text" name="nama" placeholder="Masukkan nama kriteria baru" style="width: 400px;">
			<input type="hidden" name="jenis" value="<?php echo $jenis ?>">
		</div>
		<input class="ui green button" type="submit" name="tambah" value="Tambah">
	</form>
</section>

<?php include('footer.php'); ?>