# Wallet API Documentation

## Overview

The Wallet API provides functionality for users to manage their wallet balance, add money, apply offers, and view transaction history. The API supports offer-based bonus calculations and direct Razorpay payment integration.

## Base URL

```
https://your-domain.com/api/wallet
```

## Authentication

Most endpoints require user authentication. Include the Bearer token in the Authorization header:

```
Authorization: Bearer {your_access_token}
```

## Endpoints

### 1. Get Wallet Balance

**GET** `/api/wallet/balance`

Get the current user's wallet balance and basic information.

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "message": "Wallet balance retrieved successfully",
  "data": {
    "balance": 1500.00,
    "wallet_id": 123,
    "currency": "INR",
    "last_updated": "2025-01-15T10:30:00.000000Z"
  }
}
```

### 2. Get Available Offers

**GET** `/api/wallet/offers`

Get all active wallet recharge offers.

**Response:**
```json
{
  "success": true,
  "message": "Wallet offers retrieved successfully",
  "data": [
    {
      "id": 1,
      "amount": 100.00,
      "extra_percent": 100,
      "bonus_amount": 100.00,
      "total_amount": 200.00,
      "is_popular": true,
      "label": "Most Popular",
      "status": "active",
      "sort_order": 2,
      "description": "Get 100% extra on ₹100 recharge",
      "created_at": "2025-01-15T10:30:00.000000Z",
      "updated_at": "2025-01-15T10:30:00.000000Z"
    },
    {
      "id": 2,
      "amount": 500.00,
      "extra_percent": 50,
      "bonus_amount": 250.00,
      "total_amount": 750.00,
      "is_popular": false,
      "label": null,
      "status": "active",
      "sort_order": 3,
      "description": "Get 50% extra on ₹500 recharge",
      "created_at": "2025-01-15T10:30:00.000000Z",
      "updated_at": "2025-01-15T10:30:00.000000Z"
    }
  ]
}
```

### 3. Calculate Offer

**POST** `/api/wallet/calculate-offer`

Calculate the bonus amount for a specific recharge amount.

**Request Body:**
```json
{
  "amount": 100
}
```

**Response:**
```json
{
  "success": true,
  "message": "Offer calculated successfully",
  "data": {
    "amount": 100.00,
    "bonus_amount": 100.00,
    "total_amount": 200.00,
    "offer_applied": true,
    "offer_details": {
      "id": 1,
      "extra_percent": 100,
      "is_popular": true,
      "label": "Most Popular"
    },
    "message": "You'll get ₹100 bonus on ₹100 recharge"
  }
}
```

### 4. Add Money to Wallet (Direct Success Flow)

**POST** `/api/wallet/add-money`

Add money to wallet directly after successful Razorpay payment.

**Headers:**
```
Authorization: Bearer {token}
```

**Request Body:**
```json
{
  "amount": 100,
  "payment_method": "upi",
  "offer_id": 1,
  "razorpay_payment_id": "pay_1234567890",
  "razorpay_order_id": "order_1234567890",
  "razorpay_signature": "abc123def456..."
}
```

**Parameters:**
- `amount` (required): Recharge amount (1-100000)
- `payment_method` (required): Payment method (card, upi, netbanking, wallet)
- `offer_id` (optional): ID of the offer to apply
- `razorpay_payment_id` (required): Razorpay payment ID
- `razorpay_order_id` (required): Razorpay order ID
- `razorpay_signature` (required): Razorpay payment signature

**Response:**
```json
{
  "success": true,
  "message": "Money added to wallet successfully",
  "data": {
    "transaction_id": 456,
    "amount_credited": 100.00,
    "bonus_credited": 100.00,
    "total_credited": 200.00,
    "new_balance": 1700.00,
    "payment_method": "upi",
    "offer_applied": true,
    "offer_details": {
      "id": 1,
      "extra_percent": 100,
      "is_popular": true,
      "label": "Most Popular"
    }
  }
}
```

### 5. Get Transaction History

**GET** `/api/wallet/transactions`

Get user's transaction history with pagination and filtering.

**Headers:**
```
Authorization: Bearer {token}
```

**Query Parameters:**
- `per_page` (optional): Number of transactions per page (default: 15)
- `type` (optional): Filter by transaction type (credit, debit, bonus)
- `status` (optional): Filter by status (pending, completed, failed)

**Response:**
```json
{
  "success": true,
  "message": "Transaction history retrieved successfully",
  "data": {
    "transactions": [
      {
        "id": 456,
        "amount": 100.00,
        "type": "credit",
        "description": "Wallet recharge via upi",
        "status": "completed",
        "created_at": "2025-01-15T10:30:00.000000Z",
        "completed_at": "2025-01-15T10:30:00.000000Z",
        "payment_method": "upi",
        "offer_id": 1,
        "razorpay_payment_id": "pay_1234567890",
        "razorpay_order_id": "order_1234567890"
      },
      {
        "id": 457,
        "amount": 100.00,
        "type": "bonus",
        "description": "Bonus from offer: 100% extra",
        "status": "completed",
        "created_at": "2025-01-15T10:30:00.000000Z",
        "completed_at": "2025-01-15T10:30:00.000000Z",
        "payment_method": null,
        "offer_id": 1,
        "razorpay_payment_id": null,
        "razorpay_order_id": null
      }
    ],
    "pagination": {
      "current_page": 1,
      "last_page": 5,
      "per_page": 15,
      "total": 75
    }
  }
}
```

### 6. Get Transaction Details

**GET** `/api/wallet/transactions/{id}`

Get detailed information about a specific transaction.

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "message": "Transaction details retrieved successfully",
  "data": {
    "id": 456,
    "amount": 100.00,
    "type": "credit",
    "description": "Wallet recharge via upi",
    "status": "completed",
    "created_at": "2025-01-15T10:30:00.000000Z",
    "completed_at": "2025-01-15T10:30:00.000000Z",
    "payment_method": "upi",
    "offer_id": 1,
    "razorpay_payment_id": "pay_1234567890",
    "razorpay_order_id": "order_1234567890",
    "razorpay_signature": "abc123def456..."
  }
}
```

