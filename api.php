<?php
header('Content-Type: application/json');

// Retrieve the API-KEY from URL
if (!isset($_GET['API-KEY'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Authorization failed']);
    exit;
}

// Database connection using PDO for PostgreSQL - Returns 'pdo' object
require_once("connectPSQL.php");

// Sanitize the API key input
$apiKey = preg_replace('/[^a-zA-Z0-9_]/', '', $_GET['API-KEY']);

// Validate API Key
$apiQuery = "SELECT vendor_id FROM api_keys WHERE api_key = :api_key";
$apiStmt = $pdo->prepare($apiQuery);
$apiStmt->execute([':api_key' => $apiKey]);
$vendor = $apiStmt->fetch();

// Throw Error if vendor not found
if (!$vendor) {
    http_response_code(403);
    echo json_encode(['error' => 'Invalid API key']);
    exit;
}

$vendorId = $vendor['vendor_id'];

// Fetch query parameters
$query = "SELECT * FROM applicants WHERE id > 0";
$params = [];

// Filter user inputs and update Query String and Params
if (isset($_GET['county'])){
    if ($_GET['county'] != 0) {
        $query .= ' AND county = :county';
        $clean_county = preg_replace('/[^a-zA-Z0-9_ ]/', '', $_GET['county']);
        $params[':county'] = $clean_county;
    }
}
if (isset($_GET['dbsRequired'])){
    if ($_GET['dbsRequired'] != 0) {
        $query .= ' AND require_dbs_check = :dbs';
        $clean_dbs = $_GET['dbsRequired'] == 'True' ? 'true' : 'false';
        $params[':dbs'] = $clean_dbs;
    }
}
if (isset($_GET['appliedFor'])){
    if ($_GET['appliedFor'] != 0) {
        $query .= ' AND applied_for = :appliedFor';
        $clean_appliedFor = preg_replace('/[^a-zA-Z0-9_ ]/', '', $_GET['appliedFor']);
        $params[':appliedFor'] = $clean_appliedFor;
    }
}

// If main Admin, show all results otherwise ONLY show results for specific vendor
if ($vendorId != 1){
    $query .= ' AND vendor_id = :vendor_id';
    $params[':vendor_id'] = $vendorId;
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);

$customers = $stmt->fetchAll();

echo json_encode($customers);
?>