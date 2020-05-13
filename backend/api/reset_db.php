<?php
function reset_db()
{
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
    $dropDbSql = "DROP DATABASE IF EXISTS `testdb`;";
    $createDbSql = "CREATE DATABASE IF NOT EXISTS `testdb`;";
    $createProductTableSql = "CREATE TABLE IF NOT EXISTS `testdb`.`product` (
                `ProductID` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `ProductName` VARCHAR(100) NOT NULL,
                `Cost` REAL NOT NULL,
                PRIMARY KEY (`ProductID`))
                ENGINE = MyISAM;";
    $createTransactionTableSql = "CREATE TABLE IF NOT EXISTS `testdb`.`transaction` (
                `TransactionID` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `ProductName` VARCHAR(100) NOT NULL,
                `Cost` REAL NOT NULL,
                `DiscountID` BIGINT UNSIGNED,
                `CustomerName` VARCHAR(100) NOT NULL,
                `CustomerEmail` VARCHAR(100) NOT NULL,
                `CardNumber` VARCHAR(20) NOT NULL,
                `CardExpireDate` Date NOT NULL,
                `CVC` VARCHAR(5) NOT NULL,
                PRIMARY KEY (`TransactionID`))
                ENGINE = MyISAM;";
    $createDiscountCodeTableSql = "CREATE TABLE IF NOT EXISTS `testdb`.`discountcode` (
                `DiscountID` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `DiscountPolicy` VARCHAR(100) NOT NULL DEFAULT '1PERCENTOFFEACH',
                `CreateDate` DATE NOT NULL,
                `ExpireDate` DATE NOT NULL,
                PRIMARY KEY (`DiscountID`))
                ENGINE = MyISAM;";
    try {
        $conn = new PDO($dsn, $user, $pass, $options);
        $conn->exec($dropDbSql);

        $conn->exec($createDbSql);
        $conn->exec($createProductTableSql);
        $conn->exec($createTransactionTableSql);
        $conn->exec($createDiscountCodeTableSql);
    } catch (\PDOException $e) {
        throw new \PDOException($e->getMessage(), (int) $e->getCode());
    }
}

function main()
{
    reset_db();
}

main();
header("Location: ../../frontend/admin/index.html");
