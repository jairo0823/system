<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Fetch admin username and email from database
try {
    $stmt = $pdo->prepare('SELECT username, email FROM users WHERE id = :id AND role = :role');
    $stmt->execute(['id' => $_SESSION['user_id'], 'role' => 'admin']);
    $admin = $stmt->fetch();
    if ($admin) {
        $admin_username = htmlspecialchars($admin['username']);
        $admin_email = htmlspecialchars($admin['email']);
    } else {
        $admin_username = 'Admin';
        $admin_email = '';
    }
} catch (Exception $e) {
    $admin_username = 'Admin';
    $admin_email = '';
}

try {
    $stmtApps = $pdo->prepare("SELECT id, user_id, tradename, store_premises, store_location, ownership, company_name, business_address, tin, office_tel, tenant_representative, contact_person, position, contact_tel, mobile, email, prepared_by, business_type, documents, created_at FROM tenant_details ORDER BY created_at DESC");
    $stmtApps->execute();
    $applications = $stmtApps->fetchAll();

    // Fetch total tenants count
    $stmtTotalTenants = $pdo->prepare("SELECT COUNT(*) as total FROM tenant_details");
    $stmtTotalTenants->execute();
    $totalTenants = $stmtTotalTenants->fetchColumn();

    // Fetch unpaid tenants count
    $stmtUnpaidTenants = $pdo->prepare("SELECT COUNT(DISTINCT td.id) FROM tenant_details td LEFT JOIN payments p ON td.user_id = p.user_id AND p.status = 'approved' WHERE p.id IS NULL");
    $stmtUnpaidTenants->execute();
    $unpaidTenants = $stmtUnpaidTenants->fetchColumn();

    // Fetch paid tenants count
    $stmtPaidTenants = $pdo->prepare("SELECT COUNT(DISTINCT p.user_id) FROM payments p WHERE p.status = 'approved'");
    $stmtPaidTenants->execute();
    $paidTenants = $stmtPaidTenants->fetchColumn();

    // Fetch overall tenants paid count (total approved payments)
    $stmtOverallTenantsPaid = $pdo->prepare("SELECT COUNT(*) FROM payments WHERE status = 'approved'");
    $stmtOverallTenantsPaid->execute();
    $overallTenantsPaid = $stmtOverallTenantsPaid->fetchColumn();
} catch (Exception $e) {
    $applications = [];
    $totalTenants = 0;
    $unpaidTenants = 0;
}

try {
    $stmtMaint = $pdo->prepare("SELECT permit_no, date_filed, tenant_name, scope_of_work, security_posting, rate_security, charge_security, janitorial_deployment, rate_janitorial, charge_janitorial, maintenance, rate_maintenance, charge_maintenance, personnel, created_at FROM work_permits ORDER BY created_at DESC");
    $stmtMaint->execute();
    $maintenanceRequests = $stmtMaint->fetchAll();
} catch (Exception $e) {
    $maintenanceRequests = [];
}

// Fetch renewal requests with tenant username
try {
    $stmtRenewal = $pdo->prepare("SELECT rr.id, rr.tenant_id, rr.renewal_date, rr.submitted_at, u.username FROM renewal_requests rr JOIN users u ON rr.tenant_id = u.id ORDER BY rr.submitted_at DESC");
    $stmtRenewal->execute();
    $renewalRequests = $stmtRenewal->fetchAll();
} catch (Exception $e) {
    $renewalRequests = [];
}

