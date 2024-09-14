<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "alex";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion à la base de données : " . $conn->connect_error);
}

// Traitement de l'inscription
if (isset($_POST['register'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Vérifier que les champs ne sont pas vides
    if (!empty($name) && !empty($email) && !empty($password)) {
        $password_hashed = password_hash($password, PASSWORD_BCRYPT);

        // Vérifier si l'email existe déjà
        $checkEmail = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $checkEmail->bind_param("s", $email);
        $checkEmail->execute();
        $result = $checkEmail->get_result();

        if ($result->num_rows > 0) {
            echo "<script>alert('Cet email est déjà enregistré. Veuillez en utiliser un autre.');</script>";
        } else {
            // Préparer l'insertion sécurisée
            $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $password_hashed);

            if ($stmt->execute()) {
                echo "<script>alert('Inscription réussie !');</script>";
            } else {
                echo "<script>alert('Erreur lors de l\'inscription : " . $stmt->error . "');</script>";
            }
            $stmt->close();
        }
        $checkEmail->close();
    } else {
        echo "<script>alert('Veuillez remplir tous les champs.');</script>";
    }
}

// Traitement de la connexion
if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        // Préparer une requête pour récupérer l'utilisateur
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                echo "<script>alert('Connexion réussie !');</script>";
            } else {
                echo "<script>alert('Mot de passe incorrect !');</script>";
            }
        } else {
            echo "<script>alert('Aucun utilisateur trouvé avec cet email !');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Veuillez remplir tous les champs.');</script>";
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css"
      rel="stylesheet"
    />
    <link 
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
    integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
    crossorigin="anonymous"
    referrerpolicy="no-referrer"
  />
  
  <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap');
        @import url("https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap");

    :root {
--primary-color: #ff833e;
--primary-color-dark: #db6f35;
--text-dark: #333333;
--text-light: #767268;
--white: #ffffff;
--max-width: 1200px;
--header-font: "Bebas Neue", sans-serif;
}

* {
padding: 0;
margin: 0;
box-sizing: border-box;
}
.container{
    background-color: #fff;
    border-radius: 30px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.35);
    position: relative;
    overflow: hidden;
    width: 768px;
    max-width: 100%;
    min-height: 480px;
}

.container p{
    font-size: 14px;
    line-height: 20px;
    letter-spacing: 0.3px;
    margin: 20px 0;
}

.container span{
    font-size: 12px;
}

.container a{
    color: #333;
    font-size: 13px;
    text-decoration: none;
    margin: 15px 0 10px;
}

.container button{
    background-color: #000000;
    color: #fff;
    font-size: 12px;
    padding: 10px 45px;
    border: 1px solid transparent;
    border-radius: 8px;
    font-weight: 600;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    margin-top: 10px;
    cursor: pointer;
}

.container button.hidden{
    background-color: transparent;
    border-color: #fff;
}

.container form{
    background-color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    padding: 0 40px;
    height: 100%;
}

.container input{
    background-color: #eee;
    border: none;
    margin: 8px 0;
    padding: 10px 15px;
    font-size: 13px;
    border-radius: 8px;
    width: 100%;
    outline: none;
}

.form-container{
    position: absolute;
    top: 0;
    height: 100%;
    transition: all 0.6s ease-in-out;
}

.sign-in{
    left: 0;
    width: 50%;
    z-index: 2;
}

.container.active .sign-in{
    transform: translateX(100%);
}

.sign-up{
    left: 0;
    width: 50%;
    opacity: 0;
    z-index: 1;
}

.container.active .sign-up{
    transform: translateX(100%);
    opacity: 1;
    z-index: 5;
    animation: move 0.6s;
}

@keyframes move{
    0%, 49.99%{
        opacity: 0;
        z-index: 1;
    }
    50%, 100%{
        opacity: 1;
        z-index: 5;
    }
}

.social-icons{
    margin: 20px 0;
}

.social-icons a{
    border: 1px solid #ccc;
    border-radius: 20%;
    display: inline-flex;
    justify-content: center;
    align-items: center;
    margin: 0 3px;
    width: 40px;
    height: 40px;
}

.toggle-container{
    position: absolute;
    top: 0;
    left: 50%;
    width: 50%;
    height: 100%;
    overflow: hidden;
    transition: all 0.6s ease-in-out;
    border-radius: 150px 0 0 100px;
    z-index: 1000;
}

.container.active .toggle-container{
    transform: translateX(-100%);
    border-radius: 0 150px 100px 0;
}

