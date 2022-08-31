<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Bootstrap CSS -->
    <link
      rel="stylesheet"
      type="text/css"
      href="bootstrap/css/bootstrap.min.css"
    />

    <title>Tugas Maskapai</title>
  </head>
  <body>
    <!-- Registrasi -->
    <div class="container"> <br>
      <h1 class="text-center">Registrasi Rute Penerbangan <br /></h1>
        <!-- Logo -->
        <div style="margin:10px auto; width:100px; height:100px; border-radius:100px; overflow:hidden">
          <img src="img/logo.png" style="margin:0;width:100%;height:100%">
        </div>
        <!-- /-logo -->

      <form method="post" action="">
        <div class="mb-3">
          <label class="form-label">Maskapai</label>
          <input
            type="text"
            class="form-control"
            name="nama_maskapai"
            placeholder="Nama Maskapai"
          />
        </div>
        <div class="mb-3">
          <label class="form-label">Bandara Keberangkatan</label>
          <select
            class="form-select"
            aria-label="Default select example"
            name="asal"
          >
            <option value="" disabled selected hidden>
              --Pilih Bandara Keberangkatan--
            </option>
            <!-- Array nama bandara asal atau keberangkatan -->
            <?php 
				         $bandara_a = array('Soekarno-Hatta (CGK)','Husein Sastranegara (BDO)','Abdul Rachman Saleh (MLG)','Juanda (SUB)');
				         sort($bandara_a);

				         foreach($bandara_a as $airport1){
				       ?>
            <option value="<?php echo ($airport1); ?>">
              <?php echo $airport1; ?>
            </option>
            "
            <?php 
				         }
				             ?>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Bandara Tujuan</label>
          <select
            class="form-select"
            aria-label="Default select example"
            name="tujuan"
          >
            <option value="" disabled selected hidden>
              --Pilih Bandara Tujuan--
            </option>
            <!-- Array nama bandara tujuan -->
            <?php 
				         $bandara_b = array('Ngurah Rai (DPS)','Hasanuddin (UPG)','Inanwatan (INX)','Sultan Iskandarmuda (BTJ)');
				         sort($bandara_b);

				         foreach($bandara_b as $airport2){
				       ?>
            <option value="<?php echo ($airport2); ?>">
              <?php echo $airport2; ?>
            </option>
            "
            <?php 
				         }
				             ?>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Harga Tiket</label>
          <input
            type="number"
            class="form-control"
            name="harga_tiket"
            placeholder="Masukkan Harga Tiket"
          />
        </div>
        <button type="submit" class="btn btn-primary" name="bsimpan">
          Submit
        </button> <br> <br> <hr> <br>
        <!-- /-registrasi -->
      </form>
    </div>

    <!-- Memanggil dan deklarasi File Json -->
    <?php 
      	$berkas    = "data.json";
        $dataPenerbangan  = array();

        $dataJson  = file_get_contents($berkas);
        $dataPenerbangan  = json_decode($dataJson, true);
    ?>

    <?php
      //Fungsi untuk menghitung pajak bandara asal ditambah pajak bandara tujuan
      function bayar_pajak($pajak1, $pajak2)
      {
          $total_pajak = $pajak1 + $pajak2;
          return $total_pajak;
      }

      //Menarik hasil inputan registrasi
      if (isset($_POST['bsimpan'])) {
        //Membuat variabel
        $nama = $_POST['nama_maskapai'];
        $bandara1 = $_POST['asal'];
        $bandara2 = $_POST['tujuan'];
        $harga = $_POST['harga_tiket'];

        //Menampilkan data berdasarkan nama bandara asal dan pajak
        if ($bandara1 == "Soekarno-Hatta (CGK)") {
            $pajak1 = 50000;
        } elseif ($bandara1 == "Husein Sastranegara (BDO)") {
            $pajak1 = 30000;
        } elseif ($bandara1 == "Abdul Rachman Saleh (MLG)") {
            $pajak1 = 40000;
        } else {
            $pajak1 = 40000;
        }

        //Menampilkan data berdasarkan nama bandara tujuan dan pajak
        if ($bandara2 == "Ngurah Rai (DPS)") {
            $pajak2 = 80000;
        } elseif ($bandara2 == "Hasanuddin (UPG)") {
            $pajak2 = 70000;
        } elseif ($bandara2 == "Inanwatan (INX)") {
            $pajak2 = 90000;
        } else {
            $pajak2 = 70000;
        }

        $total_pajak = bayar_pajak ($pajak1, $pajak2);
        $total_bayar = $harga + $total_pajak;

        //Menampung data baru yang diinput
        $dataBaru = array(
            'nama' => $_POST['nama_maskapai'],
            'bandara1' => $_POST['asal'],
            'bandara2' => $_POST['tujuan'],
            'harga' => $_POST['harga_tiket'],
            'total_pajak' => $total_pajak,
            'total_bayar' => $total_bayar,
        );

        array_push($dataPenerbangan, $dataBaru); //Menambahkan data baru ke dalam data yang sudah ada dalam berkas. 
        //Mengkonversi kembali data customer dari array PHP menjadi array Json dan menyimpannya ke dalam berkas.
        $dataJson = json_encode($dataPenerbangan, JSON_PRETTY_PRINT);
        file_put_contents($berkas, $dataJson);

      }
    ?>

  <!-- Data Table -->  
  <div class="container">
    <h2 class="text-center">Daftar Rute Tersedia<br /></h2><br>
    <table class="table table-bordered">
      <thead>
      <tr>
        <th scope="col">Maskapai</th>
        <th scope="col">Asal Penerbangan</th>
        <th scope="col">Tujuan Penerbangan</th>
        <th scope="col">Harga Tiket</th>
        <th scope="col">Pajak</th>
        <th scope="col">Total Harga Tiket</th>
      </tr>
      </thead>
      <!-- Proses memanggil isi pada file json -->
        <?php 
        for ($i=0; $i<count($dataPenerbangan); $i++) {
            $nama = $dataPenerbangan[$i]['nama']; 
            $bandara1 = $dataPenerbangan[$i]['bandara1']; 
            $bandara2 = $dataPenerbangan[$i]['bandara2']; 
            $harga = $dataPenerbangan[$i]['harga'];
            $totalan_pajak = $dataPenerbangan[$i]['total_pajak'];
            $totalan_bayar = $dataPenerbangan[$i]['total_bayar'];

      echo "<tbody>
      <tr>
        <td>" . $nama . "</td>
        <td>" . $bandara1 . "</td>
        <td>" . $bandara2 . "</td>
        <td>" . $harga . "</td>
        <td>" . $totalan_pajak . "</td>
        <td>" . $totalan_bayar . "</td>
      </tr>
      </tbody>";

      sort($dataPenerbangan);
      
        }
    ?>
    <!-- /-proses memanggil isi file json -->
    </table>

  </div>

    <!-- Bootstrap JavaScript -->
    <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
  </body>
</html>
