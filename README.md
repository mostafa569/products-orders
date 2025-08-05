# Products & Orders Management API

A robust Laravel-based REST API for managing products and orders with intelligent stock management, built with modern Laravel 12 architecture.

## 🚀 Features

### Core Functionality

-   **Product Management**: CRUD operations for products with stock tracking
-   **Order Management**: Complete order lifecycle with status tracking
-   **Stock Management**: Automatic stock deduction/restoration based on order status
-   **Data Validation**: Comprehensive input validation using Form Requests
-   **API Resources**: Structured JSON responses with consistent formatting
-   **Service Layer**: Business logic separation for maintainability

### Technical Features

-   **Laravel 12**: Latest Laravel framework with modern PHP 8.2+
-   **RESTful API**: Standard HTTP methods with proper status codes
-   **Database Integrity**: Foreign key constraints and data validation
-   **Error Handling**: Comprehensive error responses with meaningful messages
-   **Code Organization**: Clean architecture with proper separation of concerns

## 📋 Requirements

-   PHP 8.2 or higher
-   Composer
-   SQLite (default) or MySQL/PostgreSQL
-   Laravel 12.x

## 🛠️ Installation

1. **Clone the repository**

    ```bash
    git clone https://github.com/mostafa569/products-orders.git
    cd products
    ```

2. **Install dependencies**

    ```bash
    composer install
    ```

3. **Environment setup**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4. **Database setup**

    ```bash
    php artisan migrate
    php artisan db:seed
    ```

5. **Start the development server**
    ```bash
    php artisan serve
    ```

The API will be available at `http://localhost:8000/api`

## 📚 API Documentation

### Base URL

```
http://localhost:8000/api
```
## Artisan Commands Used:
- php artisan make:model Product -m -f

- php artisan make:controller ProductController

- php artisan make:request StoreProductRequest

- php artisan make:resource ProductResource

- php artisan make:event OrderCreated

- php artisan make:listener DeductStockOnOrderCreated

- php artisan migrate, php artisan db:seed, and php artisan serve
## 🛍️ Products API

### List All Products

```http
GET /api/products
```

**Response:**

```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "Product Name",
            "price": "99.99",
            "stock": 10,
            "created_at": "2024-01-01T00:00:00.000000Z",
            "updated_at": "2024-01-01T00:00:00.000000Z"
        }
    ]
}
```

### Create Product

```http
POST /api/products
```

**Request Body:**

```json
{
    "name": "Product Name",
    "price": 99.99,
    "stock": 10
}
```

**Response:**

```json
{
    "success": true,
    "message": "Product created successfully",
    "data": {
        "id": 1,
        "name": "Product Name",
        "price": "99.99",
        "stock": 10,
        "created_at": "2024-01-01T00:00:00.000000Z",
        "updated_at": "2024-01-01T00:00:00.000000Z"
    }
}
```

### Update Product

```http
PUT /api/products/{id}
```

**Request Body:**

```json
{
    "name": "Updated Product Name",
    "price": 149.99
}
```

**Note:** Stock cannot be directly updated through this endpoint.

### Delete Product

```http
DELETE /api/products/{id}
```

**Response:**

```json
{
    "success": true,
    "message": "Product deleted successfully"
}
```

**Error Response (if product is in order):**

```json
{
    "success": false,
    "message": "Cannot delete product that is part of an order"
}
```

## 📦 Orders API

### Create Order

```http
POST /api/orders
```

**Request Body:**

```json
{
    "customer_name": "John Doe",
    "items": [
        {
            "product_id": 1,
            "quantity": 2
        },
        {
            "product_id": 2,
            "quantity": 1
        }
    ]
}
```

**Response:**

```json
{
    "success": true,
    "message": "Order created successfully",
    "data": {
        "id": 1,
        "customer_name": "John Doe",
        "status": "pending",
        "total_price": 299.97,
        "items": [
            {
                "id": 1,
                "product_id": 1,
                "product_name": "Product Name",
                "quantity": 2,
                "price": "99.99",
                "subtotal": 199.98
            }
        ],
        "created_at": "2024-01-01T00:00:00.000000Z",
        "updated_at": "2024-01-01T00:00:00.000000Z"
    }
}
```

**Error Response (insufficient stock):**

