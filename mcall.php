<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paku Baja Agung Customer</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 5 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.css">
    <style>
    .selected {
        background-color: #ddd;
    }
    #highlight-customer {
    font-size: 2em; /* Increase the font size */
    text-align: center; /* Center the text */
    background-color: gray; /* Set the background color to gray */
    color: white; /* Set the text color to white */
    }
    </style>
</head>
<body>

<!-- Highlight Customer Name -->
<div class="container mt-5" id="highlight-customer">
</div>

<!-- Buttons Container -->
<div class="container mt-5">
    <div class="row justify-content-center mb-3">
        <div class="col-auto">
            <a id="call-button" href="#" class="btn btn-primary btn-lg">Panggil Telpon &nbsp; &nbsp;<i class="fas fa-phone"></i></a>
        </div>
        <div class="col-auto">
            <a id="whatsapp-button" href="#" target="_blank" class="btn btn-success btn-lg">Panggil Whatsapp &nbsp; &nbsp; <i class="fab fa-whatsapp"></i></a>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-auto">
            <!-- Add id="prev-button" here -->
            <button id="prev-button" type="button" class="btn btn-warning btn-lg">Sebelumnya<i class="fa-solid fa-right-long"></i></button>
        </div>
        <div class="col-auto">
            <!-- Add id="next-button" here -->
            <button id="next-button" type="button" class="btn btn-warning btn-lg">Berikutnya<i class="fa-solid fa-right-long"></i></button>
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
                    <tr class="customer-row" data-full-address="Jl Merdeka Baru, Gunung Anyar, Surabaya" data-keterangan="Bangkrut sejak 2021">
                        <th scope="row">1</th>
                        <td>John Doe</td>
                        <td>Surabaya</td>
                        <td>62887311840</td>
                        <td>Active</td>
                        <td>
                            <button type="button" class="btn btn-warning show-modal-button"><i class="fas fa-clipboard-check"></i></button>
                        </td>
                    </tr>

                    <tr class="customer-row" data-full-address="Jl Kusuma Bangsa, Tangerang" data-keterangan="Pindah ke Jakarta">
                        <th scope="row">2</th>
                        <td>Jane Doe</td>
                        <td>Tangerang</td>
                        <td>628143584130</td>
                        <td>Active</td>
                        <td>

                            <button type="button" class="btn btn-warning show-modal-button"><i class="fas fa-clipboard-check"></i></button>
                        </td>
                    </tr>

                    <tr class="customer-row" data-full-address="Jl Sudirman, Jakarta" data-keterangan="Pindah ke Surabaya">
                        <th scope="row">3</th>
                        <td>John Smith</td>
                        <td>Jakarta</td>
                        <td>6282230751123</td>
                        <td>Active</td>
                        <td>
                            <button type="button" class="btn btn-warning show-modal-button"><i class="fas fa-clipboard-check"></i></button>
                        </td>
                    </tr>
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
            html: 'Nama: ' + name + '<br>Alamat: ' + alamat + '<br>No Hp: ' + phone + '<br>Status: ' + status + '<br>Full Address: ' + fullAddress + '<br>Keterangan: ' + keterangan,
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
</script>
</body>
</html>
