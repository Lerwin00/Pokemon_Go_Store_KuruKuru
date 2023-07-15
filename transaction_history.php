<!DOCTYPE html>
<html>

<head>
  <title>Pokémon GO Store - Transaction History</title>
  <style>
    body {
      background-color: #f3f3f3;
    }

    .container {
      margin-top: 100px;
      border: 5px solid white;
      border-radius: 25px;
      padding: 10px;
      background-color: #FFFFFF;
      box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
    }

    .table {
      width: 100%;
      margin-top: 20px;
    }

    .th {
      font-weight: bold;
      text-align: center;
    }

    .tr {
      background-color: #f9f9f9;
    }

    @media (max-width: 768px) {
      .container {
        margin-top: 100px;
      }
    }

    @media (max-width: 576px) {
      .container {
        margin-top: 50px;
      }
    }

    @media (max-width: 480px) {
      .container {
        margin-top: 30px;
        padding: 5px;
      }

      .table {
        font-size: 14px;
      }

      .th,
      .td {
        padding: 5px;
      }
    }
  </style>
</head>

<body>
  <?php include_once "navbar.php" ?>
  <div>
    <div class="container">
      <h1 class="text-center">TRANSACTION HISTORY</h1>
      <table class="table">
        <thead>
          <tr class="tr">
            <th class="th">Phone Number</th>
            <th class="th">Total Amount</th>
            <th class="th">Date</th>
            <th class="th">Time</th>
          </tr>
        </thead>
        <tbody id="transaction-list"></tbody>
      </table>
      <div class="text-center mt-2">
        <a href="store.php" class="btn btn-primary">Back to Store</a>
      </div>
    </div>
  </div>
  <?php include_once "footer.php" ?>

  <script>
    // JavaScript code for displaying transaction history
    function displayTransactionHistory() {
      const transactions = JSON.parse(localStorage.getItem('transactions')) || [];
      const transactionList = document.getElementById('transaction-list');

      transactions.forEach(transaction => {
        const row = document.createElement('tr');
        row.innerHTML = `
          <td>${transaction.phoneNumber}</td>
          <td>${transaction.totalAmount}</td>
          <td>${transaction.date}</td>
          <td>${transaction.time}</td>
        `;
        transactionList.appendChild(row);
      });
    }

    displayTransactionHistory();
  </script>

</body>

</html>
