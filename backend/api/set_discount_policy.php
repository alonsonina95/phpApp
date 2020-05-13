<?php
include "./get_conn.php";

function setDiscountPolicy($discountId, $newPolicy)
{
    $updateDiscountCodePolicySql = "UPDATE `testdb`.`discountcode`
        SET `DiscountPolicy` = ?
        WHERE `DiscountID` = ?;";
    try {
        $conn = getConn();
        $stmt = $conn->prepare($updateDiscountCodePolicySql);
        $stmt->execute([$newPolicy, $discountId]);
        $stmt = null;
    } catch (\PDOException $e) {
        throw new \PDOException($e->getMessage(), (int) $e->getCode());
    }
}

function main()
{
    try {
        $discountId = $_POST['discountId'];
        $newPolicy = $_POST['newPolicy'];
        setDiscountPolicy($discountId, $newPolicy);
        echo "Success";
        header("Location: ../../frontend/admin/index.html");
    } catch (\Throwable $th) {
        echo "Invalid POST parameters:</br>" . $th;
    }
}
main();