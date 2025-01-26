# Awaj Project

This project is a web application that includes user registration, OTP generation, and complaint management functionalities.

## Features

- User Registration
- OTP Generation and Sending
- Complaint Management
- JWT Authentication

## Installation

1. Clone the repository:
    ```sh
    git clone https://github.com/iigal/awaj.git
    ```
2. Navigate to the project directory:
    ```sh
    cd awaj
    ```
3. Install the dependencies:
    ```sh
    composer install
    ```
4. Copy the `.env.example` file to `.env`:
    ```sh
    cp .env.example .env
    ```
5. Generate the application key:
    ```sh
    php artisan key:generate
    ```
6. Set up your database and update the `.env` file with your database credentials.

7. Run the database migrations:
    ```sh
    php artisan migrate
    ```

## Usage

### Running the Application

To start the application, run:
```sh
php artisan serve
