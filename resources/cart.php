<?php require_once("config.php"); ?>

<?php

if(isset($_GET['add'])) {

    $query = query("SELECT * FROM products WHERE product_id=" .escape_string($_GET['add']). " ");
    confirm($query);

    while($row = fetch_array($query)) {
        if($row['product_quantity'] != $_SESSION['product_' . $_GET['add']]) {
            $_SESSION['product_' . $_GET['add']] +=1;
            redirect("../public/checkout.php");
        } else {

            set_message("We only have " . $row['product_quantity'] . " <strong>" . $row['product_title'] . "</strong>'s available");
            redirect("../public/checkout.php");

        }
    }

}

if(isset($_GET['remove'])) {
    $_SESSION['product_' . $_GET['remove']]--;

    if($_SESSION['product_' . $_GET['remove']] < 1) {
        redirect("../public/checkout.php");
        unset($_SESSION['item_total']);
        unset($_SESSION['item_quantity']);
    } else {
        redirect("../public/checkout.php");
    }
}

if(isset($_GET['delete'])) {
    $_SESSION['product_' . $_GET['delete']] = '0';
    unset($_SESSION['item_total']);
    unset($_SESSION['item_quantity']);
    redirect("../public/checkout.php");
}


function cart()
{

    $total = 0;
    $item_quantity = 0;

    foreach ($_SESSION as $name => $value) {
        if($value > 0) {
            if (substr($name, 0, 8) == "product_") {
                $id = substr($name, 8);

                $query = query("SELECT * FROM products WHERE product_id = " . escape_string($id) . " ");
                confirm($query);

                while ($row = fetch_array($query)) {

                    $sub = $row['product_price']*$value;
                    $item_quantity += $value;

                    $product = <<<DELIMETER

             <tr>
                <td>{$row['product_title']}</td>
                <td>{$row['product_price']} &#107;&#114;</td>
                <td>{$value}</td>
                <td>{$sub} &#107;&#114;</td>
                
                <td><a href="../resources/cart.php?remove={$row['product_id']}" class="btn btn-warning"><span class="glyphicon glyphicon-minus-sign"></span></a></td>
                <td><a href="../resources/cart.php?add={$row['product_id']}" class="btn btn-success" ><span class="glyphicon glyphicon-plus"></span></a></td>
                <td><a href="../resources/cart.php?delete={$row['product_id']}" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span></a></td>
            </tr>
DELIMETER;

                    echo $product;

                    $_SESSION['item_total'] = $total += $sub;
                    $_SESSION['item_quantity'] = $item_quantity;


                }
            }
        }

    }
}

?>