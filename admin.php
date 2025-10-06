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

                :root {
                    --bg-primary: #f5f5f7;
                    --bg-secondary: #ffffff;
                    --text-primary: #1d1d1f;
                    --text-secondary: #86868b;
                    --border-color: #d2d2d7;
                    --input-bg: #ffffff;
                    --shadow: 0 4px 6px rgba(0, 0, 0, 0.07), 0 1px 3px rgba(0, 0, 0, 0.06);
                    --error-bg: #ffebee;
                    --error-border: #ffcdd2;
                    --error-text: #c62828;
                }

                @media (prefers-color-scheme: dark) {
                    :root {
                        --bg-primary: #000000;
                        --bg-secondary: #1c1c1e;
                        --text-primary: #f5f5f7;
                        --text-secondary: #98989d;
                        --border-color: #38383a;
                        --input-bg: #1c1c1e;
                        --shadow: 0 4px 6px rgba(0, 0, 0, 0.3), 0 1px 3px rgba(0, 0, 0, 0.2);
                        --error-bg: #3a1f1f;
                        --error-border: #5a2f2f;
                        --error-text: #ff6b6b;
                    }
                }

                body {
                    font-family: -apple-system, BlinkMacSystemFont, 'SF Pro Display', 'Segoe UI', sans-serif;
                    background: var(--bg-primary);
                    min-height: 100vh;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    padding: 20px;
                    transition: background-color 0.3s ease;
                    position: relative;
                    overflow: hidden;
                }

                body::before {
                    content: '';
                    position: fixed;
                    top: 50%;
                    left: 50%;
                    width: 600px;
                    height: 600px;
                    background: url('logo.png') no-repeat center;
                    background-size: contain;
                    opacity: 0.03;
                    transform: translate(-50%, -50%) rotate(-15deg);
                    pointer-events: none;
                    z-index: 0;
                }

                @media (prefers-color-scheme: dark) {
                    body::before {
                        opacity: 0.05;
                    }
                }

                .login-container {
                    background: var(--bg-secondary);
                    border-radius: 18px;
                    box-shadow: var(--shadow);
                    padding: 48px 40px;
                    width: 100%;
                    max-width: 380px;
                    text-align: center;
                    transition: background-color 0.3s ease, box-shadow 0.3s ease;
                    animation: slideUp 0.6s ease-out;
                    position: relative;
                    z-index: 1;
                }

                @keyframes slideUp {
                    from {
                        opacity: 0;
                        transform: translateY(30px);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }

                .lock-icon {
                    width: 100px;
                    height: 100px;
                    margin: 0 auto 32px;
                    animation: logoEntrance 1s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                }

                .lock-icon img {
                    width: 100%;
                    height: 100%;
                    object-fit: contain;
                    filter: drop-shadow(0 4px 12px rgba(0, 0, 0, 0.15));
                    animation: logoPulse 3s ease-in-out infinite;
                }

                @keyframes logoEntrance {
                    from {
                        opacity: 0;
                        transform: scale(0.5) rotate(-10deg);
                    }
                    to {
                        opacity: 1;
                        transform: scale(1) rotate(0deg);
                    }
                }

                @keyframes logoPulse {
                    0%, 100% {
                        transform: scale(1);
                        filter: drop-shadow(0 4px 12px rgba(0, 0, 0, 0.15));
                    }
                    50% {
                        transform: scale(1.05);
                        filter: drop-shadow(0 8px 20px rgba(0, 0, 0, 0.25));
                    }
                }

                h1 {
                    color: var(--text-primary);
                    font-size: 28px;
                    font-weight: 600;
                    margin-bottom: 32px;
                    letter-spacing: -0.5px;
                }

                .error {
                    background: var(--error-bg);
                    border: 1px solid var(--error-border);
                    color: var(--error-text);
                    padding: 12px 16px;
                    border-radius: 10px;
                    margin-bottom: 20px;
                    font-size: 14px;
                }

                .input-group {
                    margin-bottom: 24px;
                    text-align: left;
                }

                label {
                    display: block;
                    margin-bottom: 8px;
                    color: var(--text-primary);
                    font-size: 13px;
                    font-weight: 500;
                }

                input[type="password"] {
                    width: 100%;
                    padding: 12px 16px;
                    border: 1px solid var(--border-color);
                    border-radius: 10px;
                    font-size: 17px;
                    transition: all 0.2s ease;
                    background: var(--input-bg);
                    color: var(--text-primary);
                    font-family: inherit;
                }

                input[type="password"]:focus {
                    outline: none;
                    border-color: #007aff;
                    box-shadow: 0 0 0 4px rgba(0, 122, 255, 0.1);
                }

                button {
                    width: 100%;
                    padding: 14px;
                    background: #007aff;
                    color: white;
                    border: none;
                    border-radius: 10px;
                    font-size: 17px;
                    font-weight: 500;
                    cursor: pointer;
                    transition: all 0.2s ease;
                }

                button:hover {
                    background: #0051d5;
                }

                button:active {
                    transform: scale(0.98);
                }

                @media (max-width: 480px) {
                    .login-container {
                        padding: 32px 24px;
                    }
                }
            </style>
        </head>
        <body>
            <div class="login-container">
                <div class="lock-icon">
                    <img src="logo.png" alt="Logo">
                </div>
                <h1>Διαχείριση</h1>
                <?php if (isset($loginError)): ?>
                    <div class="error">Λάθος κωδικός πρόσβασης</div>
                <?php endif; ?>
                <form method="POST">
                    <div class="input-group">
                        <label for="password">Κωδικός</label>
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

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin.php');
    exit;
}

