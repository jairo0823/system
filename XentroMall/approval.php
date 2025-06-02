<?php 
require '../PHPMailer-master/src/Exception.php';
require '../PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


// Database configuration
$host = 'localhost';
$db   = 'xentromall';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}



if (isset($_POST['submit'])) { 
    $type = $_POST['type'] ?? 'application';
    $id = $_POST['application_id'] ?? null;
    $status = $_POST['status'] ?? null;
    $remarks = $_POST['remarks'] ?? '';

    if ($type === 'application') {
        // Update tenant application status and feedback
        $sql = 'UPDATE tenant_details SET status = :status, admin_feedback = :admin_feedback WHERE id = :id'; 
        $stmt = $pdo->prepare($sql); 
        $data = [ 
            'id' => $id, 
            'status' => $status, 
            'admin_feedback' => $remarks
        ]; 
        $results = $stmt->execute($data); 

        // Fetch tenant email from database
        $stmtEmail = $pdo->prepare('SELECT email FROM tenant_details WHERE id = :id');
        $stmtEmail->execute(['id' => $id]);
        $tenantEmail = $stmtEmail->fetchColumn();

        $subject = 'Application Status Update';
        $body = "Application ID: $id<br>Status: $status<br>Remarks: $remarks";
    } elseif ($type === 'payment') {
        // Update payment status and remarks
        $sql = 'UPDATE payments SET status = :status, remarks = :remarks WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        $data = [
            'id' => $id,
            'status' => $status,
            'remarks' => $remarks
        ];
        if (!$stmt->execute($data)) {
            error_log("Failed to update payment status for payment ID $id");
            // Optionally, you can add more error handling or user feedback here
        }

        // Fetch tenant email from database
        $stmtEmail = $pdo->prepare('SELECT u.email FROM payments p JOIN users u ON p.user_id = u.id WHERE p.id = :id');
        $stmtEmail->execute(['id' => $id]);
        $tenantEmail = $stmtEmail->fetchColumn();

        $subject = 'Payment Status Update';
        $body = "Payment ID: $id<br>Status: $status<br>Remarks: $remarks";

        // Insert notification for tenant
        $stmtUser = $pdo->prepare('SELECT user_id FROM payments WHERE id = :id');
        $stmtUser->execute(['id' => $id]);
        $userId = $stmtUser->fetchColumn();

        if ($userId) {
            $notificationMessage = "Your payment (ID: $id) has been $status. Remarks: $remarks";
            $stmtNotif = $pdo->prepare('INSERT INTO notifications (user_id, message, is_read, created_at) VALUES (?, ?, 0, NOW())');
            $stmtNotif->execute([$userId, $notificationMessage]);
        }
    } else {
        // Unknown type
        header('Location: admin_dashboard.php');
        exit;
    }

    // Send email notification
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'jairopogirobiso@gmail.com'; // Your Gmail address
        $mail->Password = 'wedi stuc gbbz qisl'; // Your Gmail password or App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        //Recipients
        $mail->setFrom('jairopogirobiso@gmail.com', 'XentroMall'); // Sender's email address and name
        $mail->addAddress($tenantEmail); // Send to tenant's email address

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        // Additional headers for better deliverability
        $mail->addCustomHeader('X-Mailer', 'PHP/' . phpversion());
        $mail->addCustomHeader('MIME-Version', '1.0');
        $mail->addCustomHeader('Content-Type', 'text/html; charset=UTF-8');

        // Enable SMTP debug output
        $mail->SMTPDebug = 2; // 0 = off, 1 = client messages, 2 = client and server messages
        $mail->Debugoutput = function($str, $level) {
            error_log("SMTP Debug level $level; message: $str");
        };

        // Send the email
        if(!$mail->send()) {
            error_log('Mailer Error: ' . $mail->ErrorInfo);
            echo 'Email could not be sent. Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo 'Email sent successfully';
        }
    } catch (Exception $e) {
        error_log("Exception caught while sending email: " . $e->getMessage());
        echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

    // Redirect after processing
    header('Location: admin_dashboard.php'); // Change to your desired page
    exit; // Ensure no further code is executed after redirection
} 
?>

<!DOCTYPE html> 
<html lang="en"> 
<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Form Submission</title> 
    <style> 
        body { 
            font-family: Arial, sans-serif; 
            margin: 20px; 
        } 
        .form-container { 
            max-width: 400px; 
            margin: auto; 
            padding: 20px; 
            border: 1px solid #ccc; 
            border-radius: 5px; 
            background-color: #f9f9f9; 
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); 
        } 
        .form-label { 
            display: block; 
            margin-bottom: 8px; 
            font-weight: bold; 
        } 
        .form-select, 
        input[type="text"] { 
            width: 100%; 
            padding: 8px; 
            margin-bottom: 15px; 
            border: 1px solid #ccc; 
            border-radius: 4px; 
        } 
        input[type="submit"] { 
            background-color: #4CAF50; 
            color: white; 
            padding: 10px 15px; 
            border: none; 
            border-radius: 4px; 
            cursor: pointer; 
        } 
        input[type="submit"]:hover { 
            background-color: #45a049; 
        } 
    </style> 
</head> 
<body> 

<!-- Home Button -->
<button class="home-btn" onclick="goHome()">
    <span class="material-icons">back</span>
</button>

<script>
    // Function to go to the home page
    function goHome() {
        window.location.href = 'admin_dashboard.php'; // Change to your home page URL
    }
</script>

<div class="form-container"> 
    <form action="approval.php" method="post"> 
        <div class="mb-3"> 
            <label for="id" class="form-label">ID</label> 
<input type="text" name="application_id" id="id" value="<?= isset($_GET['id']) ? htmlspecialchars($_GET['id']) : '' ?>" readonly> 
        </div> 
        <div class="mb-3"> 
            <label for="status" class="form-label">Status</label> 
            <select class="form-select" name="status" id="status" required> 
                <option selected disabled>Select one</option> 
                <option value="approved">Approved</option> 
                <option value="declined">Declined</option> 
            </select> 
        </div> 
        <div class="mb-3"> 
            <label for="remarks" class="form-label">Remarks</label> 
            <input type="text" name="remarks" id="remarks" placeholder="Enter your remarks here" required> 
        </div> 
        <input type="submit" value="Submit" name="submit"> 
    </form> 
</div> 
</body> 
</html>
