<?php
include 'config.php';

$id = $_POST['id'];
$status = $_POST['status'];

$sql = "UPDATE customer SET status = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $status, $id);
$stmt->execute();

// Fetch all rows
$sql = "SELECT * FROM customer";
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