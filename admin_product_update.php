<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];


if(!isset($admin_id)){
   header('location:admin_login.php');
};

if (isset($_POST['update_product'])) {
   
   $pid = $_POST['pid'];
   $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
   $price = filter_var($_POST['price'], FILTER_VALIDATE_FLOAT);  // Validate and sanitize price as a float

   $old_image = $_POST['old_image'];
   $image = $_FILES['image']['name'];
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'images/images/'.$image;

   $category = filter_var($_POST['category'], FILTER_SANITIZE_STRING);
   $desc = filter_var($_POST['desc'], FILTER_SANITIZE_STRING);

   $conn->beginTransaction();

   try {
      $update_product = $conn->prepare("UPDATE `products` SET name = ?, price = ?, `desc` = ?, category = ? WHERE id = ?");
      $update_product->execute([$name, $price, $desc, $category, $pid]);

      $message[] = 'Product updated successfully!';

      if (!empty($image)) {
         if ($image_size > 2000000) {
            $message[] = 'Image size is too large!';
         } else {
            $update_image = $conn->prepare("UPDATE products SET image = ? WHERE id = ?");
            $update_image->execute([$image, $pid]);
            move_uploaded_file($image_tmp_name, $image_folder);
            unlink('images/images/'.$old_image);
            $message[] = 'Image updated successfully!';
         }
      }

      $conn->commit(); // Commit the transaction

   } catch (PDOException $e) {
      $conn->rollBack(); // Roll back the transaction if there's an error
      $message[] = 'Error updating product: ' . $e->getMessage();
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>update product</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom admin style link  -->
   <link rel="stylesheet" href=" admin_style.css">

</head>
<body style="background-image: url('<?php echo './images/pizzabg2.jpg'; ?>'); background-size: cover; background-position: center;">

<?php include 'admin_header.php' ?>

<section class="update-product">

   <h1 class="heading">update product</h1>

   <?php
      $update_id = $_GET['update'];
      $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
      $select_products->execute([$update_id]);
      if($select_products->rowCount() > 0){
         while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <form action="" enctype="multipart/form-data" method="post">
      <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
      <input type="hidden" name="old_image" value="<?= $fetch_products['image']; ?>">
      <img src="images/images/<?= $fetch_products['image']; ?>" alt="">
      <input type="text" class="box" required maxlength="100" placeholder="enter product name" name="name" value="<?= $fetch_products['name']; ?>">
      <input type="number" min="0" class="box" required max="9999999999" placeholder="enter product price" onkeypress="if(this.value.length == 10) return false;" name="price" value="<?= $fetch_products['price']; ?>">
      <input type="text" class="box" required maxlength="100" placeholder="Enter Product Description" name="desc">
      <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box">
      <select name="category" class="box" id="category">
               <option disabled selected>Choose Category</option>
               <option value="Pizza">Pizza</option>
               <option value="Fries">Fries</option>
               <option value="Chicken Wings">Chicken Wings</option>
               <option value="Rice">Rice</option>
               <option value="Pasta">Pasta</option>
               <option value="Cheese Bake">Cheese Bake</option>
      </select>
      <div class="flex-btn">
         <input type="submit" value="update product" class="btn" name="update_product">
         <a href="admin_products.php" class="option-btn">go back</a>
      </div>
   </form>

   <?php
         }
      }else{
         echo '<p class="empty">no product found!</p>';
      }
   ?>

</section>




<script src="  admin_script.js"></script>

</body>
</html>