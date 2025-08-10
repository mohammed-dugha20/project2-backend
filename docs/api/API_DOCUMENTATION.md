 # API Documentation

## Authentication
All admin endpoints require authentication using Laravel Sanctum. Include the token in the Authorization header:
```
Authorization: Bearer {your_token}
```

## Admin Endpoints

### User Management

#### Get All Users
```http
GET /api/admin/users
```
Query Parameters:
- `search` (optional): Search by name, email, or phone
- `role` (optional): Filter by role (customer, service_provider, platform_admin)
- `is_active` (optional): Filter by active status (true/false)
- `per_page` (optional): Number of items per page (default: 15)

Response:
```json
{
    "data": [
        {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "phone": "+1234567890",
            "is_active": true,
            "roles": ["customer"],
            "created_at": "2024-03-20T10:00:00Z"
        }
    ],
    "meta": {
        "current_page": 1,
        "total": 100,
        "per_page": 15
    }
}
```

#### Get User by ID
```http
GET /api/admin/users/{id}
```
Response:
```json
{
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "+1234567890",
    "is_active": true,
    "roles": ["customer"],
    "created_at": "2024-03-20T10:00:00Z"
}
```

#### Create User
```http
POST /api/admin/users
```
Request Body:
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "+1234567890",
    "password": "password123",
    "role": "customer"
}
```

#### Update User
```http
PUT /api/admin/users/{id}
```
Request Body:
```json
{
    "name": "John Doe Updated",
    "email": "john.updated@example.com",
    "phone": "+1234567890",
    "is_active": true
}
```

#### Delete User
```http
DELETE /api/admin/users/{id}
```

#### Toggle User Status
```http
PATCH /api/admin/users/{id}/toggle-status
```

### Real Estate Office Management

#### Get All Real Estate Offices
```http
GET /api/real-estate-offices
```
Query Parameters:
- `search` (optional): Search by name or license number
- `is_active` (optional): Filter by active status
- `per_page` (optional): Number of items per page

Response:
```json
{
    "data": [
        {
            "id": 1,
            "name": "ABC Real Estate",
            "license_number": "RE123456",
            "address": "123 Main St",
            "phone": "+1234567890",
            "email": "contact@abcrealestate.com",
            "is_active": true,
            "user": {
                "id": 1,
                "name": "John Doe",
                "email": "john@abcrealestate.com"
            }
        }
    ],
    "meta": {
        "current_page": 1,
        "total": 50,
        "per_page": 15
    }
}
```

#### Create Real Estate Office
```http
POST /api/real-estate-offices
```
Request Body:
```json
{
    "name": "ABC Real Estate",
    "license_number": "RE123456",
    "address": "123 Main St",
    "phone": "+1234567890",
    "email": "contact@abcrealestate.com",
    "user": {
        "name": "John Doe",
        "email": "john@abcrealestate.com",
        "phone": "+1234567890",
        "password": "password123"
    }
}
```

#### Update Real Estate Office
```http
PUT /api/real-estate-offices/{id}
```
Request Body:
```json
{
    "name": "ABC Real Estate Updated",
    "license_number": "RE123456",
    "address": "456 New St",
    "phone": "+1234567890",
    "email": "new@abcrealestate.com"
}
```

#### Delete Real Estate Office
```http
DELETE /api/real-estate-offices/{id}
```

#### Toggle Real Estate Office Status
```http
PATCH /api/real-estate-offices/{id}/toggle-status
```

### Finishing Company Management

#### Get All Finishing Companies
```http
GET /api/finishing-companies
```
Query Parameters:
- `search` (optional): Search by name
- `is_active` (optional): Filter by active status
- `per_page` (optional): Number of items per page

Response:
```json
{
    "data": [
        {
            "id": 1,
            "name": "XYZ Finishing",
            "description": "Professional finishing services",
            "address": "789 Work St",
            "phone": "+1234567890",
            "email": "contact@xyzfinishing.com",
            "is_active": true,
            "services": ["Interior Design", "Renovation"],
            "work_areas": ["Residential", "Commercial"],
            "user": {
                "id": 1,
                "name": "Jane Doe",
                "email": "jane@xyzfinishing.com"
            }
        }
    ],
    "meta": {
        "current_page": 1,
        "total": 30,
        "per_page": 15
    }
}
```

#### Create Finishing Company
```http
POST /api/finishing-companies
```
Request Body:
```json
{
    "name": "XYZ Finishing",
    "description": "Professional finishing services",
    "address": "789 Work St",
    "phone": "+1234567890",
    "email": "contact@xyzfinishing.com",
    "services": ["Interior Design", "Renovation"],
    "work_areas": ["Residential", "Commercial"],
    "user": {
        "name": "Jane Doe",
        "email": "jane@xyzfinishing.com",
        "phone": "+1234567890",
        "password": "password123"
    }
}
```

#### Update Finishing Company
```http
PUT /api/finishing-companies/{id}
```
Request Body:
```json
{
    "name": "XYZ Finishing Updated",
    "description": "Updated description",
    "address": "New Address",
    "phone": "+1234567890",
    "email": "new@xyzfinishing.com",
    "services": ["Interior Design", "Renovation", "New Service"],
    "work_areas": ["Residential", "Commercial", "Industrial"]
}
```

#### Delete Finishing Company
```http
DELETE /api/finishing-companies/{id}
```

#### Toggle Finishing Company Status
```http
PATCH /api/finishing-companies/{id}/toggle-status
```

#### Get Company Services
```http
GET /api/finishing-companies/{id}/services
```

#### Get Company Work Areas
```http
GET /api/finishing-companies/{id}/work-areas
```

#### Get Company Portfolio
```http
GET /api/finishing-companies/{id}/portfolio
```

## Error Responses

All endpoints may return the following error responses:

### 401 Unauthorized
```json
{
    "message": "Unauthenticated."
}
```

### 403 Forbidden
```json
{
    "message": "This action is unauthorized."
}
```

### 404 Not Found
```json
{
    "message": "Resource not found."
}
```

### 422 Validation Error
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "field_name": [
            "Error message"
        ]
    }
}
```

