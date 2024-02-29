<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'head.php'; ?>
</head>
<body>

<!-- Highlight Customer Name -->
<div class="container mt-5" id="highlight-customer">
</div>

<!-- Include MySQL Connection -->
<?php include 'config.php'; ?>

<!-- Buttons Container -->
<div class="container mt-5">
    <div class="row justify-content-center mb-3">
        <div class="col-auto">
            <a id="call-button" href="#" class="btn btn-primary btn-lg">Panggil Telpon &nbsp; &nbsp;<i class="fas fa-phone"></i></a>
        </div>
        <div class="col-auto">
            <a id="whatsapp-button" href="#" target="_blank" class="btn btn-success btn-lg">Panggil Whatsapp &nbsp; &nbsp; <i class="fab fa-whatsapp"></i></a>
        </div>
        <div class="col-auto">
            <a id="broadcast-button" href="#" target="_blank" class="btn btn-info btn-lg">Kirim Broadcast &nbsp; &nbsp; <i class="far fa-comment-dots"></i></a>
        </div>
        <div class="col-auto">
            <a id="change-status-button" href="#" class="btn btn-secondary btn-lg">Ganti Status &nbsp; &nbsp; <i class="fas fa-cog"></i></a>
        </div>
    </div>

    <div class="row justify-content-center mb-3">
        <div class="col-auto">
            <!-- Add id="prev-button" here -->
            <button id="prev-button" type="button" class="btn btn-warning btn-lg">Sebelumnya<i class="fa-solid fa-right-long"></i></button>
        </div>
        <div class="col-auto">
            <!-- Add id="next-button" here -->
            <button id="next-button" type="button" class="btn btn-warning btn-lg">Berikutnya<i class="fa-solid fa-right-long"></i></button>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-auto">
            <!-- Add id="prev-button" here -->
            <button id="prev-button" type="button" class="btn btn-primary btn-lg">Tambah Pelanggan<i class="fa-solid fa-right-long"></i></button>
        </div>
        <div class="col-auto">
            <!-- Add id="next-button" here -->
            <button id="next-button" type="button" class="btn btn-danger btn-lg">Hapus Pelanggan<i class="fa-solid fa-right-long"></i></button>
        </div>
    </div>
</div>


<!-- Table Container -->
<div class="container mt-5">
    <div class="row">
        <div class="col">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Nama Pelanggan</th>
                        <th scope="col">Alamat</th>
                        <th scope="col">No HP</th>
                        <th scope="col">Status</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $sql = "SELECT id, nama, alamat, alamat2, no_hp, keterangan, status FROM customer";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                    // output data of each row
                    while($row = $result->fetch_assoc()) {
                        echo "<tr class='customer-row' data-full-address='" . $row["alamat2"] . "' data-keterangan='" . $row["keterangan"] . "'>";
                        echo "<th scope='row'>" . $row["id"] . "</th>";
                        echo "<td>" . $row["nama"] . "</td>";
                        echo "<td>" . $row["alamat"] . "</td>";
                        echo "<td>" . $row["no_hp"] . "</td>";
                        echo "<td>" . $row["status"] . "</td>";
                        echo "<td><button type='button' class='btn btn-warning show-modal-button'><i class='fas fa-clipboard-check'></i></button></td>";
                        echo "</tr>";
                    }
                    } else {
                    echo "0 results";
                    }
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<!-- DataTables JS -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>
</html>

<script src="client.js"></script>