# Real Estate Platform API Documentation

## Overview
This API provides endpoints for managing real estate properties, including property listings, images, attachments, and related operations. The API follows RESTful principles and uses Laravel Sanctum for authentication.

## Base URL
```
https://api.example.com/api/v1
```

## Authentication
All API requests require authentication using Laravel Sanctum. Include the bearer token in the Authorization header:

```
Authorization: Bearer {your_token}
```

## API Endpoints

### Real Estate Office Profile

#### Get Office Profile
```http
GET /office/profile
```

Response:
```json
{
    "status": "success",
    "data": {
        "office": {
            "id": 1,
            "commerical_name": "Office Name",
            "address": "Office Address",
            "license_number": "LIC123456",
            "phone_number": "+1234567890",
            "profile_description": "Office Description",
            "is_active": true,
            "documents": [
                {
                    "id": 1,
                    "document_type": "license",
                    "file_path": "documents/license.pdf",
                    "original_filename": "license.pdf",
                    "mime_type": "application/pdf",
                    "description": "Business License",
                    "created_at": "2024-03-20 10:00:00",
                    "updated_at": "2024-03-20 10:00:00"
                }
            ],
            "reviews": [
                {
                    "id": 1,
                    "rating": 5,
                    "comment": "Great service!",
                    "reviewer": {
                        "id": 1,
                        "username": "user1"
                    },
                    "is_verified": true,
                    "created_at": "2024-03-20 10:00:00"
                }
            ],
            "created_at": "2024-03-20 10:00:00",
            "updated_at": "2024-03-20 10:00:00"
        }
    }
}
```

#### Update Office Profile
```http
PUT /office/profile
```

Request Body:
```json
{
    "commerical_name": "Updated Office Name",
    "address": "Updated Address",
    "license_number": "LIC789012",
    "phone_number": "+1987654321",
    "profile_description": "Updated Description"
}
```

Response:
```json
{
    "status": "success",
    "data": {
        "office": {
            "id": 1,
            // ... same as get profile response
        }
    }
}
```

#### Upload Office Documents
```http
POST /office/documents
```

Request Body (multipart/form-data):
```json
{
    "documents": [
        {
            "file": "document_file",
            "document_type": "license",
            "description": "Business License Document"
        }
    ]
}
```

Response:
```json
{
    "status": "success",
    "data": {
        "documents": [
            {
                "id": 1,
                "document_type": "license",
                "file_path": "documents/license.pdf",
                "original_filename": "license.pdf",
                "mime_type": "application/pdf",
                "description": "Business License Document",
                "created_at": "2024-03-20 10:00:00",
                "updated_at": "2024-03-20 10:00:00"
            }
        ]
    }
}
```

#### Delete Office Document
```http
DELETE /office/documents/{document_id}
```

Response:
```json
{
    "status": "success",
    "message": "Document deleted successfully"
}
```

#### Get Office Reviews
```http
GET /office/reviews
```

Query Parameters:
- `page` (optional): Page number for pagination (default: 1)
- `per_page` (optional): Number of items per page (default: 10)
- `rating` (optional): Filter by rating (1-5)

Response:
```json
{
    "status": "success",
    "data": {
        "reviews": [
            {
                "id": 1,
                "rating": 5,
                "comment": "Great service!",
                "reviewer": {
                    "id": 1,
                    "username": "user1"
                },
                "is_verified": true,
                "created_at": "2024-03-20 10:00:00"
            }
        ],
        "pagination": {
            "total": 50,
            "per_page": 10,
            "current_page": 1,
            "last_page": 5
        }
    }
}
```

#### Update Office Status
```http
PATCH /office/status
```

Request Body:
```json
{
    "is_active": true
}
```

Response:
```json
{
    "status": "success",
    "data": {
        "office": {
            "id": 1,
            "is_active": true,
            "updated_at": "2024-03-20 10:00:00"
        }
    }
}
```

### Properties

#### List Properties
```http
GET /properties
```

Query Parameters:
- `page` (optional): Page number for pagination (default: 1)
- `per_page` (optional): Number of items per page (default: 10)
- `type` (optional): Filter by property type (apartment, villa, land, office, commercial)
- `offer_type` (optional): Filter by offer type (sale, rent)
- `min_price` (optional): Minimum price filter
- `max_price` (optional): Maximum price filter
- `location_id` (optional): Filter by location ID