### 500 Server Error
```json
{
    "message": "Server Error"
}
```

## Rate Limiting
API requests are limited to 60 requests per minute per IP address.

## Best Practices
1. Always include the Authorization header with a valid token
2. Use appropriate HTTP methods for each operation
3. Handle rate limiting by implementing exponential backoff
4. Validate all input data before sending requests
5. Implement proper error handling in your client application






# Finishing Company API Documentation

## Overview
This document describes the API endpoints available for finishing companies to manage their profiles, handle finishing requests, and view analytics.

## Authentication
All endpoints require authentication using Laravel Sanctum. Include the Bearer token in the Authorization header:
```
Authorization: Bearer {token}
```

## Base URL
```
/api/finishing-company
```

---

## Profile Management

### Get Company Profile
Retrieve the current finishing company's profile information.

**Endpoint:** `GET /api/finishing-company/profile`

**Response:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "commercial_name": "ABC Finishing Co.",
        "contact_info": "+1234567890",
        "profile_description": "Professional finishing services",
        "is_active": true,
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@abcfinishing.com",
            "phone": "+1234567890"
        },
        "services": [
            {
                "id": 1,
                "service_type": "Interior Finishing",
                "description": "Complete interior finishing services",
                "created_at": "2024-01-01T00:00:00.000000Z"
            }
        ],
        "work_areas": [
            {
                "id": 1,
                "location": {
                    "id": 1,
                    "city": "Riyadh",
                    "neighborhood": "Al Olaya",
                    "region": "Riyadh Region",
                    "address_details": "Main Street"
                },
                "created_at": "2024-01-01T00:00:00.000000Z"
            }
        ],
        "portfolio": [
            {
                "id": 1,
                "image_url": "https://example.com/image1.jpg",
                "created_at": "2024-01-01T00:00:00.000000Z"
            }
        ],
        "average_rating": 4.5,
        "reviews_count": 10,
        "created_at": "2024-01-01T00:00:00.000000Z",
        "updated_at": "2024-01-01T00:00:00.000000Z"
    },
    "message": "Company profile retrieved successfully"
}
```

### Update Company Profile
Update the finishing company's profile information.

**Endpoint:** `PUT /api/finishing-company/profile`

**Request Body:**
```json
{
    "commercial_name": "ABC Finishing Co.",
    "contact_info": "+1234567890",
    "profile_description": "Professional finishing services",
    "services": [
        {
            "service_type": "Interior Finishing",
            "description": "Complete interior finishing services"
        },
        {
            "service_type": "Exterior Finishing",
            "description": "Exterior wall finishing and painting"
        }
    ],
    "work_areas": [1, 2, 3]
}
```

**Response:**
```json
{
    "success": true,
    "data": {
        // Same structure as get profile response
    },
    "message": "Company profile updated successfully"
}
```

### Toggle Company Status
Activate or deactivate the company account.

**Endpoint:** `PATCH /api/finishing-company/toggle-status`

**Response:**
```json
{
    "success": true,
    "data": {
        "is_active": true,
        "status_message": "Company activated"
    },
    "message": "Company status updated successfully"
}
```

---

## Reviews and Performance

### Get Company Reviews
Retrieve customer reviews for the company.

**Endpoint:** `GET /api/finishing-company/reviews`

**Query Parameters:**
- `rating` (optional): Filter by rating (1-5)
- `search` (optional): Search in review comments
- `per_page` (optional): Number of items per page (default: 15)

**Response:**
```json
{
    "success": true,
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "rating": 5,
                "comment": "Excellent work quality",
                "customer": {
                    "id": 1,
                    "name": "Customer Name",
                    "email": "customer@example.com"
                },
                "created_at": "2024-01-01T00:00:00.000000Z"
            }
        ],
        "total": 10,
        "per_page": 15
    },
    "message": "Company reviews retrieved successfully"
}
```

### Get Company Analytics
Retrieve analytics data for the company.

**Endpoint:** `GET /api/finishing-company/analytics`

**Response:**
```json
{
    "success": true,
    "data": {
        "total_requests": 50,
        "pending_requests": 5,
        "accepted_requests": 30,
        "rejected_requests": 10,
        "completed_requests": 15,
        "total_revenue": 150000.00,
        "average_response_time": 2.5
    },
    "message": "Company analytics retrieved successfully"
}
```

### Get Company Performance
Retrieve performance metrics for the company.

**Endpoint:** `GET /api/finishing-company/performance`

**Response:**
```json
{
    "success": true,
    "data": {
        "average_rating": 4.5,
        "total_reviews": 25,
        "rating_distribution": {
            "5_star": 15,
            "4_star": 7,
            "3_star": 2,
            "2_star": 1,
            "1_star": 0
        },
        "acceptance_rate": 75.5,
        "completion_rate": 85.2
    },
    "message": "Company performance retrieved successfully"
}
```

---

## Finishing Requests Management

### Get Finishing Requests
Retrieve all finishing requests assigned to the company.

**Endpoint:** `GET /api/finishing-company/requests`

**Query Parameters:**
- `status_id` (optional): Filter by status ID
- `service_type` (optional): Filter by service type
- `search` (optional): Search in description or service type
- `per_page` (optional): Number of items per page (default: 15)

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "service_type": "Interior Finishing",
            "description": "Need interior finishing for 3-bedroom apartment",
            "area": 120,
            "rooms": 3,
            "floor": 5,
            "status": {
                "id": 1,
                "name": "Pending"
            },
            "customer": {
                "id": 1,
                "name": "Customer Name",
                "email": "customer@example.com",
                "phone": "+1234567890"
            },
            "location": {
                "id": 1,
                "city": "Riyadh",
                "neighborhood": "Al Olaya",
                "region": "Riyadh Region",
                "address_details": "Main Street"
            },
            "company_response": null,
            "images": [
                {
                    "id": 1,
                    "image_url": "https://example.com/image1.jpg",
                    "created_at": "2024-01-01T00:00:00.000000Z"
                }
            ],
            "created_at": "2024-01-01T00:00:00.000000Z",
            "updated_at": "2024-01-01T00:00:00.000000Z"
        }
    ],
    "pagination": {
        "current_page": 1,
        "last_page": 3,
        "per_page": 15,
        "total": 45
    },
    "message": "Finishing requests retrieved successfully"
}
```

