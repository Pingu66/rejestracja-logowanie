<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marcin Logowanie</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<body>
    <?php
    if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'login') {
        $db = new mysqli("localhost", "root", "", "auth");

        $email = $_REQUEST['Email'];
        $password = $_REQUEST['Password'];


        $email = filter_var($email, FILTER_SANITIZE_EMAIL);


        $q = $db->prepare("SELECT * FROM dane WHERE email=? LIMIT 1");
        $q->bind_param("s", $email);
        $q->execute();

        $result = $q->get_result();

        $userRow = $result->fetch_assoc();
        if ($userRow == null) {
            echo "Zły login lub hasło <br>";
        } else {
            if (password_verify($password, $userRow['PasswordHash'])) {
                echo "Działa <br>";
            } else {
                echo "Zły login lub hasło  <br>";
            }
        }
    }
    if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'register') {
        $db = new mysqli("localhost", "root", "", "auth");
        $email = $_REQUEST['Email'];
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $name = $_REQUEST['Name'];
        $surname = $_REQUEST['Surname'];

        $password = $_REQUEST['Password'];
        $passwordRepeat = $_REQUEST['PasswordRepeat'];
        if ($password == $passwordRepeat) {
            $q = $db->prepare("INSERT INTO dane VALUES (NULL, ?, ?, ?, ?)");
            $passwordHash = password_hash($password, PASSWORD_ARGON2I);
            $q->bind_param("ssss", $email, $passwordHash, $name, $surname);
            $result = $q->execute();
            if ($result) {
                echo "Konto zostało utworzone";
            } else {
                echo "Nie działa!";
            }
        } else {
            echo "Hasła się nie zgadzają";
        }
    }
    ?>
    <h1>Zaloguj Się</h1>
    <form action='login.php' method="post">
        <label for="emailInput">Email:</label>
        <input type="email" name="Email" id="emailInput">
        <label for="passwordInput">Hasło:</label>
        <input type="password" name="Password" id="passwordInput">
        <input type="hidden" name="action" value="login">
        <input type="submit" value="Zaloguj">
    </form>

    <h1>Zarejestruj Się</h1>
    <form action="login.php" method="post">
         <label for="emailInput">Email:</label>
         <input type="email" name="Email" id="emailInput">
         <label for="PasswordInput">Hasło:</label>
         <input type="password" name="Password" id="passwordInput">
         <label for="passwordInput">Powtórz hasło:</label>
         <input type="password" name="PasswordRepeat" id="PasswordRepeatInput">
         <label for="NameInput">Imię:</label>
         <input type="text" name="Name" id="NameInput">
         <label for="ForenameInput">Nazwisko:</label>
         <input type="text" name="Surname" id="SurnameInput">
         <input type="hidden" name="action" value="register">
         <input type="submit" value="Zarejestruj">
    </form>
 
</body>

</html>
