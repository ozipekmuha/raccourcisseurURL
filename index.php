<?php
if (isset($_GET["q"])) {
    $raccourcie = $_GET['q'];
    try {
        $bdd = new PDO('mysql:host=localhost;dbname=raccourcieURL;charset=utf8', 'root', '');
    } catch (Exception $e) {
        die("erreur:" . $e->getMessage());
    }

    $req = $bdd->prepare("SELECT COUNT(*) AS x FROM lien WHERE racourci=? ");
    $req->execute(array($raccourcie));

    while ($result = $req->fetch()) {
        if ($result["x"] != 1) {
             header('location:index.php?error=true&message=adresse url non connu');
            // var_dump($result);
            exit();
        }
    }
    $req = $bdd->prepare('SELECT * FROM lien WHERE racourci=?');
    $req->execute(array($raccourcie));

    while ($result = $req->fetch()) {
        header("location:" . $result['url']);
// var_dump($result);
        exit();
    }
}

// verification adresse valide :
if (isset($_POST["url"])) {

    $url = $_POST["url"];
    // filter var renvoi true normalement , mais on veut que sa sexecute au contraire
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        header("location:../?error=true&message=adresse non valide");
        exit();
    }
    $raccourcie = crypt($url, rand());

    // co bdd:
    try {
        $bdd = new PDO('mysql:host=localhost;dbname=raccourcieURL;charset=utf8', 'root', '');
    } catch (Exception $e) {
        die("erreur:" . $e->getMessage());
    }

    $req = $bdd->prepare("SELECT COUNT(*) AS x FROM lien WHERE url=? ");
    $req->execute(array($url));

    while ($result = $req->fetch()) {
        if ($result['x'] != 0) {
            header("location:index.php?error=true&message=zdresse deja raccourci");
        }
    }
    // envoi
    $req = $bdd->prepare("INSERT INTO lien(url,racourci)VALUES(?,?)");
    $req->execute(array($url, $raccourcie));
    header('location: index.php?short=' . $raccourcie);
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./design.css">
    <link rel="icon" type="image/png" href="./iconne.png">
    <title>Raccourcisseur d'url</title>
</head>

<body>
    <div id="hello">
        <div class="container">
            <header>
                <img id="logo" src="./image/logo.png" alt="logo de la societe">
            </header>
            <h1>UNE URL LONGUE ? RACCOURCISSEZ LA ? </h1>
            <h2>Largement meilleur et plus court que les autres</h2>

            <form method="POST" action="index.php">
                <input type="url" name="url" placeholder="collez un lien">
                <input type="submit" name="racourcir" value="raccourcir">
            </form>


            <?php
            if (isset($_GET['error']) && isset($_GET["message"])) {
            ?>
                <div class="center">
                    <div id="result">
                        <b><?php echo htmlspecialchars($_GET["message"]); ?></b>
                    </div>
                </div>
            <?php
            } else if (isset($_GET["short"])) {
            ?>
                <div class="center">
                    <div id="result">
                        <b>URL raccourcie :</b> http://localhost/ubuntu/projet/bitly/?q=<?php echo  htmlspecialchars($_GET["short"]); ?>
                    </div>
                </div>
            <?php
            }

            ?>

        </div>
    </div>

    <section class="brands">
        <div class="container2">
            <h3>Ces marques nous font confiance </h3>
            <img src="../bitly/image/1.png" class="picture">
            <img src="../bitly/image/2.png" class="picture">
            <img src="../bitly/image/3.png" class="picture">
            <img src="
            ../bitly/image/4.png" class="picture">

        </div>
    </section>


</body>

</html>