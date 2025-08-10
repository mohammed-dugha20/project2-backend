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