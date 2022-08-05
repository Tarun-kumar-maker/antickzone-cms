var product_total_amt = document.getElementById('product_total_amt');
var shipping_charge = document.getElementById('shipping_charge');
var total_cart_amt = document.getElementById('total_cart_amt');
var discountCode = document.getElementById('discount_code1');


const decreaseNumber = (incdec, itemprice, productprice,intcartitemid) => {
    var itemval = document.getElementById(incdec);
    var itemprice = document.getElementById(itemprice);
    console.log(itemprice.innerHTML);
    if (itemval.value <= 0) {
        itemval.value = 0;
        alert('Negative quantity not allowed');
    } else {
        itemval.value = parseInt(itemval.value) - 1;
        itemval.style.background = '#fff';
        itemval.style.color = '#000';
        itemprice.innerHTML = parseInt(itemprice.innerHTML) - productprice;
        product_total_amt.innerHTML = parseInt(product_total_amt.innerHTML) - productprice;
        gst = (parseInt(product_total_amt.innerHTML)*18)/100;
        total_cart_amt.innerHTML = parseInt(product_total_amt.innerHTML) + parseInt(gst);    
        $.ajax({
            type: 'POST',
            url: 'updatecart.php',
            data: { intitemid: intcartitemid,
                    quantity: itemval.value,
                    totalamount: parseInt(itemprice.innerHTML)},
            success: function(response) {
               
            }
        });
    }
}

const increaseNumber = (incdec, itemprice, productprice, intcartitemid) => {
    var itemval = document.getElementById(incdec);
    var itemprice = document.getElementById(itemprice);
    if (itemval.value >= 5) {
        itemval.value = 5;
        alert('max 5 allowed');
        itemval.style.background = 'red';
        itemval.style.color = '#fff';
    } else {
        itemval.value = parseInt(itemval.value) + 1;
        // add same price 
        itemprice.innerHTML = parseInt(itemprice.innerHTML) + parseInt(productprice);
        product_total_amt.innerHTML = parseInt(product_total_amt.innerHTML) +  parseInt(productprice);
        gst = (parseInt(product_total_amt.innerHTML)*18)/100;
        total_cart_amt.innerHTML = parseInt(product_total_amt.innerHTML) + parseInt(gst);
        $.ajax({
            type: 'POST',
            url: 'updatecart.php',
            data: { intitemid: intcartitemid,
                    quantity: itemval.value,
                    totalamount: parseInt(itemprice.innerHTML)},
            success: function(response) {
               
            }
        });
    }
}

const discount_code = () => {
    var totalamtcurr = parseInt(total_cart_amt.innerHTML);
    var error_trw = document.getElementById('error_trw');
    if (discountCode.value === '') {
        let newtotalamt = totalamtcurr - 15;
        total_cart_amt.innerHTML = newtotalamt;
        error_trw.innerHTML = "Hurray! code is valid";
    } else {
        error_trw.innerHTML = "Try Again! Valid code is thapa";
    }
}