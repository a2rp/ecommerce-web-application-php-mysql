<script>
    $(document).ready(function () {
        $(document).on("click", ".deleteCategoryButton", function (event) {
            // $(".deleteCategoryButton").one("click", function (event) {
            if (confirm("Delete this category ???")) {
                $.ajax({
                    method: "POST",
                    url: "adminCode.php",
                    data: {
                        "categoryId": $(this).val(),
                        "deleteCategoryButton": true
                    },
                    success: function (response) {
                        if (response == 200) {
                            // alert("Category deleted successfully.");
                        } else if (response == 500) {
                            alert("Category not deleted.");
                        }
                    },
                    complete: function () {
                        // window.location.reload();
                        $(".categoryTable").load(location.href + " .categoryTable");
                    }
                });
            } else {
                // alert("Category deletion declined");
            }
        })
    });

    $(document).ready(function () {
        $(document).on("click", ".deleteProductButton", function (event) {
            // $(".deleteProductButton").one("click", function (event) {
            if (confirm("Delete this product ???")) {
                $.ajax({
                    method: "POST",
                    url: "adminCode.php",
                    data: {
                        "productId": $(this).val(),
                        "deleteProductButton": true
                    },
                    success: function (response) {
                        if (response == 200) {
                            // alert("Product deleted successfully.");
                        } else if (response == 500) {
                            alert("Product not deleted.");
                        }
                    },
                    complete: function () {
                        // window.location.reload();
                        $(".productTable").load(location.href + " .productTable");
                    }
                });
            } else {
                // alert("Product deletion declined");
            }
        })
    });
</script>