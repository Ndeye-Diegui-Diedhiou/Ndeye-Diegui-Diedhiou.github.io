<?php
// Connexion à PostgreSQL
$host = "localhost";
$port = "5432";
$dbname = "mon_site"; // Remplace par le nom de ta base
$user = "postgres";   // Par défaut : "postgres"
$password = "011004"; // Remplace par ton vrai mot de passe

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
} catch (PDOException $e) {
    die("Erreur de connexion PostgreSQL : " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $birthdate = $_POST['birthdate'];
    $gender = $_POST['gender'];
    $country = $_POST['country'];
    $phone = $_POST['phone'];
    $interests = $_POST['interests'];
    $lookingFor = isset($_POST['lookingFor']) ? implode(',', $_POST['lookingFor']) : '';
    $photo = null;

    // Gérer l’upload de la photo
    if (isset($_FILES['profilePhoto']) && $_FILES['profilePhoto']['error'] == 0) {
        $photoName = time() . "_" . basename($_FILES['profilePhoto']['name']);
        $uploadPath = "uploads/" . $photoName;
        move_uploaded_file($_FILES['profilePhoto']['tmp_name'], $uploadPath);
        $photo = $photoName;
    }

    // Insertion dans la table
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

    echo "Inscription réussie avec PostgreSQL !";

    // Redirection vers la page de connexion ou une autre page
    $_SESSION['user_id'] = $pdo->lastInsertId(); // ou manuellement
$_SESSION['user_name'] = $firstName;
header("Location: dashboard.php");
exit();

}
?>
