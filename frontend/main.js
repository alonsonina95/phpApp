var enableCart = false;
var totalSum = 0;

//Populate product table with rows of products
async function fillTable() {
    var table = document.getElementById('productTable');


    var select = 0;
    if (table.className.localeCompare("selection") == 0) {
        select = 1;
    } else if (table.className.localeCompare("selectable") == 0) {
        select = 2;
    }


    let productList = await gp();; //This is where we will have the json product pull
    for (var i = 0; i < productList.length; i++) {
        // Note, be sure to use let here NOT var
        let product = productList[i];
        var row = table.insertRow(i);
        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);

        row.setAttribute('id', 'standard');

        if (select == 1) {
            row.setAttribute('id', 'selectoff');
            row.setAttribute('onclick', selectRow(this.id, this));
            row.onclick = function () {
                selectRow(this.id, this);
            };
        } else if (select == 2) {
            // product argument added by Brent
            row.setAttribute('onclick', addItem(this, product.ProductID));
            row.onclick = function () {
                // product argument added by Brent
                addItem(this, product.ProductID);
            };
        }

        cell1.innerHTML = product.ProductName;
        cell2.innerHTML = "$" + product.Cost.toFixed(2);
    }
    enableCart = true;
}

//Access json from php
async function gp() {
    const response = await fetch("../backend/api/get_products.php");
    const result = await response.json();
    return result;
}

//Toggle rows for deletion
function selectRow(x, row) {
    switch (x) {
        case "selectoff":
            row.setAttribute('id', 'selecton');
            break;
        case "selecton":
            row.setAttribute('id', 'selectoff');
            break;
    }
    selectCheck();
}

//select/deselect all rows
function selectAll(x) {
    var table = document.getElementById('productTable');
    for (var i = 0, row; row = table.rows[i]; i++) {
        if (x == 0) row.setAttribute('id', 'selecton');
        else row.setAttribute('id', 'selectoff');
    }
    selectCheck();
}

//toggle buttons if any rows are selected
function selectCheck() {
    var sRow = document.getElementById('selecton');
    var b1 = document.getElementById('deselectAll');
    var b2 = document.getElementById('delete');
    if (sRow != null) {
        b1.style.display = "block";
        b2.style.display = "block";
    } else {
        b1.style.display = "none";
        b2.style.display = "none";
    }
}

//delete selected rows
function deleteSelection() {
    var query = document.querySelectorAll('[id=selecton]');
    for (var i = 0; i < query.length; i++) {
        var row = query[i];
        row.parentNode.removeChild(row);
    }
    selectCheck();
    //This only deletes the html row, needs to connect to php somehow to delete product from there
}

//not working
function addRow() {
    var field = document.getElementById('newRow');
    var name = field.childNodes[0]; //Should be input 1
    var price = field.childNodes[1]; //Should be input 2
    if (name != null && price != null) {
        var table = document.getElementById('productTable');
        var row = table.insertRow(table.length);
        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);
        row.setAttribute('id', 'selectoff');
        row.setAttribute('onclick', selectRow(this.id, this));
        row.onclick = function () {
            selectRow(this.id, this);
        };
        cell1.innerHTML = name;
        cell2.innerHTML = "$" + price.toFixed(2);
    }

}

//adds item to cart (doesn't need to be async unless cart is reflected in php)
// product parameter added by Brent
async function addItem(row, productId) {
    if (enableCart) {
        var table = document.getElementById('cartTable');
        var length = table.rows.length;
        var counter;
        var flag = 0;
        var name = row.cells[0].innerHTML;
        var price = row.cells[1].innerHTML;
        for (var i = 0; i < length; i++) {
            counter = 0;
            //Check table to see if item is there, if so, increment by 1
            if (table.rows[i].cells[0].innerHTML.localeCompare(name) == 0) {
                /*This increments item
                counter += parseInt(table.rows[i].cells[1].innerHTML) + 1;
                table.rows[i].cells[1].innerHTML = counter;
                price = price.replace("$", "");
                price = parseFloat(price);
                table.rows[i].cells[2].innerHTML = "$" + (counter * price).toFixed(2);
                */
                flag = 1;
                break;
            }
        }
        ////Item isn't in table
        if (flag == 0) {
            var row = table.insertRow(length);
            var cell1 = row.insertCell(0);
            var cell2 = row.insertCell(1);
            var cell3 = row.insertCell(2);
            var cell4 = row.insertCell(3);

            document.getElementById("productId").value = productId;

            cell1.innerHTML = name;
            cell2.innerHTML = 1;
            cell3.innerHTML = price;

            var btn = document.createElement('input');
            btn.type = "button";
            btn.id = "btn";
            btn.value = "X";
            btn.onclick = function ()
            {
                deleteItem(this)
            };
            cell4.appendChild(btn);
        }
        countTotal(table);
    }
}

