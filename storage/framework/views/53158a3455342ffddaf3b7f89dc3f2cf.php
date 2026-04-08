<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
        }
        .order-details {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f8f9fa;
        }
        .total {
            font-size: 20px;
            font-weight: bold;
            color: #28a745;
            text-align: right;
            margin-top: 20px;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .button {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📚 Thank You for Your Order!</h1>
            <p>Your order has been confirmed</p>
        </div>

        <div class="content">
            <h2>Hello <?php echo e($order->user->name); ?>!</h2>
            <p>Thank you for shopping with us. Your order has been successfully placed and is being processed.</p>

            <div class="order-details">
                <p><strong>Order Number:</strong> #<?php echo e($order->order_number ?? $order->id); ?></p>
                <p><strong>Order Date:</strong> <?php echo e($order->created_at->format('F d, Y H:i')); ?></p>
                <p><strong>Payment Status:</strong> <?php echo e(ucfirst($order->payment_status)); ?></p>
                <p><strong>Payment Method:</strong> <?php echo e(ucfirst($order->payment_method ?? 'Stripe')); ?></p>
            </div>

            <h3>Order Items</h3>
            <table>
                <thead>
                    <tr>
                        <th>Book</th>
                        <th>Author</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($item->book->title); ?></td>
                        <td><?php echo e($item->book->author); ?></td>
                        <td><?php echo e($item->quantity); ?></td>
                        <td>$<?php echo e(number_format($item->price, 2)); ?></td>
                        <td>$<?php echo e(number_format($item->price * $item->quantity, 2)); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" style="text-align: right;"><strong>Total:</strong></td>
                        <td><strong>$<?php echo e(number_format($order->total_amount, 2)); ?></strong></td>
                    </tr>
                </tfoot>
            </table>

            <div class="total">
                Total Charged: N$<?php echo e(number_format($order->total_amount, 2)); ?>

            </div>

            <center>
                <a href="<?php echo e(route('books.index')); ?>" class="button">Continue Shopping</a>
            </center>
        </div>

        <div class="footer">
            <p>This is a system-generated email. Please do not reply to this message.</p>
            <p>&copy; <?php echo e(date('Y')); ?> Tales & Tomes Bookshop. All rights reserved.</p>
        </div>
    </div>
</body>
</html><?php /**PATH C:\Test\bookshop\resources\views\emails\order_confirmation.blade.php ENDPATH**/ ?>