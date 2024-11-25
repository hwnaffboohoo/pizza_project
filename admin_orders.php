<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_POST['update_payment'])){

   $order_id = $_POST['order_id'];
   $payment_status = $_POST['payment_status'];
   $payment_status = filter_var($payment_status, FILTER_SANITIZE_STRING);

   $order_status = $_POST['order_status'];
   $order_status = filter_var($order_status, FILTER_SANITIZE_STRING);

   $update_payment = $conn->prepare("UPDATE `orders` SET payment_status = ?, order_status = ? WHERE id = ?");
   $update_payment->execute([$payment_status, $order_status, $order_id]);
   $message[] = 'payment status updated!';

}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
   $delete_order->execute([$delete_id]);
   header('location:admin_orders.php');
}



$selectOrders = $conn->prepare("SELECT * FROM `orders`");
$selectOrders->execute();

while ($fetchOrders = $selectOrders->fetch(PDO::FETCH_ASSOC)) {

    echo "<hr>";
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>orders</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom admin style link  -->
   <link rel="stylesheet" href="admin_style.css">

</head>
<body style="background-image: url('<?php echo './images/pizzabg2.jpg'; ?>'); background-size: cover; background-position: center;">

<?php include 'admin_header.php' ?>

<section class="orders">

<h1 class="heading">placed orders</h1>

<div class="box-container">

   <?php
      $select_orders = $conn->prepare("SELECT * FROM `orders`");
      $select_orders->execute();
      if($select_orders->rowCount() > 0){
         while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
   ?>
      <div class="box">
         <p> Placed On : <span><?= $fetch_orders['placed_on']; ?></span> </p>
         <p> Name : <span><?= $fetch_orders['name']; ?></span> </p>
         <p> Number : <span><?= $fetch_orders['number']; ?></span> </p>
         <p> Total Products : <span><?= $fetch_orders['total_products']; ?></span> </p>
         <p> Total Price : <span><?= $fetch_orders['total_price']; ?></span> </p>
         <p> Payment Method : <span><?= $fetch_orders['method']; ?></span> </p>
         <!-- Display the receipt if available -->
         <?php if (!empty($fetch_orders['receipt'])) : ?>
            <p> Receipt: <a href="<?= $fetch_orders['receipt']; ?>" target='receipt'>View Receipt</a></p>
         <?php endif; ?>
         
         <form action="" method="post">
            <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
            <select name="payment_status" class="select">
                  <option selected disabled><?= $fetch_orders['payment_status']; ?></option>
                  <option value="pending">pending</option>
                  <option value="Completed Payment">completed</option>
            </select>
            <select name="order_status" class="select">
                  <option selected disabled><?= $fetch_orders['order_status']; ?></option>
                  <option value="preparing">preparing</option>
                  <option value="Completed">completed</option>
            </select>
            <div class="flex-btn">
                  <input type="submit" value="update" class="option-btn" name="update_payment">
                  <a href="admin_orders.php?delete=<?= $fetch_orders['id']; ?>" class="delete-btn" onclick="return confirm('delete this order?');">delete</a>
            </div>
         </form>
      </div>

   <?php
         }
      }else{
         echo '<p class="empty">no orders placed yet!</p>';
      }
   ?>

</div>

</section>



<script src="admin_script.js"></script>

</body>
</html>