## Error Responses

### Validation Error
```json
{
  "success": false,
  "message": "The amount field is required.",
  "status_code": 422
}
```

### Unauthorized
```json
{
  "success": false,
  "message": "User not authenticated",
  "status_code": 401
}
```

### Invalid Payment Signature
```json
{
  "success": false,
  "message": "Invalid payment signature",
  "status_code": 400
}
```

### Not Found
```json
{
  "success": false,
  "message": "Transaction not found",
  "status_code": 404
}
```

### Server Error
```json
{
  "success": false,
  "message": "Error adding money to wallet: Database connection failed",
  "status_code": 500
}
```

## Razorpay Integration

### Frontend Flow

1. **User selects recharge amount and offer**
2. **Frontend calls Razorpay to create order**
3. **User completes payment on Razorpay**
4. **Razorpay returns success with payment details**
5. **Frontend calls API with payment details**
6. **API verifies signature and credits wallet**

### Frontend Integration Example

```javascript
// 1. Create Razorpay order
const orderData = {
  amount: 10000, // Amount in paise (₹100)
  currency: 'INR',
  receipt: 'receipt_' + Date.now()
};

// Create order with Razorpay
const order = await razorpay.orders.create(orderData);

// 2. Initialize payment
const options = {
  key: 'your_razorpay_key',
  amount: orderData.amount,
  currency: orderData.currency,
  name: 'Your App Name',
  description: 'Wallet Recharge',
  order_id: order.id,
  handler: function(response) {
    // 3. Payment successful - call API
    addMoneyToWallet(response);
  }
};

const rzp = new Razorpay(options);
rzp.open();

// 4. Add money to wallet
async function addMoneyToWallet(razorpayResponse) {
  const data = {
    amount: 100, // Amount in rupees
    payment_method: 'upi',
    offer_id: selectedOfferId, // if offer selected
    razorpay_payment_id: razorpayResponse.razorpay_payment_id,
    razorpay_order_id: razorpayResponse.razorpay_order_id,
    razorpay_signature: razorpayResponse.razorpay_signature
  };

  try {
    const response = await fetch('/api/wallet/add-money', {
      method: 'POST',
      headers: {
        'Authorization': 'Bearer ' + userToken,
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(data)
    });

    const result = await response.json();
    
    if (result.success) {
      // Update UI with new balance
      updateWalletBalance(result.data.new_balance);
      showSuccessMessage('Money added successfully!');
    } else {
      showErrorMessage(result.message);
    }
  } catch (error) {
    showErrorMessage('Error adding money to wallet');
  }
}
```

