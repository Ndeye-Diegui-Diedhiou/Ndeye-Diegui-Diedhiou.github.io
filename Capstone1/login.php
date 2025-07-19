<?php
session_start();

// Connexion PostgreSQL
$host = "localhost";
$port = "5432";
$dbname = "mon_site";
$user = "postgres";
$password = "011004";

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
} catch (PDOException $e) {
    die("Erreur connexion : " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $passwordInput = $_POST['password'];

    // Récupération de l'utilisateur
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($passwordInput, $user['password'])) {
        // Authentification réussie
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['first_name'];

        header("Location: dashboard.php");
        exit();
    } else {
        echo "Identifiants incorrects.";
    }
}
?>
