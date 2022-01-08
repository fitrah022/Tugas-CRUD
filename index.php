<!DOCTYPE html>
<html>

<head>
    <title>CRUD</title>
</head>

<body>

    <?php
    $koneksi = mysqli_connect("localhost", "root", "", "crud_db") or die(mysqli_error());

    function tambah($koneksi)
    {

        if (isset($_POST['btn_simpan'])) {
            $id = time();
            $nm_tanaman = $_POST['nm_tanaman'];
            $hasil_panen = $_POST['hasil'];
            $lama_panen = $_POST['lama_panen'];
            $tgl_panen = $_POST['tgl_panen'];

            if (!empty($nm_tanaman) && !empty($hasil_panen) && !empty($lama_panen) && !empty($tgl_panen)) {
                $sql = "INSERT INTO panen (id,tanaman, hasil, durasi, tgl_panen) VALUES(" . $id . ",'" . $nm_tanaman . "','" . $hasil_panen . "','" . $lama_panen . "','" . $tgl_panen . "')";
                $simpan = mysqli_query($koneksi, $sql);
                if ($simpan && isset($_GET['aksi'])) {
                    if ($_GET['aksi'] == 'create') {
                        header('location: index.php');
                    }
                }
            } else {
                $pesan = "Tidak dapat menyimpan, lengkapi data";
            }
        }
    ?>
        <form action="" method="POST">
            <fieldset>
                <legend>
                    <h2>Tambah data</h2>
                </legend>
                <label>Nama tanaman <input type="text" name="nm_tanaman" /></label> <br>
                <label>Hasil panen <input type="number" name="hasil" /> kg</label><br>
                <label>Lama tanam <input type="number" name="lama_panen" /> bulan</label> <br>
                <label>Tanggal panen <input type="date" name="tgl_panen" /></label> <br>
                <br>
                <label>
                    <input type="submit" name="btn_simpan" value="Simpan" />
                    <input type="reset" name="reset" value="Besihkan" />
                </label>
                <br>
                <p><?php echo isset($pesan) ? $pesan : "" ?></p>
            </fieldset>
        </form>
        <?php
    }

    function tampil_data($koneksi)
    {
        $sql = "SELECT * FROM panen";
        $query = mysqli_query($koneksi, $sql);

        echo "<fieldset>";
        echo "<legend><h2>Data Panen</h2></legend>";

        echo "<table border='1' cellpadding='10'>";
        echo "<tr>
            <th>ID</th>
            <th>Nama Tanaman</th>
            <th>Hasil Panen</th>
            <th>Lama Tanam</th>
            <th>Tanggal Panen</th>
            <th>Tindakan</th>
          </tr>";

        while ($data = mysqli_fetch_array($query)) {
        ?>
            <tr>
                <td><?php echo $data['id']; ?></td>
                <td><?php echo $data['tanaman']; ?></td>
                <td><?php echo $data['hasil']; ?> Kg</td>
                <td><?php echo $data['durasi']; ?> bulan</td>
                <td><?php echo $data['tgl_panen']; ?></td>
                <td>
                    <a href="index.php?aksi=update&id=<?php echo $data['id']; ?>&nama=<?php echo $data['tanaman']; ?>&hasil=<?php echo $data['hasil']; 
                    ?>&lama_panen=<?php echo $data['durasi']; ?>&tanggal=<?php echo $data['tgl_panen']; ?>">Ubah</a> |
                    <a href="index.php?aksi=delete&id=<?php echo $data['id']; ?>">Hapus</a>
                </td>
            </tr>
        <?php
        }
        echo "</table>";
        echo "</fieldset>";
    }

    function ubah($koneksi)
    {

        if (isset($_POST['btn_ubah'])) {
            $id = $_POST['id'];
            $nm_tanaman = $_POST['nm_tanaman'];
            $hasil_panen = $_POST['hasil'];
            $lama_panen = $_POST['lama_panen'];
            $tgl_panen = $_POST['tgl_panen'];

            if (!empty($nm_tanaman) && !empty($hasil_panen) && !empty($lama_panen) && !empty($tgl_panen)) {
                $perubahan = "tanaman='" . $nm_tanaman . "',hasil=" . $hasil_panen . ",durasi=" . $lama_panen . ",tgl_panen='" . $tgl_panen . "'";
                $sql_update = "UPDATE panen SET " . $perubahan . " WHERE id=$id";
                $update = mysqli_query($koneksi, $sql_update);
                if ($update && isset($_GET['aksi'])) {
                    if ($_GET['aksi'] == 'update') {
                        header('location: index.php');
                    }
                }
            } else {
                $pesan = "Data tidak lengkap!";
            }
        }


        if (isset($_GET['id'])) {
        ?>
            <a href="index.php"> &laquo; Home</a> |
            <a href="index.php?aksi=create"> (+) Tambah Data</a>
            <hr>

            <form action="" method="POST">
                <fieldset>
                    <legend>
                        <h2>Ubah data</h2>
                    </legend>
                    <input type="hidden" name="id" value="<?php echo $_GET['id'] ?>" />
                    <label>Nama tanaman <input type="text" name="nm_tanaman" value="<?php echo $_GET['nama'] ?>" /></label> <br>
                    <label>Hasil panen <input type="number" name="hasil" value="<?php echo $_GET['hasil'] ?>" /> kg</label><br>
                    <label>Lama tanam <input type="number" name="lama_panen" value="<?php echo $_GET['lama_panen'] ?>" /> bulan</label> <br>
                    <label>Tanggal panen <input type="date" name="tgl_panen" value="<?php echo $_GET['tanggal'] ?>" /></label> <br>
                    <br>
                    <label>
                        <input type="submit" name="btn_ubah" value="Simpan Perubahan" /> atau <a href="index.php?aksi=delete&id=<?php echo $_GET['id'] ?>"> (x) Hapus data ini</a>!
                    </label>
                    <br>
                    <p><?php echo isset($pesan) ? $pesan : "" ?></p>

                </fieldset>
            </form>
    <?php
        }
    }

    function hapus($koneksi)
    {
        if (isset($_GET['id']) && isset($_GET['aksi'])) {
            $id = $_GET['id'];
            $sql_hapus = "DELETE FROM panen WHERE id=" . $id;
            $hapus = mysqli_query($koneksi, $sql_hapus);

            if ($hapus) {
                if ($_GET['aksi'] == 'delete') {
                    header('location: index.php');
                }
            }
        }
    }

    if (isset($_GET['aksi'])) {
        switch ($_GET['aksi']) {
            case "create":
                echo '<a href="index.php"> &laquo; Home</a>';
                tambah($koneksi);
                break;
            case "read":
                tampil_data($koneksi);
                break;
            case "update":
                ubah($koneksi);
                tampil_data($koneksi);
                break;
            case "delete":
                hapus($koneksi);
                break;
            default:
                echo "<h3>Aksi <i>" . $_GET['aksi'] . "</i> tidaka ada!</h3>";
                tambah($koneksi);
                tampil_data($koneksi);
        }
    } else {
        tambah($koneksi);
        tampil_data($koneksi);
    }
    ?>
</body>

</html>
