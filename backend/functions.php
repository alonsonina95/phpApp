<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
</head>

<body>

    <h1>Testing PHP Functionality</h1>
    <form action="./api/add_product.php" method="POST">
        <label for="productName">Name</label>
        <input type="text" name="productName" id="productName">
        <label for="cost">Cost</label>
        <input type="decimal" name="cost" id="cost">
        <input type="submit" value="Add product" name="addProductBtn">
    </form>
    <form action="./api/reset_db.php" method="post">
        <input type="submit" value="Reset DB" name="resetDbBtn">
    </form>
    <form action="./api/create_transaction.php" method="POST">
        <label for="productId">Product ID</label>
        <input required type="number" name="productId" id="productId">
        <label for="customerName">Customer Name</label>
        <input required type="text" name="customerName" id="customerName">
        <label for="customerEmail">Customer Email</label>
        <input required type="email" name="customerEmail" id="customerEmail">
        <label for="discountCode">Discount Code</label>
        <input type="email" name="discountCode" id="discountCode">
        <input type="submit" value="Make transaction">
    </form>
    <h2>Products JSON</h2>
    <button id="getProductsBtn">Get products JSON</button>
    <div id="productsJsonDiv">
    </div>
    <h2>Transactions JSON</h2>
    <button id="getTransactionsBtn">Get transactions JSON</button>
    <div id="transactionsJsonDiv">
    </div>
    <h2>DiscountCodes JSON</h2>
    <script>
        const productsBtn = document.getElementById("getProductsBtn");
        productsBtn.onclick = async function() {
            const productsJsonDiv = document.getElementById("productsJsonDiv");
            const response = await fetch("./api/get_products.php");
            const result = await response.text();
            productsJsonDiv.innerText = result;
        }
        const transactionsBtn = document.getElementById("getTransactionsBtn");
        transactionsBtn.onclick = async function() {
            const transactionsJsonDiv = document.getElementById("transactionsJsonDiv");
            const response = await fetch("./api/get_transactions.php");
            const result = await response.text();
            transactionsJsonDiv.innerText = result;
        }
    </script>

</body>

</html>