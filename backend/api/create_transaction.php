<?php
include './get_conn.php';

function createTransaction($productId, $customerName, $customerEmail, $discountCode, $cardNumber, $cardExpireDate, $cvc)
{
    $getProductSql = "SELECT *
        FROM `testdb`.`product`
        WHERE `ProductID` = ?;";

    $createTransactionSql = "INSERT INTO `testdb`.`transaction`
        (`ProductName`,`Cost`,`DiscountID`,`CustomerName`,`CustomerEmail`,`CardNumber`,`CardExpireDate`,`CVC`)
        VALUES (?,?,?,?,?,?,?,?);";

    try {
        $conn = getConn();
        $getProductStmt = $conn->prepare($getProductSql);
        $getProductStmt->execute([$productId]);
        $product = $getProductStmt->fetch(PDO::FETCH_ASSOC);
        $productName = $product['ProductName'];
        $cost = $product['Cost'];
        $getProductStmt = null;
        $createTransactionStmt = $conn->prepare($createTransactionSql);
        echo $discountCode;
        $createTransactionStmt->execute([$productName, $cost, $discountCode, $customerName, $customerEmail, $cardNumber, $cardExpireDate, $cvc]);
        $createTransactionStmt = null;
    } catch (\Throwable $th) {
        throw $th;
    }
}

function main()
{
    $productId = $_POST['productId'];
    $customerEmail  = $_POST['customerEmail'];
    $customerName = $_POST['customerName'];
    $cardNumber = $_POST['16digitsCreditCard'];
    $cardExpireDate = $_POST['ExpirationDate'];
    $cvc = $_POST['cvc'];
    $discountCode = null;
    if (isset($_POST['discountCode']) && strlen(trim($_POST['discountCode'])) > 0) {
        $discountCode = $_POST['discountCode'];
    }
    createTransaction($productId, $customerName, $customerEmail, $discountCode, $cardNumber, $cardExpireDate, $cvc);
    try {
        echo "Success";
        header("Location: ../../frontend/admin/index.html");
    } catch (\Throwable $th) {
        echo "Error";
        throw $th;
    }
}

main();