// Include database configuration
include 'db_config.php';

// Default sorting parameters
$columns = ['fullName', 'telephoneNumber', 'email', 'dateOfRegister'];
$orderBy = 'dateOfRegister';
$orderDir = 'DESC';

if (isset($_GET['column']) && in_array($_GET['column'], $columns)) {
    $orderBy = $_GET['column'];
}
if (isset($_GET['dir']) && in_array($_GET['dir'], ['ASC', 'DESC'])) {
    $orderDir = $_GET['dir'];
}

$newOrderDir = $orderDir === 'ASC' ? 'DESC' : 'ASC';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Σφάλμα σύνδεσης: " . $conn->connect_error);
}

$conn->set_charset("utf8");

// Count total donors
$countSql = "SELECT COUNT(*) as totalDonors FROM donors";
$countResult = $conn->query($countSql);
$totalDonors = $countResult->fetch_assoc()['totalDonors'];

// Handle delete request
if (isset($_POST['delete_id'])) {
    $delete_id = intval($_POST['delete_id']);
    $conn->query("DELETE FROM donors WHERE id = $delete_id");
    header('Location: admin.php');
    exit;
}

// Handle edit request
if (isset($_POST['edit_id'])) {
    $edit_id = intval($_POST['edit_id']);
    $fullName = $conn->real_escape_string($_POST['fullName']);
    $telephoneNumber = $conn->real_escape_string($_POST['telephoneNumber']);
    $email = $conn->real_escape_string($_POST['email']);

    $conn->query("UPDATE donors SET fullName='$fullName', telephoneNumber='$telephoneNumber', email='$email' WHERE id=$edit_id");
    header('Location: admin.php');
    exit;
}

// Handle search
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$searchCondition = '';
if (!empty($search)) {
    $searchEscaped = $conn->real_escape_string($search);
    $searchCondition = " WHERE fullName LIKE '%$searchEscaped%' OR telephoneNumber LIKE '%$searchEscaped%' OR email LIKE '%$searchEscaped%'";
}

