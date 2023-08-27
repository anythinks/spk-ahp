<?php

include('config.php');
include('fungsi.php');


// menghitung perankingan
$jmlKriteria = getJumlahKriteria();
$jmlAlternatif = getJumlahAlternatif();
$nilai = array();

// mendapatkan nilai tiap alternatif
for ($x = 0; $x <= ($jmlAlternatif - 1); $x++) {
	// inisialisasi
	$nilai[$x] = 0;

	for ($y = 0; $y <= ($jmlKriteria - 1); $y++) {
		$id_alternatif = getAlternatifID($x);
		$id_kriteria = getKriteriaID($y);

		$pv_alternatif = getAlternatifPV($id_alternatif, $id_kriteria);
		$pv_kriteria = getKriteriaPV($id_kriteria);

		$nilai[$x] += ($pv_alternatif * $pv_kriteria);
	}
}

// update nilai ranking
for ($i = 0; $i <= ($jmlAlternatif - 1); $i++) {
	$id_alternatif = getAlternatifID($i);
	$query = "INSERT INTO ranking VALUES ($id_alternatif,$nilai[$i]) ON DUPLICATE KEY UPDATE nilai=$nilai[$i]";
	$result = mysqli_query($koneksi, $query);
	if (!$result) {
		echo "Gagal mengupdate ranking";
		exit();
	}
}

include('header.php');

?>

<section class="content">
	<h2 class="ui header">Hasil Perhitungan</h2>
	<table class="ui celled table">
		<thead>
			<tr>
				<th>Overall Composite Height</th>
				<th>Priority Vector (rata-rata)</th>
				<?php
				for ($i = 0; $i <= (getJumlahAlternatif() - 1); $i++) {
					echo "<th>" . getAlternatifNama($i) . "</th>\n";
				}
				?>
			</tr>
		</thead>
		<tbody>

			<?php
			for ($x = 0; $x <= (getJumlahKriteria() - 1); $x++) {
				echo "<tr>";
				echo "<td>" . getKriteriaNama($x) . "</td>";
				echo "<td>" . round(getKriteriaPV(getKriteriaID($x)), 5) . "</td>";

				for ($y = 0; $y <= (getJumlahAlternatif() - 1); $y++) {
					echo "<td>" . round(getAlternatifPV(getAlternatifID($y), getKriteriaID($x)), 5) . "</td>";
				}


				echo "</tr>";
			}
			?>
		</tbody>

		<tfoot>
			<tr>
				<th colspan="2">Total</th>
				<?php
				for ($i = 0; $i <= ($jmlAlternatif - 1); $i++) {
					echo "<th>" . round($nilai[$i], 5) . "</th>";
				}
				?>
			</tr>
		</tfoot>

	</table>


	<h2 class="ui header">Perankingan</h2>
	<table class="ui celled collapsing table">
		<thead>
			<tr>
				<th>Peringkat</th>
				<th>Alternatif</th>
				<th>Nilai</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$query = "SELECT id,nama,id_alternatif,nilai FROM alternatif,ranking WHERE alternatif.id = ranking.id_alternatif ORDER BY nilai DESC";
			$result = mysqli_query($koneksi, $query);

			$i = 0;
			while ($row = mysqli_fetch_array($result)) {
				$i++;
				?>
				<tr>
					<?php if ($i == 1) {
						echo "<td><div class=\"ui ribbon label\">Pertama</div></td>";
					} else {
						echo "<td>" . $i . "</td>";
					}

					?>

					<td>
						<?php echo $row['nama'] ?>
					</td>
					<td>
						<?php echo $row['nilai'] ?>
					</td>
				</tr>

			<?php
			}


			?>
		</tbody>
	</table>
</section>
<script src="js/jquery-3.2.1.js"></script>
<script src="js/highcharts.js"></script>
<script src="js/exporting.js"></script>
<script>
	var chart1; // globally available
	$(document).ready(function () {
		chart1 = new Highcharts.Chart({
			chart: {
				renderTo: 'container2',
				type: 'column'
			},
			title: {
				text: 'Grafik Usulan'
			},
			xAxis: {
				categories: ['Alternatif']
			},
			yAxis: {
				title: {
					text: 'Jumlah Nilai'
				}
			},
			series: [
				<?php while ($row4 = $stmt4->fetch(PDO::FETCH_ASSOC)): ?>
						//data yang diambil dari database dimasukan ke variable nama dan data
						{
						name: '<?php echo $row4['nama'] ?>',
						data: [<?php echo $row4['hasil_akhir'] ?>]
					},
				<?php endwhile; ?>
			]
		});
	});

</script>
<?php include('footer.php'); ?>