
$(document).ready(function () {
    var ws = new WebSocket('ws://localhost:8080');

        ws.onmessage = function(event) {
        var data = JSON.parse(event.data);
        if (!Array.isArray(data)) {
            data = [data];
        }
        var tbody = $('.table tbody');
        var selectedId = $('.customer-row.selected').find('th').text();
        tbody.empty();
        data.forEach(function(row) {
            var tr = $('<tr class="customer-row"></tr>');
            tr.append('<th scope="row">' + row.id + '</th>');
            tr.append('<td>' + row.nama + '</td>');
            tr.append('<td>' + row.alamat + '</td>');
            tr.append('<td>' + row.no_hp + '</td>');
            tr.append('<td>' + row.status + '</td>');
            tr.append('<td><button type="button" class="btn btn-warning show-modal-button"><i class="fas fa-clipboard-check"></i></button></td>');
            // Add the missing data attributes here
            tr.data('full-address', row.alamat2);
            tr.data('keterangan', row.keterangan);
            if (row.id == selectedId) {
                tr.addClass('selected');
            }
            tbody.append(tr);
        });
    };

    $('.table').DataTable();

    $('body').on('click', '.customer-row', function () {
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

    $('body').on('click', '.show-modal-button', function (event) {
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
                            $('#highlight-customer').html('Nama: ' + selected.find('td:nth-child(2)').text() + '<br>Alamat: ' + selected.find('td:nth-child(3)').text() + '<br>No Hp: ' + selected.find('td:nth-child(4)').text() + '<br>Status: ' + status);
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
});