//Calculate the total
function countTotal(table) {
    var totalText = document.getElementById('cartTotal');
    var total = 0;
    var value;
    for (var i = 0; i < table.rows.length; i++) {
        value = table.rows[i].cells[2].innerHTML;
        value = value.replace("$", "");
        total += parseFloat(value);
    }
    totalSum = total.toFixed(2);
    totalText.innerHTML = "Total: $" + totalSum;
    
}

//delete item from cart
function deleteItem(row) {
    var x = row.parentNode.parentNode.rowIndex;
    document.getElementById('cartTable').deleteRow(x);

    // Added by Brent
    document.getElementById("productId").value = null;

    countTotal(document.getElementById('cartTable'));
}

function create_transaction(form) {
    if(totalSum > 0)
    {
        var name = form.elements[1].value;
        var email = form.elements[3].value;
        var num = form.elements[5].value;
        var card = form.elements[8].value;
        var expDate = form.elements[10].value;
        var cvc = form.elements[12].value;
        var coupon = form.elements[14].value;
        //alert(name + "\n" + email + "\n" + num + "\n" + card + "\n" + expDate + "\n" + cvc + "\n" + coupon);
        if (valid(name) && valid(email) && valid(num) && valid(card) && valid(cvc)) {
            if (!goodcard(expDate)) return alert("Card is expired");
            else {
                var id = form.elements[16].value;
                var modal = document.getElementById("myModal");
                var modalContent = document.getElementById("modalContent");
                var span = document.getElementsByClassName("close")[0];
                    span.onclick = function () {
                    modal.style.display = "none";
                    modalContent.removeChild(document.getElementById("emailButton"));
                    }
                    window.onclick = function (event) {
                        if (event.target == modal) {
                            modal.style.display = "none";
                            modalContent.removeChild(document.getElementById("emailButton"));
                        }
                    }

                $.ajax({
                    url: '../backend/api/create_transaction.php',
                    method:'post',
                    data: 
                        { 
                            productId: id,
                            customerEmail: email,
                            customerName: name,
                            discountCode: coupon,
                            '16digitsCreditCard': card,
                            ExpirationDate: expDate,
                            cvc: cvc
                        },
                        success: function(response)
                        {
                            if (response === '404') {
                                var textShown = document.getElementById("textShown").innerHTML = "Invalid Transaction";
                                modalContent.style.width = "28%";
                                modal.style.display = "block";
                            }
                            else {
                                var textShown = document.getElementById("textShown").innerHTML = "Transaction Complete";
                                modalContent.style.width = "40%";
                                modal.style.display = "block";
                            }
                        }
                });
            }
        }
    }
    else alert("Cart is Empty");
}

function valid(x) {
    if (x == "") return false;
    return true;
}

function goodcard(x) {
    var n = new Date().toISOString().substring(0, 10);
    if (x > n) return true;
    return false;
}

function checkCoupon(coupon)
{
    if(coupon == null) return false;
    $.ajax({
        url: '../backend/api/check_discount_code.php',
        method: 'post',
        data: { couponCode: coupon },
        success: function (response) {
            if (response === '404') {
                return true;
            } else {
                return false;
            }
        },
        error: function (error) {
            alert(error);
        }
    });
    return false;
}

function requestCode(x)
{
    $.ajax({
        url: '../backend/api/create_discount_code.php',
        method:'post',
        success: function(response)
        {
            alert("Code Request Complete");
            var cField = document.getElementById("coupon");
            cField.value = response;

        }
    });
    x.style.display = "none";

}
// Code added by Brent
function initForm() {
    let transactionForm = document.forms.transaction;
    transactionForm.onsubmit = e => {
        e.preventDefault();
        create_transaction(transactionForm);
    }
}
// this gets called as soon as the script is loaded
// the defer attribute has to be set on the document for this to work
initForm();