<?php
session_start();

// Password protection
if (!isset($_SESSION['authenticated'])) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $password = $_POST['password'];
        if ($password === 'orestislef') {
            $_SESSION['authenticated'] = true;
        } else {
            echo "<p style='color:red;'>Λάθος κωδικός πρόσβασης!</p>";
        }
    }
    
    if (!isset($_SESSION['authenticated'])) {
        echo '<form method="POST">
                <label for="password">Εισάγετε τον κωδικό πρόσβασης:</label>
                <input type="password" id="password" name="password" required>
                <button type="submit">Σύνδεση</button>
              </form>';
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
    <title>Λίστα Εγγραφών Δοτών Αίματος</title>
	<link rel="icon" type="image/x-icon" href="favicon.ico"
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            padding: 20px;
        }

        h1 {
            color: #e74c3c;
            margin-bottom: 20px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            max-width: 800px;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        table th, table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            cursor: pointer;
        }

        table th {
            background-color: #e74c3c;
            color: #fff;
            text-transform: uppercase;
            font-weight: bold;
        }

        table tr:hover {
            background-color: #f5f5f5;
        }

        .container {
            width: 100%;
            max-width: 800px;
        }

        .action-buttons .fa {
            margin-left: 10px;
            cursor: pointer;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        .sortable:after {
            content: "\f0dc";
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            margin-left: 5px;
        }
    </style>
    <script>
        function copyToClipboard(text) {
            const textarea = document.createElement('textarea');
            textarea.value = text;
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);
            alert('Αντιγράφηκε στο πρόχειρο: ' + text);
        }
    </script>
</head>
<body>

<div class="container">
    <h1>Λίστα Εγγραφών Δοτών Αίματος (<?php echo $totalDonors; ?>)</h1>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th onclick="window.location.href='?column=fullName&dir=<?php echo $newOrderDir; ?>'">Ονοματεπώνυμο</th>
                    <th onclick="window.location.href='?column=telephoneNumber&dir=<?php echo $newOrderDir; ?>'">Τηλέφωνο</th>
                    <th onclick="window.location.href='?column=email&dir=<?php echo $newOrderDir; ?>'">Email</th>
                    <th onclick="window.location.href='?column=dateOfRegister&dir=<?php echo $newOrderDir; ?>'">Ημερομηνία Εγγραφής</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['fullName']); ?></td>
                        <td>
                            <a href="tel:<?php echo htmlspecialchars($row['telephoneNumber']); ?>">
                                <?php echo htmlspecialchars($row['telephoneNumber']); ?>
                            </a>
                            <i class="fa fa-copy" title="Αντιγραφή στο πρόχειρο" onclick="copyToClipboard('<?php echo htmlspecialchars($row['telephoneNumber']); ?>')"></i>
                        </td>
                        <td>
                            <a href="mailto:<?php echo htmlspecialchars($row['email']); ?>">
                                <?php echo htmlspecialchars($row['email']); ?>
                            </a>
                            <i class="fa fa-copy" title="Αντιγραφή στο πρόχειρο" onclick="copyToClipboard('<?php echo htmlspecialchars($row['email']); ?>')"></i>
                        </td>
                        <td><?php echo htmlspecialchars($row['dateOfRegister']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Δεν υπάρχουν εγγραφές προς το παρόν.</p>
    <?php endif; ?>

</div>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