```json
{
    "success": false,
    "message": "Insufficient stock for product: Product Name. Available: 5, Requested: 10"
}
```

### Get Order Details

```http
GET /api/orders/{id}
```

**Response:**

```json
{
    "success": true,
    "data": {
        "id": 1,
        "customer_name": "John Doe",
        "status": "pending",
        "total_price": 299.97,
        "items": [
            {
                "id": 1,
                "product_id": 1,
                "product_name": "Product Name",
                "quantity": 2,
                "price": "99.99",
                "subtotal": 199.98
            }
        ],
        "created_at": "2024-01-01T00:00:00.000000Z",
        "updated_at": "2024-01-01T00:00:00.000000Z"
    }
}
```

### Update Order Status

```http
PUT /api/orders/{id}
```

**Request Body:**

```json
{
    "status": "completed"
}
```

**Available Statuses:**

-   `pending`
-   `processing`
-   `completed`
-   `cancelled`

### Cancel Order

```http
DELETE /api/orders/{id}
```

**Response:**

```json
{
    "success": true,
    "message": "Order cancelled successfully"
}
```

**Error Response (if order is completed):**

```json
{
    "success": false,
    "message": "Cannot cancel a completed order"
}
```

## 🏗️ Architecture

### Directory Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── ProductController.php
│   │   └── OrderController.php
│   ├── Requests/
│   │   ├── StoreProductRequest.php
│   │   ├── UpdateProductRequest.php
│   │   ├── StoreOrderRequest.php
│   │   └── UpdateOrderRequest.php
│   └── Resources/
│       ├── ProductResource.php
│       ├── OrderResource.php
│       └── OrderItemResource.php
├── Models/
│   ├── Product.php
│   ├── Order.php
│   └── OrderItem.php
├── Services/
│   └── OrderService.php
└── Events/
    ├── OrderCreated.php
    └── OrderCancelled.php
```

### Key Components

#### Models

-   **Product**: Manages product data and stock
-   **Order**: Handles order information and status
-   **OrderItem**: Links products to orders with quantity and price

#### Controllers

-   **ProductController**: Handles product CRUD operations
-   **OrderController**: Manages order lifecycle and status updates

#### Services

-   **OrderService**: Contains business logic for order creation and management

#### Form Requests

-   Input validation with custom rules and error messages
-   Ensures data integrity and security

#### API Resources

-   Consistent JSON response formatting
-   Data transformation and presentation

## 🔄 Stock Management Logic

### Order Creation

1. Validates stock availability for all items
2. Deducts stock from products
3. Creates order with items and calculated totals
4. Stores product price at time of order

### Order Status Updates

-   **Pending → Processing**: No stock changes
-   **Processing → Completed**: No stock changes
-   **Any Status → Cancelled**: Restores stock to products

### Stock Protection

-   Products cannot be deleted if they're part of an order
-   Stock cannot be directly updated through product update endpoint
-   Automatic stock restoration on order cancellation



## 🚀 Development

### Development Server

```bash
php artisan serve
```


### Database Seeding

```bash
php artisan db:seed
```


## 📊 Database Schema

### Products Table

```sql
CREATE TABLE products (
    id BIGINT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    stock INTEGER NOT NULL DEFAULT 0,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Orders Table

```sql
CREATE TABLE orders (
    id BIGINT PRIMARY KEY,
    customer_name VARCHAR(255) NOT NULL,
    status ENUM('pending', 'completed', 'cancelled') DEFAULT 'pending',
    total_price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Order Items Table

```sql
CREATE TABLE order_items (
    id BIGINT PRIMARY KEY,
    order_id BIGINT NOT NULL,
    product_id BIGINT NOT NULL,
    quantity INTEGER NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);
```

## 🔧 Configuration


### Database Configuration

The application uses SQLite by default for simplicity. For production, update the database configuration in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST= host
DB_PORT= port 
DB_DATABASE=products_orders
DB_USERNAME= u_name
DB_PASSWORD=
```

## 📝 API Response Format

All API responses follow a consistent format:

### Success Response

```json
{
    "success": true,
    "message": "Operation completed successfully",
    "data": { ... }
}
```

### Error Response

```json
{
    "success": false,
    "message": "Error description",
    "errors": { ... }
}
```
 
