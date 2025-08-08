Ecommerce & Service Request API

A Laravel-based RESTful API for an **e-commerce** and **service request** platform.
The API supports **user authentication**, **role-based access control (Admin/User)**, **product & category management**, **favorites**, **shopping cart**, **prescriptions**, and **service requests**.

---

## 📌 Features

### **Authentication**

* User registration, login, logout
* Password reset with OTP verification
* Role-based access: `admin` and `user`
* Sanctum authentication for secure API access

### **Admin Capabilities**

* Manage **products** (CRUD)
* Manage **categories** (CRUD)
* View & manage **favorites**
* View & manage **shopping carts**
* Manage **prescriptions**
* Manage **service requests**

### **User Capabilities**

* Browse products & categories
* Add/remove favorites
* Manage personal shopping cart (add, update, delete items)
* Submit and manage prescriptions
* Submit and manage service requests

---

## 🚀 Tech Stack

* **Backend**: Laravel (Latest Version)
* **Authentication**: Laravel Sanctum
* **Database**: MySQL / MariaDB
* **Authorization**: Role-based middleware
* **API Format**: JSON

---

## 📂 API Endpoints

### **Public Routes**

| Method | Endpoint           | Description                 |
| ------ | ------------------ | --------------------------- |
| `POST` | `/login`           | User login                  |
| `POST` | `/signup`          | User registration           |
| `POST` | `/forgot-password` | Send OTP for password reset |
| `POST` | `/reset-password`  | Reset password with OTP     |

---

### **Authenticated Routes**

| Method | Endpoint  | Description             |
| ------ | --------- | ----------------------- |
| `POST` | `/logout` | Logout the current user |

---

### **Admin Routes** (`/admin`, requires `role:admin`)

| Method                | Endpoint            | Description                   |
| --------------------- | ------------------- | ----------------------------- |
| `GET/POST/PUT/DELETE` | `/products`         | Manage products               |
| `GET/POST/PUT/DELETE` | `/categories`       | Manage categories             |
| `GET/POST/PUT/DELETE` | `/favourites`       | Manage favorites              |
| `GET`                 | `/carts`            | View all carts                |
| `GET`                 | `/carts/{user}`     | View cart for a specific user |
| `GET/POST/PUT/DELETE` | `/prescriptions`    | Manage prescriptions          |
| `GET/POST/PUT/DELETE` | `/request-services` | Manage service requests       |

---

### **User Routes** (requires `role:user`)

| Method                | Endpoint                              | Description                 |
| --------------------- | ------------------------------------- | --------------------------- |
| `GET`                 | `/products`                           | View products               |
| `GET`                 | `/categories`                         | View categories             |
| `GET`                 | `/favourites`                         | View user favorites         |
| `POST`                | `/favourites`                         | Add favorite                |
| `DELETE`              | `/favourites`                         | Remove favorite             |
| `GET`                 | `/cart`                               | View current user cart      |
| `DELETE`              | `/cart/{cart}`                        | Delete user cart            |
| `POST`                | `/cart/items`                         | Add item to cart            |
| `PUT`                 | `/cart/items/{cartItem}`              | Update cart item            |
| `PUT`                 | `/cart/items/by-product/{product_id}` | Update cart item by product |
| `DELETE`              | `/cart/items/{cartItem}`              | Remove item from cart       |
| `DELETE`              | `/cart/items/by-product/{product_id}` | Remove item by product      |
| `GET/POST/PUT/DELETE` | `/prescriptions`                      | Manage prescriptions        |
| `GET/POST/PUT/DELETE` | `/request-services`                   | Manage service requests     |

---

## 🔐 Authentication & Authorization

* **Auth**: Laravel Sanctum is used for API token authentication.
* **Roles**:

  * **Admin**: Full control over all resources.
  * **User**: Restricted to their own data and limited actions.

Example of role-based route protection:

```php
Route::middleware(['auth:sanctum', 'role:admin'])->group(function() {
    // Admin routes here
});
```

---

## 🛠 Installation

1. **Clone the repository**

```bash
git clone https://github.com/habibaesam13/Pharma-Care.git
```

2. **Install dependencies**

```bash
composer install
```

3. **Copy `.env` file**

```bash
cp .env.example .env
```

4. **Configure environment variables** in `.env`

* Database connection
* Sanctum settings
* Mail settings (for OTP)

5. **Run migrations**

```bash
php artisan migrate
```

6. **Run server**

```bash
php artisan serve
```

---

## 📌 Example Requests

**Login Request**

```http
POST /api/login
Content-Type: application/json

{
  "email": "user@example.com",
  "password": "password123"
}
```

**Login Response**

```json
{
  "status": "success",
  "token": "1|XdfgHjklQwerty..."
}


I can also make you a **diagram showing the relationships between routes, controllers, and features** so your README looks even more professional.
Do you want me to include that?
