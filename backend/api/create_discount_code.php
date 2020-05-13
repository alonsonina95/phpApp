<?php

include './get_conn.php';

define("MAX_VALUE_INT_UNSIGNED", 4294967295);
define("MIN_VALUE_INT_UNSIGNED", 0);

function createDiscountCode()
{
    $discountCode = random_int(MIN_VALUE_INT_UNSIGNED, MAX_VALUE_INT_UNSIGNED);
    $defaultPolicy = "1PERCENTOFFEACH";
    $createDate = date("Y-m-d");
    $expireDate = date("Y-m-d", strtotime("+7 days"));
    $addDiscountCodeSql = "INSERT INTO `testdb`.`discountcode` (DiscountID, DiscountPolicy, CreateDate, ExpireDate) VALUES (?,?,?,?)";
    try {
        $conn = getConn();
        $stmt = $conn->prepare($addDiscountCodeSql);
        $stmt->execute([$discountCode, $defaultPolicy, $createDate, $expireDate]);
        $stmt = null;
    } catch (\PDOException $e) {
        throw new \PDOException($e->getMessage(), (int) $e->getCode());
    }
    return $discountCode;
}

function main()
{
    try {
        $code_created = createDiscountCode();
        echo $code_created;
        // echo "Success. <br> Created discount code " . $code_created . "<a href='../../frontend/admin/index.html'>Go back to admin page</a>";
        // header("Location: ../../frontend/admin/index.html");
    } catch (\Throwable $th) {
        echo "Error:<\br>" . $th;
    }
}

main();
