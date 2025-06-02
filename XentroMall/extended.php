<!DOCTYPE html>
<html>
<head>
    <title>Extended BIR Approvals - Design Only</title>
    <style>
        table {
            border-collapse: collapse;
            width: 90%;
            margin: 20px auto;
        }
        th, td {
            border: 1px solid #aaa;
            padding: 8px 12px;
            text-align: left;
        }
        th {
            background-color: #eee;
        }
        .action-btn {
            padding: 6px 12px;
            margin-right: 5px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }
        .approve-btn {
            background-color: #4CAF50;
            color: white;
        }
        .decline-btn {
            background-color: #f44336;
            color: white;
        }
        .status-approved {
            color: green;
            font-weight: bold;
        }
        .status-declined {
            color: red;
            font-weight: bold;
        }
        .message {
            width: 90%;
            margin: 10px auto;
            padding: 10px;
            background-color: #dff0d8;
            color: #3c763d;
            border-radius: 4px;
        }
        .container {
            width: 90%;
            margin: 20px auto;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #4CAF50 0%, #007bff 100%);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.3);
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .info-text {
            text-align: center;
            margin-bottom: 20px;
            color: #555;
        }
        .button-group {
            text-align: center;
            margin-top: 20px;
        }
        .button-group button {
            padding: 10px 20px;
            margin: 0 10px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .approve-btn-simple {
            background-color: #4CAF50;
            color: white;
        }
        .decline-btn-simple {
            background-color: #f44336;
            color: white;
        }
        .back-button {
            display: block;
            width: 120px;
            margin: 20px auto;
            padding: 10px 0;
            text-align: center;
            background: linear-gradient(90deg, #4CAF50 0%, #007bff 100%);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(0, 123, 255, 0.4);
            transition: background 0.3s ease;
        }
        .back-button:hover {
            background: linear-gradient(90deg, #007bff 0%, #4CAF50 100%);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="header">Extended BIR Approvals - Design Only</h2>
        <p class="info-text">This is a static design preview of the Extended BIR approvals page with action buttons.</p>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tenant Name</th>
                    <th>BIR Details</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Sample static rows for design -->
                <tr>
                    <td>1</td>
                    <td>Juan Dela Cruz</td>
                    <td>Extended BIR Document.pdf</td>
                    <td><span class="status-approved">Pending</span></td>
                    <td>
                        <div class="button-group">
                            <button class="approve-btn-simple">Approve</button>
                            <button class="decline-btn-simple">Decline</button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Maria Santos</td>
                    <td>Extended BIR Document 2.pdf</td>
                    <td><span class="status-declined">Declined</span></td>
                    <td>
                        <div class="button-group">
                            <button class="approve-btn-simple" disabled>Approve</button>
                            <button class="decline-btn-simple" disabled>Decline</button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Carlos Reyes</td>
                    <td>Extended BIR Document 3.pdf</td>
                    <td><span class="status-approved">Approved</span></td>
                    <td>
                        <div class="button-group">
                            <button class="approve-btn-simple" disabled>Approve</button>
                            <button class="decline-btn-simple" disabled>Decline</button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <a href="admin_dashboard.php" class="back-button">Back to Dashboard</a>
    </div>
</body>
</html>
