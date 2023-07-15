<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
  header("location: login.php");
  exit;
}

$servername = "localhost";
$username = "root";
$db_password = "";
$dbname = "storedb";

$conn = new mysqli($servername, $username, $db_password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("SELECT uname, email, playerID FROM accountstbl WHERE uname = ?");
$stmt->bind_param("s", $_SESSION["uname"]);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();
$conn->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $current_password = $_POST["password"];
  $new_password = $_POST["new_password"];
  $confirm_password = $_POST["confirm_password"];

  if ($current_password === $row["password"]) {
    if ($new_password === $confirm_password) {
      $conn = new mysqli($servername, $username, $db_password, $dbname);
      if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
      }
      $stmt = $conn->prepare("UPDATE accountstbl SET password = ? WHERE uname = ?");
      $stmt->bind_param("ss", $new_password, $_SESSION["uname"]);
      $stmt->execute();
      $stmt->close();
      $conn->close();

      session_destroy();

      header("location: login.php");
      exit;
    } else {
      $password_error = "New password and confirm password do not match.";
    }
  } else {
    $password_error = "Incorrect current password.";
  }
}
?>
  <style>
    body {
      background-color: #f2f2f2;
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 20px;
    }

    h1 {
      text-align: center;
      color: #333;
      margin: 0 auto;
    }

    h2 {
      text-align: center;
      color: #333;
    }

    .container {
      max-width: 1000px;
      margin: 0 auto;
      margin-top: 65px;
    }

    .grid-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
      padding: 0px;
    }

    .grid2 {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
      padding: 0px;
    }

    .item-container {
      display: flex;
      flex-direction: column;
      align-items: center;
      background-color: #fff;
      padding: 20px;
      border-radius: 5px;
    }

    .item-image {
      margin-bottom: 10px;
    }

    .item-title {
      font-weight: bold;
      font-size: 20px;
      margin-bottom: 5px;
      color: #333;
    }

    .item-info {
      margin-bottom: 10px;
      color: #777;
      font-weight: bold;
    }

    .item-price {
      font-weight: bold;
      color: #4CAF50;
      font-size: 18px;
      margin-right: 10px;
    }

    .img {
      border: 3px solid black;
      border-color: gray;
      width: 100px;
      height: 100px;
    }

    .add-to-cart {
      background-color: #4CAF50;
      color: white;
      padding: 10px 20px;
      border: none;
      cursor: pointer;
      font-size: 16px;
      transition: background-color 0.3s ease;
    }

    .add-to-cart:hover {
      background-color: #45a049;
    }

    #cart-items {
      width: 400px;
      padding: 40px;
      background-color: #fff;
      border: 1px solid #dddfe2;
      position: absolute;
      right: 70px;
      top: 40%;
    }

    #cart-items h2 {
      font-size: 20px;
      margin-bottom: 10px;
      color: #333;
    }

    .cart-table {
      top: 1000px;
      width: 100%;
      border-collapse: collapse;
    }

    .cart-table th,
    .cart-table td {
      padding: 8px;
      border-bottom: 1px solid #ccc;
      text-align: left;
    }

    .cart-table th {
      background-color: #f2f2f2;
    }

    .cart-item-total {
      font-weight: bold;
    }

    @media (max-width: 900px) {
      .grid-container,
      .grid2 {
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
      }

      .container {
        max-width: 500px;
        margin-top: 100px;
      }

      #cart-items {
        width: 300px;
        padding: 30px;
        right: 50px;
        top: 160%;
      }

      .cart-table {
        width: 90%;
      }

      .item-title {
        font-size: 15px;
      }

      .item-info {
        font-size: 15px;
      }

      .item-price {
        font-size: 15px;
      }

      .img {
        width: 80px;
        height: 80px;
      }

      .add-to-cart {
        padding: 6px 14px;
        font-size: 12px;
      }
    }

    @media (max-width: 700px) {
      .container {
        max-width: 500px;
        margin-top: 100px;
      }

      #cart-items {
        width: 300px;
        padding: 40px;
        right: 70px;
        top: 220%;
      }

      .cart-table {
        width: 100%;
      }

      .item-title {
        font-size: 10px;
      }

      .item-info {
        font-size: 10px;
      }

      .item-price {
        font-size: 12px;
      }

      .img {
        width: 50px;
        height: 50px;
      }

      .add-to-cart {
        padding: 5px 12px;
        font-size: 4px;
      }
    }

    @media (max-width: 500px) {
      .container {
        max-width: 500px;
        margin-top: 200px;
      }

      .item-title {
        font-size: 5px;
      }

      .item-info {
        font-size: 5px;
      }

      .item-price {
        font-size: 5px;
      }

      .img {
        width: 50px;
        height: 50px;
      }

      .add-to-cart {
        padding: 2px 8px;
        font-size: 4px;
      }
    }
  </style>
