<?php
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" type="image" href="https://wallpaperaccess.com/full/2811067.jpg">
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
    $ord = preg_split('//u', $ord, -1, PREG_SPLIT_NO_EMPTY);
    setcookie("ord", "$ord");
    
    error_reporting(E_ERROR | E_PARSE);
    $startOrd = implode("", $ord);

    if (isset($_COOKIE["skjulStartOrd"]))
    {
        $skjulStartOrd = $_COOKIE["skjulStartOrd"];
    }
    else
    {
        $skjulStartOrd = substr_replace($startOrd, str_repeat("-", mb_strlen($startOrd)), 0, strlen($startOrd));
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
        $forsøk = mb_strlen($startOrd) * 2;
        $forsøk++;
        $start = true;
    }

    $gjettetBokstav1 = $_POST["gjettetBokstav"];
    $gjettetBokstav2 = strtolower(htmlentities($gjettetBokstav1));
    $gjettetBokstav = html_entity_decode($gjettetBokstav2);

    if ($start)
    {
        $bruktBokstav = $gjettetBokstav;
    }
    else
    {
        $bruktBokstav .= $gjettetBokstav . ", ";
    }

    setcookie("bruktBokstav", "$bruktBokstav");

    $pos = mb_strpos($startOrd, $gjettetBokstav);
    if ($pos !== false)
    {
        $i = 0;
        $skjulOrdArray = preg_split('//u', $skjulStartOrd, -1, PREG_SPLIT_NO_EMPTY);
        
        foreach ($ord as $bokstav)
        {
            if ($bokstav == $gjettetBokstav)
            {
                echo "hhoi";
                $skjulOrdArray[$i] = $gjettetBokstav;
            }
            $i++;
        }
        $skjulStartOrd = implode("", $skjulOrdArray);
        setcookie("skjulStartOrd", "$skjulStartOrd");
    }
    $forsøk--;
    setcookie("forsok", $forsøk);

    $melding = "<form method='post' id='form'> $startord <br>
        <div class='ord'>Gjett ordet; <br> $skjulStartOrd</div> <br>
        Brukte bokstaver: $bruktBokstav <br>
        Du har $forsøk forsøk igjen <br>
        Ditt neste gjett? <input type='text' maxlength='1' name='gjettetBokstav' required> <br>
        <input type='submit' value='Gjett' name='gjettKnapp'>
        </form>";
    echo $melding;

    $pos1 = mb_strpos($skjulStartOrd, "-");

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