Response:
```json
{
    "status": "success",
    "data": {
        "properties": [
            {
                "id": 1,
                "title": "Property Title",
                "description": "Property Description",
                "type": "apartment",
                "price": 100000,
                "area": 150,
                "rooms": 3,
                "legal_status": "registered",
                "offer_type": "sale",
                "status": {
                    "id": 1,
                    "name": "Available"
                },
                "contact_visible": true,
                "office": {
                    "id": 1,
                    "name": "Office Name"
                },
                "location": {
                    "id": 1,
                    "city": "City Name",
                    "neighborhood": "Neighborhood",
                    "region": "Region",
                    "address_details": "Address Details"
                },
                "images": [...],
                "attachments": [...],
                "created_at": "2024-03-20 10:00:00",
                "updated_at": "2024-03-20 10:00:00"
            }
        ],
        "pagination": {
            "total": 100,
            "per_page": 10,
            "current_page": 1,
            "last_page": 10
        }
    }
}
```

#### Get Property Details
```http
GET /properties/{id}
```

Response:
```json
{
    "status": "success",
    "data": {
        "property": {
            "id": 1,
            "title": "Property Title",
            // ... same as list properties response
        }
    }
}
```

#### Create Property
```http
POST /properties
```

Request Body:
```json
{
    "title": "Property Title",
    "description": "Property Description",
    "type": "apartment",
    "price": 100000,
    "area": 150,
    "rooms": 3,
    "legal_status": "registered",
    "offer_type": "sale",
    "status_id": 1,
    "contact_visible": true,
    "office_id": 1,
    "location_id": 1,
    "images": [
        {
            "file": "image_file",
            "is_primary": true
        }
    ],
    "attachments": [
        {
            "file": "document_file"
        }
    ]
}
```

Response:
```json
{
    "status": "success",
    "data": {
        "property": {
            "id": 1,
            // ... same as list properties response
        }
    }
}
```

#### Update Property
```http
PUT /properties/{id}
```

Request Body: Same as Create Property (all fields optional)

Response:
```json
{
    "status": "success",
    "data": {
        "property": {
            "id": 1,
            // ... same as list properties response
        }
    }
}
```

#### Delete Property
```http
DELETE /properties/{id}
```

Response:
```json
{
    "status": "success",
    "message": "Property deleted successfully"
}
```

#### Update Property Status
```http
PATCH /properties/{id}/status
```

Request Body:
```json
{
    "status_id": 2
}
```

Response:
```json
{
    "status": "success",
    "data": {
        "property": {
            "id": 1,
            // ... same as list properties response
        }
    }
}
```

### Property Images

#### Upload Images
```http
POST /properties/{id}/images
```

Request Body (multipart/form-data):
```json
{
    "images": [
        {
            "file": "image_file",
            "is_primary": true
        }
    ]
}
```

Response:
```json
{
    "status": "success",
    "data": {
        "images": [
            {
                "id": 1,
                "url": "http://example.com/storage/properties/images/image.jpg",
                "created_at": "2024-03-20 10:00:00",
                "updated_at": "2024-03-20 10:00:00"
            }
        ]
    }
}
```

#### Delete Image
```http
DELETE /properties/{id}/images/{image_id}
```

Response:
```json
{
    "status": "success",
    "message": "Image deleted successfully"
}
```

### Property Attachments

#### Upload Attachments
```http
POST /properties/{id}/attachments
```

Request Body (multipart/form-data):
```json
{
    "attachments": [
        {
            "file": "document_file",
            "description": "Property Documents"
        }
    ]
}
```

Response:
```json
{
    "status": "success",
    "data": {
        "attachments": [
            {
                "id": 1,
                "name": "document.pdf",
                "url": "http://example.com/storage/properties/attachments/document.pdf",
                "type": "application/pdf",
                "description": "Property Documents",
                "created_at": "2024-03-20 10:00:00",
                "updated_at": "2024-03-20 10:00:00"
            }
        ]
    }
}
```

#### Delete Attachment
```http
DELETE /properties/{id}/attachments/{attachment_id}
```

Response:
```json
{
    "status": "success",
    "message": "Attachment deleted successfully"
}
```

## Error Responses

### Validation Error
```json
{
    "status": "error",
    "message": "The given data was invalid.",
    "errors": {
        "field_name": [
            "Error message"
        ]
    }
}
```

### Not Found Error
```json
{
    "status": "error",
    "message": "Resource not found"
}
```

### Authentication Error
```json
{
    "status": "error",
    "message": "Unauthenticated"
}
```

### Server Error
```json
{
    "status": "error",
    "message": "Internal server error"
}
```

## Data Types

### Property Types
- apartment
- villa
- land
- office
- commercial

### Legal Status
- registered
- pending
- customary

### Offer Types
- sale
- rent

### Document Types
- license
- certificate
- contract
- other

## File Upload Requirements

### Images
- Allowed formats: JPEG, PNG, JPG, GIF
- Maximum size: 2MB per file
- Maximum dimensions: 1920x1080 pixels

### Attachments
- Allowed formats: PDF, DOC, DOCX
- Maximum size: 5MB per file

