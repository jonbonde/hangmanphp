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
    <h1>Hangman</h1>
    <?php
    include("db-tilkobling.php");

    $sqlSetning = "SELECT * FROM startord ORDER BY RAND() LIMIT 1;";
    $sqlResultat = mysqli_query($db, $sqlSetning) or die("Ikke mulig å hente data");
    $rad = mysqli_fetch_array($sqlResultat);

    if (isset($_COOKIE["ord"]))
    {
        $ord = $_COOKIE["ord"];
    }
    else
    {
        $ord = $rad["ord"];
    }

    setcookie("ord", "$ord");

    error_reporting(E_ERROR | E_PARSE);
    $startOrd = $ord;

    if (isset($_COOKIE["skjulStartOrd"]))
    {
        $skjulStartOrd = $_COOKIE["skjulStartOrd"];
    }
    else
    {
        $skjulStartOrd = substr_replace($startOrd, str_repeat("-", strlen($startOrd)), 0, strlen($startOrd));
    }

    if (isset($_COOKIE["bruktBokstav"]))
    {
        $bruktBokstav = $_COOKIE["bruktBokstav"];
    }
    else
    {
        $bruktBokstav = "";
    }


    if (isset($_COOKIE["forsok"]))
    {
        $forsøk = $_COOKIE["forsok"];
        $start = false;
    }
    else
    {
        $forsøk = strlen($startOrd) * 2;
        $forsøk++;
        $start = true;
    }

    $gjettetBokstav = strtolower($_POST["gjettetBokstav"]);

    if ($start)
    {
        $bruktBokstav = $gjettetBokstav;
    }
    else
    {
        $bruktBokstav .= $gjettetBokstav . ", ";
    }

    setcookie("bruktBokstav", "$bruktBokstav");

    $pos = strpos($startOrd, $gjettetBokstav);
    if ($pos !== false)
    {
        for ($i = 0; $i < strlen($startOrd); $i++)
        {
            if ($startOrd[$i] == $gjettetBokstav)
            {

                $skjulStartOrd[$i] = $gjettetBokstav;
                setcookie("skjulStartOrd", "$skjulStartOrd");
            }
        }
    }
    $forsøk--;
    setcookie("forsok", $forsøk);

    $melding = "<form method='post' id='form'>
        <div class='ord'>Gjett ordet; <br> $skjulStartOrd</div> <br>
        Brukte bokstaver: $bruktBokstav <br>
        Du har $forsøk forsøk igjen <br>
        Ditt neste gjett? <input type='text' maxlength='1' name='gjettetBokstav' required> <br>
        <input type='submit' value='Gjett' name='gjettKnapp'>
        </form>";
    echo $melding;

    $pos1 = strpos($skjulStartOrd, "-");

    if ($pos1 === false && !$start)
    {
        echo "<h1><b>GRATULERER DU VANT!!</b></h1>";
        slett();
        echo "<article><a href='hangman.php' class='zoom'>Klikk for å spille igjen</a></article>";
    }
    else if ($forsøk == 0)
    {
        echo "<h1><b>GAME OVER!</b></h1>";
        slett();
        echo "<article><a href='hangman.php' class='zoom'>Klikk for å spille igjen</a></article>";
    }

    function slett()
    {
        setcookie("skjulStartOrd", "");
        setcookie("forsok", "");
        setcookie("bruktBokstav", "");
        setcookie("ord", "");
    }
    ?>
</body>

</html>