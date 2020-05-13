<?php
include './get_conn.php';

function getDiscountCodes()
{
    $results = array();
    try {
        $conn = getConn();
        foreach ($conn->query("SELECT * FROM `testdb`.`discountcode`;", PDO::FETCH_NAMED) as $row) {
            array_push($results, $row);
        }
    } catch (\PDOException $e) {
        throw new \PDOException($e->getMessage(), (int) $e->getCode());
    }
    return $results;
}

function getDiscountCodesJson()
{
    return json_encode(getDiscountCodes());
}

echo getDiscountCodesJson();
