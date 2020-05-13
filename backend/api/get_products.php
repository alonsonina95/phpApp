<?php
include './get_conn.php';

function getProducts()
{
    $results = array();
    try {
        $conn = getConn();
        foreach ($conn->query("SELECT * FROM `testdb`.`product`;", PDO::FETCH_NAMED) as $row) {
            array_push($results, $row);
        }
    } catch (\PDOException $e) {
        throw new \PDOException($e->getMessage(), (int) $e->getCode());
    }
    return $results;
}

function getProductsJson()
{
    return json_encode(getProducts());
}

echo getProductsJson();
