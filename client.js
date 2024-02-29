$(document).ready(function () {
    var ws = new WebSocket('ws://localhost:8080');
    $('.table').DataTable();
    ws.onmessage = function(event) {
        var data = JSON.parse(event.data);
        if (!Array.isArray(data)) {
            data = [data];
        }
        var tbody = $('.table tbody');
        var selectedId = $('.customer-row.selected').find('th').text();
        tbody.empty();
        var counter = 1; // Initialize counter
        data.forEach(function(row) {
            var tr = $('<tr class="customer-row"></tr>');
            tr.append('<th scope="row">' + counter + '</th>'); // Use counter instead of id
            tr.append('<td>' + row.kode_pel + '</td>');
            tr.append('<td>' + row.nama + '</td>');
            tr.append('<td>' + row.alamat + '</td>');
            tr.append('<td>' + row.no_hp + '</td>');
            tr.append('<td>' + row.status + '</td>');
            tr.append('<td><button type="button" class="btn btn-warning show-modal-button"><i class="fas fa-clipboard-check"></i></button></td>');
            tr.data('full-address', row.alamat2);
            tr.data('keterangan', row.keterangan);
            tr.data('id', row.id);
            tbody.append(tr);
            counter++; // Increment counter
        });
    };

    $('#change-status-button').click(function (event) {
        var selected = $('.customer-row.selected');
        if (selected.length === 0) {
            event.preventDefault();
        } else {
            Swal.fire({
                title: 'Ganti Status',
                input: 'text',
                inputPlaceholder: 'Masukkan status baru',
                showCancelButton: true,
                confirmButtonText: 'Kirim',
                cancelButtonText: 'Tutup',
                preConfirm: function (status) {
                    return new Promise(function (resolve, reject) {
                        $.ajax({
                            url: 'update_status.php',
                            type: 'POST',
                            data: {
                                id: selected.find('th').text(),
                                status: status
                            },
                            success: function () {
                                selected.find('td:nth-child(5)').text(status);
                                $('#highlight-customer').html('Nama: ' + selected.find('td:nth-child(3)').text() + '<br>Alamat: ' + selected.find('td:nth-child(4)').text() + '<br>No Hp: ' + selected.find('td:nth-child(5)').text() + '<br>Status: ' + status);
                                resolve();
                            },
                            error: function () {
                                reject('Error updating status');
                            }
                        });
                    });
                }
            }).then(function (result) {
                if (result.isConfirmed) {
                    Swal.fire('Updated!', 'The status has been updated.', 'success');
                }
            });
        }
    });








    $('body').on('click', '#remove-button', function () {
        var selected = $('.customer-row.selected');
        if (selected.length === 0) {
            Swal.fire('Error!', 'No customer selected.', 'error');
        } else {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'delete_customer.php',
                        type: 'POST',
                        data: {
                            id: selected.data('id')
                        },
                        success: function () {
                            Swal.fire('Deleted!', 'The customer has been deleted.', 'success');
                        },
                        error: function () {
                            Swal.fire('Error!', 'There was an error deleting the customer.', 'error');
                        }
                    });
                }
            });
        }
    });

    $('body').on('click', '.customer-row', function () {
        $('.customer-row').removeClass('selected');
        $(this).addClass('selected');

        var kode_pel = $(this).find('td:nth-child(2)').text();
        var name = $(this).find('td:nth-child(3)').text();
        var alamat = $(this).find('td:nth-child(4)').text();
        var phone = $(this).find('td:nth-child(5)').text();
        var status = $(this).find('td:nth-child(6)').text();

        $('#highlight-customer').html('Nama: ' + name + '<br>Alamat: ' + alamat + '<br>No Hp: ' + phone + '<br>Status: ' + status);

        $('#call-button').attr('href', 'tel:' + phone);
        $('#whatsapp-button').attr('href', 'https://wa.me/' + phone);
    });

    $('body').on('click', '.show-modal-button', function (event) {
        event.stopPropagation(); // Prevent triggering the row click event

        var row = $(this).closest('.customer-row'); // Get the closest row

        var kode_pel = row.find('td:nth-child(2)').text();
        var name = row.find('td:nth-child(3)').text();
        var alamat = row.find('td:nth-child(4)').text();
        var phone = row.find('td:nth-child(5)').text();
        var status = row.find('td:nth-child(6)').text();

        var fullAddress = row.data('full-address');
        var keterangan = row.data('keterangan');
        var id = row.data('id');

        Swal.fire({
            html: `
            <table class="table">
                <tr>
                    <td>ID</td>
                    <td>${id}</td>
                <tr>
                    <td>Kode Pelanggan</td>
                    <td>${kode_pel}</td>
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

$('#add-button').click(function () {
    Swal.fire({
        title: 'Tambah Pelanggan',
        html: `
            <input id="swal-input1" class="swal2-input" placeholder="Kode Pelanggan">
            <input id="swal-input2" class="swal2-input" placeholder="Nama Pelanggan">
            <input id="swal-input3" class="swal2-input" placeholder="Alamat">
            <input id="swal-input4" class="swal2-input" placeholder="Alamat Lengkap">
            <input id="swal-input5" class="swal2-input" placeholder="No HP">
            <input id="swal-input6" class="swal2-input" placeholder="Keterangan">
            <input id="swal-input7" class="swal2-input" placeholder="Status">
        `,
        focusConfirm: false,
        showCancelButton: true, // This will show the cancel button
        confirmButtonText: 'Tambahkan', // This will change the text of the confirm button
        cancelButtonText: 'Batal', // This will change the text of the cancel button
        preConfirm: () => {
            return [
                document.getElementById('swal-input1').value,
                document.getElementById('swal-input2').value,
                document.getElementById('swal-input3').value,
                document.getElementById('swal-input4').value,
                document.getElementById('swal-input5').value,
                document.getElementById('swal-input6').value,
                document.getElementById('swal-input7').value
            ]
        }
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'insert_customer.php',
                type: 'POST',
                data: {
                    kode_pel: result.value[0],
                    nama: result.value[1],
                    alamat: result.value[2],
                    alamat2: result.value[3],
                    no_hp: result.value[4],
                    keterangan: result.value[5],
                    status: result.value[6]
                },
                success: function () {
                    Swal.fire('Inserted!', 'The new customer has been added.', 'success');
                },
                error: function () {
                    Swal.fire('Error!', 'There was an error adding the new customer.', 'error');
                }
            });
        }
    });
});
});