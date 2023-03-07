<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Basics</title>
    <style>
        body {
            display: grid;
            place-items: center;
            height: 100vh;
            margin: 0;
            font-family: sans-serif;
        }
    </style>
</head>
<body>
    <h1>
        <?php foreach ($fitleredBooks as $book): ?>
            <li>
                <a href="<?= $book["purchaseUrl"] ?>">
                    <?= $book["name"] ?> (<?= $book["releaseYear"] ?>) - By <?= $book["author"] ?>
                </a>
            </li>    
        <?php endforeach; ?>
    </h1>
</body>
</html>