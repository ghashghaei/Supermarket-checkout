<?php
// Associative array to store item details
$items = [
    'A' => ['unit_price' => 50, 'special' => ['quantity' => 3, 'price' => 130]],
    'B' => ['unit_price' => 30, 'special' => ['quantity' => 2, 'price' => 45]],
    'C' => ['unit_price' => 20],
    'D' => ['unit_price' => 15],
];

// Function to calculate total price for an item
function calculateTotalPrice($item, $quantity, $items)
{
    if (isset($items[$item]['special'])) {
        $special = $items[$item]['special'];
        $num_specials = intdiv($quantity, $special['quantity']);
        $remaining = $quantity % $special['quantity'];
        return $num_specials * $special['price'] +
            $remaining * $items[$item]['unit_price'];
    } else {
        return $quantity * $items[$item]['unit_price'];
    }
}

// Handle form submission
$totalPrice = [];
$grandTotal = 0;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($items as $item => $details) {
        $quantityKey = 'qty_' . $item;
        $quantity = isset($_POST[$quantityKey])
            ? (int) $_POST[$quantityKey]
            : 0;
        $totalPrice[$item] = calculateTotalPrice($item, $quantity, $items);
        $grandTotal += $totalPrice[$item]; // Add each item's total to the grand total
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pricing Table</title>
</head>
<body>
    <form method="POST">
        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Unit Price</th>
                    <th>Special Price</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item => $details) {
                    echo '<tr>';
                    echo '<td>' . $item . '</td>';
                    echo '<td>' . $details['unit_price'] . '</td>';
                    if (isset($details['special'])) {
                        echo '<td>' .
                            $details['special']['quantity'] .
                            ' for ' .
                            $details['special']['price'] .
                            '</td>';
                    } else {
                        echo '<td></td>';
                    }
                    echo "<td><input type='number' name='qty_" .
                        $item .
                        "' value='" .
                        (isset($_POST['qty_' . $item])
                            ? $_POST['qty_' . $item]
                            : '0') .
                        "'></td>";
                    // Display calculated total price
                    $calculatedTotal = isset($totalPrice[$item])
                        ? $totalPrice[$item]
                        : 0;
                    echo '<td>' . $calculatedTotal . '</td>';
                    echo '</tr>';
                } ?>
            </tbody>
        </table>

        <button type="submit">Calculate Total</button>
    </form>

    <div>
        <h2>Grand Total: <?php echo $grandTotal; ?></h2>
    </div>
</body>
</html>
