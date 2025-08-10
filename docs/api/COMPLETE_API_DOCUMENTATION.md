# Complete API Documentation

## Table of Contents
1. [Authentication](#authentication)
2. [Public Endpoints](#public-endpoints)
3. [Customer Endpoints](#customer-endpoints)
4. [Real Estate Office Endpoints](#real-estate-office-endpoints)
5. [Finishing Company Endpoints](#finishing-company-endpoints)
6. [Admin Endpoints](#admin-endpoints)
7. [Super Admin Endpoints](#super-admin-endpoints)
8. [Error Responses](#error-responses)

## Authentication

### User Authentication
All protected endpoints require authentication using Laravel Sanctum. Include the token in the Authorization header:
```
Authorization: Bearer {your_token}
```

### Admin Authentication
Admin endpoints require admin authentication:
```
Authorization: Bearer {admin_token}
```

## Public Endpoints

### User Registration
```http
POST /api/register
```

**Request Body:**
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "+1234567890",
    "password": "password123",
    "password_confirmation": "password123",
    "role": "customer"
}
```

**Response:**
```json
{
    "message": "User registered successfully",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "phone": "+1234567890",
        "created_at": "2024-03-20T10:00:00Z"
    }
}
```

### User Login
```http
POST /api/login
```

**Request Body:**
```json
{
    "email": "john@example.com",
    "password": "password123"
}
```

**Response:**
```json
{
    "message": "Login successful",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "roles": ["customer"]
    },
    "token": "1|abc123..."
}
```

### Admin Login
```http
POST /api/admin/auth/login
```

**Request Body:**
```json
{
    "email": "admin@example.com",
    "password": "password123"
}
```

**Response:**
```json
{
    "message": "Admin login successful",
    "admin": {
        "id": 1,
        "name": "Admin User",
        "email": "admin@example.com",
        "roles": ["platform_admin"]
    },
    "token": "1|abc123..."
}
```

## Customer Endpoints

### Authentication Required
All customer endpoints require authentication with customer role.

### Get Profile
```http
GET /api/customer/profile
```

**Response:**
```json
{
    "profile": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "phone": "+1234567890",
        "created_at": "2024-03-20T10:00:00Z"
    }
}
```

### Update Profile
```http
PUT /api/customer/profile
```

**Request Body:**
```json
{
    "name": "John Doe Updated",
    "email": "john.updated@example.com",
    "phone": "+1234567890"
}
```

**Response:**
```json
{
    "message": "Profile updated successfully",
    "profile": {
        "id": 1,
        "name": "John Doe Updated",
        "email": "john.updated@example.com",
        "phone": "+1234567890"
    }
}
```

### Get Customer Properties
```http
GET /api/customer/properties
```

**Query Parameters:**
- `search` (optional): Search by property title or description
- `type` (optional): Filter by property type
- `status` (optional): Filter by property status
- `per_page` (optional): Number of items per page (default: 15)

**Response:**
```json
{
    "data": [
        {
            "id": 1,
            "title": "Beautiful Apartment",
            "description": "Modern apartment in city center",
            "price": 250000,
            "type": "apartment",
            "status": "available",
            "location": {
                "city": "New York",
                "address": "123 Main St"
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

### Get Favorites
```http
GET /api/customer/favorites
```

**Response:**
```json
{
    "favorites": [
        {
            "id": 1,
            "property": {
                "id": 1,
                "title": "Beautiful Apartment",
                "price": 250000
            }
        }
    ]
}
```

### Add to Favorites
```http
POST /api/customer/favorites/{property}
```

**Response:**
```json
{
    "message": "Property added to favorites successfully"
}
```

### Remove from Favorites
```http
DELETE /api/customer/favorites/{property}
```

**Response:**
```json
{
    "message": "Property removed from favorites successfully"
}
```

### Property Requests

#### Get Property Requests
```http
GET /api/property-requests
```

**Response:**
```json
{
    "requests": [
        {
            "id": 1,
            "property_id": 1,
            "message": "I'm interested in this property",
            "status": "pending",
            "created_at": "2024-03-20T10:00:00Z"
        }
    ]
}
```

#### Create Property Request
```http
POST /api/property-requests
```

**Request Body:**
```json
{
    "property_id": 1,
    "message": "I'm interested in this property",
    "preferred_contact": "email"
}
```

**Response:**
```json
{
    "message": "Property request created successfully",
    "request": {
        "id": 1,
        "property_id": 1,
        "message": "I'm interested in this property",
        "status": "pending"
    }
}
```

#### Get Property Request Details
```http
GET /api/property-requests/{request}
```

#### Update Property Request
```http
PUT /api/property-requests/{request}
```

**Request Body:**
```json
{
    "message": "Updated message",
    "preferred_contact": "phone"
}
```

#### Delete Property Request
```http
DELETE /api/property-requests/{request}
```

### Finishing Requests

#### Get Finishing Requests
```http
GET /api/finishing-requests
```

#### Create Finishing Request
```http
POST /api/finishing-requests
```

**Request Body:**
```json
{
    "service_type": "interior_design",
    "description": "Need interior design for my apartment",
    "budget": 5000,
    "preferred_location": "New York",
    "timeline": "3 months"
}
```

#### Get Finishing Request Details
```http
GET /api/finishing-requests/{request}
```

#### Update Finishing Request
```http
PUT /api/finishing-requests/{request}
```

#### Delete Finishing Request
```http
DELETE /api/finishing-requests/{request}
```

## Real Estate Office Endpoints

### Authentication Required
All real estate office endpoints require authentication with real_estate_office role.

### Get Profile
```http
GET /api/real-estate-office/profile
```

**Response:**
```json
{
    "profile": {
        "id": 1,
        "commercial_name": "ABC Real Estate",
        "license_number": "RE123456",
        "address": "123 Main St",
        "phone_number": "+1234567890",
        "profile_description": "Professional real estate services",
        "is_active": true,
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@abcrealestate.com"
        }
    }
}
```

### Update Profile
```http
PUT /api/real-estate-office/profile
```

**Request Body:**
```json
{
    "commercial_name": "ABC Real Estate Updated",
    "address": "456 New St",
    "phone_number": "+1234567890",
    "license_number": "RE123456",
    "profile_description": "Updated description"
}
```

### Upload Document
```http
POST /api/real-estate-office/documents
```

**Request Body (multipart/form-data):**
```
document: [file]
type: "license"
description: "Business license document"
```

### Remove Document
```http
DELETE /api/real-estate-office/documents/{documentId}
```

### Get Properties
```http
GET /api/real-estate-office/properties
```

**Query Parameters:**
- `search` (optional): Search by property title
- `status` (optional): Filter by property status
- `type` (optional): Filter by property type
- `per_page` (optional): Number of items per page

### Get Analytics
```http
GET /api/real-estate-office/analytics
```

**Response:**
```json
{
    "analytics": {
        "total_properties": 25,
        "active_properties": 20,
        "total_views": 1500,
        "total_requests": 45,
        "monthly_revenue": 50000
    }
}
```

### Get Reviews
```http
GET /api/real-estate-office/reviews
```

**Response:**
```json
{
    "reviews": [
        {
            "id": 1,
            "rating": 5,
            "comment": "Excellent service",
            "customer_name": "Jane Doe",
            "created_at": "2024-03-20T10:00:00Z"
        }
    ]
}
```

## Finishing Company Endpoints

### Authentication Required
All finishing company endpoints require authentication with finishing_company role.

### Get Profile
```http
GET /api/finishing-company/profile
```

**Response:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "ABC Finishing Co",
        "description": "Professional finishing services",
        "phone": "+1234567890",
        "email": "contact@abcfinishing.com",
        "address": "123 Business St",
        "is_active": true,
        "services": [
            {
                "id": 1,
                "name": "Interior Design",
                "description": "Complete interior design services"
            }
        ],
        "work_areas": [
            {
                "id": 1,
                "city": "New York",
                "area": "Manhattan"
            }
        ]
    },
    "message": "Company profile retrieved successfully"
}
```

### Update Profile
```http
PUT /api/finishing-company/profile
```

**Request Body:**
```json
{
    "name": "ABC Finishing Co Updated",
    "description": "Updated description",
    "phone": "+1234567890",
    "email": "new@abcfinishing.com",
    "address": "456 New Business St"
}
```

### Toggle Status
```http
PATCH /api/finishing-company/toggle-status
```

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

### Get Reviews
```http
GET /api/finishing-company/reviews
```

**Query Parameters:**
- `rating` (optional): Filter by rating (1-5)
- `search` (optional): Search in comments
- `per_page` (optional): Number of items per page

### Get Analytics
```http
GET /api/finishing-company/analytics
```

**Response:**
```json
{
    "success": true,
    "data": {
        "total_requests": 150,
        "completed_requests": 120,
        "average_rating": 4.5,
        "total_revenue": 75000,
        "monthly_growth": 15
    },
    "message": "Analytics retrieved successfully"
}
```

### Get Performance
```http
GET /api/finishing-company/performance
```

**Response:**
```json
{
    "success": true,
    "data": {
        "completion_rate": 80,
        "average_response_time": "2.5 hours",
        "customer_satisfaction": 4.5,
        "repeat_customers": 65
    },
    "message": "Performance data retrieved successfully"
}
```

### Finishing Requests Management

#### Get Requests
```http
GET /api/finishing-company/requests
```

**Query Parameters:**
- `status_id` (optional): Filter by status
- `service_type` (optional): Filter by service type
- `search` (optional): Search in description
- `per_page` (optional): Number of items per page

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "service_type": "interior_design",
            "description": "Need interior design for apartment",
            "budget": 5000,
            "status": "pending",
            "customer": {
                "id": 1,
                "name": "John Doe"
            },
            "created_at": "2024-03-20T10:00:00Z"
        }
    ],
    "pagination": {
        "current_page": 1,
        "last_page": 5,
        "per_page": 15,
        "total": 75
    },
    "message": "Finishing requests retrieved successfully"
}
```

#### Get Request Details
```http
GET /api/finishing-company/requests/{requestId}
```

#### Respond to Request
```http
POST /api/finishing-company/requests/{requestId}/respond
```

**Request Body:**
```json
{
    "response": "I can help you with this project",
    "estimated_cost": 4500,
    "estimated_duration": "2 months",
    "proposal_details": "Detailed proposal description"
}
```

### Get Request History
```http
GET /api/finishing-company/history
```

**Query Parameters:**
- `date_from` (optional): Filter from date
- `date_to` (optional): Filter to date
- `status` (optional): Filter by status
- `per_page` (optional): Number of items per page

## Properties Endpoints

### Get All Properties (Public)
```http
GET /api/properties
```

**Query Parameters:**
- `search` (optional): Search by title or description
- `type` (optional): Filter by property type
- `status` (optional): Filter by property status
- `min_price` (optional): Minimum price
- `max_price` (optional): Maximum price
- `location` (optional): Filter by location
- `per_page` (optional): Number of items per page

**Response:**
```json
{
    "data": [
        {
            "id": 1,
            "title": "Beautiful Apartment",
            "description": "Modern apartment in city center",
            "price": 250000,
            "type": "apartment",
            "status": "available",
            "bedrooms": 2,
            "bathrooms": 2,
            "area": 120,
            "location": {
                "city": "New York",
                "address": "123 Main St"
            },
            "real_estate_office": {
                "id": 1,
                "name": "ABC Real Estate"
            },
            "images": [
                {
                    "id": 1,
                    "url": "https://example.com/image1.jpg",
                    "is_primary": true
                }
            ]
        }
    ],
    "meta": {
        "current_page": 1,
        "total": 100,
        "per_page": 15
    }
}
```

### Get Property Details
```http
GET /api/properties/{property}
```

### Get Property Images
```http
GET /api/properties/{property}/images
```

### Get Property Attachments
```http
GET /api/properties/{property}/attachments
```

### Property Management (Real Estate Offices Only)

#### Create Property
```http
POST /api/properties
```

**Request Body:**
```json
{
    "title": "New Property",
    "description": "Property description",
    "price": 300000,
    "type": "apartment",
    "bedrooms": 3,
    "bathrooms": 2,
    "area": 150,
    "location_id": 1,
    "features": ["parking", "garden", "balcony"]
}
```

#### Update Property
```http
PUT /api/properties/{property}
```

#### Delete Property
```http
DELETE /api/properties/{property}
```

#### Update Property Status
```http
PATCH /api/properties/{property}/status
```

**Request Body:**
```json
{
    "status": "sold"
}
```

#### Upload Property Images
```http
POST /api/properties/{property}/images
```

**Request Body (multipart/form-data):**
```
images: [files]
is_primary: true
```

#### Upload Property Attachments
```http
POST /api/properties/{property}/attachments
```

**Request Body (multipart/form-data):**
```
attachments: [files]
```

#### Delete Property Image
```http
DELETE /api/properties/images/{image}
```

#### Delete Property Attachment
```http
DELETE /api/properties/attachments/{attachment}
```

## Admin Endpoints

### Authentication Required
All admin endpoints require authentication with admin role.

### User Management

#### Get All Users
```http
GET /api/admin/users
```

**Query Parameters:**
- `search` (optional): Search by name, email, or phone
- `role` (optional): Filter by role
- `is_active` (optional): Filter by active status
- `per_page` (optional): Number of items per page

**Response:**
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

#### Create User
```http
POST /api/admin/users
```

**Request Body:**
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

#### Delete User
```http
DELETE /api/admin/users/{id}
```

#### Toggle User Status
```http
PATCH /api/admin/users/{id}/toggle-status
```

#### Assign Role
```http
POST /api/admin/users/{userId}/roles
```

**Request Body:**
```json
{
    "role": "real_estate_office"
}
```

#### Remove Role
```http
DELETE /api/admin/users/{userId}/roles
```

**Request Body:**
```json
{
    "role": "real_estate_office"
}
```

#### Assign Permission
```http
POST /api/admin/users/{userId}/permissions
```

**Request Body:**
```json
{
    "permission": "manage_properties"
}
```

#### Remove Permission
```http
DELETE /api/admin/users/{userId}/permissions
```

**Request Body:**
```json
{
    "permission": "manage_properties"
}
```

### Real Estate Office Management

#### Get All Real Estate Offices
```http
GET /api/admin/real-estate-offices
```

**Query Parameters:**
- `search` (optional): Search by name or license number
- `is_active` (optional): Filter by active status
- `per_page` (optional): Number of items per page

#### Get Real Estate Office by ID
```http
GET /api/admin/real-estate-offices/{id}
```

#### Create Real Estate Office
```http
POST /api/admin/real-estate-offices
```

**Request Body:**
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
PUT /api/admin/real-estate-offices/{id}
```

#### Delete Real Estate Office
```http
DELETE /api/admin/real-estate-offices/{id}
```

#### Toggle Real Estate Office Status
```http
PATCH /api/admin/real-estate-offices/{id}/toggle-status
```

#### Get Real Estate Office Reviews
```http
GET /api/admin/real-estate-offices/{id}/reviews
```

#### Get Real Estate Office Documents
```http
GET /api/admin/real-estate-offices/{id}/documents
```

#### Get Real Estate Office Properties
```http
GET /api/admin/real-estate-offices/{id}/properties
```

#### Get Real Estate Office Performance
```http
GET /api/admin/real-estate-offices/{id}/performance
```

#### Get Real Estate Office Analytics
```http
GET /api/admin/real-estate-offices/{id}/analytics
```

### Finishing Company Management

#### Get All Finishing Companies
```http
GET /api/admin/finishing-companies
```

**Query Parameters:**
- `search` (optional): Search by name
- `is_active` (optional): Filter by active status
- `per_page` (optional): Number of items per page

#### Get Finishing Company by ID
```http
GET /api/admin/finishing-companies/{id}
```

#### Create Finishing Company
```http
POST /api/admin/finishing-companies
```

**Request Body:**
```json
{
    "name": "ABC Finishing Co",
    "description": "Professional finishing services",
    "phone": "+1234567890",
    "email": "contact@abcfinishing.com",
    "address": "123 Business St",
    "user": {
        "name": "John Doe",
        "email": "john@abcfinishing.com",
        "phone": "+1234567890",
        "password": "password123"
    }
}
```

#### Update Finishing Company
```http
PUT /api/admin/finishing-companies/{id}
```

#### Delete Finishing Company
```http
DELETE /api/admin/finishing-companies/{id}
```

#### Toggle Finishing Company Status
```http
PATCH /api/admin/finishing-companies/{id}/toggle-status
```

#### Get Finishing Company Services
```http
GET /api/admin/finishing-companies/{id}/services
```

#### Get Finishing Company Work Areas
```http
GET /api/admin/finishing-companies/{id}/work-areas
```

#### Get Finishing Company Portfolio
```http
GET /api/admin/finishing-companies/{id}/portfolio
```

#### Get Finishing Company Requests
```http
GET /api/admin/finishing-companies/{id}/requests
```

#### Get Finishing Company Reviews
```http
GET /api/admin/finishing-companies/{id}/reviews
```

#### Get Finishing Company Analytics
```http
GET /api/admin/finishing-companies/{id}/analytics
```

#### Get Finishing Company Performance
```http
GET /api/admin/finishing-companies/{id}/performance
```

### System Management

#### Get Roles
```http
GET /api/admin/system/roles
```

#### Get Permissions
```http
GET /api/admin/system/permissions
```

#### Get Analytics
```http
GET /api/admin/system/analytics
```

**Response:**
```json
{
    "analytics": {
        "total_users": 1000,
        "total_properties": 500,
        "total_real_estate_offices": 50,
        "total_finishing_companies": 30,
        "active_properties": 400,
        "pending_requests": 25
    }
}
```

#### Get Reports
```http
GET /api/admin/system/reports
```

#### Get Settings
```http
GET /api/admin/system/settings
```

#### Update Settings
```http
PUT /api/admin/system/settings
```

### Dispute Management

#### Get All Disputes
```http
GET /api/disputes
```

**Query Parameters:**
- `company_type` (optional): Filter by company type
- `company_id` (optional): Filter by company ID
- `status` (optional): Filter by status
- `date_from` (optional): Filter from date
- `date_to` (optional): Filter to date
- `search` (optional): Search in description
- `per_page` (optional): Number of items per page

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "company_type": "finishing_company",
            "company_id": 1,
            "description": "Dispute description",
            "status": "pending",
            "created_at": "2024-03-20T10:00:00Z",
            "resolved_at": null
        }
    ],
    "pagination": {
        "current_page": 1,
        "last_page": 5,
        "per_page": 15,
        "total": 75
    },
    "message": "Disputes retrieved successfully"
}
```

#### Get Dispute by ID
```http
GET /api/disputes/{id}
```

#### Update Dispute
```http
PUT /api/disputes/{id}
```

**Request Body:**
```json
{
    "status": "resolved",
    "resolution_notes": "Dispute resolved through mediation",
    "resolution_type": "mediation"
}
```

## Super Admin Endpoints

### Authentication Required
All super admin endpoints require authentication with super_admin role.

### Admin Management

#### Get All Admins
```http
GET /api/admin/management/admins
```

#### Get Admin by ID
```http
GET /api/admin/management/admins/{id}
```

#### Create Admin
```http
POST /api/admin/management/admins
```

**Request Body:**
```json
{
    "name": "New Admin",
    "email": "newadmin@example.com",
    "password": "password123",
    "role": "platform_admin"
}
```

#### Update Admin
```http
PUT /api/admin/management/admins/{id}
```

#### Delete Admin
```http
DELETE /api/admin/management/admins/{id}
```

#### Toggle Admin Status
```http
PATCH /api/admin/management/admins/{id}/toggle-status
```

## Error Responses

### Validation Error (422)
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "email": [
            "The email field is required."
        ],
        "password": [
            "The password must be at least 8 characters."
        ]
    }
}
```

### Authentication Error (401)
```json
{
    "message": "Unauthenticated."
}
```

### Authorization Error (403)
```json
{
    "message": "Unauthorized."
}
```

### Not Found Error (404)
```json
{
    "message": "Resource not found."
}
```

### Server Error (500)
```json
{
    "message": "Internal server error."
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
- `500` - Internal Server Error

## Rate Limiting

API endpoints are rate-limited to prevent abuse. Limits vary by endpoint type:
- Public endpoints: 60 requests per minute
- Authenticated endpoints: 120 requests per minute
- Admin endpoints: 300 requests per minute

## Pagination

Most list endpoints support pagination with the following query parameters:
- `page` (optional): Page number (default: 1)
- `per_page` (optional): Items per page (default: 15, max: 100)

Pagination metadata is included in responses:
```json
{
    "data": [...],
    "meta": {
        "current_page": 1,
        "last_page": 5,
        "per_page": 15,
        "total": 75,
        "from": 1,
        "to": 15
    }
}
```

## File Uploads

File upload endpoints accept multipart/form-data requests. Supported file types and sizes:
- Images: JPG, PNG, GIF (max 5MB each)
- Documents: PDF, DOC, DOCX (max 10MB each)
- Attachments: Any file type (max 10MB each)

## Webhooks

The API supports webhooks for real-time notifications. Webhook endpoints will be documented separately based on your specific requirements. 