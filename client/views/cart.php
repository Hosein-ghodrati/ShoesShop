<?php
require_once("client/views/templates/header.php");
if (session_status() === PHP_SESSION_NONE) session_start();
$errors = $_SESSION['errors'] ?? [];
$old    = $_SESSION['old'] ?? [];

unset($_SESSION['errors'], $_SESSION['old']);

$cart = $_SESSION['cart'] ?? [];
$totalPrice = 0;
?>

<div style="max-width: 900px; margin: 150px auto; font-family: sans-serif; padding: 20px;">
    <h2 style="margin-bottom: 20px; text-align: center;">🛒 Your Cart</h2>

    <!-- show flash messages -->
    <?php if (!empty($_SESSION['success'])): ?>
        <div style="background:#e6ffed;border:1px solid #b7f0cc;padding:10px;border-radius:6px;margin-bottom:15px;color:#12421a;">
            <?php echo htmlspecialchars($_SESSION['success']);
            unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['errors'])): ?>
        <div style="background:#fff0f0;border:1px solid #f2b8b8;padding:10px;border-radius:6px;margin-bottom:15px;color:#7a1a1a;">
            <?php foreach ($_SESSION['errors'] as $err): ?>
                <div><?php echo htmlspecialchars($err); ?></div>
            <?php endforeach;
            unset($_SESSION['errors']); ?>
        </div>
    <?php endif; ?>

    <?php if (empty($cart)): ?>
        <p style="text-align: center; font-size: 18px; color: #666;">Your cart is empty.</p>
    <?php else: ?>
        <?php foreach ($cart as $item):
            $quantity = isset($item['quantity']) ? (int)$item['quantity'] : 1;
            $price = isset($item['price']) ? (int)$item['price'] : 0;
            $inStock = isset($item['in_stock']) ? $item['in_stock'] : 'N/A';
            $size = isset($item['size']) ? $item['size'] : '-';
            $image = isset($item['image']) ? $item['image'] : 'default.png';

            $itemTotal = $price * $quantity;
            $totalPrice += $itemTotal;
        ?>
            <div style="position: relative; display: flex; align-items: center; justify-content: space-between; background: #f9f9f9; margin-bottom: 15px; padding: 15px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">

                <!-- Product Image -->
                <div style="flex: 0 0 100px;">
                    <img src="/media/<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" style="max-width: 100%; border-radius: 5px;">
                </div>

                <!-- Product Info -->
                <div style="flex: 1; margin-left: 15px;">
                    <h3 style="margin: 0 0 8px;"><?php echo htmlspecialchars($item['name']); ?></h3>
                    <p style="margin: 0; color: #555;">Size: <?php echo htmlspecialchars($size); ?></p>
                    <p style="margin: 0; color: #777;">In Stock: <?php echo htmlspecialchars($inStock); ?></p>
                </div>

                <!-- Price & Quantity -->
                <div style="flex: 0 0 180px; text-align: right;">
                    <p style="margin: 0; font-weight: bold; color: #e63946;"><?php echo number_format($price); ?> Toman</p>
                    <p style="margin: 0;">Qty: <?php echo $quantity; ?></p>
                    <p style="margin: 0; font-size: 14px; color: #555;">Subtotal: <?php echo number_format($itemTotal); ?> Toman</p>
                </div>

                <!-- Remove button -->
                <form method="post" action="/index.php?path=cart" style="position: absolute; top: 10px; right: 10px; margin: 0;">
                    <input type="hidden" name="remove_id" value="<?php echo htmlspecialchars($item['id']); ?>">
                    <button type="submit" style="background: transparent; border: none; color: #e63946; cursor: pointer; font-size: 20px;" title="Remove">&#10005;</button>
                </form>

            </div>
        <?php endforeach; ?>

        <!-- Total Price -->
        <div style="text-align: right; font-size: 20px; margin-top: 20px; font-weight: bold;">
            Total: <?php echo number_format($totalPrice); ?> Toman
        </div>

        <!-- Checkout Form -->
        <form action="/checkout" method="post" style="margin-top: 30px; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <h3 style="margin-bottom: 15px;">اطلاعات ارسال</h3>

            <label style="display: block; margin-bottom: 10px;">
                Address:
                <input placeholder="آدرس خود را وارد کنید" type="text" name="address" required autocomplete="off"
                    style="width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 5px;"
                    value="<?php echo htmlspecialchars($old['address'] ?? ''); ?>">
                <?php if (!empty($errors['address'])): ?>
                    <span style="color: red; font-size: 14px;">
                        <?php echo $errors['address']; ?>
                    </span>
                <?php endif; ?>
            </label>

            <label style="display: block; margin-bottom: 10px;">
                Phone Number:
                <input placeholder="برای مثال 09121234567" type="text" name="phone" required autocomplete="off"
                    style="width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 5px;"
                    value="<?php echo htmlspecialchars($old['phone'] ?? ''); ?>">
                <?php if (!empty($errors['phone'])): ?>
                    <span style="color: red; font-size: 14px;">
                        <?php echo $errors['phone']; ?>
                    </span>
                <?php endif; ?>
            </label>

            <input type="hidden" name="total_price" value="<?php echo htmlspecialchars($totalPrice); ?>">

            <button name="purchase" type="submit"
                style="background: #457b9d; color: #fff; padding: 12px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
                درگاه پرداخت
            </button>
        </form>

    <?php endif; ?>
</div>

<?php
require_once("client/views/templates/footer.php");
?>