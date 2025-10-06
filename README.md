# 🩸 Blood Donor Registration System
## Street Thugs Salonica - Σύστημα Εγγραφής Δοτών Αίματος

Μια σύγχρονη εφαρμογή διαδικτύου με Apple-inspired minimal design για την εγγραφή και διαχείριση δοτών αίματος. Το σύστημα περιλαμβάνει φόρμα εγγραφής για χρήστες και πλήρες admin panel για διαχείριση.

---

## ✨ Χαρακτηριστικά

### Φόρμα Εγγραφής (index.php)
- **Apple-Styled Minimal UI** - Καθαρό, μινιμαλιστικό design εμπνευσμένο από την Apple
- **Dark/Light Mode** - Αυτόματη προσαρμογή στο θέμα του συστήματος
- **Smart Animations**
  - Logo entrance animation με rotation & scale
  - Staggered form field animations
  - Interactive input animations με lift effect
  - Loading state με spinning logo
  - Success animation με logo bounce & glow
- **FAQ Section** - Ενημέρωση για τη σημασία της αιμοδοσίας
- **Real-time Validation** - Green/red borders για έγκυρα/άκυρα πεδία
- **Responsive Design** - Πλήρης υποστήριξη για mobile συσκευές

### Admin Panel (admin.php)
- **Password Protection** - Ασφαλής πρόσβαση με κωδικό
- **Search Functionality** - Αναζήτηση με όνομα, τηλέφωνο ή email
- **Edit Donors** - Επεξεργασία στοιχείων μέσω modal popup
- **Delete Donors** - Διαγραφή με confirmation dialog
- **Sortable Columns** - Ταξινόμηση ανά στήλη (ASC/DESC)
- **Copy to Clipboard** - Γρήγορη αντιγραφή τηλεφώνου/email
- **Logo Integration** - Logo στο header με pulse animation
- **Staggered Table Animations** - Smooth row animations
- **Dark/Light Mode Support** - Συγχρονισμός με θέμα συστήματος

### Τεχνικά Χαρακτηριστικά
- **Auto Database Setup** - Αυτόματη δημιουργία βάσης & πινάκων στην πρώτη επίσκεψη
- **No CORS Issues** - Καμία ανάγκη για CORS (same-origin)
- **Clean Architecture** - Οργανωμένος & maintainable κώδικας
- **MySQL Database** - Ασφαλής αποθήκευση δεδομένων
- **Email Validation** - Αποτροπή διπλών εγγραφών

---

## 📁 Δομή Έργου

```
sts_blood/
├── index.php              # Φόρμα εγγραφής δοτών (με FAQ)
├── admin.php             # Admin panel με search/edit/delete
├── api.php               # API endpoint για εγγραφές
├── db_config.php         # Ρυθμίσεις βάσης δεδομένων
├── logo.png              # Street Thugs Salonica logo
├── favicon.ico           # Favicon
└── README.md             # Αυτό το αρχείο
```

---

## 🚀 Εγκατάσταση & Ρύθμιση

### Προαπαιτούμενα
- PHP 7.4+
- MySQL 5.7+
- Apache/Nginx web server

### Βήμα 1: Ρύθμιση Βάσης Δεδομένων
Επεξεργαστείτε το `db_config.php`:

```php
$servername = "localhost";
$username = "root";
$password = "your_password";
$dbname = "bloodGivers";
```

### Βήμα 2: Ανέβασμα Αρχείων
Ανεβάστε όλα τα αρχεία στον web server σας (π.χ. `/var/www/html/sts_blood/`)

### Βήμα 3: Πρώτη Επίσκεψη
Επισκεφθείτε `http://yourdomain.com/sts_blood/index.php`

**Η βάση δεδομένων και οι πίνακες θα δημιουργηθούν αυτόματα!**

---

## 🔐 Διαχείριση

### Admin Credentials
- **URL**: `/sts_blood/admin.php`
- **Password**: `orestislef`

### Admin Panel Features
1. **Search Bar** - Αναζήτηση δοτών με όνομα, τηλέφωνο ή email
2. **Edit Button** - Επεξεργασία στοιχείων δότη
3. **Delete Button** - Διαγραφή δότη (με επιβεβαίωση)
4. **Copy Buttons** - Αντιγραφή τηλεφώνου/email με ένα κλικ
5. **Sortable Columns** - Κλικ στις στήλες για ταξινόμηση
6. **Total Count** - Εμφάνιση συνολικών δοτών

---

## 🎨 Design Features

