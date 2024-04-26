<?php

//Helper Functions

function set_message($msg) {

    if(!empty($msg)) {
        $_SESSION['message'] = $msg;
    } else {
        $msg = "";
    }

}

function display_message() {
    if (isset($_SESSION['message'])){

        echo $_SESSION['message'];
        unset ($_SESSION['message']);
        
    }
}

function redirect($location) {

    header("Location: $location");

}

function query($sql)
{

    global $connection;
    return mysqli_query($connection, $sql);
}

function confirm($result)
{
    global $connection;
    if (!$result) {
        die ("QUERY FAILED" . mysqli_error($connection));

    }

}

function escape_string($string)
{
    global $connection;
    return mysqli_real_escape_string($connection, $string);

}

function fetch_array($result)
{
    return mysqli_fetch_array($result);
}

/************* FRONTEND funktioner **************/

//Get Products

function get_products()
{

    $query = query("SELECT * FROM products");
    confirm($query);

    while ($row = fetch_array($query)) {

        $product = <<<DELIMETER
<div class="col-sm-4 col-lg-4 col-md-4">
                        <div class="thumbnail">
                            <a href="item.php?id={$row['product_id']}">
                            <img src={$row['product_image']} alt="">
                            </a>
                            <div class="caption">
                                <h4 class="pull-right">{$row['product_price']} &#107;&#114;</h4>
                                <h4><a href="item.php?id={$row['product_id']}">{$row['product_title']}</a>
                                </h4>
                                <!--<p>{$row['product_description']}</a>.</p>-->
                                <a class="btn btn-primary" target="_self" href="../resources/cart.php?add={$row['product_id']}">Læg i kurv</a>
                            </div>



                            <!--<div class="ratings">
                                <p class="pull-right">15 reviews</p>
                                <p>
                                    <span class="glyphicon glyphicon-star"></span>
                                    <span class="glyphicon glyphicon-star"></span>
                                    <span class="glyphicon glyphicon-star"></span>
                                    <span class="glyphicon glyphicon-star"></span>
                                    <span class="glyphicon glyphicon-star"></span>
                                </p>
                            </div> -->
                        </div>
                    </div>
DELIMETER;

        echo $product;


    }

}

function get_categories(){
    $query = query("SELECT * FROM categories");
    confirm($query);

    while($row = fetch_array($query)) {

        $categories_links = <<<DELIMETER
        <a href='category.php?id={$row['cat_id']}' class='list-group-item'>{$row['cat_title']}</a>
DELIMETER;

echo $categories_links;
    }

}



function get_products_in_cat_page()
{

    $query = query("SELECT * FROM products WHERE product_category_id = " . escape_string($_GET['id']) . " ");
    confirm($query);

    while ($row = fetch_array($query)) {

        $product = <<<DELIMETER
                    <div class="col-md-3 col-sm-6 hero-feature">
                        <div class="thumbnail">
                            <a href="item.php?id={$row['product_id']}">
                            <img src={$row['product_image']} alt="" height="800" width="150">
                            </a>
                            <div class="caption">
                                <h4 class="pull-right">{$row['product_price']} &#107;&#114;</h4>
                                <h4><a href="item.php?id={$row['product_id']}">{$row['product_title']}</a>
                                </h4>
                                <!--<p>{$row['product_description']}</a>.</p>-->
                                <a class="btn btn-primary" target="_self" href="item.php?id={$row['product_id']}">Læg i kurv</a> 
                                <a class="btn btn-default" target="_self" href="item.php?id={$row['product_id']}">Mere info</a> 
                            </div>
                        </div>
                    </div>

DELIMETER;

        echo $product;


    }

}


function get_products_in_shop_page()
{

    $query = query("SELECT * FROM products");
    confirm($query);

    while ($row = fetch_array($query)) {

        $product = <<<DELIMETER
                    <div class="col-md-3 col-sm-6 hero-feature">
                        <div class="thumbnail">
                            <a href="item.php?id={$row['product_id']}">
                            <img src={$row['product_image']} alt="" height="800" width="150">
                            </a>
                            <div class="caption">
                                <h4 class="pull-right">{$row['product_price']} &#107;&#114;</h4>
                                <h4><a href="item.php?id={$row['product_id']}">{$row['product_title']}</a>
                                </h4>
                                <!--<p>{$row['product_description']}</a>.</p>-->
                                <a class="btn btn-primary" target="_self" href="item.php?id={$row['product_id']}">Læg i kurv</a> 
                                <a class="btn btn-default" target="_self" href="item.php?id={$row['product_id']}">Mere info</a> 
                            </div>
                        </div>
                    </div>

DELIMETER;

        echo $product;


    }

}


/* LOGIN *
I PHP bruges funktionen escape_string() til at sikre data, der indgår i SQL-forespørgsler, for at undgå sikkerhedshuller som SQL injections.
SQL injection er en teknik, hvor en angriber kan indsætte eller "injicere" ondsindet SQL-kode i en databaseforespørgsel.
Dette kan føre til uautoriseret adgang til databasen, manipulation af data eller andre sikkerhedsbrud.
*/
function login_user()
{
    if (isset($_POST['submit'])){
        $username = escape_string($_POST['username']);
        $password = escape_string($_POST['password']);

        $query = query("SELECT * FROM users WHERE username = '{$username}' AND password = '{$password}'");
        confirm($query);

        if (mysqli_num_rows($query) == 0 ) {

            set_message("Your Password or Username are wrong");
            redirect("login.php");
        } else {
            $_SESSION['username'] = $username;
            redirect("admin");
        }

    }
}


function send_message()
{
    if(isset($_POST['submit']));

    $to             =           "hidesh@live.dk";
    $from_name      =           $_POST['name'];
    $email          =           $_POST['email'];
    $subject        =           $_POST['subject'];
    $message        =           $_POST['message'];

    $headers = "From: {$from_name} $email";

    $result = mail($to, $subject, $message, $headers);

    if(!$result) {
        set_message("Sorry we couldn't send your message :(");
        redirect("contact.php");
    } else {
        set_message("Your message is sent! :)");
        redirect("contact.php"); 
    }
}




/************* BACKEND funktioner **************/



function get_products_in_admin()
{
    $query = query("SELECT * FROM products");
    confirm($query);

    while ($row = fetch_array($query)) {

        $product = <<<DELIMETER
                    <tr>
            <td>{$row['product_id']}</td>
            <td>{$row['product_title']} <br>
              <a href="index.php?edit_product&id={$row['product_id']}"><img src={$row['product_image']} alt=""></a>
            </td>
            <td>Category</td>
            <td>{$row['product_price']}</td>
            <td>{$row['product_quantity']}</td>
            <td>
                <a href="../resources/templates/back/delete_product.php?id={$row['product_id']}" class="btn btn-danger">
                <span class="glyphicon glyphicon-remove">
            
                </span>
                </a>
            </td>
        </tr>

DELIMETER;

        echo $product;
    }

}


function add_product(){

    if(isset($_POST['publish'])) {

        $product_title =   escape_string($_POST['product_title']);
        $product_category_id =   escape_string($_POST['product_category_id']);
        $product_price =   escape_string($_POST['product_price']);
        $product_description =   escape_string($_POST['product_description']);
        $short_desc =   escape_string($_POST['short_desc']);
        $product_quantity =   escape_string($_POST['product_quantity']);



    }

}