// Fetch registrations
$sql = "SELECT id, fullName, telephoneNumber, email, dateOfRegister FROM donors$searchCondition ORDER BY $orderBy $orderDir";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Διαχείριση Δοτών</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --bg-primary: #f5f5f7;
            --bg-secondary: #ffffff;
            --text-primary: #1d1d1f;
            --text-secondary: #86868b;
            --border-color: #e5e5ea;
            --table-hover: #fafafa;
            --thead-bg: #f5f5f7;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.07), 0 1px 3px rgba(0, 0, 0, 0.06);
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --bg-primary: #000000;
                --bg-secondary: #1c1c1e;
                --text-primary: #f5f5f7;
                --text-secondary: #98989d;
                --border-color: #38383a;
                --table-hover: #2c2c2e;
                --thead-bg: #1c1c1e;
                --shadow: 0 4px 6px rgba(0, 0, 0, 0.3), 0 1px 3px rgba(0, 0, 0, 0.2);
            }
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'SF Pro Display', 'Segoe UI', sans-serif;
            background: var(--bg-primary);
            min-height: 100vh;
            padding: 24px;
            transition: background-color 0.3s ease;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            top: 50%;
            left: 50%;
            width: 800px;
            height: 800px;
            background: url('logo.png') no-repeat center;
            background-size: contain;
            opacity: 0.02;
            transform: translate(-50%, -50%) rotate(-15deg);
            pointer-events: none;
            z-index: 0;
        }

        @media (prefers-color-scheme: dark) {
            body::before {
                opacity: 0.04;
            }
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .header {
            background: var(--bg-secondary);
            border-radius: 18px;
            padding: 32px 40px;
            margin-bottom: 16px;
            box-shadow: var(--shadow);
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            animation: slideDown 0.6s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .header-content {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .header-logo {
            width: 48px;
            height: 48px;
            animation: logoPulse 3s ease-in-out infinite;
        }

        .header-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            filter: drop-shadow(0 2px 8px rgba(0, 0, 0, 0.1));
        }

        .header-text h1 {
            color: var(--text-primary);
            font-size: 32px;
            font-weight: 600;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .stats {
            color: var(--text-secondary);
            font-size: 15px;
        }

        .stats span {
            color: #007aff;
            font-weight: 600;
            font-size: 24px;
        }

        .search-container {
            margin-top: 20px;
            width: 100%;
        }

        .search-input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            font-size: 15px;
            background: var(--bg-primary);
            color: var(--text-primary);
            transition: all 0.2s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: #007aff;
            box-shadow: 0 0 0 4px rgba(0, 122, 255, 0.1);
        }

        .logout-btn {
            background: #ff3b30;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .logout-btn:hover {
            background: #d32f2f;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .edit-btn, .delete-btn {
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .edit-btn {
            background: #007aff;
            color: white;
        }

        .edit-btn:hover {
            background: #0051d5;
            transform: translateY(-1px);
        }

        .delete-btn {
            background: #ff3b30;
            color: white;
        }

        .delete-btn:hover {
            background: #d32f2f;
            transform: translateY(-1px);
        }

        .table-container {
            background: var(--bg-secondary);
            border-radius: 18px;
            box-shadow: var(--shadow);
            overflow: hidden;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            animation: fadeInUp 0.8s ease-out 0.2s both;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: var(--thead-bg);
            border-bottom: 1px solid var(--border-color);
        }

        th {
            padding: 16px 20px;
            text-align: left;
            font-weight: 500;
            font-size: 13px;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        @media (prefers-color-scheme: dark) {
            th:hover {
                background: #2c2c2e;
            }
        }

        @media (prefers-color-scheme: light) {
            th:hover {
                background: #ebebeb;
            }
        }

        tbody tr {
            border-bottom: 1px solid var(--border-color);
            transition: background 0.2s ease;
            animation: rowFadeIn 0.5s ease-out both;
        }

        tbody tr:nth-child(1) { animation-delay: 0.05s; }
        tbody tr:nth-child(2) { animation-delay: 0.1s; }
        tbody tr:nth-child(3) { animation-delay: 0.15s; }
        tbody tr:nth-child(4) { animation-delay: 0.2s; }
        tbody tr:nth-child(5) { animation-delay: 0.25s; }
        tbody tr:nth-child(n+6) { animation-delay: 0.3s; }

        @keyframes rowFadeIn {
            from {
                opacity: 0;
                transform: translateX(-10px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        tbody tr:hover {
            background: var(--table-hover);
        }

        td {
            padding: 16px 20px;
            color: var(--text-primary);
            font-size: 15px;
        }

        .contact-cell {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .contact-link {
            color: #007aff;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .contact-link:hover {
            color: #0051d5;
        }

        .copy-btn {
            background: var(--thead-bg);
            border: none;
            padding: 6px 10px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            color: var(--text-secondary);
            opacity: 0;
            transition: all 0.2s ease;
        }

        tr:hover .copy-btn {
            opacity: 1;
        }

        @media (prefers-color-scheme: dark) {
            .copy-btn:hover {
                background: #2c2c2e;
                color: var(--text-primary);
            }
        }

        @media (prefers-color-scheme: light) {
            .copy-btn:hover {
                background: #e5e5ea;
                color: var(--text-primary);
            }
        }

        .no-data {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-secondary);
            font-size: 17px;
        }

        .notification {
            position: fixed;
            top: 24px;
            right: 24px;
            background: #34c759;
            color: white;
            padding: 16px 24px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(52, 199, 89, 0.3);
            transform: translateY(-100px);
            opacity: 0;
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .notification.show {
            transform: translateY(0);
            opacity: 1;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.3s ease;
        }

        .modal.show {
            display: flex;
        }

        .modal-content {
            background: var(--bg-secondary);
            border-radius: 18px;
            padding: 32px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.3s ease-out;
        }

        .modal-content h2 {
            color: var(--text-primary);
            margin-bottom: 24px;
            font-size: 24px;
        }

        .modal-input-group {
            margin-bottom: 20px;
        }

        .modal-input-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-primary);
            font-size: 13px;
            font-weight: 500;
        }

        .modal-input-group input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            font-size: 15px;
            background: var(--bg-primary);
            color: var(--text-primary);
            transition: all 0.2s ease;
        }

        .modal-input-group input:focus {
            outline: none;
            border-color: #007aff;
            box-shadow: 0 0 0 4px rgba(0, 122, 255, 0.1);
        }

        .modal-buttons {
            display: flex;
            gap: 12px;
            margin-top: 24px;
        }

        .modal-save, .modal-cancel {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .modal-save {
            background: #007aff;
            color: white;
        }

        .modal-save:hover {
            background: #0051d5;
        }

        .modal-cancel {
            background: var(--border-color);
            color: var(--text-primary);
        }

        .modal-cancel:hover {
            background: var(--text-secondary);
            color: white;
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 16px;
                text-align: center;
            }

            .table-container {
                overflow-x: auto;
            }

            table {
                min-width: 700px;
            }

            .modal-content {
                padding: 24px;
            }
        }
    </style>
    <script>
        function copyToClipboard(text, button) {
            navigator.clipboard.writeText(text).then(function() {
                showNotification('Αντιγράφηκε: ' + text);
                const originalText = button.textContent;
                button.textContent = 'Copied!';
                button.style.background = '#34c759';
                button.style.color = 'white';
                setTimeout(() => {
                    button.textContent = originalText;
                    button.style.background = '';
                    button.style.color = '';
                }, 1500);
            });
        }

        function showNotification(message) {
            const existing = document.querySelector('.notification');
            if (existing) existing.remove();

            const notification = document.createElement('div');
            notification.className = 'notification';
            notification.textContent = message;
            document.body.appendChild(notification);

            setTimeout(() => notification.classList.add('show'), 100);
            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        function editDonor(id, fullName, telephoneNumber, email) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_fullName').value = fullName;
            document.getElementById('edit_telephoneNumber').value = telephoneNumber;
            document.getElementById('edit_email').value = email;
            document.getElementById('editModal').classList.add('show');
        }

        function closeModal() {
            document.getElementById('editModal').classList.remove('show');
        }

        // Close modal on outside click
        document.addEventListener('click', function(event) {
            const modal = document.getElementById('editModal');
            if (event.target === modal) {
                closeModal();
            }
        });
    </script>
</head>
<body>

<div class="container">
    <div class="header">
        <div class="header-content">
            <div class="header-logo">
                <img src="logo.png" alt="Logo">
            </div>
            <div class="header-text">
                <h1>Δότες Αίματος</h1>
                <div class="stats">Σύνολο: <span><?php echo $totalDonors; ?></span></div>
                <div class="search-container">
                    <form method="GET" action="">
                        <input type="text" name="search" class="search-input" placeholder="Αναζήτηση με όνομα, τηλέφωνο ή email..." value="<?php echo htmlspecialchars($search); ?>">
                    </form>
                </div>
            </div>
        </div>
        <a href="?logout=1" class="logout-btn">Αποσύνδεση</a>
    </div>

    <div class="table-container">
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th onclick="window.location.href='?column=fullName&dir=<?php echo $newOrderDir; ?><?php echo !empty($search) ? '&search='.urlencode($search) : ''; ?>'">Ονοματεπώνυμο</th>
                        <th onclick="window.location.href='?column=telephoneNumber&dir=<?php echo $newOrderDir; ?><?php echo !empty($search) ? '&search='.urlencode($search) : ''; ?>'">Τηλέφωνο</th>
                        <th onclick="window.location.href='?column=email&dir=<?php echo $newOrderDir; ?><?php echo !empty($search) ? '&search='.urlencode($search) : ''; ?>'">Email</th>
                        <th onclick="window.location.href='?column=dateOfRegister&dir=<?php echo $newOrderDir; ?><?php echo !empty($search) ? '&search='.urlencode($search) : ''; ?>'">Ημερομηνία</th>
                        <th>Ενέργειες</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($row['fullName']); ?></strong></td>
                            <td>
                                <div class="contact-cell">
                                    <a href="tel:<?php echo htmlspecialchars($row['telephoneNumber']); ?>" class="contact-link">
                                        <?php echo htmlspecialchars($row['telephoneNumber']); ?>
                                    </a>
                                    <button class="copy-btn" onclick="copyToClipboard('<?php echo htmlspecialchars($row['telephoneNumber']); ?>', this)">Copy</button>
                                </div>
                            </td>
                            <td>
                                <div class="contact-cell">
                                    <a href="mailto:<?php echo htmlspecialchars($row['email']); ?>" class="contact-link">
                                        <?php echo htmlspecialchars($row['email']); ?>
                                    </a>
                                    <button class="copy-btn" onclick="copyToClipboard('<?php echo htmlspecialchars($row['email']); ?>', this)">Copy</button>
                                </div>
                            </td>
                            <td><?php echo date('d/m/Y H:i', strtotime($row['dateOfRegister'])); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="edit-btn" onclick="editDonor(<?php echo $row['id']; ?>, '<?php echo addslashes($row['fullName']); ?>', '<?php echo addslashes($row['telephoneNumber']); ?>', '<?php echo addslashes($row['email']); ?>')">Επεξεργασία</button>
                                    <form method="POST" style="display: inline;" onsubmit="return confirm('Είστε σίγουροι ότι θέλετε να διαγράψετε αυτόν τον δότη;');">
                                        <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" class="delete-btn">Διαγραφή</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-data">Δεν υπάρχουν εγγραφές</div>
        <?php endif; ?>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <h2>Επεξεργασία Δότη</h2>
        <form method="POST">
            <input type="hidden" id="edit_id" name="edit_id">
            <div class="modal-input-group">
                <label for="edit_fullName">Ονοματεπώνυμο</label>
                <input type="text" id="edit_fullName" name="fullName" required>
            </div>
            <div class="modal-input-group">
                <label for="edit_telephoneNumber">Τηλέφωνο</label>
                <input type="tel" id="edit_telephoneNumber" name="telephoneNumber" required>
            </div>
            <div class="modal-input-group">
                <label for="edit_email">Email</label>
                <input type="email" id="edit_email" name="email" required>
            </div>
            <div class="modal-buttons">
                <button type="submit" class="modal-save">Αποθήκευση</button>
                <button type="button" class="modal-cancel" onclick="closeModal()">Ακύρωση</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>

<?php
$conn->close();
?>