### Office Documents
- Allowed formats: PDF, DOC, DOCX, JPG, PNG
- Maximum size: 10MB per file

## Rate Limiting
- 60 requests per minute per IP address
- 1000 requests per hour per authenticated user

## Versioning
The API version is included in the URL path. The current version is v1.

## Support
For API support, please contact:
- Email: support@example.com
- Documentation: https://docs.example.com

# Laravel Backend API

## Finishing Company Management API

### Overview
This document describes the API endpoints available for finishing companies to manage their profiles, handle finishing requests, and view analytics.

### Authentication
All endpoints require authentication using Laravel Sanctum. Include the Bearer token in the Authorization header:
```
Authorization: Bearer {token}
```

### Base URL
```
/api/finishing-company
```

---

## Profile Management

### Get Company Profile
**Endpoint:** `GET /api/finishing-company/profile`

Retrieve the current finishing company's profile information including services, work areas, and portfolio.

### Update Company Profile
**Endpoint:** `PUT /api/finishing-company/profile`

Update the finishing company's profile information including commercial name, contact info, services, and work areas.

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
        }
    ],
    "work_areas": [1, 2, 3]
}
```

### Toggle Company Status
**Endpoint:** `PATCH /api/finishing-company/toggle-status`

Activate or deactivate the company account temporarily.

---

## Reviews and Performance

### Get Company Reviews
**Endpoint:** `GET /api/finishing-company/reviews`

Retrieve customer reviews with optional filtering by rating and search.

### Get Company Analytics
**Endpoint:** `GET /api/finishing-company/analytics`

Retrieve analytics data including total requests, acceptance rates, and revenue.

### Get Company Performance
**Endpoint:** `GET /api/finishing-company/performance`

Retrieve performance metrics including average rating and completion rates.

---

## Finishing Requests Management

### Get Finishing Requests
**Endpoint:** `GET /api/finishing-company/requests`

Retrieve all finishing requests assigned to the company with filtering options.

### Get Request Details
**Endpoint:** `GET /api/finishing-company/requests/{requestId}`

Retrieve detailed information about a specific finishing request.

### Respond to Request
**Endpoint:** `POST /api/finishing-company/requests/{requestId}/respond`

Submit a response to a finishing request (accept or reject).

**Request Body (Accept):**
```json
{
    "status": "accepted",
    "estimated_cost": 15000.00,
    "implementation_period": "2-3 weeks",
    "materials": "High-quality finishing materials",
    "work_phases": "Phase 1: Preparation\nPhase 2: Finishing\nPhase 3: Handover"
}
```

**Request Body (Reject):**
```json
{
    "status": "rejected",
    "reason": "Currently at full capacity"
}
```

---

## Request History and Analysis

### Get Request History
**Endpoint:** `GET /api/finishing-company/history`

Retrieve historical data of completed, rejected, or cancelled requests.

---

## Functional Requirements Implementation

### ✅ Access and Account Management
1. **Self-registration**: Not permitted - accounts created by platform administrators
2. **Profile editing**: Full profile management including services, work areas, and portfolio
3. **Account activation/deactivation**: Toggle status functionality
4. **Customer reviews**: View and analyze customer feedback

### ✅ Managing Cladding Requests
5. **View requests**: List all assigned finishing requests
6. **Request details**: Detailed view with property info, location, and photos
7. **Respond to requests**: Accept/reject with price quotes and implementation details

### ✅ Archiving and Analysis
9. **Request history**: View completed and historical requests
10. **Performance analytics**: Average ratings and company performance metrics

---

## Error Handling

All endpoints return consistent error responses:

```json
{
    "success": false,
    "message": "Error description",
    "error": "Detailed error information"
}
```

## Status Codes
- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `500` - Server Error

---

## Implementation Notes

- **Repository Pattern**: All data access is handled through repositories
- **Service Layer**: Business logic is separated in service classes
- **Form Requests**: Input validation using Laravel Form Requests
- **API Resources**: Consistent response formatting using API Resources
- **Middleware**: Role-based access control using Spatie Permissions
- **Clean Code**: Following SOLID principles and clean architecture

## Database Schema

### FinishingRequestResponse Table
```sql
CREATE TABLE finishing_request_responses (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    finishing_request_id BIGINT NOT NULL,
    status ENUM('accepted', 'rejected') NOT NULL,
    reason TEXT NULL,
    estimated_cost DECIMAL(10,2) NULL,
    implementation_period VARCHAR(255) NULL,
    materials TEXT NULL,
    work_phases TEXT NULL,
    notes TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (finishing_request_id) REFERENCES finishing_requests(id) ON DELETE CASCADE
);
```

This implementation provides a complete solution for finishing company management following Laravel best practices and clean code principles.
