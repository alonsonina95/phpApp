<?php
include './get_conn.php';

function checkDiscountCode($discountId)
{
    try {
        $conn = getConn();
        $statement = $conn->prepare("SELECT ExpireDate FROM `testdb`.`discountcode` WHERE DiscountID=?");
        $statement->execute([$discountId]);
        return $statement->fetch();
    } catch (\PDOException $e) {
        throw new \PDOException($e->getMessage(), (int) $e->getCode());
    }
}

function main()
{
    try {
        $couponCode = $_POST['couponCode'];
        $responseFromDatabase = checkDiscountCode($couponCode);
        if (is_array($responseFromDatabase)) {
            $value = $responseFromDatabase['ExpireDate'];
            echo $value;
        } else {
            echo "404";
        }
    } catch (\Throwable $th) {
        echo "Error:<\br>" . $th;
    }
}
main();
