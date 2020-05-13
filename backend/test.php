<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Test</title>
</head>

<body>
    <a href="./functions.php">Test that database is working</a>
    <?php
    $host = 'localhost';
    $db   = '';
    $user = 'root';
    $pass = '';
    $charset = 'utf8mb4';
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";


    echo "<h1>Test</h1>";
    try {
        $conn = new PDO($dsn, $user, $pass, $options);
        echo "<h2>Databases</h2>";
        foreach ($conn->query("Show databases;", PDO::FETCH_NUM) as $row) {
            echo $row[0] . '<br/>';
        }
        echo "<h2>Tables</h2>";
        $conn->exec("USE `testdb`;");
        foreach ($conn->query("Show tables;", PDO::FETCH_NUM) as $row) {
            echo $row[0] . '<br/>';
        }
    } catch (\PDOException $e) {
        throw new \PDOException($e->getMessage(), (int) $e->getCode());
    }
    ?>
</body>

</html>