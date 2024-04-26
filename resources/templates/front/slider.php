<?php
require_once(__DIR__ . '/../../config.php');

$query = query("SELECT product_image FROM products LIMIT 3");
confirm($query);

$images = [];
while ($row = mysqli_fetch_assoc($query)) {
    $images[] = $row['product_image'];
}
?>

<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
        <?php foreach ($images as $index => $img): ?>
            <li data-target="#carousel-example-generic" data-slide-to="<?= $index ?>" <?= $index == 0 ? 'class="active"' : '' ?>></li>
        <?php endforeach; ?>
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner">
        <?php foreach ($images as $index => $img): ?>
            <div class="item <?= $index == 0 ? 'active' : '' ?>">
                <img class="slide-image" src="<?= $img ?>" alt="Product Image <?= $index ?>">
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Controls -->
    <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left"></span>
    </a>
    <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right"></span>
    </a>
</div>
