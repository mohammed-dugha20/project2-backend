# Real Estate Office & Property API Documentation

## Table of Contents
- [Real Estate Office Endpoints](#real-estate-office-endpoints)
  - [Public](#public-real-estate-office-endpoints)
  - [Authenticated (Real Estate Office)](#authenticated-real-estate-office-endpoints)
  - [Admin](#admin-real-estate-office-endpoints)
- [Property Endpoints](#property-endpoints)
  - [Public](#public-property-endpoints)
  - [Authenticated (Customer/Office)](#authenticated-property-endpoints)
  - [Admin](#admin-property-endpoints)

---

## Real Estate Office Endpoints

### Public Real Estate Office Endpoints
| Method | Path | Description |
|--------|------|-------------|
| GET    | `/api/public/real-estate-offices` | List all active real estate offices |
| GET    | `/api/public/real-estate-offices/{id}` | Get details of a specific real estate office |

### Authenticated Real Estate Office Endpoints
_Requires: `auth:sanctum` + `real_estate_office` middleware_

| Method | Path | Description |
|--------|------|-------------|
| GET    | `/api/real-estate-office/profile` | Get the profile of the authenticated office |
| PUT    | `/api/real-estate-office/profile` | Update the profile |
| POST   | `/api/real-estate-office/documents` | Upload a document |
| DELETE | `/api/real-estate-office/documents/{documentId}` | Remove a document |
| GET    | `/api/real-estate-office/properties` | List properties owned by the office |
| GET    | `/api/real-estate-office/analytics` | Get analytics for the office |
| GET    | `/api/real-estate-office/reviews` | Get reviews for the office |

### Admin Real Estate Office Endpoints
_Requires: `auth:sanctum` + `platform_admin` middleware_

| Method | Path | Description |
|--------|------|-------------|
| GET    | `/api/admin/real-estate-offices` | List all offices |
| GET    | `/api/admin/real-estate-offices/{id}` | Get office details |
| POST   | `/api/admin/real-estate-offices` | Create a new office |
| PUT    | `/api/admin/real-estate-offices/{id}` | Update an office |
| PATCH  | `/api/admin/real-estate-offices/{id}/toggle-status` | Toggle office status |
| GET    | `/api/admin/real-estate-offices/{id}/reviews` | Get office reviews |
| GET    | `/api/admin/real-estate-offices/{id}/documents` | Get office documents |
| GET    | `/api/admin/real-estate-offices/{id}/properties` | Get office properties |
| GET    | `/api/admin/real-estate-offices/{id}/performance` | Get office performance |
| GET    | `/api/admin/real-estate-offices/{id}/analytics` | Get office analytics |

---

## Property Endpoints

### Public Property Endpoints
| Method | Path | Description |
|--------|------|-------------|
| GET    | `/api/public/properties` | List all active properties |
| GET    | `/api/public/properties/{property}` | Get property details |
| GET    | `/api/public/search` | Search properties |

### Authenticated Property Endpoints
_Requires: `auth:sanctum`_



#### For Real Estate Offices
| Method | Path | Description |
|--------|------|-------------|
| GET    | `/api/properties` | List all properties (owned by office) |
| GET    | `/api/properties/{property}` | Get property details |
| POST   | `/api/properties` | Create a new property |
| PUT    | `/api/properties/{property}` | Update a property |
| DELETE | `/api/properties/{property}` | Delete a property |
| PATCH  | `/api/properties/{property}/status` | Update property status |
| POST   | `/api/properties/{property}/images` | Upload property images |
| POST   | `/api/properties/{property}/attachments` | Upload property attachments |
| DELETE | `/api/properties/images/{image}` | Delete property image |
| DELETE | `/api/properties/attachments/{attachment}` | Delete property attachment |
| GET    | `/api/properties/{property}/images` | List property images |
| GET    | `/api/properties/{property}/attachments` | List property attachments |

---

## Notes
- All endpoints return JSON responses.
- Authenticated endpoints require a valid Bearer token (via Laravel Sanctum).
- Admin endpoints require additional admin or platform admin roles.
- For request/response body details, see controller validation or resource classes.

---

_Last updated: [auto-generated]_ 