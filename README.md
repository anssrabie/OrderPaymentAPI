# Extendable Order and Payment Management API

## Objective
The goal of this project is to build a **Laravel-based API** for managing orders and payments while ensuring clean code principles, security, and extensibility. The system allows adding new payment gateways with minimal effort using the **Strategy Pattern**.

---

## Features

### ✅ Order Management:
- **Create Order**: Accepts user details and purchased items (product name, quantity, price) and calculates the total.
- **Update Order**: Modify existing order details.
- **Delete Order**: Remove an order (only if no payments are associated).
- **View Orders**: Retrieve all orders or filter by status (`pending`, `confirmed`, `cancelled`).

### ✅ Payment Management:
- **Process Payment**: Simulates payment processing for an order.
- **Payment Details**:
    - `payment_id` (Unique Payment Identifier)
    - `order_id` (Associated Order)
    - `status` (Pending, Successful, Failed)
    - `method` (e.g., Credit Card, PayPal)
- **Extensible Payment Gateway Integration**:
    - Uses **Strategy Pattern** to allow adding new gateways with minimal code changes.
- **View Payments**: Retrieve payment details for a specific order or all payments.

### ✅ Business Rules:
- Payments can only be processed for **confirmed orders**.
- Orders **cannot be deleted** if they have associated payments.

### ✅ API Design:
- Follows **RESTful principles**.
- Uses appropriate **HTTP methods & status codes**.
- Provides **pagination** for list endpoints.

### ✅ Authentication & Security:
- Uses **JWT authentication**.
- Includes endpoints for **user registration & login**.

### ✅ Validation & Error Handling:
- Ensures **all API inputs are validated**.
- Provides **meaningful error messages**.

### ✅ Testing:
- Unit and Feature tests for:
    - Order management
    - Payment processing
    - Authentication

---

## Tech Stack
- **Framework**: Laravel 11
- **Database**: MySQL
- **Authentication**: JWT (JSON Web Token)
- **Design Pattern**: Strategy Pattern for payment gateways

---

## Setup Instructions

### Requirements
<p align="center">
  <a href="https://www.php.net/">
    <img src="https://www.php.net/images/logos/new-php-logo.svg" height="60">
  </a>
  <a href="https://www.mysql.com/">
    <img src="https://www.mysql.com/common/logos/logo-mysql-170x115.png" height="60">
  </a>
  <a href="https://getcomposer.org/">
    <img src="https://getcomposer.org/img/logo-composer-transparent.png" height="60">
  </a>
  <a href="https://laravel.com/">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" height="60">
  </a>
</p>

- **PHP**: 8.2 or higher
- **MySQL**: 8.0 or higher
- **Composer**: 2.0 or higher

### Installation

1. **Clone the repository**:
   ```bash
   git clone https://github.com/your-repo/OrderPaymentAPI.git
   ```

2. **Navigate into the project folder**:
   ```bash
   cd OrderPaymentAPI
   ```

3. **Install dependencies**:
   ```bash
   composer install
   ```

4. **Environment setup**:
   ```bash
   cp .env.example .env
   ```

5. **Generate the application key**:
   ```bash
   php artisan key:generate
   ```

6. **Set up the database**:
    - Update your `.env` file with your database credentials.
    - Run the migrations and seeders:
   ```bash
   php artisan migrate:fresh --seed
   ```

7. **Run the application**:
   ```bash
   php artisan serve
   ```

---

## API Documentation (Postman)

You can access the **Postman API documentation** for this project using the following link:

[Postman API Documentation](https://documenter.getpostman.com/view/43547209/2sB2cPjkTu)

---


## Adding a New Payment Gateway

This project uses the **Strategy Pattern** for payment gateways, allowing easy integration of new payment methods.

### Steps to Add a New Payment Gateway:
1. **Create a New Payment Gateway Class**:
    - Inside `App\Services\Payments\Gateways`, create a new gateway class implementing `PaymentGatewayInterface`.

2. **Implement the Payment Logic**:
   ```php
   namespace App\Services\Payments\Gateways;

   class StripeGateway implements PaymentGatewayInterface
   {
       public function processPayment(float $amount): array
       {
           return [
            'status' => PaymentStatus::Pending->value,
            'payment_id' => 'stripe_' . uniqid(),
            'message' => 'Payment processed via Stripe',
            'method' => PaymentMethod::CreditCard->value,
           ];
       }
   }
   ```

3. **Register the New Gateway**:
    - Modify `PaymentGatewayFactory` to support the new gateway.
   ```php
   $gateway = match ($method) {
       'credit_card' => new CreditCardGateway(),
       'paypal' => new PayPalGateway(),
       'stripe' => new StripeGateway(), // New gateway
   };
   ```

---

## Testing

To run unit and feature tests, execute:
```bash
php artisan test
```

---

## Conclusion
This API is designed to be **secure, extensible, and easy to maintain**. By leveraging **design patterns**, clean coding practices, and robust authentication, it ensures smooth order and payment management.

