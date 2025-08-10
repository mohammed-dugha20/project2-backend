# Admin API Documentation

This document provides a detailed overview of the API endpoints available for administrative users.

## Authentication

All admin endpoints require a valid Sanctum API token with the `admin` ability. The token must be included in the `Authorization` header as a Bearer token.

---

### 1. Admin Login

- **Method:** `POST`
- **Endpoint:** `/api/admin/auth/login`
- **Description:** Authenticates an admin user and returns an API token.
- **Request Body:**
  ```json
  {
    "email": "admin@example.com",
    "password": "password"
  }
  ```
- **Success Response (200 OK):**
  ```json
  {
    "message": "Admin login successful",
    "admin": { ... },
    "token": "your-api-token"
  }
  ```

### 2. Admin Logout

- **Method:** `POST`
- **Endpoint:** `/api/admin/auth/logout`
- **Description:** Revokes the admin's current API token.
- **Authentication:** Required.
- **Success Response (200 OK):**
  ```json
  {
    "message": "Admin successfully logged out"
  }
  ```

### 3. Get Authenticated Admin

- **Method:** `GET`
- **Endpoint:** `/api/admin/auth/me`
- **Description:** Retrieves the profile of the currently authenticated admin.
- **Authentication:** Required.
- **Success Response (200 OK):**
  ```json
  {
    "admin": { ... }
  }
  ```

---

## User Management

Base URL: `/api/admin/users`

### 1. Get All Users

- **Method:** `GET`
- **Endpoint:** `/api/admin/users`
- **Description:** Retrieves a paginated list of all users.
- **Authentication:** Required.
- **Success Response (200 OK):**
  ```json
  {
    "data": [ ... ],
    "pagination": { ... }
  }
  ```

### 2. Get User by ID

- **Method:** `GET`
- **Endpoint:** `/api/admin/users/{id}`
- **Description:** Retrieves a single user by their ID.
- **Authentication:** Required.
- **Success Response (200 OK):**
  ```json
  {
    "data": { ... }
  }
  ```

### 3. Create User

- **Method:** `POST`
- **Endpoint:** `/api/admin/users`
- **Description:** Creates a new user.
- **Authentication:** Required.
- **Request Body:**
  ```json
  {
    "name": "John Doe",
    "email": "user@example.com",
    "password": "password"
  }
  ```

### 4. Update User

- **Method:** `PUT`
- **Endpoint:** `/api/admin/users/{id}`
- **Description:** Updates an existing user.
- **Authentication:** Required.

### 5. Delete User

- **Method:** `DELETE`
- **Endpoint:** `/api/admin/users/{id}`
- **Description:** Deletes a user.
- **Authentication:** Required.

### 6. Toggle User Status

- **Method:** `PATCH`
- **Endpoint:** `/api/admin/users/{id}/toggle-status`
- **Description:** Activates or deactivates a user's account.
- **Authentication:** Required.

---

## Real Estate Office Management

Base URL: `/api/admin/real-estate-offices`

*(Endpoints for managing real estate offices follow a similar CRUD pattern: GET all, GET one, CREATE, UPDATE, DELETE, TOGGLE STATUS, GET reviews, GET documents)*

---

## Finishing Company Management

Base URL: `/api/admin/finishing-companies`

*(Endpoints for managing finishing companies follow a similar CRUD pattern: GET all, GET one, CREATE, UPDATE, DELETE, TOGGLE STATUS, and endpoints for services, work areas, portfolio, requests, reviews, analytics, and performance)*

---

## Dispute Management

Base URL: `/api/disputes`

### 1. Get All Disputes

- **Method:** `GET`
- **Endpoint:** `/api/disputes`
- **Description:** Retrieves a list of all disputes.
- **Authentication:** Required.

### 2. Get Dispute by ID

- **Method:** `GET`
- **Endpoint:** `/api/disputes/{id}`
- **Description:** Retrieves a single dispute.
- **Authentication:** Required.

### 3. Update Dispute

- **Method:** `PUT`
- **Endpoint:** `/api/disputes/{id}`
- **Description:** Updates the status or details of a dispute.
- **Authentication:** Required.

---

## System Management

Base URL: `/api/admin/system`

*(Includes endpoints for getting roles, permissions, analytics, reports, and settings.)*

---

## Super Admin Management

Base URL: `/api/admin/management`

*(Requires `super_admin` role. Includes endpoints for managing other admin accounts.)* 