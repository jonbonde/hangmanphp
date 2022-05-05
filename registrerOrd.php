<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hangman</title>
    <link rel="stylesheet" href="hangman.css">
</head>
<body>
    <h2>Registrer ord</h2>
    <form method="post">
        Ord <input type="text" name="ord" required> <br>
        <input type="submit" name="registrer" value="Registrer">
        <input type="reset" value="Nullstill">
    </form>

    <a href="hangman.php">Spill Hangman</a>

    <?php
    if(isset($_POST["registrer"]))
    {
        $ord = $_POST["ord"];

        if(!$ord)
        {
            echo "Vennligst skriv inn ett ord";
        }
        else
        {
            include("db-tilkobling.php");

            $sqlSetning = "INSERT INTO startord VALUES('$ord');";
            mysqli_query($db, $sqlSetning) or die ("Ikke mulig å registrere i databasen");

            echo "Du har registrert $ord til databasen";
        }
    }
    ?>
</body>
</html>