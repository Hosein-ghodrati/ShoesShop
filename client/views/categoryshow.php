<?php
require_once("client/views/templates/header.php");
?>


<div class="container" style="margin-top: 120px; margin-top: 150px;">
    <!-- Category Title -->
    <h2 style="text-align: center; margin-bottom: 30px; ">
        محصولات در دسته بندی: <?= htmlspecialchars($category['name']) ?>
    </h2>

    <?php if (empty($products)): ?>
        <p style="text-align: center; font-size: 18px; color: #666;"> دسته بندی ای یافت نشد! </p>
    <?php else: ?>
        <div class="products-grid" style="display: flex; flex-wrap: wrap; gap: 20px; justify-content: center;">
            <?php foreach ($products as $product): ?>
                <div class="single-product" style="border: 1px solid #eee; border-radius: 8px; padding: 15px; width: 220px; text-align: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                    <img src="/media/<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="object-fit: cover; height: 200px; max-width: 100%; border-radius: 5px; margin-bottom: 10px;">
                    <h3 style="font-size: 18px; margin-bottom: 10px;"><?php echo htmlspecialchars($product['name']); ?></h3>
                    <p style="font-weight: bold; color: #e63946;">قیمت: <?php echo $product['price']; ?> تومان</p>
                    <a href="/product?id=<?php echo $product['id']; ?>" style="display: inline-block; margin-top: 10px; padding: 8px 12px; background: #457b9d; color: #fff; text-decoration: none; border-radius: 5px;">
                        مشاهده
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php
require_once("client/views/templates/footer.php");
?>