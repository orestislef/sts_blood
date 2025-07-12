<?php
session_start();

// Password protection
if (!isset($_SESSION['authenticated'])) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $password = $_POST['password'];
        if ($password === 'orestislef') {
            $_SESSION['authenticated'] = true;
        } else {
            $loginError = true;
        }
    }
    
    if (!isset($_SESSION['authenticated'])) {
        ?>
        <!DOCTYPE html>
        <html lang="el">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Σύνδεση Διαχειριστή</title>
            <link rel="icon" type="image/x-icon" href="favicon.ico">
            <style>
                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }

                body {
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    min-height: 100vh;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    padding: 20px;
                }

                .login-container {
                    background: rgba(255, 255, 255, 0.95);
                    backdrop-filter: blur(10px);
                    border-radius: 20px;
                    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
                    padding: 40px;
                    width: 100%;
                    max-width: 400px;
                    text-align: center;
                }

                .lock-icon {
                    width: 80px;
                    height: 80px;
                    background: linear-gradient(135deg, #ff6b6b, #ee5a52);
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin: 0 auto 30px;
                    box-shadow: 0 10px 20px rgba(238, 90, 82, 0.3);
                    font-size: 32px;
                    color: white;
                }

                h1 {
                    color: #2c3e50;
                    font-size: 24px;
                    font-weight: 700;
                    margin-bottom: 30px;
                    background: linear-gradient(135deg, #667eea, #764ba2);
                    -webkit-background-clip: text;
                    -webkit-text-fill-color: transparent;
                    background-clip: text;
                }

                .input-group {
                    margin-bottom: 25px;
                    text-align: left;
                }

                label {
                    display: block;
                    margin-bottom: 8px;
                    color: #555;
                    font-weight: 600;
                    font-size: 14px;
                }

                input[type="password"] {
                    width: 100%;
                    padding: 15px 20px;
                    border: 2px solid #e0e6ed;
                    border-radius: 12px;
                    font-size: 16px;
                    transition: all 0.3s ease;
                    background: #f8f9fa;
                }

                input[type="password"]:focus {
                    outline: none;
                    border-color: #667eea;
                    background: white;
                    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
                    transform: translateY(-2px);
                }

                button {
                    width: 100%;
                    padding: 15px;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                    border: none;
                    border-radius: 12px;
                    font-size: 16px;
                    font-weight: 600;
                    cursor: pointer;
                    transition: all 0.3s ease;
                }

                button:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
                }

                .error {
                    background: #fee;
                    border: 1px solid #fcc;
                    color: #c66;
                    padding: 15px;
                    border-radius: 8px;
                    margin-bottom: 20px;
                    animation: shake 0.5s ease-in-out;
                }

                @keyframes shake {
                    0%, 100% { transform: translateX(0); }
                    25% { transform: translateX(-5px); }
                    75% { transform: translateX(5px); }
                }
            </style>
        </head>
        <body>
            <div class="login-container">
                <div class="lock-icon">🔒</div>
                <h1>Πρόσβαση Διαχειριστή</h1>
                <?php if (isset($loginError)): ?>
                    <div class="error">Λάθος κωδικός πρόσβασης!</div>
                <?php endif; ?>
                <form method="POST">
                    <div class="input-group">
                        <label for="password">Κωδικός Πρόσβασης</label>
                        <input type="password" id="password" name="password" required autofocus>
                    </div>
                    <button type="submit">Σύνδεση</button>
                </form>
            </div>
        </body>
        </html>
        <?php
        exit;
    }
}

// Include database configuration
include 'db_config.php';

// Default sorting parameters
$columns = ['fullName', 'telephoneNumber', 'email', 'dateOfRegister'];
$orderBy = 'dateOfRegister';
$orderDir = 'DESC';

// Check if sorting parameters are provided in the URL
if (isset($_GET['column']) && in_array($_GET['column'], $columns)) {
    $orderBy = $_GET['column'];
}
if (isset($_GET['dir']) && in_array($_GET['dir'], ['ASC', 'DESC'])) {
    $orderDir = $_GET['dir'];
}

// Toggle sorting direction
$newOrderDir = $orderDir === 'ASC' ? 'DESC' : 'ASC';

// Create connection to MySQL
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Σφάλμα σύνδεσης: " . $conn->connect_error);
}

// Count the total number of donors
$countSql = "SELECT COUNT(*) as totalDonors FROM donors";
$countResult = $conn->query($countSql);
$totalDonors = $countResult->fetch_assoc()['totalDonors'];

