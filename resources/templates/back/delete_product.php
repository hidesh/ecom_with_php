<?php require_once(__DIR__ . '/../../config.php'); ?>

<?php

if(isset($_GET['delete_product'])) {

    $query = query("DELETE FROM products WHERE product_id = " . escape_string($_GET['delete_product']) . " ");
    confirm($query);

    set_message("Product Deleted");
    redirect("../../../public/admin/index.php?products");

} else {

    redirect("../../../public/admin/index.php?products");
}



?>