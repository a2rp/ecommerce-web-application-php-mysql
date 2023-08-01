<?php
$page = substr($_SERVER["SCRIPT_NAME"], strpos($_SERVER["SCRIPT_NAME"],"/")+1);
// echo $page;
?>
<a class="controlLinks dashboard" href="index.php">DASHBOARD</a>
<a class="controlLinks category" href="../admin/category.php">CATEGORIES</a>
<!-- <a class="controlLinks addCategory" href="../admin/addCategory.php">ADD CATEGORY</a> -->
<a class="controlLinks product" href="../admin/product.php">PRODUCTS</a>
<!-- <a class="controlLinks addProduct" href="../admin/addProduct.php">ADD PRODUCT</a> -->
<a class="controlLinks orders" href="../admin/orders.php">ORDERS</a>
<a class="controlLinks logout" href="../logout.php">LOGOUT</a>

