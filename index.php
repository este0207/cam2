<?php  
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = :email AND password = :password";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['email' => $email, 'password' => $password]);

    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION['user_name'] = $user['firstname'];
        $_SESSION['user_id'] = $user['id'];

        if ($email === 'admin@gmail.com') {
            $_SESSION['is_admin'] = true;
            header("Location: admin.php");
        } else {
            $_SESSION['is_admin'] = false;
            header("Location: index.php");
        }
        exit();
    } else {
        $error = "Email ou mot de passe incorrect.";
    }
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parc de la Barben</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <script src="script.js" defer></script>
</head>
<body>
    
<header>
        <nav class="nav">
            <a href="index.php">Accueil</a>
            <a href="billetterie.php">Billetterie</a>
            <a href="animaux.php">Animaux</a>
            <a href="services.php">Services</a>
            <button class="btnlogin">login</button>
        </nav>
        <?php if (isset($_SESSION['user_name'])): ?>
            <div class="user-dropdown">
                <span><?php echo $_SESSION['user_name']; ?></span>
                <div class="dropdown-content">
                    <a href="logout.php">Se déconnecter</a>
                </div>
            </div>
        <?php else: ?>
        <?php endif; ?>
    </header>

    
<div class="wrapper hidden" id="popup">
    <span class="icon-close"><ion-icon name="close"></ion-icon></span>
    <div class="form-box login">
        <h2>Connexion</h2>
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="index.php" method="POST">
            <div class="input box">
                <span class="icon"><ion-icon name="mail"></ion-icon></span>
                <input type="email" name="email" required>
                <label>Email</label>
            </div>
            <div class="input box">
                <span class="icon"><ion-icon name="lock-closed"></ion-icon></span>
                <input type="password" name="password" required>
                <label>Mot de Passe</label>
            </div>
            <div class="remember-forgot">
                <a href="#forgot-pass">Mot de Passe oublié</a>
            </div>
            <button type="submit" class="btnlog">Se connecter</button>
            <div class="login-register">
                <p>Vous n’avez pas de compte ? <a href="register.php" class="register-link">Inscrivez-vous</a></p>
            </div>
        </form>
    </div>
</div>

<section class='log'>
    <?php if (isset($_SESSION['user_name'])): ?>*
        <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true): ?>
            <h1>Bienvenue Administrateur</h1>
            <p>Vous pouvez modifier les paramètres du site <a href="admin.php">ici.</a></p>
        <?php else: ?>
            <h1>Bienvenue <?php echo $_SESSION['user_name']; ?></h1>
        <?php endif; ?> 
    <?php else: ?>
        <h1>Bienvenue au Parc de la Barben</h1>
    <?php endif; ?> 
</section>
    <section class="container">
        <div class="slider-wrapper">
            <div class="slider">
                <img id="tigre" src="images_i/fond.jpg" alt="Tigre">
            </div>
        </div>
    </section>
</body>
</html>