### Get Request Details
Retrieve detailed information about a specific finishing request.

**Endpoint:** `GET /api/finishing-company/requests/{requestId}`

**Response:**
```json
{
    "success": true,
    "data": {
        // Same structure as request item in get requests response
    },
    "message": "Request details retrieved successfully"
}
```

### Respond to Request
Submit a response to a finishing request (accept or reject).

**Endpoint:** `POST /api/finishing-company/requests/{requestId}/respond`

**Request Body (Accept):**
```json
{
    "status": "accepted",
    "estimated_cost": 15000.00,
    "implementation_period": "2-3 weeks",
    "materials": "High-quality finishing materials including paint, tiles, and fixtures",
    "work_phases": "Phase 1: Preparation and cleaning\nPhase 2: Painting and finishing\nPhase 3: Final inspection and handover",
    "notes": "Additional notes about the project"
}
```

**Request Body (Reject):**
```json
{
    "status": "rejected",
    "reason": "Currently at full capacity and cannot take on new projects"
}
```

**Response:**
```json
{
    "success": true,
    "data": {
        // Updated request with company response
    },
    "message": "Response submitted successfully"
}
```

---

## Request History and Analysis

### Get Request History
Retrieve historical data of completed, rejected, or cancelled requests.

**Endpoint:** `GET /api/finishing-company/history`

