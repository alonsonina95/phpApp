<?php
include "get_conn.php";

function getExpiredDiscountCodes()
{
  $results = array();
  $getExpiredDiscountCodeSql = "SELECT * FROM testdb.discountcode WHERE ExpireDate <= CURDATE()";
  try {
    $conn = getConn();
    foreach ($conn->query($getExpiredDiscountCodeSql, PDO::FETCH_NAMED) as $row) {
      array_push($results, $row);
    }
  } catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int) $e->getCode());
  }
  return $results;
}

function getExpiredDiscountCodesJson()
{
  return json_encode(getExpiredDiscountCodes());
}

function main()
{
  try {
    echo getExpiredDiscountCodesJson();
  } catch (\Throwable $th) {
    throw $th;
  }
}

main();
