<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$paymentId = $_GET['id'] ?? null;
$status = $_GET['status'] ?? null;

if (!$paymentId || !in_array($status, ['approved', 'declined'])) {
    header('Location: admin_dashboard.php');
    exit;
}

try {
    // Check current payment status
    $stmtCheck = $pdo->prepare("SELECT status, user_id FROM payments WHERE id = ?");
    $stmtCheck->execute([$paymentId]);
    $payment = $stmtCheck->fetch();

    if (!$payment) {
        // Payment not found, redirect
        header('Location: admin_dashboard.php');
        exit;
    }

    if (in_array($payment['status'], ['approved', 'declined'])) {
        // Already approved or declined, do not update again
        header('Location: admin_dashboard.php');
        exit;
    }

    // Update payment status
    $stmt = $pdo->prepare("UPDATE payments SET status = ? WHERE id = ?");
    $stmt->execute([$status, $paymentId]);

    $userId = $payment['user_id'];

    if ($userId) {
        if ($status === 'approved') {
            $message = "Your payment has been completed.";
        } elseif ($status === 'declined') {
            $message = "Your payment has been declined.";
        } else {
            $message = "Your payment status has been updated.";
        }
        $stmtNotif = $pdo->prepare("INSERT INTO notifications (user_id, message, is_read, created_at) VALUES (?, ?, 0, NOW())");
        $stmtNotif->execute([$userId, $message]);
    }
} catch (Exception $e) {
    // Log error or handle as needed
}

header('Location: admin_dashboard.php');
exit;
?>
