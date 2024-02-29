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

<script>
    $(document).ready( function () {
    $('.table').DataTable();

    $('.customer-row').click(function () {
        $('.customer-row').removeClass('selected');
        $(this).addClass('selected');

        var name = $(this).find('td:nth-child(2)').text();
        var alamat = $(this).find('td:nth-child(3)').text();
        var phone = $(this).find('td:nth-child(4)').text();
        var status = $(this).find('td:nth-child(5)').text();

        $('#highlight-customer').html('Nama: ' + name + '<br>Alamat: ' + alamat + '<br>No Hp: ' + phone + '<br>Status: ' + status);

        $('#call-button').attr('href', 'tel:' + phone);
        $('#whatsapp-button').attr('href', 'https://wa.me/' + phone);
    });

    $('.show-modal-button').click(function (event) {
    event.stopPropagation(); // Prevent triggering the row click event

    var row = $(this).closest('.customer-row'); // Get the closest row

    var name = row.find('td:nth-child(2)').text();
    var alamat = row.find('td:nth-child(3)').text();
    var phone = row.find('td:nth-child(4)').text();
    var status = row.find('td:nth-child(5)').text();

    var fullAddress = row.data('full-address');
    var keterangan = row.data('keterangan');

    Swal.fire({
        html: `
        <table class="table">
            <tr>
                <td>Nama</td>
                <td>${name}</td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>${alamat}</td>
            </tr>
            <tr>
                <td>No Hp</td>
                <td>${phone}</td>
            </tr>
            <tr>
                <td>Full Address</td>
                <td>${fullAddress}</td>
            </tr>
            <tr>
                <td>Keterangan</td>
                <td>${keterangan}</td>
            </tr>
            <tr>
                <td>Status</td>
                <td>${status}</td>
            </tr>
        </table>
        `,
        icon: 'info'
    });
});

    $('#whatsapp-button').click(function (event) {
        var selected = $('.customer-row.selected');
        if (selected.length === 0) {
            event.preventDefault();
        }
    });

    $('#next-button').click(function () {
        var selected = $('.customer-row.selected');
        if (selected.length === 0) {
            $('.customer-row:first').click();
        } else {
            selected.next('.customer-row').click();
        }
    });

    $('#prev-button').click(function () {
        var selected = $('.customer-row.selected');
        if (selected.length === 0) {
            $('.customer-row:first').click();
        } else {
            selected.prev('.customer-row').click();
        }
    });
});

$('#change-status-button').click(function (event) {
    var selected = $('.customer-row.selected');
    if (selected.length === 0) {
        event.preventDefault();
    } else {
        var id = selected.find('th:nth-child(1)').text();

        Swal.fire({
            title: 'Change Status',
            input: 'text',
            inputPlaceholder: 'Enter new status',
            showCancelButton: true,
            confirmButtonText: 'Kirim',
            cancelButtonText: 'Tutup',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'change_status.php',
                    type: 'POST',
                    data: {
                        id: id,
                        status: result.value
                    },
                    success: function(response) {
                        Swal.fire('Success', 'Status updated successfully', 'success');
                        location.reload(); // Reload the page to update the status in the table
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        Swal.fire('Error', 'Failed to update status', 'error');
                    }
                });
            }
        });
    }
});
</script>
