<?php
include "./get_conn.php";

function computeTransactionDiscount($discountId)
{
    $policyPercents = [
        "1PERCENTOFFEACH" => 1,
        "2PERCENTOFFEACH" => 2,
        "3PERCENTOFFEACH" => 3,
        "4PERCENTOFFEACH" => 4,
        "5PERCENTOFFEACH" => 5
    ];
    try {
        $conn = getConn();
        $getPolicyStatement = $conn->prepare("SELECT DiscountPolicy from `testdb`.`discountcode` WHERE DiscountID = ?");
        $getPolicyStatement->execute([$discountId]);

        $response = $getPolicyStatement->fetch();
        $discountPolicy = $response['DiscountPolicy'];
        $percent = $policyPercents[$discountPolicy];
        $getPolicyStatement = null;

        $getTransactionCountstatement = $conn->prepare("SELECT COUNT(`TransactionID`) as TransactionCount
        FROM `testdb`.`transaction`
        WHERE `DiscountID` = ?;");

        $getTransactionCountstatement->execute([$discountId]);
        $response = $getTransactionCountstatement->fetch();
        $numTransactions = (int) $response['TransactionCount'];

        $getTransactionCountstatement = null;

        return $numTransactions * $percent;
    } catch (\PDOException $e) {
        throw new \PDOException($e->getMessage(), (int) $e->getCode());
    }
}

function main()
{
    try {
        $discountId = $_POST['computeTransactionDiscountDiscountId'];
        $response = computeTransactionDiscount($discountId);
        echo $response;
    } catch (\Throwable $th) {
        echo "Error:</br>" . $th;
    }
}

main();
