function stringIsNumeric(str) {
    return /^\d+$/.test(str);
}

async function getProducts() {
    const response = await fetch("../../backend/api/get_products.php");
    return await response.json();
}

async function getExpiredDiscountCodes() {
    const response = await fetch("../../backend/api/get_expired_discount_codes.php");
    return await response.json();
}


async function init() {
    const productsBtn = document.getElementById("getProductsBtn");
    productsBtn.onclick = async function () {
        const productsJsonDiv = document.getElementById("productsJsonDiv");
        const response = await fetch("../../backend/api/get_products.php");
        const result = await response.text();
        productsJsonDiv.innerText = result;
    }
    const transactionsBtn = document.getElementById("getTransactionsBtn");
    transactionsBtn.onclick = async function () {
        const transactionsJsonDiv = document.getElementById("transactionsJsonDiv");
        const response = await fetch("../../backend/api/get_transactions.php");
        const result = await response.text();
        transactionsJsonDiv.innerText = result;
    }
    const discountCodesBtn = document.getElementById("getDiscountCodesBtn");
    discountCodesBtn.onclick = async function () {
        const discountCodesJsonDiv = document.getElementById("discountCodesJsonDiv");
        const response = await fetch("../../backend/api/get_discount_codes.php");
        const result = await response.text();
        discountCodesJsonDiv.innerText = result;
    }
    const createDiscountCodeBtn = document.getElementById("createDiscountCodeBtn");
    createDiscountCodeBtn.onclick = async function (event) {
        event.preventDefault();
        const responseDiv = document.getElementById("createdDiscountCodeDiv");
        const response = await fetch("../../backend/api/create_discount_code.php", {
            method: "POST"
        });
        const result = await response.text();
        if (stringIsNumeric(result)) {
            responseDiv.innerText = result
        } else {
            responseDiv.innerText = "Error";
        }
    }

    const computeTransactionDiscountBtn = document.getElementById("computeTransactionDiscountBtn");
    computeTransactionDiscountBtn.onclick = async function (event) {
        event.preventDefault();
        const responseDiv = document.getElementById("computeTransactionDiscountResponseDiv");
        const body = new FormData(document.getElementById("computeTransactionDiscountForm"))
        const response = await fetch("../../backend/api/compute_transaction_discount.php", {
            method: "POST",
            body
        });
        const result = await response.text();
        // check if the response only contains numbers as it should
        // if I had a way of just setting the status code of the response in php
        // of a failed request to 500 and checking that I would much rather do that
        if (stringIsNumeric(result)) {
            responseDiv.innerText = `${result}% off for each customer`;
        } else {
            responseDiv.innerText = "Error";
        }
    }

    const productsToRemoveSelect = document.getElementById("removeProductId");
    const products = await getProducts();
    if (products.length === 0) {
        productsToRemoveSelect.parentElement.removeChild(productsToRemoveSelect);
        const productsToRemoveLabel = document.querySelector("label[for='removeProductId']");
        productsToRemoveLabel.innerText = "No products to remove";
    } else {
        products.forEach(product => {
            console.log(product);
            const option = document.createElement("option")
            option.innerText = product.ProductName
            option.value = product.ProductID
            productsToRemoveSelect.appendChild(option)
        });
    }

    const expiredDiscountCodesList = document.getElementById("expiredDiscountCodesList");
    const expiredDiscountCodes = await getExpiredDiscountCodes();
    expiredDiscountCodes.forEach(code => {
        const li = document.createElement("li");
        li.innerText = code.DiscountID;
        expiredDiscountCodesList.appendChild(li);
    });

    const getNewCodesBtn = document.getElementById("getNewCodesBtn");
    const newCodesList = document.getElementById("newCodesList");
    getNewCodesBtn.onclick = async (event) => {
        event.preventDefault();
        while (newCodesList.firstChild)
            newCodesList.removeChild(newCodesList.firstChild)
        const body = new FormData(document.getElementById("getNewCodesForm"))
        const response = await fetch("../../backend/api/getNewCodes.php");
        const newCodes = await response.json();
        newCodes.forEach(code => {
            const li = document.createElement('li');
            li.innerText = code['DiscountID'];
            newCodesList.appendChild(li);
        });
    }

    const getInterestedCustomersBtn = document.getElementById("getInterestedCustomersBtn");
    const interestedCustomersList = document.getElementById("interestedCustomersList");
    getInterestedCustomersBtn.onclick = async (event) => {
        event.preventDefault();
        while (interestedCustomersList.firstChild)
            interestedCustomersList.removeChild(interestedCustomersList.firstChild)
        const body = new FormData(document.getElementById("getInterestedCustomersForm"))
        const response = await fetch("../../backend/api/get_interested_customers.php", {
            method: "POST",
            body
        });
        const interestedCustomers = await response.json();
        interestedCustomers.forEach(customer => {
            const li = document.createElement('li');
            li.innerText = `${customer['CustomerName']} - ${customer['CustomerEmail']}`;
            interestedCustomersList.appendChild(li);
        });
    }

    const getAssociatedTransactionsBtn = document.getElementById("associatedTransactionsBtn");
    const associatedTransactionsTable = document.getElementById("associatedTransactionsTable");
    getAssociatedTransactionsBtn.onclick = async (event) => {
        event.preventDefault();
        while (associatedTransactionsTable.rows.length > 0)
            associatedTransactionsTable.deleteRow(0)
        const body = new FormData(document.getElementById("associatedTransactionsForm"))
        const response = await fetch("../../backend/api/get_transactions_associated_with_code.php", {
            method: "POST",
            body
        });
        const associatedTransactions = await response.json();
        associatedTransactions.forEach(transaction => {
            associatedTransactionsTable.insertRow(0);
            for (const field in transaction) {
                const td = document.createElement("td");
                td.innerText = transaction[field];
                associatedTransactionsTable.rows[0].appendChild(td);
            }
        });
    }
}

if (document.readyState !== 'loading') {
    init();
} else {
    document.onreadystatechange = async (event) => {
        if (event.target.readyState === 'complete') {
            await init()
        }
    };
}