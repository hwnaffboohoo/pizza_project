<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['add_product'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'images/images/'.$image;

   $category = $_POST['category'];
   $category = filter_var($category, FILTER_SANITIZE_STRING);

   $desc = $_POST['desc'];
   $desc = filter_var($desc, FILTER_SANITIZE_STRING);

   $select_product = $conn->prepare("SELECT * FROM `products` WHERE name = ?");
   $select_product->execute([$name]);

   if($select_product->rowCount() > 0){
      $message[] = 'product name already exist!';
   }else{
      if($image_size > 2000000){
         $message[] = 'image size is too large!';
      }else{
         $insert_product = $conn->prepare("INSERT INTO `products` (name, price, `desc`, image, category) VALUES (?, ?, ?, ?, ?)");
         $insert_product->execute([$name, $price, $desc, $image, $category]);
         move_uploaded_file($image_tmp_name, $image_folder);
         $message[] = 'New product added!';         
      }
   }

}

if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $delete_product = $conn->prepare("DELETE FROM `products` WHERE id = ?");
   $delete_product->execute([$delete_id]);
   $delete_product_image = $conn->prepare("SELECT image FROM `products` WHERE id = ?");
   $delete_product_image->execute([$delete_id]);
   $fetch_delete_image = $delete_product_image->fetch(PDO::FETCH_ASSOC);
   unlink('images/images/'.$fetch_delete_image['image']);
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid = ?");
   $delete_cart->execute([$delete_id]);
   header('location:admin_products.php');

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>products</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom admin style link  -->
   <link rel="stylesheet" href=" admin_style.css">

</head>
<body style="background-image: url('<?php echo './images/pizzabg2.jpg'; ?>'); background-size: cover; background-position: center;">

<?php include 'admin_header.php' ?>

<section class="add-products">

   <h1 class="heading">add product</h1>

   <form action="" method="post" enctype="multipart/form-data">
      <input type="text" class="box" required maxlength="100" placeholder="Enter Product Name" name="name">
      <input type="number" min="0" class="box" required max="9999999999" placeholder="Enter Product Price" onkeypress="if(this.value.length == 10) return false;" name="price">
      <input type="text" class="box" required maxlength="100" placeholder="Enter Product Description" name="desc">
      <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box" required>
      <select name="category" class="box" id="category">
               <option disabled selected>Choose Category</option>
               <option value="Pizza">Pizza</option>
               <option value="Fries">Fries</option>
               <option value="Chicken Wings">Chicken Wings</option>
               <option value="Rice">Rice</option>
               <option value="Pasta">Pasta</option>
               <option value="Cheese Bake">Cheese Bake</option>
      </select>
      <input type="submit" value="add product" class="btn" name="add_product">
   </form>

</section>

<section class="show-products">

   <h1 class="heading">Pizza</h1>

   <div class="box-container">

   <?php
      $select_products = $conn->prepare("SELECT * FROM `products` where category = 'Pizza'");
      $select_products->execute();
      if($select_products->rowCount() > 0){
         while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <div class="box">
      <div class="price">RM<span><?= $fetch_products['price']; ?></span></div>
      <img src="images/images/<?= $fetch_products['image']; ?>" alt="">
      <div class="name"><?= $fetch_products['name']; ?></div>
      <div class="desc"><?= $fetch_products['desc']; ?></div>
      <div class="flex-btn">
         <a href="admin_product_update.php?update=<?= $fetch_products['id']; ?>" class="option-btn">update</a>
         <a href="admin_products.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('delete this product?');">delete</a>
      </div>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">no products added yet!</p>';
      }
   ?>
   
   </div>



<div ckass="pad" style="padding-top: 4rem;">
   <h1 class="heading">Fries</h1>
   <style>
      .pad .heading{
   margin-top: 20px;
}
   </style>

<div class="box-container">

<?php
   $select_products = $conn->prepare("SELECT * FROM `products` where category = 'Fries'");
   $select_products->execute();
   if($select_products->rowCount() > 0){
      while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 
?>
<div class="box">
   <div class="price">RM<span><?= $fetch_products['price']; ?></span></div>
   <img src="images/images/<?= $fetch_products['image']; ?>" alt="">
   <div class="name"><?= $fetch_products['name']; ?></div>
   <div class="desc"><?= $fetch_products['desc']; ?></div>
   <div class="flex-btn">
      <a href="admin_product_update.php?update=<?= $fetch_products['id']; ?>" class="option-btn">update</a>
      <a href="admin_products.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('delete this product?');">delete</a>
   </div>
</div>
<?php
      }
   }else{
      echo '<p class="empty">no products added yet!</p>';
   }