**Query Parameters:**
- `status_id` (optional): Filter by status ID
- `date_from` (optional): Filter from date (YYYY-MM-DD)
- `date_to` (optional): Filter to date (YYYY-MM-DD)
- `per_page` (optional): Number of items per page (default: 15)

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "service_type": "Interior Finishing",
            "description": "Completed interior finishing project",
            "status": {
                "id": 5,
                "name": "Completed"
            },
            "customer": {
                "id": 1,
                "name": "Customer Name",
                "email": "customer@example.com"
            },
            "location": {
                "id": 1,
                "city": "Riyadh",
                "neighborhood": "Al Olaya",
                "region": "Riyadh Region"
            },
            "company_response": {
                "status": "accepted",
                "estimated_cost": 15000.00,
                "implementation_period": "2-3 weeks",
                "materials": "High-quality materials",
                "work_phases": "3 phases",
                "notes": "Project completed successfully",
                "responded_at": "2024-01-01T00:00:00.000000Z"
            },
            "created_at": "2024-01-01T00:00:00.000000Z"
        }
    ],
    "pagination": {
        "current_page": 1,
        "last_page": 2,
        "per_page": 15,
        "total": 30
    },
    "message": "Request history retrieved successfully"
}
```

---

## Error Responses

### Validation Error
```json
{
    "success": false,
    "message": "The given data was invalid.",
    "errors": {
        "commercial_name": ["The commercial name field is required."],
        "estimated_cost": ["The estimated cost must be a number."]
    }
}
```

### Authentication Error
```json
{
    "success": false,
    "message": "Unauthenticated"
}
```

### Authorization Error
```json
{
    "success": false,
    "message": "Access denied. Only finishing companies can access this resource."
}
```

### Not Found Error
```json
{
    "success": false,
    "message": "Finishing request not found",
    "error": "No query results for model [App\\Models\\FinishingRequest] 123"
}
```

### Server Error
```json
{
    "success": false,
    "message": "Failed to retrieve company profile",
    "error": "Database connection failed"
}
```

---

## Status Codes

- `200` - Success
- `201` - Created
- `400` - Bad Request (Validation errors)
- `401` - Unauthorized (Authentication required)
- `403` - Forbidden (Insufficient permissions)
- `404` - Not Found
- `422` - Unprocessable Entity (Validation errors)
- `500` - Internal Server Error

---

## Notes

1. **Self-registration**: Finishing companies cannot self-register. Accounts are created by platform administrators after signing external contracts.

2. **Account Status**: Companies can temporarily activate/deactivate their accounts when they stop accepting requests.

3. **Request Response**: Companies must respond to requests within a reasonable timeframe to maintain good performance metrics.

4. **Data Privacy**: All customer information is protected and should be handled according to privacy regulations.

5. **File Uploads**: Portfolio images and request attachments should be uploaded through separate endpoints (not covered in this documentation). 