<?php
include './get_conn.php';

function addProduct($productName, $cost)
{
    $addProductSql = "INSERT INTO `testdb`.`product` (`ProductName`,`Cost`) VALUES (?,?)";
    try {
        $conn = getConn();
        $stmt = $conn->prepare($addProductSql);
        $stmt->execute([$productName, $cost]);
        $stmt = null;
    } catch (\PDOException $e) {
        throw new \PDOException($e->getMessage(), (int) $e->getCode());
    }
}

function main()
{
    try {
        $productName = $_POST['productName'];
        $cost = $_POST['cost'];
        addProduct($productName, $cost);
        echo "Success";
        header("Location: ../../frontend/admin/index.html");
    } catch (\Throwable $th) {
        echo "Error:<\br>" . $th;
    }
}
main();
