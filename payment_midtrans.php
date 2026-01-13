<?php
include_once 'dbconnect.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$serverKey = getenv('MIDTRANS_SERVER_KEY');
if (!$serverKey) {
    error_log("Midtrans Server Key is missing from environment variables.");
}
$isProduction = false;

$userId = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;

if ($userId <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid user ID']);
    exit;
}

try {
    $stmt = $conn->prepare("
        SELECT 
            c.quantity,
            p.price,
            p.promo,
            p.stock,
            p.name
        FROM cart c
        JOIN product_items p ON c.product_id = p.id
        WHERE c.user_id = ?
    ");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $totalAmount = 0;
    $items = [];
    
    while ($row = $result->fetch_assoc()) {
        if ($row['quantity'] > $row['stock']) {
             echo json_encode(['status' => 'error', 'message' => 'Stok produk ' . $row['name'] . ' tidak mencukupi']);
             exit;
        }
        
        $price = $row['promo'] > 0 ? $row['promo'] : $row['price'];
        $totalAmount += $price * $row['quantity'];
        
        $items[] = [
            'id' => uniqid(),
            'price' => intval($price),
            'quantity' => intval($row['quantity']),
            'name' => substr($row['name'], 0, 50)   
        ];
    }
    $stmt->close();

    if ($totalAmount <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Cart is empty or total is 0']);
        exit;
    }

    $orderId = 'TRX-' . $userId . '-' . time();
    
    $transaction_details = [
        'order_id' => $orderId,
        'gross_amount' => (int)$totalAmount,
    ];

    $params = [
        'transaction_details' => $transaction_details,
        'item_details' => $items,
        'credit_card' => [
            'secure' => true
        ]
    ];

    $auth = base64_encode($serverKey . ':');
    $url = $isProduction 
        ? 'https://app.midtrans.com/snap/v1/transactions' 
        : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Accept: application/json',
        'Content-Type: application/json',
        'Authorization: Basic ' . $auth
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode == 201) {
        $json = json_decode($response, true);
        echo json_encode([
            'status' => 'success', 
            'redirect_url' => $json['redirect_url'],
            'token' => $json['token'],
            'order_id' => $orderId
        ]);
    } else {
        echo json_encode([
            'status' => 'error', 
            'message' => 'Midtrans Error: ' . $response,
            'code' => $httpCode
        ]);
    }

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Server Error: ' . $e->getMessage()]);
}

$conn->close();
?>
