# Eteam Test

This is a console-based e-commerce application built with Laravel Zero framework. The application allows users to perform various operations related to product purchase and order.

## Requirements

-   PHP 8.1+
-   Composer 2+

## Installation

1. Clone the repository to your local machine using the following command:

```bash
git clone https://github.com/toko-cheetah/eteam-test.git
```

2. Navigate to the project directory and run the following command to install the dependencies:

```bash
composer install
```

## Usage

To run the application, navigate to the project directory and use the following commands:

To output all commands:

```bash
php eteam-test
```

To run the command:

```bash
php eteam-test <command> [arguments]
```

Replace `<command>` with one of the following commands:

-   `save_product`: Add a new product to the catalog or modify an existing one.
-   `purchase_product`: Purchase a product.
-   `order_product`: Place an order for the product.
-   `get_quantity_of_product`: Return the remaining quantity of a specific product.
-   `get_average_price`: Get the average price of a specific product.
-   `get_product_profit`: Get the profit of a specific product.
-   `get_fewest_product`: Get the product with the lowest remaining quantity.
-   `get_most_popular_product`: Get the product with the highest number of orders.
-   `get_purchases_report`: Get a report of all purchases made.
-   `get_orders_report`: Get a report of all orders made.
-   `exit`: Close the console application.

Replace `[arguments]` with the necessary arguments for each command.

For example, to run the `save_product` command:

```bash
php eteam-test save_product prod001 iphone 2800
```

## Storage

The root directory of the files saved is: `eteam-test/storage`. You can modify or delete the data manually if needed.
