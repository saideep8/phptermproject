<?php
include('./config/pdo.php');

//function to get form inputs and sanitize them
function getFormValue($method, $form_id, $filter)
{
    $value = filter_input($method, $form_id, $filter);
    $result = trim(htmlentities(strip_tags($value)));
    return $result;
}

//Get products
function getProducts()
{
    if (!isset($_GET['search_data_product'])) {
        global $pdo;
        $select_query = "SELECT * from products order by rand() LIMIT 0,12";
        $stmt = $pdo->query($select_query);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $product_name = $row['product_name'];
            $product_description = $row['product_description'];
            $img = $row['image'];
            $price = $row['price'];
            echo ("<div class='col-md-3 mb-2'>
                    <div class='card'>
                        <img src='./images/product_images/$img' class='card-img-top' alt='$product_name'>
                        <div class='card-body'>
                            <h5 class='card-title'>$product_name</h5>
                            <p class='card-text'>$product_description</p>
                            <p class='card-text'>Price: $$price</p>
                            <a href='#' class='btn btn-info'>Add to cart</a>
                            <a href='#' class='btn btn-secondary'>View more</a>
                        </div>
                    </div>
                </div>");
        }
    }
}

// get Search products
function getSearchProducts()
{
    global $pdo;
    if (isset($_GET['search_data_product'])) {
        $search_value = getFormValue(INPUT_GET, 'search_data', FILTER_DEFAULT);
        $search_query = "SELECT * from products where product_keywords LIKE '%$search_value%'";
        $stmt = $pdo->prepare($search_query);
        $stmt->execute();
        $count = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (sizeof($count) > 0) {
            $search_query = "SELECT * from products where product_keywords LIKE '%$search_value%'";
            $stmt = $pdo->prepare($search_query);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $product_name = $row['product_name'];
                $product_description = $row['product_description'];
                $img = $row['image'];
                $price = $row['price'];
                echo ("<div class='col-md-3 mb-2'>
                        <div class='card'>
                            <img src='./images/product_images/$img' class='card-img-top' alt='$product_name'>
                            <div class='card-body'>
                                <h5 class='card-title'>$product_name</h5>
                                <p class='card-text'>$product_description</p>
                                <p class='card-text'>Price: $$price</p>
                                <a href='#' class='btn btn-info'>Add to cart</a>
                                <a href='#' class='btn btn-secondary'>View more</a>
                            </div>
                        </div>
                    </div>");
            }
        } else {
            echo ("<h2 class='text-center text-danger'>Oops! We cannot find the product you're looking for.</h2>");
        }
    }
}