### Environment Variables

Add these to your `.env` file:

```env
RAZORPAY_KEY=your_razorpay_key
RAZORPAY_SECRET=your_razorpay_secret
RAZORPAY_WEBHOOK_SECRET=your_webhook_secret
```

## Usage Examples

### Frontend Integration

#### 1. Display Wallet Balance
```javascript
// Get user's wallet balance
fetch('/api/wallet/balance', {
  headers: {
    'Authorization': 'Bearer ' + userToken
  }
})
.then(response => response.json())
.then(data => {
  if (data.success) {
    document.getElementById('wallet-balance').textContent = 
      '₹' + data.data.balance.toFixed(2);
  }
});
```

#### 2. Show Available Offers
```javascript
// Get available offers
fetch('/api/wallet/offers')
.then(response => response.json())
.then(data => {
  if (data.success) {
    data.data.forEach(offer => {
      // Display offer cards
      createOfferCard(offer);
    });
  }
});
```

#### 3. Calculate Offer for Amount
```javascript
// Calculate offer for user input amount
const amount = document.getElementById('recharge-amount').value;
fetch('/api/wallet/calculate-offer', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({ amount: parseFloat(amount) })
})
.then(response => response.json())
.then(data => {
  if (data.success) {
    if (data.data.offer_applied) {
      document.getElementById('bonus-amount').textContent = 
        'Bonus: ₹' + data.data.bonus_amount;
      document.getElementById('total-amount').textContent = 
        'Total: ₹' + data.data.total_amount;
    }
  }
});
```

## Database Schema

### Wallet Transactions
- `id`: Primary key
- `wallet_id`: Foreign key to wallets table
- `amount`: Transaction amount
- `type`: Transaction type (credit, debit, bonus)
- `description`: Transaction description
- `meta`: JSON field for additional data (payment method, status, Razorpay details, etc.)

### Wallet Offers
- `id`: Primary key
- `amount`: Recharge amount for the offer
- `extra_percent`: Extra percentage bonus
- `is_popular`: Boolean for "Most Popular" tag
- `label`: Optional label/tag
- `status`: Offer status (active/inactive)
- `sort_order`: Display order

## Security Considerations

1. **Authentication**: All sensitive endpoints require user authentication
2. **Validation**: All input is validated and sanitized
3. **Signature Verification**: Razorpay payment signatures are verified
4. **Transaction Integrity**: Database transactions ensure data consistency
5. **Rate Limiting**: Consider implementing rate limiting for payment endpoints
6. **Logging**: All wallet transactions are logged for audit purposes

## Testing

### Test Data
Use the provided seeders to create test offers:
```bash
php artisan db:seed --class=WalletOfferSeeder
```

### API Testing
Test the endpoints using tools like Postman or curl:

```bash
# Get wallet balance
curl -H "Authorization: Bearer {token}" \
  https://your-domain.com/api/wallet/balance

# Get offers
curl https://your-domain.com/api/wallet/offers

# Add money (after successful Razorpay payment)
curl -X POST -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "amount": 100,
    "payment_method": "upi",
    "offer_id": 1,
    "razorpay_payment_id": "pay_test123",
    "razorpay_order_id": "order_test123",
    "razorpay_signature": "test_signature"
  }' \
  https://your-domain.com/api/wallet/add-money
```

## Common Function

The `addMoneyToWallet()` function is a common method that can be easily modified or extended:

```php
protected function addMoneyToWallet($user, $amount, $bonusAmount, $paymentMethod, $offer = null, $paymentMeta = [])
{
    // This function handles all wallet credit logic
    // Easy to modify for different payment gateways or business logic
}
```

This makes it easy to:
- Change payment gateway integration
- Add additional validation
- Modify bonus calculation logic
- Add notification systems
- Implement different transaction types 
