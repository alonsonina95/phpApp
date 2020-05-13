<?php
include "get_conn.php";

function get_transactions_associated_with_code($discountId)
{
    $getAssociatedTransactionsSql = "SELECT *
    FROM `testdb`.`transaction`
    WHERE `DiscountID` = ?;";

    $results = null;

    try {
        $conn = getConn();
        $getAssociatedTransactionsStmt = $conn->prepare($getAssociatedTransactionsSql);
        $getAssociatedTransactionsStmt->execute([$discountId]);
        $results = $getAssociatedTransactionsStmt->fetchAll(PDO::FETCH_NAMED);
        $getAssociatedTransactionsStmt = null;
    } catch (\PDOException $e) {
        throw new \PDOException($e->getMessage(), (int) $e->getCode());
    }
    return $results;
}

function get_transactions_associated_with_code_json($discountId)
{
    return json_encode(get_transactions_associated_with_code($discountId));
}

function main()
{
    $discountId = $_POST["associatedTransactionsDiscountId"];
    echo get_transactions_associated_with_code_json($discountId);
}

main();
