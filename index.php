<?php
// Auto-create database and table if they don't exist
include 'db_config.php';

// Create connection without selecting database first
$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if not exists
$conn->query("CREATE DATABASE IF NOT EXISTS $dbname");
$conn->select_db($dbname);

// Create table if not exists
$conn->query("CREATE TABLE IF NOT EXISTS donors (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    fullName VARCHAR(100) NOT NULL,
    telephoneNumber VARCHAR(15) NOT NULL,
    email VARCHAR(50) NOT NULL UNIQUE,
    dateOfRegister TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$conn->close();
?>
<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Εγγραφή Δοτών Αίματος</title>
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
            color: var(--text-primary);
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

        .container {
            background: var(--bg-secondary);
            border-radius: 18px;
            box-shadow: var(--shadow);
            padding: 48px 40px;
            width: 100%;
            max-width: 440px;
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

        .logo {
            width: 120px;
            height: 120px;
            margin: 0 auto 24px;
            position: relative;
            animation: logoEntrance 1s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .logo img {
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
            text-align: center;
            color: var(--text-primary);
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
            animation: fadeIn 0.8s ease-out 0.2s both;
        }

        .subtitle {
            text-align: center;
            color: var(--text-secondary);
            font-size: 15px;
            margin-bottom: 32px;
            animation: fadeIn 0.8s ease-out 0.3s both;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .input-group {
            margin-bottom: 20px;
            animation: fadeIn 0.8s ease-out both;
        }

        .input-group:nth-child(1) { animation-delay: 0.4s; }
        .input-group:nth-child(2) { animation-delay: 0.5s; }
        .input-group:nth-child(3) { animation-delay: 0.6s; }

        label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-primary);
            font-size: 13px;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .input-group.focused label {
            color: #007aff;
        }

        input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            font-size: 17px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: var(--input-bg);
            color: var(--text-primary);
            font-family: inherit;
        }

        input:focus {
            outline: none;
            border-color: #007aff;
            box-shadow: 0 0 0 4px rgba(0, 122, 255, 0.1);
            transform: translateY(-2px);
        }

        input::placeholder {
            color: var(--text-secondary);
            opacity: 0.6;
        }

        input:valid:not(:placeholder-shown) {
            border-color: #34c759;
        }

        input:invalid:not(:placeholder-shown):not(:focus) {
            border-color: #ff3b30;
        }

        .submit-btn {
            width: 100%;
            padding: 14px;
            background: #007aff;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 17px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin-top: 8px;
            position: relative;
            overflow: hidden;
            animation: fadeIn 0.8s ease-out 0.7s both;
        }

        .submit-btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .submit-btn:hover::before {
            width: 300px;
            height: 300px;
        }

        .submit-btn:hover {
            background: #0051d5;
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0, 122, 255, 0.3);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .submit-btn:disabled {
            background: var(--border-color);
            cursor: not-allowed;
            transform: none;
        }

        .submit-btn.loading {
            pointer-events: none;
            color: transparent;
        }

        .submit-btn.loading::after {
            content: '';
            position: absolute;
            width: 24px;
            height: 24px;
            background: url('logo.png') no-repeat center;
            background-size: contain;
            animation: logoSpin 1s ease-in-out infinite;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        @keyframes logoSpin {
            0% {
                transform: translate(-50%, -50%) rotate(0deg) scale(1);
                opacity: 1;
            }
            50% {
                transform: translate(-50%, -50%) rotate(180deg) scale(0.8);
                opacity: 0.7;
            }
            100% {
                transform: translate(-50%, -50%) rotate(360deg) scale(1);
                opacity: 1;
            }
        }

        .thank-you-message {
            display: none;
            text-align: center;
        }

        .thank-you-message.show {
            animation: scaleIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.8);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .thank-you-message .success-icon {
            width: 100px;
            height: 100px;
            margin: 0 auto 24px;
            position: relative;
            animation: successBounce 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        .thank-you-message .success-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            filter: drop-shadow(0 4px 20px rgba(52, 199, 89, 0.4));
        }

        .thank-you-message .success-icon::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(52, 199, 89, 0.2) 0%, transparent 70%);
            animation: successGlow 2s ease-in-out infinite;
            border-radius: 50%;
        }

        @keyframes successBounce {
            0% {
                transform: scale(0) rotate(-180deg);
                opacity: 0;
            }
            60% {
                transform: scale(1.15) rotate(10deg);
                opacity: 1;
            }
            80% {
                transform: scale(0.95) rotate(-5deg);
            }
            100% {
                transform: scale(1) rotate(0deg);
                opacity: 1;
            }
        }

        @keyframes successGlow {
            0%, 100% {
                transform: scale(1);
                opacity: 0.5;
            }
            50% {
                transform: scale(1.3);
                opacity: 0.8;
            }
        }

        .thank-you-message h2 {
            color: var(--text-primary);
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 12px;
            animation: fadeIn 0.6s ease-out 0.3s both;
        }

        .thank-you-message p {
            color: var(--text-secondary);
            font-size: 17px;
            line-height: 1.5;
            margin-bottom: 12px;
            animation: fadeIn 0.6s ease-out 0.4s both;
        }

        .faq-section {
            margin-top: 32px;
            padding-top: 32px;
            border-top: 1px solid var(--border-color);
            animation: fadeIn 0.8s ease-out 0.8s both;
        }

        .faq-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 16px;
        }

        .faq-item {
            margin-bottom: 16px;
            padding: 16px;
            background: var(--bg-primary);
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .faq-item:hover {
            transform: translateX(4px);
            background: var(--border-color);
        }

        .faq-question {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .faq-answer {
            font-size: 13px;
            color: var(--text-secondary);
            line-height: 1.5;
        }

        .admin-link {
            position: fixed;
            bottom: 24px;
            right: 24px;
            color: #007aff;
            text-decoration: none;
            font-size: 15px;
            opacity: 0.5;
            transition: all 0.3s ease;
            padding: 8px 16px;
            border-radius: 20px;
            background: rgba(0, 122, 255, 0.1);
            z-index: 10;
        }

        .admin-link:hover {
            opacity: 1;
            transform: translateY(-2px);
            background: rgba(0, 122, 255, 0.15);
        }

        @media (max-width: 480px) {
            .container {
                padding: 32px 24px;
            }

            h1 {
                font-size: 24px;
            }

            .admin-link {
                bottom: 16px;
                right: 16px;
            }
        }
    </style>

    <script>
        async function registerUser(event) {
            event.preventDefault();

            const submitBtn = document.querySelector('.submit-btn');
            const originalText = submitBtn.textContent;

            submitBtn.disabled = true;
            submitBtn.classList.add('loading');
            submitBtn.textContent = '';

            const fullName = document.getElementById('fullName').value;
            const telephoneNumber = document.getElementById('telephoneNumber').value;
            const email = document.getElementById('email').value;

            const now = new Date();
            const dateOfRegister = now.getFullYear() + "-" +
                                   String(now.getMonth() + 1).padStart(2, '0') + "-" +
                                   String(now.getDate()).padStart(2, '0') + " " +
                                   String(now.getHours()).padStart(2, '0') + ":" +
                                   String(now.getMinutes()).padStart(2, '0') + ":" +
                                   String(now.getSeconds()).padStart(2, '0');

            const data = {
                fullName: fullName,
                telephoneNumber: telephoneNumber,
                email: email,
                dateOfRegister: dateOfRegister
            };

            try {
                const response = await fetch('api.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                if (response.ok) {
                    document.querySelector('form').style.display = 'none';
                    document.getElementById('faqSection').style.display = 'none';
                    const thankYouMsg = document.querySelector('.thank-you-message');
                    thankYouMsg.style.display = 'block';
                    thankYouMsg.classList.add('show');
                } else {
                    const error = await response.json();
                    alert(error.error || 'Υπήρξε πρόβλημα με την εγγραφή σας.');
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('loading');
                    submitBtn.textContent = originalText;
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Υπήρξε πρόβλημα με την εγγραφή σας. Παρακαλώ δοκιμάστε ξανά.');
                submitBtn.disabled = false;
                submitBtn.classList.remove('loading');
                submitBtn.textContent = originalText;
            }
        }

        // Enhanced input interactions
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('input');

            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('focused');
                });

                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('focused');
                });
            });
        });
    </script>
