<?php
include './get_conn.php';
function getNewCodes()
{
  $results = array();
  $getNewCodesSql = "SELECT *
  FROM `testdb`.`discountcode`
  WHERE `CreateDate` >= CURDATE() - INTERVAL 1 DAY
  AND `CreateDate` <= CURDATE();";
  try {
    $conn = getConn();
    foreach ($conn->query($getNewCodesSql, PDO::FETCH_NAMED) as $row) {
      array_push($results, $row);
    }
  } catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int) $e->getCode());
  }
  return $results;
}

function getNewCodesJson()
{
  return json_encode(getNewCodes());
}

function main()
{
  echo getNewCodesJson();
}

main();