<!DOCTYPE html>
<html>

<head>
  <title>Pokémon GO Store</title>
</head>

<body>
  <?php include_once "navbar.php" ?>
  <div class="container">

  <h2>Cart</h2>
    <table class="cart-table">
      <thead>
        <tr>
          <th>Item</th>
          <th>Quantity</th>
          <th>Price</th>
        </tr>
      </thead>
      <tbody id="cart-items-list"></tbody>
      <tfoot>
        <tr>
          <td colspan="2" class="cart-item-total">Total:</td>
          <td>₱<span id="cart-total">0.00</span></td>
        </tr>
        <tr>
          <td colspan="3" class="text-center">
            <div class="text-center">
              <button class="btn btn-primary" onclick="proceedToPayment()">Proceed to Payment</button>
            </div>
          </td>
        </tr>
      </tfoot>
    </table>

    <br>
    <h2> Poké Box </h2>
    <div class="grid2">
      <div class="item-container">
        <div class="item-image">
          <img class="img"
            src="https://store.pokemongolive.com/_next/image?url=https%3A%2F%2Fcdn3.xsolla.com%2Fimg%2Fmisc%2Fimages%2F4f1b16fe72e50c089f2b2e884f8d96db.png&w=384&q=75">
        </div>
        <div>
          <div class="item-title">Anniversary Box</div>
          <div class="item-info">Only for a limited time</div>
          <div class="item-price">₱249.00</div>
          <button class="add-to-cart" onclick="addToCart('Anniversary Box', 249.00)">Add to Cart</button>
        </div>
      </div>

      <div class="item-container">
        <div class="item-image">
          <img class="img"
            src="https://store.pokemongolive.com/_next/image?url=https%3A%2F%2Fcdn3.xsolla.com%2Fimg%2Fmisc%2Fimages%2F3ef14d1b276a08ed7a8d2e125f9607a2.png&w=256&q=75">
        </div>
        <div>
          <div class="item-title">Ultra Community Day Box</div>
          <div class="item-info">Web Exclusive and Limited Time only</div>
          <div class="item-price">₱499.00</div>
          <button class="add-to-cart" onclick="addToCart('Ultra Community Day Box', 499.00)">Add to Cart</button>
        </div>
      </div>
    </div>
  </div>

    <h2>Pokécoins</h2>
    <div class="grid-container">
      <div class="item-container">
        <div class="item-image">
          <img class="img"
            src="https://store.pokemongolive.com/_next/image?url=https%3A%2F%2Fcdn3.xsolla.com%2Fimg%2Fmisc%2Fimages%2F070822898eca123f2f2dd9f1759f9734.png&w=128&q=75">
        </div>
        <div>
          <div class="item-title">600 Pokécoins</div>
          <div class="item-info">50 Web store bonus Coins</div>
          <div class="item-price">₱249.00</div>
          <button class="add-to-cart" onclick="addToCart('650 Pokécoins', 249.00)">Add to Cart</button>
        </div>
      </div>

      <div class="item-container">
        <div class="item-image">
          <img class="img"
            src="https://store.pokemongolive.com/_next/image?url=https%3A%2F%2Fcdn3.xsolla.com%2Fimg%2Fmisc%2Fimages%2F28042bf667a908f5761e677f1cb62f1f.png&w=128&q=75">
        </div>
        <div>
          <div class="item-title">1300 Pokécoins</div>
          <div class="item-info">100 Web store bonus Coins</div>
          <div class="item-price">₱499.00</div>
          <button class="add-to-cart" onclick="addToCart('1400 Pokécoins', 499.00)">Add to Cart</button>
        </div>
      </div>

      <div class="item-container">
        <div class="item-image">
          <img class="img"
            src="https://store.pokemongolive.com/_next/image?url=https%3A%2F%2Fcdn3.xsolla.com%2Fimg%2Fmisc%2Fimages%2Fdb583406b118ae8d86b525add9a4132d.png&w=128&q=75">
        </div>
        <div>
          <div class="item-title">2700 Pokécoins</div>
          <div class="item-info">200 Web store bonus Coins</div>
          <div class="item-price">₱999.00</div>
          <button class="add-to-cart" onclick="addToCart('2900 Pokécoins', 999.00)">Add to Cart</button>
        </div>
      </div>

      <div class="item-container">
        <div class="item-image">
          <img class="img"
            src="https://store.pokemongolive.com/_next/image?url=https%3A%2F%2Fcdn3.xsolla.com%2Fimg%2Fmisc%2Fimages%2F8cabb613cc3703e052629e0bc7eec1af.png&w=128&q=75">
        </div>
        <div>
          <div class="item-title">5600 Pokécoins</div>
          <div class="item-info">400 Web store bonus Coins (Best Value)</div>
          <div class="item-price">₱1,999.00</div>
          <button class="add-to-cart" onclick="addToCart('6000 Pokécoins', 1999.00)">Add to Cart</button>
        </div>
      </div>

      <div class="item-container">
        <div class="item-image">
          <img class="img"
            src="https://store.pokemongolive.com/_next/image?url=https%3A%2F%2Fcdn3.xsolla.com%2Fimg%2Fmisc%2Fimages%2F7ca5a8f30cbcf9ab08548b391a425e06.png&w=128&q=75">
        </div>
        <div>
          <div class="item-title">15,500 Pokécoins</div>
          <div class="item-info">1000 Web store bonus Coins (Best Value)</div>
          <div class="item-price">₱4,999.00</div>
          <button class="add-to-cart" onclick="addToCart('16,500 Pokécoins', 4999.00)">Add to Cart</button>
        </div>
      </div>
    </div>
  </div>

  <?php include_once "footer.php" ?>


  <script>
    // JavaScript code for handling the button clicks
    function proceedToPayment() {
      if (cartItems.length === 0) {
        alert('Please select an item before proceeding to payment.');
        return;
      }

      const totalAmount = parseFloat(document.getElementById('cart-total').textContent);
      window.location.href = `payment.php?total=${totalAmount}`;
    }

    function viewTransactionHistory() {
      window.location.href = 'transaction_history.php';
    }

    function redirectToProfile() {
      window.location.href = 'profile.php';
    }
  </script>



  <script>
    // JavaScript code for handling cart functionality
    let cartItems = [];
    let cartTotal = 0;

    function addToCart(itemName, itemPrice) {
      const existingItem = cartItems.find(item => item.name === itemName);

      if (existingItem) {
        existingItem.quantity++;
      } else {
        cartItems.push({ name: itemName, price: itemPrice, quantity: 1 });
      }

      cartTotal += itemPrice;
      displayCartItems();
    }

    function displayCartItems() {
      const cartItemsList = document.getElementById('cart-items-list');
      const cartTotalElement = document.getElementById('cart-total');
      cartItemsList.innerHTML = '';
      cartTotalElement.textContent = cartTotal.toFixed(2);

      cartItems.forEach(item => {
        const itemRow = document.createElement('tr');
        const itemNameCell = document.createElement('td');
        const itemQuantityCell = document.createElement('td');
        const itemPriceCell = document.createElement('td');

        itemNameCell.textContent = item.name;
        itemQuantityCell.textContent = item.quantity;
        itemPriceCell.textContent = '₱' + (item.price * item.quantity).toFixed(2);

        itemRow.appendChild(itemNameCell);
        itemRow.appendChild(itemQuantityCell);
        itemRow.appendChild(itemPriceCell);
        cartItemsList.appendChild(itemRow);
      });
    }
  </script>
</body>

</html>