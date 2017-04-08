<?php session_start(); 

    include 'sanitasi.php';
    include 'db.php';
    $session_id = session_id();
    //mengirim data sesuai dengan variabel denagn metode POST 

    $kode_barang = stringdoang($_POST['kode_barang']);
    $harga = angkadoang($_POST['harga']);
    $harga_baru = angkadoang($_POST['harga_baru']);
    $jumlah_barang = angkadoang($_POST['jumlah_barang']);
    $nama_barang = stringdoang($_POST['nama_barang']);
    $sales = stringdoang($_POST['sales']);
    $user = $_SESSION['nama'];
    $potongan = stringdoang($_POST['potongan']);
    $level_harga = stringdoang($_POST['level_harga']);
    $ber_stok = stringdoang($_POST['ber_stok']);

    

    if ( $harga != $harga_baru) {
      

            if ($level_harga == "Level 1") {

               $query00 = $db->query("UPDATE barang SET harga_jual = '$harga_baru' WHERE kode_barang = '$kode_barang'");
            
            }
            else if ($level_harga == "Level 2") {

               $query00 = $db->query("UPDATE barang SET harga_jual2 = '$harga_baru' WHERE kode_barang = '$kode_barang'");
              
            }
            else if ($level_harga == "Level 3") {

               $query00 = $db->query("UPDATE barang SET harga_jual3 = '$harga_baru' WHERE kode_barang = '$kode_barang'");
              
            }
           
       $harga = $harga_baru;
    }

    else {


       $harga = $harga;

    }


    $a = $harga * $jumlah_barang;
    $tahun_sekarang = date('Y');
    $bulan_sekarang = date('m');
    $tanggal_sekarang = date('Y-m-d');
    $jam_sekarang = date('H:i:sa');
    $tahun_terakhir = substr($tahun_sekarang, 2);


          if(strpos($potongan, "%") !== false)
          {
              $potongan_jadi = $a * $potongan / 100;
              $potongan_tampil = $potongan_jadi;
          }
          else{

             $potongan_jadi = $potongan;
             $potongan_tampil = $potongan;
          }


    $tax = stringdoang($_POST['tax']);
    $satu = 1;
    $x = $a - $potongan_tampil;

    $hasil_tax = $satu + ($tax / 100);

    $hasil_tax2 = $x / $hasil_tax;

    $tax_persen = $x - $hasil_tax2;

    $query9 = $db->query("SELECT * FROM fee_produk WHERE nama_petugas = '$sales' AND kode_produk = '$kode_barang'");
    $cek9 = mysqli_fetch_array($query9);
    $prosentase = $cek9['jumlah_prosentase'];
    $nominal = $cek9['jumlah_uang'];



    if ($prosentase != 0){
      
      $query90 = $db->query("SELECT * FROM tbs_penjualan WHERE session_id = '$session_id' AND kode_barang = '$kode_barang'");
      $cek01 = mysqli_num_rows($query90);

      $cek90 = mysqli_fetch_array($query90);
      $jumlah1 = $cek90['jumlah_barang'];
      $jumlah0 = $jumlah_barang + $jumlah1;

          $subtotal_prosentase = $harga * $jumlah0;
          
          $fee_prosentase_produk = $prosentase * $subtotal_prosentase / 100;

      $komisi = $fee_prosentase_produk;

      if ($cek01 > 0) {
        $query91 = $db->query("UPDATE tbs_fee_produk SET jumlah_fee = '$komisi' WHERE nama_petugas = '$sales' AND kode_produk = '$kode_barang'");
      }

      else
      {

          $subtotal_prosentase = $harga * $jumlah_barang;
          
          $fee_prosentase_produk = $prosentase * $subtotal_prosentase / 100;

          $query10 = $db->query("INSERT INTO tbs_fee_produk (nama_petugas, session_id, kode_produk, nama_produk, jumlah_fee, tanggal, jam) VALUES ('$sales', '$session_id', '$kode_barang',
            '$nama_barang', '$fee_prosentase_produk', '$tanggal_sekarang', '$jam_sekarang')");

      }
      
      



    }

    elseif ($nominal != 0) {

      $query900 = $db->query("SELECT * FROM tbs_penjualan WHERE session_id = '$session_id' AND kode_barang = '$kode_barang'");
      $cek011 = mysqli_num_rows($query900);

      $cek900 = mysqli_fetch_array($query900);
      $jumlah1 = $cek900['jumlah_barang'];
      $jumlah0 = $jumlah_barang + $jumlah1;

          $fee_nominal_produk = $nominal * $jumlah0;

      $komisi0 = $fee_nominal_produk;

      if ($cek011 > 0) {

        $query911 = $db->query("UPDATE tbs_fee_produk SET jumlah_fee = '$komisi0' WHERE nama_petugas = '$user' AND kode_produk = '$kode_barang'");
      }

      else
        {

      $fee_nominal_produk = $nominal * $jumlah_barang;

      $query10 = $db->query("INSERT INTO tbs_fee_produk (nama_petugas, session_id, kode_produk, nama_produk, jumlah_fee, tanggal, jam) VALUES ('$user', '$session_id', '$kode_barang', '$nama_barang', '$fee_nominal_produk', '$tanggal_sekarang', '$jam_sekarang')");
      }

    }

    else
    {

    }




