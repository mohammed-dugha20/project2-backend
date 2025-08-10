# Admin API Documentation: Real Estate Office Management

This document provides a detailed overview of the API endpoints for managing Real Estate Offices.

**Base URL:** `/api/admin/real-estate-offices`

**Authentication:** All endpoints require a valid Sanctum API token with the `admin` ability.

---

### 1. Get All Real Estate Offices

- **Method:** `GET`
- **Endpoint:** `/`
- **Description:** Retrieves a paginated list of all real estate offices.
- **Success Response (200 OK):**
  ```json
  {
    "success": true,
    "data": [
      {
        "id": 1,
        "name": "Prime Properties",
        "is_active": true,
        ...
      }
    ],
    "pagination": { ... }
  }
  ```

### 2. Get Real Estate Office by ID

- **Method:** `GET`
- **Endpoint:** `/{id}`
- **Description:** Retrieves a single real estate office by its ID, including related user data and documents.
- **Success Response (200 OK):**
  ```json
  {
    "success": true,
    "data": {
      "id": 1,
      "name": "Prime Properties",
      ...
    }
  }
  ```

### 3. Create Real Estate Office

- **Method:** `POST`
- **Endpoint:** `/`
- **Description:** Creates a new real estate office and an associated user account.
- **Request Body:**
  ```json
  {
    "user": {
      "name": "Office Manager",
      "email": "manager@primeproperties.com",
      "phone": "987654321",
      "password": "strongpassword"
    },
    "name": "Prime Properties",
    "address": "123 Main St, Anytown",
    "phone_number": "1122334455",
    "is_active": true
  }
  ```
- **Success Response (201 Created):**
  ```json
  {
    "success": true,
    "data": { ... },
    "message": "Real estate office created successfully"
  }
  ```

### 4. Update Real Estate Office

- **Method:** `PUT`
- **Endpoint:** `/{id}`
- **Description:** Updates an existing real estate office and its associated user data.
- **Request Body:** (Include only fields to be updated)
  ```json
  {
    "user": {
      "name": "Updated Manager Name"
    },
    "name": "Prime Properties Group"
  }
  ```
- **Success Response (200 OK):**
  ```json
  {
    "success": true,
    "data": { ... },
    "message": "Real estate office updated successfully"
  }
  ```

### 5. Delete Real Estate Office

- **Method:** `DELETE`
- **Endpoint:** `/{id}`
- **Description:** Deletes a real estate office and its associated user.
- **Success Response (200 OK):**
  ```json
  {
    "success": true,
    "message": "Real estate office deleted successfully"
  }
  ```

### 6. Toggle Real Estate Office Status

- **Method:** `PATCH`
- **Endpoint:** `/{id}/toggle-status`
- **Description:** Activates or deactivates a real estate office's account.
- **Success Response (200 OK):**
  ```json
  {
    "success": true,
    "data": {
        "id": 1,
        "is_active": false
    },
    "message": "Office status updated successfully"
  }
  ```

### 7. Get Real Estate Office Reviews

- **Method:** `GET`
- **Endpoint:** `/{id}/reviews`
- **Description:** Retrieves a list of reviews for a specific real estate office.
- **Success Response (200 OK):**
  ```json
  {
    "success": true,
    "data": [ ... ]
  }
  ```

### 8. Get Real Estate Office Documents

- **Method:** `GET`
- **Endpoint:** `/{id}/documents`
- **Description:** Retrieves a list of documents uploaded by a specific real estate office.
- **Success Response (200 OK):**
  ```json
  {
    "success": true,
    "data": [ ... ]
  }
  ``` 