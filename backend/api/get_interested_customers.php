<?php
include './get_conn.php';
function getInterestedCustomers($discountId)
{
  // $getInterestedCustomersSql = "SELECT `CustomerName`, `CustomerEmail`
  // FROM (SELECT *
  //       FROM  testdb.transaction t
  //       WHERE t.ProductName IN (SELECT ProductName
  //                               FROM testdb.transaction
  //                               WHERE DiscountID = :id)
  //       AND `DiscountID` != :id);";
  $getInterestedCustomersSql = "SELECT `CustomerName`, `CustomerEmail`
  FROM
      (
        SELECT *
        FROM `testdb`.`transaction`
        WHERE `ProductName` IN
          (
            SELECT `ProductName`
            FROM `testdb`.`transaction`
            WHERE `DiscountID` = ?
          )
      ) AS Filtered
  WHERE `DiscountID` != ?;";
  try {
    $conn = getConn();
    $getInterestedCustomersStmt = $conn->prepare($getInterestedCustomersSql);
    $getInterestedCustomersStmt->execute([$discountId, $discountId]);
    // $getInterestedCustomersStmt->execute();
    $results = $getInterestedCustomersStmt->fetchAll(PDO::FETCH_NAMED);
    $getInterestedCustomersStmt = null;
  } catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int) $e->getCode());
  }
  return $results;
}

function getInterestedCustomersJson($discountId)
{
  return json_encode(getInterestedCustomers($discountId));
}

function main()
{

  $discountId = $_POST['getInterestedCustomersId'];
  echo getInterestedCustomersJson($discountId);
}

main();
