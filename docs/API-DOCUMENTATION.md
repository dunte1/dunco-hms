# Dunco HMS — API Documentation

## Overview

Dunco HMS provides a RESTful API secured with Laravel Sanctum authentication.

**Base URL:** https://hmse.duncowebsolutions.co.ke/api

## Authentication

### Register

```
POST /api/register
Content-Type: application/json

{
    "name": "User Name",
    "email": "user@example.com",
    "password": "password",
    "password_confirmation": "password"
}
```

### Login

```
POST /api/login
Content-Type: application/json

{
    "email": "user@example.com",
    "password": "password"
}
```

Response includes `token` for subsequent requests.

### Logout

```
POST /api/logout
Authorization: Bearer {token}
```

## Protected Endpoints

All protected endpoints require `Authorization: Bearer {token}` header.

### Patients

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /api/patients | List patients (paginated) |
| POST | /api/patients | Create patient |
| GET | /api/patients/{id} | Get patient |
| PUT | /api/patients/{id} | Update patient |
| DELETE | /api/patients/{id} | Delete patient |

### Doctors

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /api/doctors | List doctors |
| POST | /api/doctors | Create doctor |
| GET | /api/doctors/{id} | Get doctor |
| PUT | /api/doctors/{id} | Update doctor |
| DELETE | /api/doctors/{id} | Delete doctor |

### Appointments

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /api/appointments | List appointments |
| POST | /api/appointments | Create appointment |
| GET | /api/appointments/{id} | Get appointment |
| PUT | /api/appointments/{id} | Update appointment |
| DELETE | /api/appointments/{id} | Delete appointment |

### Beds

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /api/beds | List beds |
| POST | /api/beds | Create bed |

### Invoices

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /api/invoices | List invoices |
| POST | /api/invoices | Create invoice |
| GET | /api/invoices/{id} | Get invoice |
| PUT | /api/invoices/{id} | Update invoice |
| DELETE | /api/invoices/{id} | Delete invoice |

### Payments

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /api/payments | List payments |
| POST | /api/payments | Create payment |

### Tokens

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /api/tokens | List API tokens |
| POST | /api/tokens | Create API token |

## M-Pesa Webhooks (No CSRF)

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | /api/mpesa/callback | M-Pesa callback |
| POST | /api/mpesa/result | M-Pesa result |
| POST | /api/mpesa/confirmation | M-Pesa confirmation |
| POST | /api/mpesa/validation | M-Pesa validation |

## Rate Limiting

- **API Rate:** 60 requests per minute per user/IP
- **Web Routes:** Standard Laravel session-based

## Error Responses

```json
{
    "success": false,
    "message": "Error description"
}
```

Status codes: 400 (Bad Request), 401 (Unauthorized), 403 (Forbidden), 404 (Not Found), 422 (Validation Error), 500 (Server Error)