$cek = $db->query("SELECT * FROM tbs_penjualan WHERE kode_barang = '$kode_barang' AND session_id = '$session_id'");

$jumlah = mysqli_num_rows($cek);

  
$cek = $db->query("SELECT * FROM tbs_penjualan WHERE kode_barang = '$kode_barang' AND session_id = '$session_id'");

$jumlah = mysqli_num_rows($cek);
    
    if ($jumlah > 0)
    {
        # code...
        $query1 = $db->prepare("UPDATE tbs_penjualan SET jumlah_barang = jumlah_barang + ?, subtotal = subtotal + ?, potongan = ? WHERE kode_barang = ? AND session_id = ?");

        $query1->bind_param("iisss",
            $jumlah_barang, $subtotal, $potongan_tampil, $kode_barang, $session_id);

            
            $jumlah_barang = angkadoang($_POST['jumlah_barang']);
            $kode_barang = stringdoang($_POST['kode_barang']);
            $tax = angkadoang($_POST['tax']);
            $subtotal = $harga* $jumlah_barang - $potongan_jadi;

        $query1->execute();

    }
    else
    {
            $perintah = $db->prepare("INSERT INTO tbs_penjualan (session_id,kode_barang,nama_barang,jumlah_barang,satuan,harga,subtotal,potongan,tax,tipe_barang) VALUES (?,?,
            ?,?,?,?,?,?,?,?)");
            
            
            $perintah->bind_param("sssisiiiss",
            $session_id, $kode_barang, $nama_barang, $jumlah_barang, $satuan, $harga, $subtotal, $potongan_tampil, $tax_persen, $ber_stok);
            
            
            $kode_barang = stringdoang($_POST['kode_barang']);
            $jumlah_barang = angkadoang($_POST['jumlah_barang']); 
            $nama_barang = stringdoang($_POST['nama_barang']);
            $ber_stok = stringdoang($_POST['ber_stok']);
            $satuan = stringdoang($_POST['satuan']);
            $tax = angkadoang($_POST['tax']);
            $subtotal = $harga * $jumlah_barang - $potongan_jadi;
            
            
            $perintah->execute();

    }



    ?>

    <?php
  //menampilkan semua data yang ada pada tabel tbs penjualan dalam DB
                $perintah = $db->query("SELECT tp.id,tp.kode_barang,tp.satuan,tp.nama_barang,tp.jumlah_barang,tp.harga,tp.subtotal,tp.potongan,tp.tax,s.nama FROM tbs_penjualan tp INNER JOIN satuan s ON tp.satuan = s.id WHERE tp.session_id = '$session_id' AND tp.kode_barang = '$kode_barang'");
                
                //menyimpan data sementara yang ada pada $perintah
                
               $data1 = mysqli_fetch_array($perintah);

                //menampilkan data
                echo "<tr class='tr-kode-". $data1['kode_barang'] ." tr-id-". $data1['id'] ."' data-kode-barang='".$data1['kode_barang']."'>
                <td>". $data1['kode_barang'] ."</td>
                <td>". $data1['nama_barang'] ."</td>
                <td class='edit-jumlah' data-id='".$data1['id']."' align='right'><span id='text-jumlah-".$data1['id']."'>". $data1['jumlah_barang'] ."</span> <input type='hidden' id='input-jumlah-".$data1['id']."' value='".$data1['jumlah_barang']."' class='input_jumlah' data-id='".$data1['id']."' autofocus='' data-kode='".$data1['kode_barang']."' data-harga='".$data1['harga']."' data-satuan='".$data1['satuan']."' > </td>
                <td>". $data1['nama'] ."</td>
                <td  align='right'>". rp($data1['harga']) ."</td>
     
                <td  align='right'><span id='text-potongan-".$data1['id']."'>". rp($data1['potongan']) ."</span></td>
                <td  align='right'><span id='text-tax-".$data1['id']."'>". rp($data1['tax']) ."</span></td>
                 <td  align='right'> <span id='text-subtotal-".$data1['id']."'>". rp($data1['subtotal']) ."</span></td>";

                echo "<td style='font-size:15px'> <button class='btn btn-danger btn-sm btn-hapus-tbs' id='hapus-tbs-".$data1['id']."' data-id='". $data1['id'] ."' data-kode-barang='". $data1['kode_barang'] ."' data-barang='". $data1['nama_barang'] ."' data-subtotal='". $data1['subtotal'] ."'>Hapus</button> </td> 

                </tr>";



//Untuk Memutuskan Koneksi Ke Database
mysqli_close($db);   
    ?>





