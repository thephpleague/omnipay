# Omnipay: PayTheFly

**PayTheFly crypto payment gateway driver for the Omnipay PHP payment processing library.**

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](LICENSE)

## Installation

```bash
composer require paythefly/omnipay-paythefly
```

## Usage

### Purchase (Payment Request)

```php
use Omnipay\Omnipay;

$gateway = Omnipay::create('PayTheFly');
$gateway->initialize([
    'projectId'  => 'your-project-id',
    'projectKey' => getenv('PAYTHEFLY_PROJECT_KEY'),  // Load from env
    'privateKey' => getenv('PAYTHEFLY_PRIVATE_KEY'),  // Load from env
    'chainId'    => 56, // BSC mainnet (or 728126428 for TRON)
]);

$response = $gateway->purchase([
    'amount'   => '10.00',  // Human-readable amount (NOT raw units)
    'token'    => '0x55d398326f99059fF775485246999027B3197955', // USDT on BSC
    'serialNo' => 'ORDER-' . uniqid(),
    'deadline' => time() + 1800, // 30 minutes
])->send();

if ($response->isRedirect()) {
    $response->redirect(); // Redirect to PayTheFly payment page
}
```

### Webhook Handling (Complete Purchase)

```php
// In your webhook controller
$gateway = Omnipay::create('PayTheFly');
$gateway->initialize([
    'projectKey' => getenv('PAYTHEFLY_PROJECT_KEY'),
]);

$webhookBody = json_decode(file_get_contents('php://input'), true);

$response = $gateway->completePurchase([
    'webhookData' => $webhookBody,
])->send();

if ($response->isSuccessful()) {
    // Payment confirmed
    $serialNo = $response->getTransactionReference(); // serial_no
    $txHash   = $response->getTransactionId();        // tx_hash
    $value    = $response->getAmount();                // value (NOT amount)
    $wallet   = $response->getWallet();                // payer wallet

    // Update your order...
}

// IMPORTANT: Response must contain "success" for PayTheFly
echo 'success';
```

## Supported Chains

| Chain | Chain ID    | Decimals |
|-------|-------------|----------|
| BSC   | 56          | 18       |
| TRON  | 728126428   | 6        |

## Webhook Format

### Request Body

```json
{
    "data": "{\"serial_no\":\"ORDER-123\",\"value\":\"10.00\",\"confirmed\":true,\"tx_hash\":\"0x...\",\"wallet\":\"0x...\",\"tx_type\":1}",
    "sign": "hmac_sha256_hex_signature",
    "timestamp": 1709312400
}
```

### Signature Verification

```
HMAC-SHA256(data + "." + timestamp, projectKey)
```

### Payload Fields

| Field      | Description                        |
|------------|------------------------------------|
| value      | Payment amount (NOT "amount")      |
| confirmed  | Payment confirmed (NOT "status")   |
| serial_no  | Order serial number                |
| tx_hash    | Blockchain transaction hash        |
| wallet     | Payer's wallet address             |
| tx_type    | 1 = payment, 2 = withdrawal       |

## Security Notes

- **Private keys** and **project keys** must be loaded from environment variables
- Webhook signatures use **timing-safe comparison** (`hash_equals`)
- EIP-712 signing uses **Keccak-256** (NOT SHA3-256)
- Never log or expose private keys

## License

MIT License. See [LICENSE](LICENSE) for details.