</head>
<body>

<div class="container">
    <div class="logo">
        <img src="logo.png" alt="Logo">
    </div>
    <h1>Εγγραφή Δοτών</h1>
    <p class="subtitle">Γίνετε δότης αίματος σήμερα</p>

    <form onsubmit="registerUser(event)">
        <div class="input-group">
            <label for="fullName">Ονοματεπώνυμο</label>
            <input type="text" id="fullName" name="fullName" required>
        </div>
        <div class="input-group">
            <label for="telephoneNumber">Τηλέφωνο</label>
            <input type="tel" id="telephoneNumber" name="telephoneNumber" required>
        </div>
        <div class="input-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>
        <button type="submit" class="submit-btn">Εγγραφή</button>
    </form>

    <div class="faq-section" id="faqSection">
        <div class="faq-title">Γιατί να γίνω δότης;</div>
        <div class="faq-item">
            <div class="faq-question">Σώζετε ζωές</div>
            <div class="faq-answer">Κάθε δωρεά αίματος μπορεί να σώσει έως και 3 ζωές. Το αίμα χρησιμοποιείται σε επείγοντα περιστατικά, χειρουργεία και για ασθενείς με χρόνιες παθήσεις.</div>
        </div>
        <div class="faq-item">
            <div class="faq-question">Είναι ασφαλές</div>
            <div class="faq-answer">Η διαδικασία είναι απολύτως ασφαλής και χρησιμοποιούνται μόνο αποστειρωμένα υλικά μιας χρήσης.</div>
        </div>
        <div class="faq-item">
            <div class="faq-question">Βοηθάτε την κοινότητα</div>
            <div class="faq-answer">Η αιμοδοσία είναι μια πράξη αλληλεγγύης που ενισχύει τη συνοχή της κοινότητας και βοηθά συνανθρώπους μας.</div>
        </div>
    </div>

    <div class="thank-you-message">
        <div class="success-icon">
            <img src="logo.png" alt="Success">
        </div>
        <h2>Ευχαριστούμε!</h2>
        <p>Η εγγραφή σας ολοκληρώθηκε επιτυχώς.</p>
        <p>Θα επικοινωνήσουμε σύντομα μαζί σας.</p>
    </div>
</div>

<a href="admin.php" class="admin-link">Διαχείριση</a>

</body>
</html>
