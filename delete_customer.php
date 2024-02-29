<?php
include 'config.php';

if (!isset($_POST['id'])) {
    die('No ID provided for deletion');
}

$id = $_POST['id'];

$sql = "DELETE FROM customer WHERE id = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die('Failed to prepare the SQL query');
}

$stmt->bind_param("s", $id); // Change "i" to "s"
$executionResult = $stmt->execute();

if ($executionResult === false) {
    die('Failed to execute the SQL query');
}

// Fetch all rows
$sql = "SELECT id,kode_pel, nama, alamat, alamat2, no_hp, keterangan, status FROM customer";
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