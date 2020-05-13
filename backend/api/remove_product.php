<?php
include 'get_conn.php';

function removeProduct($productId)
{
    $removeProductSql = "DELETE FROM `testdb`.`product` WHERE ProductID = ?;";
    try {
        $conn = getConn();
        $stmt = $conn->prepare($removeProductSql);
        $stmt->execute([$productId]);
        $stmt = null;
    } catch (\PDOException $e) {
        throw new \PDOException($e->getMessage(), (int) $e->getCode());
    }
}

function main()
{
    try {
        $productId = $_POST['removeProductId'];
        removeProduct($productId);
        echo "Success";
        header("Location: ../../frontend/admin/index.html");
    } catch (\Throwable $th) {
        echo "Invalid POST parameters";
    }
}

main();
