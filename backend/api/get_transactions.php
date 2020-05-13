<?php
include './get_conn.php';

function getTransactions()
{
    $results = array();
    try {
        $conn = getConn();
        foreach ($conn->query("SELECT * FROM `testdb`.`transaction`;", PDO::FETCH_NAMED) as $row) {
            array_push($results, $row);
        }
    } catch (\PDOException $e) {
        throw new \PDOException($e->getMessage(), (int) $e->getCode());
    }
    return $results;
}

function getTransactionsJson()
{
    return json_encode(getTransactions());
}

echo getTransactionsJson();
