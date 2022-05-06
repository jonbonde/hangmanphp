<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hangman</title>
    <link rel="stylesheet" href="hangman.css">
</head>

<body class="bakgrunn">
    <h1>Registrer ord</h1>
    <form method="post">
        Ord <input type="text" name="ord" required> <br>
        <input type="submit" name="registrer" value="Registrer">
        <input type="reset" value="Nullstill">
    </form>
    <p class="center">
        <?php
        if (isset($_POST["registrer"]))
        {
            $ord = strtolower($_POST["ord"]);

            if (!ctype_alpha($ord))
            {
                echo "Registrer bare ett ord som kun består av bokstaver";
            }
            else if (!$ord)
            {
                echo "Vennligst skriv inn ett ord";
            }
            else
            {
                include("db-tilkobling.php");

                $sqlSetning = "INSERT INTO startord VALUES('$ord');";
                mysqli_query($db, $sqlSetning) or die("Ikke mulig å registrere i databasen <br><a href=\"hangman.php\">Spill Hangman</a>");

                echo "Du har registrert $ord til databasen";
            }
        }
        ?>
    </p>
    <article><a href="hangman.php" class="zoom">Spill Hangman</a><br>
        <a href="index.php" class="zoom">Tilbake</a>
    </article>
</body>

</html>