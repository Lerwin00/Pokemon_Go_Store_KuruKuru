<!DOCTYPE html>
<html>

<head>
  <title>Pokémon GO Store - Payment</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style> 
    body {
      background-color: #f3f3f3;
    }

    .container {
      margin-top: 250px;
      width: 500px;
      border-radius: 25px;
      border: 5px solid white;
      padding: 5px;
      box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
      background-color: #ffffff;
    }

    @media (max-width: 900px) {
      .container {
        margin-top: 300px;
        width: 400px;
      }
    }

    @media (max-width: 700px) {
      .container {
        margin-top: 200px;
        width: 300px;
      }
    }

    @media (max-width: 500px) {
      .container {
        margin-top: 100px;
        width: 250px;
      }
    }
  </style>
</head>

<body>
  <div class="container">
    <h1 class="text-center">Payment</h1>

    <div id="payment-form">
      <div class="form-group">
        <input type="text" class="form-control" id="phone-number" placeholder="Phone Number">
      </div>
      <div class="form-group">
        <label for="total-amount">Total Amount:</label>
        <span id="total-amount">₱0.00</span>
      </div>
      <div class="text-center">
        <button class="btn btn-danger" onclick="proceedToPayment()">Proceed to Payment</button>
      </div>
      <div class="text-center mt-2">
        <a href="store.php" class="btn btn-secondary">Back to Store</a>
      </div>
    </div>

    <div id="payment-success" style="display: none;">
      <h2 class="text-center">Payment Successful!</h2>
      <p class="text-center">Thank you for your purchase.</p>
      <div class="text-center mt-2">
        <a href="store.php" class="btn btn-dark">Back to Store</a>
      </div>
    </div>
  </div>

  <script>
    // JavaScript code for handling the payment module
    let cartTotal = 0;

    function calculateTotal() {
      const urlParams = new URLSearchParams(window.location.search);
      cartTotal = parseFloat(urlParams.get('total'));
      document.getElementById('total-amount').textContent = '₱' + cartTotal.toFixed(2);
    }

    function validatePhoneNumber(phoneNumber) {
  // Philippine phone number regular expression
  const phoneRegex = /^(\+?63|0)9\d{9}$|^09\d{9}$/;
  return phoneRegex.test(phoneNumber);
}

    function proceedToPayment() {
      const phoneNumber = document.getElementById('phone-number').value;

      if (phoneNumber.trim() === '') {
        alert('Please enter a phone number.');
        return;
      }

      if (!validatePhoneNumber(phoneNumber)) {
        alert('Please enter a valid Philippine phone number.');
        return;
      }

      // Simulating payment processing
      setTimeout(() => {
        document.getElementById('payment-form').style.display = 'none';
        document.getElementById('payment-success').style.display = 'block';
        saveTransaction(phoneNumber);
      }, 2000);
    }

    function saveTransaction(phoneNumber) {
      const transaction = {
        phoneNumber: phoneNumber,
        totalAmount: cartTotal.toFixed(2),
        date: new Date().toLocaleDateString(),
        time: new Date().toLocaleTimeString(),
      };

      const transactions = JSON.parse(localStorage.getItem('transactions')) || [];
      transactions.push(transaction);
      localStorage.setItem('transactions', JSON.stringify(transactions));
    }

    calculateTotal();
  </script>
</body>

</html>