// Fetch registrations with dynamic sorting
$sql = "SELECT fullName, telephoneNumber, email, dateOfRegister FROM donors ORDER BY $orderBy $orderDir";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Πίνακας Δοτών Αίματος</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px 20px 0 0;
            padding: 30px 40px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            color: #2c3e50;
            font-size: 32px;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 10px;
        }

        .stats {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
            margin-top: 20px;
        }

        .stat-item {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 15px 25px;
            border-radius: 50px;
            font-weight: 600;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .stat-number {
            font-size: 24px;
            font-weight: 700;
        }

        .logout-btn {
            position: absolute;
            top: 30px;
            right: 30px;
            background: rgba(231, 76, 60, 0.9);
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .logout-btn:hover {
            background: rgba(231, 76, 60, 1);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.4);
        }

        .table-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 0 0 20px 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        th {
            padding: 20px 15px;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        th:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        th::after {
            content: "⇅";
            position: absolute;
            right: 10px;
            opacity: 0.7;
            font-size: 12px;
        }

        tbody tr {
            border-bottom: 1px solid #eee;
            transition: all 0.3s ease;
        }

        tbody tr:hover {
            background: rgba(102, 126, 234, 0.05);
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        td {
            padding: 20px 15px;
            color: #555;
            font-size: 14px;
        }

        .contact-cell {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .contact-link {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .contact-link:hover {
            color: #764ba2;
        }

        .copy-btn {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.3s ease;
            opacity: 0;
        }

        tr:hover .copy-btn {
            opacity: 1;
        }

        .copy-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 10px rgba(40, 167, 69, 0.3);
        }

        .no-data {
            text-align: center;
            padding: 60px 20px;
            color: #666;
            font-size: 18px;
        }

        .no-data-icon {
            font-size: 48px;
            color: #ccc;
            margin-bottom: 20px;
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .container {
                padding: 0;
            }
            
            .header {
                border-radius: 0;
                padding: 20px;
            }
            
            .header h1 {
                font-size: 24px;
            }
            
            .logout-btn {
                position: static;
                margin-top: 15px;
                width: auto;
            }
            
            .table-container {
                border-radius: 0;
                overflow-x: auto;
            }
            
            table {
                min-width: 600px;
            }
            
            th, td {
                padding: 15px 10px;
                font-size: 12px;
            }
            
            .contact-cell {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }
        }

        /* Animation for new entries */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        tbody tr {
            animation: slideIn 0.5s ease;
        }

        /* Success notification */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 15px 25px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(40, 167, 69, 0.3);
            transform: translateX(400px);
            transition: transform 0.3s ease;
            z-index: 1000;
        }

        .notification.show {
            transform: translateX(0);
        }
    </style>
    <script>
        function copyToClipboard(text, button) {
            navigator.clipboard.writeText(text).then(function() {
                showNotification('Αντιγράφηκε: ' + text);
                
                // Visual feedback on button
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="fas fa-check"></i>';
                button.style.background = 'linear-gradient(135deg, #28a745, #20c997)';
                
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.style.background = 'linear-gradient(135deg, #28a745, #20c997)';
                }, 1000);
            }).catch(function() {
                // Fallback for older browsers
                const textarea = document.createElement('textarea');
                textarea.value = text;
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
                showNotification('Αντιγράφηκε: ' + text);
            });
        }

        function showNotification(message) {
            // Remove existing notification
            const existing = document.querySelector('.notification');
            if (existing) existing.remove();
            
            // Create new notification
            const notification = document.createElement('div');
            notification.className = 'notification';
            notification.innerHTML = '<i class="fas fa-check"></i> ' + message;
            document.body.appendChild(notification);
            
            // Show notification
            setTimeout(() => notification.classList.add('show'), 100);
            
            // Hide notification after 3 seconds
            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // Logout function
        function logout() {
            if (confirm('Είστε σίγουροι ότι θέλετε να αποσυνδεθείτε;')) {
                window.location.href = '?logout=1';
            }
        }
    </script>
</head>
<body>

<?php
// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}
?>

<div class="container">
    <div class="header">
        <button class="logout-btn" onclick="logout()">
            <i class="fas fa-sign-out-alt"></i> Αποσύνδεση
        </button>
        
        <h1><i class="fas fa-tint"></i> Πίνακας Δοτών Αίματος</h1>
        
        <div class="stats">
            <div class="stat-item">
                <div class="stat-number"><?php echo $totalDonors; ?></div>
                <div>Συνολικοί Δότες</div>
            </div>
        </div>
    </div>

    <div class="table-container">
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th onclick="window.location.href='?column=fullName&dir=<?php echo $newOrderDir; ?>'">
                            <i class="fas fa-user"></i> Ονοματεπώνυμο
                        </th>
                        <th onclick="window.location.href='?column=telephoneNumber&dir=<?php echo $newOrderDir; ?>'">
                            <i class="fas fa-phone"></i> Τηλέφωνο
                        </th>
                        <th onclick="window.location.href='?column=email&dir=<?php echo $newOrderDir; ?>'">
                            <i class="fas fa-envelope"></i> Email
                        </th>
                        <th onclick="window.location.href='?column=dateOfRegister&dir=<?php echo $newOrderDir; ?>'">
                            <i class="fas fa-calendar"></i> Ημερομηνία Εγγραφής
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <strong><?php echo htmlspecialchars($row['fullName']); ?></strong>
                            </td>
                            <td>
                                <div class="contact-cell">
                                    <a href="tel:<?php echo htmlspecialchars($row['telephoneNumber']); ?>" class="contact-link">
                                        <i class="fas fa-phone"></i> <?php echo htmlspecialchars($row['telephoneNumber']); ?>
                                    </a>
                                    <button class="copy-btn" onclick="copyToClipboard('<?php echo htmlspecialchars($row['telephoneNumber']); ?>', this)">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </td>
                            <td>
                                <div class="contact-cell">
                                    <a href="mailto:<?php echo htmlspecialchars($row['email']); ?>" class="contact-link">
                                        <i class="fas fa-envelope"></i> <?php echo htmlspecialchars($row['email']); ?>
                                    </a>
                                    <button class="copy-btn" onclick="copyToClipboard('<?php echo htmlspecialchars($row['email']); ?>', this)">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </td>
                            <td>
                                <i class="fas fa-calendar-alt"></i> 
                                <?php echo date('d/m/Y H:i', strtotime($row['dateOfRegister'])); ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-data">
                <div class="no-data-icon">
                    <i class="fas fa-inbox"></i>
                </div>
                <div>Δεν υπάρχουν εγγραφές προς το παρόν.</div>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>