?>
</div>
</div>



<div ckass="pad" style="padding-top: 4rem;">
   <h1 class="heading">Chicken Wings</h1>
   <style>
      .pad .heading{
   margin-top: 20px;
}
   </style>

<div class="box-container">

<?php
   $select_products = $conn->prepare("SELECT * FROM `products` where category = 'Chicken Wings'");
   $select_products->execute();
   if($select_products->rowCount() > 0){
      while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 
?>
<div class="box">
   <div class="price">RM<span><?= $fetch_products['price']; ?></span></div>
   <img src="images/images/<?= $fetch_products['image']; ?>" alt="">
   <div class="name"><?= $fetch_products['name']; ?></div>
   <div class="desc"><?= $fetch_products['desc']; ?></div>
   <div class="flex-btn">
      <a href="admin_product_update.php?update=<?= $fetch_products['id']; ?>" class="option-btn">update</a>
      <a href="admin_products.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('delete this product?');">delete</a>
   </div>
</div>
<?php
      }
   }else{
      echo '<p class="empty">no products added yet!</p>';
   }
?>
</div>
</div>




<div ckass="pad" style="padding-top: 4rem;">
   <h1 class="heading">Rice</h1>
   <style>
      .pad .heading{
   margin-top: 20px;
}
   </style>

<div class="box-container">

<?php
   $select_products = $conn->prepare("SELECT * FROM `products` where category = 'Rice'");
   $select_products->execute();
   if($select_products->rowCount() > 0){
      while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 
?>
<div class="box">
   <div class="price">RM<span><?= $fetch_products['price']; ?></span></div>
   <img src="images/images/<?= $fetch_products['image']; ?>" alt="">
   <div class="name"><?= $fetch_products['name']; ?></div>
   <div class="desc"><?= $fetch_products['desc']; ?></div>
   <div class="flex-btn">
      <a href="admin_product_update.php?update=<?= $fetch_products['id']; ?>" class="option-btn">update</a>
      <a href="admin_products.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('delete this product?');">delete</a>
   </div>
</div>
<?php
      }
   }else{
      echo '<p class="empty">no products added yet!</p>';
   }
?>
</div>
</div>



<div ckass="pad" style="padding-top: 4rem;">
   <h1 class="heading">Pasta</h1>
   <style>
      .pad .heading{
   margin-top: 20px;
}
   </style>

<div class="box-container">

<?php
   $select_products = $conn->prepare("SELECT * FROM `products` where category = 'Pasta'");
   $select_products->execute();
   if($select_products->rowCount() > 0){
      while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 
?>
<div class="box">
   <div class="price">RM<span><?= $fetch_products['price']; ?></span></div>
   <img src="images/images/<?= $fetch_products['image']; ?>" alt="">
   <div class="name"><?= $fetch_products['name']; ?></div>
   <div class="desc"><?= $fetch_products['desc']; ?></div>
   <div class="flex-btn">
      <a href="admin_product_update.php?update=<?= $fetch_products['id']; ?>" class="option-btn">update</a>
      <a href="admin_products.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('delete this product?');">delete</a>
   </div>
</div>
<?php
      }
   }else{
      echo '<p class="empty">no products added yet!</p>';
   }
?>
</div>
</div>



<div ckass="pad" style="padding-top: 4rem;">
   <h1 class="heading">Cheese Baked</h1>
   <style>
      .pad .heading{
   margin-top: 20px;
}
   </style>

<div class="box-container">

<?php
   $select_products = $conn->prepare("SELECT * FROM `products` where category = 'Cheese Bake'");
   $select_products->execute();
   if($select_products->rowCount() > 0){
      while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 
?>
<div class="box">
   <div class="price">RM<span><?= $fetch_products['price']; ?></span></div>
   <img src="images/images/<?= $fetch_products['image']; ?>" alt="">
   <div class="name"><?= $fetch_products['name']; ?></div>
   <div class="desc"><?= $fetch_products['desc']; ?></div>
   <div class="flex-btn">
      <a href="admin_product_update.php?update=<?= $fetch_products['id']; ?>" class="option-btn">update</a>
      <a href="admin_products.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('delete this product?');">delete</a>
   </div>
</div>
<?php
      }
   }else{
      echo '<p class="empty">no products added yet!</p>';
   }
?>
</div>
</div>


</section>



<script src="  admin_script.js"></script>

</body>
</html>