// Fetch payments with username
try {
    $stmtPayments = $pdo->prepare("SELECT p.id, p.user_id, p.payment_image, p.payment_date, p.status, u.username FROM payments p JOIN users u ON p.user_id = u.id ORDER BY p.payment_date DESC");
    $stmtPayments->execute();
    $payments = $stmtPayments->fetchAll();
} catch (Exception $e) {
    $payments = [];
}
?>
<!DOCTYPE html>
<html lang="en">
 <head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1" name="viewport"/>
  <title>
   Admin Dashboard
  </title>
  <script src="https://cdn.tailwindcss.com">
  </script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&amp;display=swap" rel="stylesheet"/>
  <style>
   body {
      font-family: "Poppins", sans-serif;
    }
  </style>
 </head>
 <body class="bg-white min-h-screen p-6">
  <div class="flex gap-8 max-w-[1400px] mx-auto">
   <!-- Sidebar -->
   <aside class="bg-green-600 rounded-2xl w-64 p-6 flex flex-col gap-8 shadow-lg" style="box-shadow: 0 8px 15px rgb(0 0 0 / 0.05)">
    <div class="flex items-center gap-3">
     <div class="bg-blue-600 p-3 rounded-md text-white">
      <i class="fas fa-wallet fa-lg">
      </i>
     </div>
     <h1 class="font-extrabold text-xl select-none text-white">
      Admin
     </h1>
     <button aria-label="Toggle menu" class="ml-auto text-2xl text-white hover:text-blue-300 relative" id="adminSettingsToggle">
      <i class="fas fa-ellipsis-v"></i>
     </button>
     <div id="adminSettingsMenu" class="hidden absolute right-6 top-12 bg-white rounded shadow-lg w-48 z-50">
      <a href="settings.php" class="block px-4 py-2 text-gray-700 hover:bg-green-600 hover:text-white">Settings</a>
      <a href="profile.php" class="block px-4 py-2 text-gray-700 hover:bg-green-600 hover:text-white">Profile</a>
      <a href="change_password.php" class="block px-4 py-2 text-gray-700 hover:bg-green-600 hover:text-white">Change Password</a>
     </div>
    </div>
     <div class="flex items-center gap-4 bg-green-700 rounded-xl p-4 border border-green-800">
      <div class="rounded-full bg-blue-600 text-white p-3">
       <i class="fas fa-user fa-lg"></i>
      </div>
      <div class="text-sm text-white">
       <p class="font-bold select-none">
        Welcome,
        <span class="select-none">
         <?php echo htmlspecialchars($admin_username); ?>
        </span>
       </p>
       <p class="select-none">
        <?php echo htmlspecialchars($admin_email); ?>
       </p>
      </div>
     </div>
    <nav class="flex flex-col gap-6 text-white select-none">
     <a class="flex items-center gap-3 hover:text-blue-300 transition cursor-pointer" id="viewDashboardLink">
      <i class="fas fa-tachometer-alt"></i>
      <span>Dashboard</span>
     </a>
     <a class="flex items-center gap-3 hover:text-blue-300 transition cursor-pointer" id="viewApplicationsLink">
      <i class="fas fa-file-alt"></i>
      <span>View Applications</span>
       <a class="flex items-center gap-3 hover:text-blue-300 transition" href="extended.php">
        <i class="fas fa-bullhorn"></i>
        <span>View Extended BIR</span>
     </a>
     <a class="flex items-center gap-3 hover:text-blue-300 transition cursor-pointer" id="viewMaintenanceLink">
      <i class="fas fa-tools"></i>
      <span>View Maintenance Requests</span>
     </a>
     <a class="flex items-center gap-3 hover:text-blue-300 transition cursor-pointer" id="viewRenewalLink">
      <i class="fas fa-sync-alt"></i>
      <span>View Renewal Requests</span>
     </a>
     <a class="flex items-center gap-3 hover:text-blue-300 transition cursor-pointer" id="viewPaymentsLink">
      <i class="fas fa-credit-card"></i>
      <span>View Payments</span>
     </a>
      <a class="flex items-center gap-3 hover:text-blue-300 transition" href="posting.php">
        <i class="fas fa-bullhorn"></i>
        <span> Post Announcement</span>
      </a>
     <a class="flex items-center gap-3 hover:text-blue-300 transition" href="form.php">
      <i class="fas fa-bullhorn"></i>
      <span> Send Message</span>
       </a>
      <a class="flex items-center gap-3 hover:text-blue-300 transition" href="admin_register.php">
        <i class="fas fa-bullhorn"></i>
        <span>Register Here</span>
      </a>
     </a> 
     <a class="flex items-center gap-3 hover:text-blue-300 transition" href="logout.php">
      <i class="fas fa-sign-out-alt"></i>
      <span>Logout</span>
     </a>
    </nav>
   </aside>
   <!-- Main content -->
   <main class="flex-1 flex flex-col gap-6 overflow-auto scrollbar-thin" style="max-height: 90vh;">
    <header class="flex items-center justify-between">
     <h2 class="font-extrabold text-2xl select-none text-gray-800" id="sectionTitle">
      Dashboard
     </h2>
    </header>

    <section id="dashboardSection" class="space-y-6">
      <div class="flex flex-wrap gap-6">
        <div id="totalTenantsBox" class="cursor-pointer flex-1 min-w-[220px] max-w-[280px] bg-gradient-to-tr from-green-700 to-blue-600 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
        <div class="absolute top-0 left-0 w-24 h-24 bg-gradient-to-tr from-blue-700 to-green-700 rounded-full opacity-30 -translate-x-12 -translate-y-12">
        </div>
        <div class="flex items-center gap-3 mb-4">
         <div class="bg-white/30 rounded-full p-3">
          <i class="fas fa-file-alt text-xl">
          </i>
         </div>
         <div class="text-2xl font-semibold select-none">
          <?php echo htmlspecialchars($totalTenants); ?>
         </div>
        </div>
         <p class="text-sm select-none">
          Total Tenants
         </p>
         <canvas id="totalTenantsChart" class="mt-4" style="max-width: 100%; height: 150px;"></canvas>
       </div>
       <div class="flex-1 min-w-[220px] max-w-[280px] bg-gradient-to-tr from-blue-600 to-green-700 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
        <div class="absolute top-0 left-0 w-24 h-24 bg-gradient-to-tr from-green-700 to-blue-600 rounded-full opacity-30 -translate-x-12 -translate-y-12">
        </div>
        <div class="flex items-center gap-3 mb-4">
         <div class="bg-white/30 rounded-full p-3">
          <i class="fas fa-check-circle text-xl">
          </i>
         </div>
         <div class="text-2xl font-semibold select-none">
          <?php echo htmlspecialchars($paidTenants); ?>
         </div>
        </div>
        <p class="text-sm select-none">
         Paid Tenants
        </p>
       </div>
       <div class="flex-1 min-w-[220px] max-w-[280px] bg-gradient-to-tr from-green-700 to-blue-600 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
        <div class="absolute top-0 left-0 w-24 h-24 bg-gradient-to-tr from-blue-700 to-green-700 rounded-full opacity-30 -translate-x-12 -translate-y-12">
        </div>
        <div class="flex items-center gap-3 mb-4">
         <div class="bg-white/30 rounded-full p-3">
          <i class="fas fa-times-circle text-xl">
          </i>
         </div>
         <div class="text-2xl font-semibold select-none">
          <?php echo htmlspecialchars($unpaidTenants); ?>
         </div>
        </div>
        <p class="text-sm select-none">
         Unpaid Tenants
        </p>
       </div>
       <div class="flex-1 min-w-[220px] max-w-[280px] bg-gradient-to-tr from-blue-600 to-green-700 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
        <div class="absolute top-0 left-0 w-24 h-24 bg-gradient-to-tr from-green-700 to-blue-600 rounded-full opacity-30 -translate-x-12 -translate-y-12">
        </div>
        <div class="flex items-center gap-3 mb-4">
         <div class="bg-white/30 rounded-full p-3">
          <i class="fas fa-file-alt text-xl">
          </i>
         </div>
         <div class="text-2xl font-semibold select-none">
          <?php echo htmlspecialchars($overallTenantsPaid); ?>
         </div>
        </div>
        <p class="text-sm select-none">
         Overall tenants Paid
        </p>
       </div>
      </div>
    </section>

    <section id="applicationsSection" class="hidden space-y-6 overflow-auto scrollbar-thin" style="max-height: 80vh;">
      <h1 class="font-bold text-2xl mb-4">Application Submissions</h1>
      <table class="min-w-full bg-white border border-gray-300 rounded shadow">
        <thead>
          <tr class="bg-green-600 text-white">
            <th class="border px-4 py-2">ID</th>
            <th class="border px-4 py-2">User ID</th>
            <th class="border px-4 py-2">Trade Name</th>
            <th class="border px-4 py-2">Store Premises</th>
            <th class="border px-4 py-2">Store Location</th>
            <th class="border px-4 py-2">Ownership</th>
            <th class="border px-4 py-2">Company Name</th>
            <th class="border px-4 py-2">Business Address</th>
            <th class="border px-4 py-2">TIN</th>
            <th class="border px-4 py-2">Office Tel</th>
            <th class="border px-4 py-2">Tenant Representative</th>
            <th class="border px-4 py-2">Contact Person</th>
            <th class="border px-4 py-2">Position</th>
            <th class="border px-4 py-2">Contact Tel</th>
            <th class="border px-4 py-2">Mobile</th>
            <th class="border px-4 py-2">Email</th>
            <th class="border px-4 py-2">Prepared By</th>
            <th class="border px-4 py-2">Business Type</th>
            <th class="border px-4 py-2">Created At</th>
            <th class="border px-4 py-2">Remarks</th>
            <th class="border px-4 py-2">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($applications): ?>
            <?php foreach ($applications as $app): ?>
              <tr class="hover:bg-green-50">
                <td class="border px-4 py-2"><?php echo htmlspecialchars($app['id']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($app['user_id']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($app['tradename']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($app['store_premises']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($app['store_location']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($app['ownership']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($app['company_name']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($app['business_address']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($app['tin']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($app['office_tel']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($app['tenant_representative']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($app['contact_person']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($app['position']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($app['contact_tel']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($app['mobile']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($app['email']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($app['prepared_by']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($app['business_type']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($app['created_at']); ?></td>
                <td class="border px-4 py-2 flex gap-2 justify-center">
                  <a href="approval.php?id=<?php echo urlencode($app['id']); ?>&status=approved" class="bg-green-600 text-white px-4 py-1 rounded-full hover:bg-green-700 transition flex items-center gap-2" style="text-decoration:none;">
                    <i class="fas fa-check"></i>
                    <span>Approve</span>
                  </a>
                  <a href="approval.php?id=<?php echo urlencode($app['id']); ?>&status=declined" class="bg-red-600 text-white px-4 py-1 rounded-full hover:bg-red-700 transition flex items-center gap-2" style="text-decoration:none;">
                    <i class="fas fa-times"></i>
                    <span>Decline</span>
                  </a>
                </td>
                <td class="border px-4 py-2">
                  <?php if (!empty($app['documents'])): ?>
                    <a href="<?php echo htmlspecialchars($app['documents']); ?>" target="_blank" class="text-blue-600 underline">View Documents</a>
                  <?php else: ?>
                    No Documents
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="21" class="border px-4 py-2 text-center">No application submissions found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </section>

    <section id="maintenanceSection" class="hidden space-y-6 overflow-auto scrollbar-thin" style="max-height: 80vh;">
      <h1 class="font-bold text-2xl mb-4">Work Permit</h1>

      <div class="bg-white p-6 rounded-lg shadow-md border border-green-600 mb-6 max-w-3xl">
        <form method="POST" action="maintenance_request.php" class="space-y-4">
          <div>
            <label for="maintenance_details" class="block font-semibold mb-1 text-green-700">Maintenance Details</label>
            <textarea id="maintenance_details" name="maintenance_details" required class="w-full p-2 border border-green-600 rounded"></textarea>
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label for="date_from" class="block font-semibold mb-1 text-green-700">Permit Valid From</label>
              <input type="date" id="date_from" name="date_from" required class="w-full p-2 border border-green-600 rounded" />
            </div>
            <div>
              <label for="date_to" class="block font-semibold mb-1 text-green-700">Permit Valid To</label>
              <input type="date" id="date_to" name="date_to" required class="w-full p-2 border border-green-600 rounded" />
            </div>
          </div>
          <div>
            <label for="tenant_email" class="block font-semibold mb-1 text-green-700">Tenant Email</label>
            <input type="email" id="tenant_email" name="tenant_email" required class="w-full p-2 border border-green-600 rounded" />
          </div>
          <div>
            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 transition">Submit</button>
          </div>
        </form>
      </div>

      <table class="min-w-full bg-white border border-gray-300 rounded shadow">
        <thead>
          <tr class="bg-green-600 text-white">
            <th class="border px-4 py-2">Permit No</th>
            <th class="border px-4 py-2">Date Filed</th>
            <th class="border px-4 py-2">Tenant Name</th>
            <th class="border px-4 py-2">Scope of Work</th>
            <th class="border px-4 py-2">Security Posting</th>
            <th class="border px-4 py-2">Rate Security</th>
            <th class="border px-4 py-2">Charge Security</th>
            <th class="border px-4 py-2">Janitorial Deployment</th>
            <th class="border px-4 py-2">Rate Janitorial</th>
            <th class="border px-4 py-2">Charge Janitorial</th>
            <th class="border px-4 py-2">Maintenance</th>
            <th class="border px-4 py-2">Rate Maintenance</th>
            <th class="border px-4 py-2">Charge Maintenance</th>
            <th class="border px-4 py-2">Personnel</th>
            <th class="border px-4 py-2">Created At</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($maintenanceRequests): ?>
            <?php foreach ($maintenanceRequests as $req): ?>
              <tr class="hover:bg-green-50">
                <td class="border px-4 py-2"><?php echo htmlspecialchars($req['permit_no']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($req['date_filed']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($req['tenant_name']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($req['scope_of_work']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($req['security_posting']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($req['rate_security']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($req['charge_security']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($req['janitorial_deployment']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($req['rate_janitorial']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($req['charge_janitorial']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($req['maintenance']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($req['rate_maintenance']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($req['charge_maintenance']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($req['personnel']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($req['created_at']); ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="15" class="border px-4 py-2 text-center">No maintenance requests found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </section>

    <section id="renewalSection" class="hidden space-y-6 overflow-auto scrollbar-thin" style="max-height: 80vh;">
      <h1 class="font-bold text-2xl mb-4">Renewal Requests</h1>
      <table class="min-w-full bg-white border border-gray-300 rounded shadow">
        <thead>
          <tr class="bg-green-600 text-white">
            <th class="border px-4 py-2">ID</th>
            <th class="border px-4 py-2">Tenant_id</th>
            <th class="border px-4 py-2">Renewal Date</th>
            <th class="border px-4 py-2">Submitted At</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($renewalRequests): ?>
            <?php foreach ($renewalRequests as $req): ?>
              <tr class="hover:bg-green-50">
                <td class="border px-4 py-2"><?php echo htmlspecialchars($req['id']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($req['username']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($req['renewal_date']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($req['submitted_at']); ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="4" class="border px-4 py-2 text-center">No renewal requests found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </section>

    <section id="paymentsSection" class="hidden space-y-6 overflow-auto scrollbar-thin" style="max-height: 80vh;">
      <h1 class="font-bold text-2xl mb-4">Payments</h1>
      <table class="min-w-full bg-white border border-gray-300 rounded shadow">
        <thead>
          <tr class="bg-green-600 text-white">
            <th class="border px-4 py-2">ID</th>
            <th class="border px-4 py-2">Tenant Username</th>
            <th class="border px-4 py-2">Payment Image</th>
            <th class="border px-4 py-2">Payment Date</th>
            <th class="border px-4 py-2">Status</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($payments): ?>
            <?php foreach ($payments as $pay): ?>
              <tr class="hover:bg-green-50">
                <td class="border px-4 py-2"><?php echo htmlspecialchars($pay['id']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($pay['username']); ?></td>
                <td class="border px-4 py-2"><a href="<?php echo htmlspecialchars($pay['payment_image']); ?>" target="_blank" class="text-blue-600 underline">View Image</a></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($pay['payment_date']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars(ucfirst($pay['status'])); ?></td>
                <td class="border px-4 py-2 flex gap-2 justify-center">
                  <a href="payment_approval.php?id=<?php echo urlencode($pay['id']); ?>&status=approved" class="bg-green-600 text-white px-4 py-1 rounded-full hover:bg-green-700 transition flex items-center gap-2" style="text-decoration:none;">
                    <i class="fas fa-check"></i>
                    <span>Approve</span>
                  </a>
                  <a href="payment_approval.php?id=<?php echo urlencode($pay['id']); ?>&status=declined" class="bg-red-600 text-white px-4 py-1 rounded-full hover:bg-red-700 transition flex items-center gap-2" style="text-decoration:none;">
                    <i class="fas fa-times"></i>
                    <span>Decline</span>
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="6" class="border px-4 py-2 text-center">No payments found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </section>

  </main>
  </div>

  <script>
    const viewDashboardLink = document.getElementById('viewDashboardLink');
    const viewApplicationsLink = document.getElementById('viewApplicationsLink');
    const viewMaintenanceLink = document.getElementById('viewMaintenanceLink');
    const viewRenewalLink = document.getElementById('viewRenewalLink');
    const viewPaymentsLink = document.getElementById('viewPaymentsLink');

    const dashboardSection = document.getElementById('dashboardSection');
    const applicationsSection = document.getElementById('applicationsSection');
    const maintenanceSection = document.getElementById('maintenanceSection');
    const renewalSection = document.getElementById('renewalSection');
    const paymentsSection = document.getElementById('paymentsSection');
    const sectionTitle = document.getElementById('sectionTitle');

    function hideAllSections() {
      dashboardSection.classList.add('hidden');
      applicationsSection.classList.add('hidden');
      maintenanceSection.classList.add('hidden');
      renewalSection.classList.add('hidden');
      paymentsSection.classList.add('hidden');
    }

    viewDashboardLink.addEventListener('click', () => {
      hideAllSections();
      dashboardSection.classList.remove('hidden');
      sectionTitle.textContent = 'Dashboard';
    });

    viewApplicationsLink.addEventListener('click', () => {
      hideAllSections();
      applicationsSection.classList.remove('hidden');
      sectionTitle.textContent = 'Application Submissions';
    });

    viewMaintenanceLink.addEventListener('click', () => {
      hideAllSections();
      maintenanceSection.classList.remove('hidden');
      sectionTitle.textContent = 'Maintenance Requests';
    });

    viewRenewalLink.addEventListener('click', () => {
      hideAllSections();
      renewalSection.classList.remove('hidden');
      sectionTitle.textContent = 'Renewal Requests';
    });

    viewPaymentsLink.addEventListener('click', () => {
      hideAllSections();
      paymentsSection.classList.remove('hidden');
      sectionTitle.textContent = 'Payments';
    });

    // Add click event listener for total tenants box
    const totalTenantsBox = document.getElementById('totalTenantsBox');
    totalTenantsBox.addEventListener('click', () => {
      hideAllSections();
      applicationsSection.classList.remove('hidden');
      sectionTitle.textContent = 'Tenant Representatives';
    });

    // Load Chart.js library
    const script = document.createElement('script');
    script.src = 'https://cdn.jsdelivr.net/npm/chart.js';
    script.onload = () => {
      const ctx = document.getElementById('totalTenantsChart').getContext('2d');
      const data = {
        labels: ['Paid Tenants', 'Unpaid Tenants'],
        datasets: [{
          data: [<?php echo $paidTenants; ?>, <?php echo $unpaidTenants; ?>],
          backgroundColor: ['#22c55e', '#3b82f6'],
          borderColor: ['#fff', '#fff'],
          hoverOffset: 30
        }]
      };
      const config = {
        type: 'doughnut',
        data: data,
        options: {
          responsive: true,
          plugins: {
            legend: {
              position: 'bottom',
              labels: {
                color: '#333',
                font: {
                  size: 14,
                  weight: 'bold'
                }
              }
            },
            tooltip: {
              enabled: true
            }
          }
        }
      };
      new Chart(ctx, config);
    };
    document.head.appendChild(script);
  </script>
  <script>
    // Toggle admin settings menu
    const adminSettingsToggle = document.getElementById('adminSettingsToggle');
    const adminSettingsMenu = document.getElementById('adminSettingsMenu');

    adminSettingsToggle.addEventListener('click', () => {
      adminSettingsMenu.classList.toggle('hidden');
    });

    // Close menu when clicking outside
    document.addEventListener('click', (event) => {
      if (!adminSettingsToggle.contains(event.target) && !adminSettingsMenu.contains(event.target)) {
        adminSettingsMenu.classList.add('hidden');
      }
    });
  </script>
 </body>
</html>
