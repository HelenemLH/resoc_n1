<?php
session_start();
?>
<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>ReSoC - Connexion</title>
    <meta name="author" content="Julien Falconnet">
    <link rel="stylesheet" href="assets/css/style.css" />
</head>

<body>
    <?php include 'includes/header.php'; ?>

    <div id="wrapper">

        <aside>
            <h2>Présentation</h2>
            <p>Bienvenue sur notre réseau social.</p>
        </aside>
        <main>
            <article>
                <h2>Connexion</h2>
                <?php
                include 'database/connect.php';

                // Etape 1 : vérifier si on est en train d'afficher ou de traiter le formulaire
                // si on reçoit un champ email rempli il y a une chance que ce soit un traitement
                $enCoursDeTraitement = isset($_POST['email']);
                if ($enCoursDeTraitement) {
                    // Etape 2: récupérer ce qu'il y a dans le formulaire
                    $emailAVerifier = $_POST['email'];
                    $passwdAVerifier = $_POST['motpasse'];

                    // Etape 4 : Sécurité contre l'injection SQL
                    $emailAVerifier = $mysqli->real_escape_string($emailAVerifier);
                    $passwdAVerifier = $mysqli->real_escape_string($passwdAVerifier);

                    // on crypte le mot de passe
                    $passwdAVerifier = md5($passwdAVerifier);

                    // Etape 5 : construction de la requête
                    $lInstructionSql = "SELECT * FROM users WHERE email LIKE '" . $emailAVerifier . "'";

                    // Etape 6: Vérification de l'utilisateur
                    $res = $mysqli->query($lInstructionSql);
                    $user = $res->fetch_assoc();

                    if (!$user || $user["password"] != $passwdAVerifier) {
                        echo "La connexion a échouée.";
                    } else {
                        echo "Votre connexion est un succès : " . $user['alias'] . ".";
                        // Etape 7 : Se souvenir que l'utilisateur s'est connecté pour la suite
                        $_SESSION['connected_id'] = $user['id'];
                    }
                }
                ?>
                <form action="login.php" method="post">
                    <dl>
                        <dt><label for='email'>E-Mail</label></dt>
                        <dd><input type='email' name='email'></dd>
                        <dt><label for='motpasse'>Mot de passe</label></dt>
                        <dd><input type='password' name='motpasse'></dd>
                    </dl>
                    <input type='submit' value="Se connecter">
                </form>
                <p>
                    Pas de compte?
                    <a href='registration.php'>Inscrivez-vous.</a>
                </p>

            </article>
        </main>
    </div>
</body>

</html>