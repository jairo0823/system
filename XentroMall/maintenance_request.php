<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Since maintenance_requests table uses tenant_name, no tenant_id foreign key
    $tenantName = $_SESSION['username'] ?? '';
    $unitNumber = $_POST['unit_number'] ?? '';
    $issueDescription = $_POST['issue_description'] ?? '';
    $category = $_POST['category'] ?? '';
    $urgency = $_POST['urgency'] ?? '';
    $photos = [];

    if (!empty($_FILES['photos']['name'][0])) {
        $uploadDir = 'uploads/maintenance_requests/' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $tenantName) . '/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        foreach ($_FILES['photos']['tmp_name'] as $key => $tmpName) {
            $fileName = basename($_FILES['photos']['name'][$key]);
            $targetFile = $uploadDir . uniqid() . '_' . $fileName;
            if (move_uploaded_file($tmpName, $targetFile)) {
                $photos[] = $targetFile;
            }
        }
    }

    $photosJson = !empty($photos) ? json_encode($photos) : null;

$stmt = $pdo->prepare("INSERT INTO maintenance_requests (tenant_name, unit_number, issue_description, category, urgency, photos, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->execute([$tenantName, $unitNumber, $issueDescription, $category, $urgency, $photosJson, 'pending']);

    $_SESSION['maintenance_success_message'] = "Maintenance request submitted successfully.";
    header("Location: tenant_dashboard.php?page=maintenance_requests");
    exit;
} else {
    header("Location: tenant_dashboard.php");
    exit;
}
