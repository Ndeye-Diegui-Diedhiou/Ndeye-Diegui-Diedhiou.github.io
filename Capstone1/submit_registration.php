<?php
// Démarrer la session en premier
session_start();

// Afficher les erreurs pour le débogage (à désactiver en production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: RegistrationForm.html");
    exit();
}

// Connexion à PostgreSQL
$host = "localhost";
$port = "5432";
$dbname = "mon_site";
$user = "postgres";
$password = "011004";

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Stocker l'erreur dans la session et rediriger
    $_SESSION['error'] = "Erreur de connexion PostgreSQL : " . $e->getMessage();
    header("Location: RegistrationForm.html");
    exit();
}

// Récupération et validation des données
$firstName = htmlspecialchars($_POST['firstName'] ?? '');
$lastName = htmlspecialchars($_POST['lastName'] ?? '');
$email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
$rawPassword = $_POST['password'] ?? '';
$birthdate = $_POST['birthdate'] ?? '';
$gender = $_POST['gender'] ?? '';
$country = htmlspecialchars($_POST['country'] ?? '');
$phone = htmlspecialchars($_POST['phone'] ?? '');
$interests = htmlspecialchars($_POST['interests'] ?? '');
$lookingFor = isset($_POST['lookingFor']) ? implode(',', $_POST['lookingFor']) : '';
$photo = null;

// Validation des données
$errors = [];

if (empty($firstName)) $errors[] = "Le prénom est requis";
if (empty($lastName)) $errors[] = "Le nom est requis";
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email invalide";
if (strlen($rawPassword) < 8) $errors[] = "Le mot de passe doit contenir au moins 8 caractères";
if (empty($birthdate)) $errors[] = "La date de naissance est requise";
if (empty($gender)) $errors[] = "Le genre est requis";
if (empty($country)) $errors[] = "Le pays est requis";

// Si erreurs, rediriger avec les messages
if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header("Location: RegistrationForm.html");
    exit();
}

// Hachage du mot de passe
$password = password_hash($rawPassword, PASSWORD_DEFAULT);

// Gérer l'upload de la photo
if (isset($_FILES['profilePhoto']) && $_FILES['profilePhoto']['error'] == UPLOAD_ERR_OK) {
    // Créer le répertoire uploads s'il n'existe pas
    if (!is_dir('uploads')) {
        mkdir('uploads', 0755, true);
    }
    
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $fileType = $_FILES['profilePhoto']['type'];
    
    if (in_array($fileType, $allowedTypes)) {
        $photoName = time() . '_' . basename($_FILES['profilePhoto']['name']);
        $uploadPath = "uploads/" . $photoName;
        
        if (move_uploaded_file($_FILES['profilePhoto']['tmp_name'], $uploadPath)) {
            $photo = $photoName;
        } else {
            $_SESSION['error'] = "Erreur lors de l'upload de la photo";
            header("Location: RegistrationForm.html");
            exit();
        }
    } else {
        $_SESSION['error'] = "Type de fichier non autorisé. Formats acceptés: JPG, PNG, GIF";
        header("Location: RegistrationForm.html");
        exit();
    }
}

// Insertion dans la base de données
try {
    $sql = "INSERT INTO users (first_name, last_name, email, password, birthdate, gender, country, phone, interests, looking_for, photo)
            VALUES (:first_name, :last_name, :email, :password, :birthdate, :gender, :country, :phone, :interests, :looking_for, :photo)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':first_name' => $firstName,
        ':last_name' => $lastName,
        ':email' => $email,
        ':password' => $password,
        ':birthdate' => $birthdate,
        ':gender' => $gender,
        ':country' => $country,
        ':phone' => $phone,
        ':interests' => $interests,
        ':looking_for' => $lookingFor,
        ':photo' => $photo
    ]);

    // Stocker un message de succès
    $_SESSION['success'] = "Inscription réussie! Vous pouvez maintenant vous connecter.";
    
    // Redirection vers la page de connexion
    header("Location: login.php");
    exit();

} catch (PDOException $e) {
    $_SESSION['error'] = "Erreur lors de l'inscription : " . $e->getMessage();
    header("Location: RegistrationForm.html");
    exit();
}
?>