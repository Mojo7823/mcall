<?php
include 'config.php';

$kode_pel = $_POST['kode_pel'];
$nama = $_POST['nama'];
$alamat = $_POST['alamat'];
$alamat2 = $_POST['alamat2'];
$no_hp = $_POST['no_hp'];
$keterangan = $_POST['keterangan'];
$status = $_POST['status'];

$sql = "INSERT INTO customer (kode_pel, nama, alamat, alamat2, no_hp, keterangan, status) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssss", $kode_pel, $nama, $alamat, $alamat2, $no_hp, $keterangan, $status);
$stmt->execute();

// Fetch all rows
$sql = "SELECT id, kode_pel, nama, alamat, alamat2, no_hp, keterangan, status FROM customer";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$rows = $result->fetch_all(MYSQLI_ASSOC);

// Notify the WebSocket server about the update
$ch = curl_init('http://localhost:8080/update');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($rows));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen(json_encode($rows))
));
$result = curl_exec($ch);

$conn->close();
?>