### Color Scheme
- **Light Mode**: Light gray background, white cards, dark text
- **Dark Mode**: Pure black background, dark gray cards, light text
- **Accent Color**: Apple Blue (#007aff)
- **Success Color**: Green (#34c759)
- **Error Color**: Red (#ff3b30)

### Animations
1. **Logo Entrance** - Scale + rotate animation on load
2. **Logo Pulse** - Continuous breathing effect (3s loop)
3. **Container Slide-up** - Smooth entrance from bottom
4. **Form Stagger** - Sequential fade-in for form fields
5. **Input Lift** - Elevation on focus with shadow
6. **Button Ripple** - Expanding circle on hover
7. **Loading Spinner** - Rotating logo during submission
8. **Success Bounce** - Logo bounces with glow effect
9. **Table Rows** - Staggered fade-in from left
10. **Modal Popup** - Fade in with blur backdrop

### Typography
- **Font**: SF Pro Display (Apple's system font)
- **Weights**: 500 (regular), 600 (semibold)
- **Sizes**: 13px-32px responsive scaling

---

## 📊 Database Schema

### Table: `donors`
| Column | Type | Description |
|--------|------|-------------|
| `id` | INT(6) AUTO_INCREMENT | Primary key |
| `fullName` | VARCHAR(100) | Ονοματεπώνυμο |
| `telephoneNumber` | VARCHAR(15) | Αριθμός τηλεφώνου |
| `email` | VARCHAR(50) UNIQUE | Email (unique) |
| `dateOfRegister` | TIMESTAMP | Ημερομηνία εγγραφής |

---

## 🔧 Χρήση

### Για Χρήστες
1. Επισκεφθείτε το `index.php`
2. Συμπληρώστε τη φόρμα (όνομα, τηλέφωνο, email)
3. Διαβάστε το FAQ section
4. Πατήστε "Εγγραφή"
5. Λάβετε μήνυμα επιβεβαίωσης

### Για Διαχειριστές
1. Επισκεφθείτε το `admin.php`
2. Εισάγετε τον κωδικό: `orestislef`
3. Αναζητήστε, επεξεργαστείτε ή διαγράψτε δότες
4. Ταξινομήστε τα αποτελέσματα ανά στήλη
5. Αντιγράψτε στοιχεία επικοινωνίας με ένα κλικ

---

## 🌟 FAQ Section Content

**Γιατί να γίνω δότης;**

1. **Σώζετε ζωές** - Κάθε δωρεά αίματος μπορεί να σώσει έως και 3 ζωές
2. **Είναι ασφαλές** - Χρήση μόνο αποστειρωμένων υλικών μιας χρήσης
3. **Βοηθάτε την κοινότητα** - Πράξη αλληλεγγύης και προσφοράς

---

## 🛡️ Ασφάλεια

- Password protection για admin panel
- SQL injection protection με prepared statements
- Email validation & duplicate prevention
- HTTPS recommended για production
- Session-based authentication

---

## 📱 Browser Support

- ✅ Chrome 90+
- ✅ Firefox 88+
- ✅ Safari 14+
- ✅ Edge 90+
- ✅ Mobile browsers (iOS Safari, Chrome Mobile)

---

## 🎯 Roadmap / Future Features

- [ ] Email notifications για νέες εγγραφές
- [ ] Export δοτών σε CSV/Excel
- [ ] Blood type tracking
- [ ] Donation history
- [ ] SMS notifications
- [ ] Multi-language support

---

## 📝 Changelog

### Version 2.0 (2025)
- ✨ Complete UI redesign με Apple-style minimal design
- 🌙 Dark/light mode support
- 🎨 Logo integration με animations
- 🔍 Search functionality στο admin panel
- ✏️ Edit & delete functionality
- 📱 Improved mobile responsiveness
- ❓ FAQ section addition
- 🚀 Auto database setup
- 🎭 Advanced animations σε όλα τα στοιχεία

### Version 1.0 (Initial)
- Basic registration form
- Admin list view
- Database integration

---

## 👨‍💻 Developer

**Orestis Lef**
- Organization: Street Thugs Salonica
- Project: Blood Donor Registration System

---

## 📄 License

Αυτό το έργο είναι αδειοδοτημένο υπό την άδεια του **Orestis Lef**.

---

## 🆘 Troubleshooting

### Database Connection Issues
- Βεβαιωθείτε ότι οι ρυθμίσεις στο `db_config.php` είναι σωστές
- Ελέγξτε αν ο MySQL server τρέχει
- Βεβαιωθείτε ότι ο χρήστης έχει δικαιώματα CREATE DATABASE

### Styling Issues
- Καθαρίστε το browser cache
- Βεβαιωθείτε ότι το `logo.png` υπάρχει στον φάκελο

### Admin Login Issues
- Default password: `orestislef`
- Για αλλαγή, επεξεργαστείτε τη γραμμή 8 του `admin.php`

---

**Made with ❤️ by Street Thugs Salonica**