.toggle{
    background-color: #fa5300;
    height: 100%;
    background: linear-gradient(to right, #ff833e, #ff833e);
    color: #fff;
    position: relative;
    left: -100%;
    height: 100%;
    width: 200%;
    transform: translateX(0);
    transition: all 0.6s ease-in-out;
}

.container.active .toggle{
    transform: translateX(50%);
}

.toggle-panel{
    position: absolute;
    width: 50%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    padding: 0 30px;
    text-align: center;
    top: 0;
    transform: translateX(0);
    transition: all 0.6s ease-in-out;
}

.toggle-left{
    transform: translateX(-200%);
}

.container.active .toggle-left{
    transform: translateX(0);
}

.toggle-right{
    right: 0;
    transform: translateX(0);
}

.container.active .toggle-right{
    transform: translateX(200%);
}
img {
display: flex;
width: 100%;
}

a {
text-decoration: none;
transition: 0.3s;
}

body {
font-family: "Poppins", sans-serif;
}

nav {
position: fixed;
isolation: isolate;
width: 100%;
z-index: 9;
}

.nav__header {
padding: 1rem;
width: 100%;
display: flex;
align-items: center;
justify-content: space-between;
background-color: var(--primary-color);
}

.nav__logo a {
font-size: 1.75rem;
font-weight: 400;
font-family: var(--header-font);
color: var(--white);
}

.nav__menu__btn {
font-size: 1.5rem;
color: var(--white);
cursor: pointer;
}

.nav__links {
position: absolute;
top: 64px;
left: 0;
width: 100%;
padding: 2rem;
list-style: none;
display: flex;
align-items: center;
justify-content: center;
flex-direction: column;
gap: 2rem;
background-color: var(--primary-color);
transition: 0.5s;
z-index: -1;
transform: translateY(-100%);
}

.nav__links.open {
transform: translateY(0);
}

.nav__links a {
font-weight: 500;
color: var(--white);
}

.nav__links a:hover {
color: var(--text-dark);
}

.nav__btns {
display: none;
}
@media (width > 768px) {
nav {
position: static;
padding-block: 2rem 0;
padding-inline: 1rem;
max-width: var(--max-width);
margin-inline: auto;
display: flex;
align-items: center;
justify-content: space-between;
gap: 2rem;
}}

.nav__header {
flex: 1;
padding: 0;
background-color: transparent;
}

.nav__logo a {
color: var(--text-dark);
}

.nav__logo a span {
color: var(--primary-color);
}

.nav__menu__btn {
display: none;
}

.nav__links {
position: static;
padding: 0;
flex-direction: row;
background-color: transparent;
transform: none;
}

.nav__links a {
padding-block: 5px;
color: var(--text-dark);
border-bottom: 4px solid transparent;
}

.nav__links a:hover {
border-color: var(--primary-color);
}

.nav__btns {
display: flex;
flex: 1;
}

.nav__btns .btn {
padding: 0.75rem 1.5rem;
outline: none;
border: none;
font-size: 1rem;
white-space: nowrap;
border-radius: 10px;
transition: 0.3s;
cursor: pointer;
}

.sign__up {
color: var(--text-dark);
background-color: transparent;
}

.sign__up:hover {
color: var(--primary-color);
}

.sign__in {
color: var(--white);
background-color: var(--primary-color);
}

.sign__in:hover {
background-color: var(--primary-color-dark);
}   
</style>
</head>

 <body>
    <nav>
      <div class="nav__header">
        <div class="nav__logo">
          <a href="#">AYMEN<span>Travel</span>.</a>
        </div>
        <div class="nav__menu__btn" id="menu-btn">
          <span><i class="ri-menu-line"></i></span>
        </div>
      </div>
      <ul class="nav__links" id="nav-links">
        <li><a href="#">Destination</a></li>
        <li><a href="#">Package</a></li>
        <li><a href="#">Blog</a></li>
        <li><a href="#">About Us</a></li>
        <li><a href="#">Contact</a></li>
      </ul>
      <div class="nav__btns">
        <button class="btn sign__up">Sign Up</button>
        <button class="btn sign__in">Sign In</button>
      </div>
    </nav>
    <div class="container" id="container">
        <div class="form-container sign-up">
        <form action="index.php" method="POST">
    <h1>Create Account</h1>
    <div class="social-icons">
        <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
        <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
        <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
        <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
    </div>
    <span>or use your email for registration</span>
    <input type="text" name="name" placeholder="Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit" name="register">Sign Up</button>
</form>

<form action="index.php" method="POST">
    <h1>Sign In</h1>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit" name="login">Sign In</button>
</form>

        </div>
        <div class="form-container sign-in">
            <form action="index.php" method="post">
                <h1>Sign In</h1>
                <div class="social-icons">
                    <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
                <span>or use your email password</span>
                <input type="text" name="name" placeholder="Nom" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Mot de passe" required >
                <a href="#">Forget Your Password?</a>
                <button>Sign In</button>
            </form>
        </div>
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Welcome Back!</h1>
                    <p>Enter your personal details to use all of site features</p>
                    <button class="hidden" id="login">Sign In</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Hello, Friend!</h1>
                    <p>Register with your personal details to use all of site features</p>
                    <button class="hidden" id="register">Sign Up</button>
                </div>
            </div>
        </div>
    </div>
    <script>const container = document.getElementById('container');
        const registerBtn = document.getElementById('register');
        const loginBtn = document.getElementById('login');
        
        registerBtn.addEventListener('click', () => {
            container.classList.add("active");
        });
        
        loginBtn.addEventListener('click', () => {
            container.classList.remove("active");
        });</script>
    </body>
</html>    