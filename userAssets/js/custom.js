$(document).ready(function () {
    $(document).on('click', ".plus", function (event) {
        // const prevQuantity = $("#itemNumber").val();
        const prevQuantity = $(this).closest("div.inputArea").find("input").val();
        // console.log(prevValue);
        let value = parseInt(prevQuantity, 10);
        value = isNaN(value) ? 0 : value;
        if (value < 10) {
            ++value;
            // $("#itemNumber").val(value);
            $(this).closest("div.inputArea").find("input").val(value);
        }
    });

    $(document).on('click', ".minus", function (event) {
        // const prevQuantity = $("#itemNumber").val();
        const prevQuantity = $(this).closest("div.inputArea").find("input").val();
        // console.log(prevValue);
        let value = parseInt(prevQuantity, 10);
        value = isNaN(value) ? 1 : value;
        if (value > 1) {
            --value;
            // $("#itemNumber").val(value);
            $(this).closest("div.inputArea").find("input").val(value);
        }
    });

    $(document).on('click', ".addToCartButton", function (event) {
        const productQuantity = $("#itemNumber").val();
        const productId = $(this).val();
        // alert(productQuantity + " " + productId);
        $.ajax({
            type: "POST",
            url: "functions/handleCart.php",
            data: {
                productId,
                productQuantity,
                "scope": "add",
                "repeatThisOrder": $(".repeatOrderCheckbox").is(":checked")
            },
            success: function (response) {
                if (response == 201) {
                    $(".repeatOrderCheckbox").prop("checked", false);
                    alert("Product added to cart successfully");
                } else if (response == "existing") {
                    alert("Product already exists! You may update it in your cart.");
                } else if (response == 401) {
                    alert("Unauthorized! Login to continue.");
                } else if (response == 500) {
                    alert("Something went wrong");
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert(textStatus);
            },
            complete: function () {
            }
        });
    });

    $(document).on('click', ".updateQuantity", function (event) {
        const productQuantity = $(this).closest("div.inputArea").find("input").val();
        const productId = $(this).closest("div.incrementDecrement").find(".productId").val();
        // alert(productQuantity + ", " + productId);
        $.ajax({
            method: "POST",
            url: "functions/handleCart.php",
            data: {
                productId,
                productQuantity,
                "scope": "update"
            },
            success: function (response) {
                // alert(response);
            }
        });
    });

    $(document).on('click', ".deleteItem", function (event) {
        const cartId = $(this).val();
        $.ajax({
            method: "POST",
            url: "functions/handleCart.php",
            data: {
                cartId,
                "scope": "delete"
            },
            success: function (response) {
                if (response == 200) {
                    // window.location.reload();
                    $(".cartsContentContainer").load(location.href + " .cartsContentContainer");
                    alert("Cart item deleted successfully");
                } else {
                    alert(response);
                }
            },
            fail: function (jqXHR, textStatus, errorThrown) {
                alert(textStatus);
            }
        });
    });


});
