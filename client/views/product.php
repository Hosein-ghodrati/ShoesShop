<?php
require_once("client/views/templates/header.php")
?>

<div class="single-product-page" style="margin-top: 100px; max-width: 800px; margin: 0 auto; padding: 20px; font-family: sans-serif;">

    <div class="product-image" style="text-align:center; margin-bottom:20px; width:300px; height:300px; overflow:hidden; margin-left:auto; margin-right:auto;">
        <img style="object-fit: cover; height: 200px;" class="single-product-page" src="/media/<?php echo htmlspecialchars($product['image']); ?>"
            alt="<?php echo htmlspecialchars($product['name']); ?>">
    </div>


    <div class="product-info" style="padding: 0 10px;">

        <h2 style="text-align: center; margin-bottom: 10px;"><?php echo htmlspecialchars($product['name']); ?></h2>
        <p style="margin-bottom: 5px; color: #555;"><?php echo htmlspecialchars($product['short_desc']); ?></p>
        <p style="margin-bottom: 15px; color: #777;"><?php echo $product['desc']; ?></p>

        <div style="display: flex; gap: 20px; margin-bottom: 20px; align-items: center; justify-content: center;">
            <div class="price" style="font-weight: bold; font-size: 18px; color: #e63946;">
                $<?php echo number_format($product['price'], 0); ?>
            </div>
            <div class="in-stock" style="text-align: center; font-size: 16px; color: <?php echo $product['in_stock'] > 0 ? 'green' : 'red'; ?>;">
                <?php echo $product['in_stock'] > 0 ? $product['in_stock'] . ' in stock' : 'Out of stock'; ?>
            </div>
        </div>

        <a href="/addProductController?product_id=<?php echo $product['id']; ?>"
            class="social-info"
            style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none; background-color: #457b9d; color: white; padding: 10px 20px; border-radius: 5px;">
            <span class="ti-bag"></span>
            <p class="hover-text" style="margin: 0;">اضافه کردن به سبد خرید</p>
        </a>

    </div>
</div>
<?php
require_once("client/views/templates/footer.php")
?>