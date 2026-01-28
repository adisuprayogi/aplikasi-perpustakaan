# API Specification
## Aplikasi Perpustakaan Kampus - RESTful API

**Version:** 1.0
**Date:** 2026-01-27
**Base URL:** `https://library.kampus.ac.id/api`
**Architecture:** Monolit (Laravel)

---

## 1. General Information

### 1.1 Authentication
- **Method:** Bearer Token (Sanctum) / Session Cookie
- **Header:** `Authorization: Bearer {token}` atau Cookie `laravel_session`
- **Token Expiry:** 24 hours (API), 30 minutes idle (Session)

### 1.2 Response Format
```json
{
  "success": true|false,
  "message": "Human readable message",
  "data": {...}|[...],
  "errors": {...},
  "meta": {
    "page": 1,
    "per_page": 15,
    "total": 100,
    "from": 1,
    "to": 15
  }
}
```

### 1.3 HTTP Status Codes
| Code | Meaning |
|------|---------|
| 200 | Success |
| 201 | Created |
| 204 | No Content |
| 400 | Bad Request |
| 401 | Unauthorized |
| 403 | Forbidden |
| 404 | Not Found |
| 422 | Validation Error |
| 429 | Too Many Requests |
| 500 | Server Error |

### 1.4 Pagination
```
?page=1&per_page=15
```

### 1.5 Sorting
```
?sort=field&direction=asc|desc
```

### 1.6 Filtering
```
?filter[field]=value&filter[field2]=value2
```

---

## 2. Authentication Endpoints

### 2.1 Login
```
POST /auth/login
```

**Request:**
```json
{
  "email": "user@example.com",
  "password": "secret123",
  "remember_me": true
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "role": "staff",
      "branch_id": 1
    },
    "token": "1|abc123...",
    "abilities": ["circulation:*", "catalog:read", "catalog:write"]
  }
}
```

### 2.2 Logout
```
POST /auth/logout
```
**Auth Required:** Yes

**Response (200):**
```json
{
  "success": true,
  "message": "Logged out successfully"
}
```

### 2.3 Me (Current User)
```
GET /auth/me
```
**Auth Required:** Yes

**Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "role": "staff",
    "branch": {
      "id": 1,
      "name": "Perpustakaan Pusat"
    },
    "permissions": ["circulation:create", "catalog:read"]
  }
}
```

### 2.4 Refresh Token
```
POST /auth/refresh
```
**Auth Required:** Yes

**Response (200):**
```json
{
  "success": true,
  "data": {
    "token": "1|xyz789..."
  }
}
```

---

## 3. Branch Endpoints

### 3.1 List Branches
```
GET /branches
```
**Auth Required:** Yes

**Query Params:**
- `page`: Page number
- `per_page`: Items per page (default: 15)
- `filter[type]`: Filter by type (central, faculty, study_program)
- `filter[is_active]`: Filter by status (true, false)

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "code": "PUSAT",
      "name": "Perpustakaan Pusat",
      "type": "central",
      "address": "Jl. Pendidikan No. 1",
      "phone": "021-123456",
      "email": "lib@kampus.ac.id",
      "is_active": true,
      "created_at": "2024-01-01T00:00:00Z",
      "updated_at": "2024-01-01T00:00:00Z"
    }
  ],
  "meta": {...}
}
```

### 3.2 Get Branch
```
GET /branches/{id}
```
**Auth Required:** Yes

**Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "code": "PUSAT",
    "name": "Perpustakaan Pusat",
    "type": "central",
    "address": "Jl. Pendidikan No. 1",
    "phone": "021-123456",
    "email": "lib@kampus.ac.id",
    "is_active": true,
    "statistics": {
      "total_items": 15432,
      "total_members": 2341,
      "active_loans": 567
    }
  }
}
```

### 3.3 Create Branch
```
POST /branches
```
**Auth Required:** Yes (Super Admin only)

**Request:**
```json
{
  "code": "FKIP",
  "name": "Perpustakaan FKIP",
  "type": "faculty",
  "address": "Gedung FKIP Lt. 2",
  "phone": "021-234567",
  "email": "lib-fkip@kampus.ac.id"
}
```

**Response (201):**
```json
{
  "success": true,
  "message": "Branch created successfully",
  "data": {...}
}
```

### 3.4 Update Branch
```
PUT/PATCH /branches/{id}
```
**Auth Required:** Yes (Super Admin only)

**Request:** (Same as Create)

**Response (200):**
```json
{
  "success": true,
  "message": "Branch updated successfully",
  "data": {...}
}
```

### 3.5 Delete Branch
```
DELETE /branches/{id}
```
**Auth Required:** Yes (Super Admin only)

**Response (200):**
```json
{
  "success": true,
  "message": "Branch deleted successfully"
}
```

---

## 4. Member Endpoints

### 4.1 List Members
```
GET /members
```
**Auth Required:** Yes (Staff, Admin)

**Query Params:**
- `page`, `per_page`
- `filter[type]`: Filter by type (student, lecturer, staff)
- `filter[status]`: Filter by status (active, suspended, expired)
- `filter[branch_id]`: Filter by branch
- `search`: Search by name, member_no, email

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "member_no": "M2024001",
      "name": "Ahmad Suryadi",
      "type": "student",
      "email": "ahmad@student.kampus.ac.id",
      "phone": "08123456789",
      "branch_id": 1,
      "status": "active",
      "valid_until": "2025-12-31",
      "photo": "https://...",
      "current_loans": 2,
      "total_fines": 0
    }
  ],
  "meta": {...}
}
```

### 4.2 Get Member
```
GET /members/{id}
```
**Auth Required:** Yes

**Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "member_no": "M2024001",
    "name": "Ahmad Suryadi",
    "type": "student",
      "email": "ahmad@student.kampus.ac.id",
    "phone": "08123456789",
    "address": "Jl. Mahasiswa No. 10",
    "branch": {
      "id": 1,
      "name": "Perpustakaan Pusat"
    },
    "status": "active",
    "valid_until": "2025-12-31",
    "photo": "https://...",
    "statistics": {
      "total_loans": 45,
      "current_loans": 2,
      "overdue_loans": 0,
      "total_fines": 15000,
      "unpaid_fines": 0
    },
    "current_loans": [
      {
        "id": 101,
        "item": {
          "title": "Algoritma dan Pemrograman",
          "barcode": "B00123456"
        },
        "loan_date": "2024-01-20",
        "due_date": "2024-01-27",
        "is_overdue": false
      }
    ]
  }
}
```

### 4.3 Create Member
```
POST /members
```
**Auth Required:** Yes (Staff, Admin)

**Request:**
```json
{
  "type": "student",
  "name": "Ahmad Suryadi",
  "email": "ahmad@student.kampus.ac.id",
  "phone": "08123456789",
  "address": "Jl. Mahasiswa No. 10",
  "branch_id": 1,
  "valid_until": "2025-12-31",
  "photo": "base64_image..."
}
```

**Response (201):**
```json
{
  "success": true,
  "message": "Member created successfully",
  "data": {
    "id": 1,
    "member_no": "M2024001",
    ...
  }
}
```

### 4.4 Update Member
```
PUT/PATCH /members/{id}
```
**Auth Required:** Yes (Staff, Admin)

**Request:** (Same as Create, all fields optional)

**Response (200):**
```json
{
  "success": true,
  "message": "Member updated successfully",
  "data": {...}
}
```

### 4.5 Suspend Member
```
POST /members/{id}/suspend
```
**Auth Required:** Yes (Staff, Admin)

**Request:**
```json
{
  "reason": "Overdue fines exceed limit",
  "suspended_until": "2024-02-01"
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Member suspended successfully"
}
```

### 4.6 Renew Membership
```
POST /members/{id}/renew
```
**Auth Required:** Yes (Staff, Admin)

**Request:**
```json
{
  "valid_until": "2026-12-31"
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Membership renewed successfully",
  "data": {
    "valid_until": "2026-12-31"
  }
}
```

### 4.7 Get Member Loans
```
GET /members/{id}/loans
```
**Auth Required:** Yes

**Query Params:**
- `status`: Filter by status (active, returned, overdue)
- `page`, `per_page`

**Response (200):**
```json
{
  "success": true,
  "data": [...],
  "meta": {...}
}
```

---

## 5. Collection Endpoints

### 5.1 List Collections
```
GET /collections
```
**Auth Required:** Yes

**Query Params:**
- `page`, `per_page`
- `filter[type]`: Filter by type (book, journal, thesis, reference)
- `filter[branch_id]`: Filter by branch
- `search`: Search by title, author, isbn, subject

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Algoritma dan Pemrograman",
      "authors": ["Rinaldi Munir"],
      "isbn": "978-623-02-3456-7",
      "publisher": "Informatika",
      "year": 2022,
      "edition": "Edisi 5",
      "pages": 450,
      "language": "id",
      "classification": "005.1 MUN a",
      "abstract": "Buku ini membahas...",
      "cover_image": "https://...",
      "total_items": 3,
      "available_items": 2,
      "subjects": ["Algoritma", "Pemrograman", "Komputer"]
    }
  ],
  "meta": {...}
}
```

### 5.2 Get Collection
```
GET /collections/{id}
```
**Auth Required:** Yes

**Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "title": "Algoritma dan Pemrograman",
    "authors": ["Rinaldi Munir"],
    "isbn": "978-623-02-3456-7",
    "publisher": "Informatika",
    "year": 2022,
    "edition": "Edisi 5",
    "pages": 450,
    "language": "id",
    "classification": "005.1 MUN a",
    "abstract": "Buku ini membahas...",
    "cover_image": "https://...",
    "subjects": ["Algoritma", "Pemrograman", "Komputer"],
    "items": [
      {
        "id": 101,
        "barcode": "B00123456",
        "call_number": "005.1 MUN a 001",
        "branch": {
          "id": 1,
          "name": "Perpustakaan Pusat"
        },
        "location": "Rak A-10",
        "status": "available",
        "condition": "good"
      },
      {
        "id": 102,
        "barcode": "B00123457",
        "call_number": "005.1 MUN a 002",
        "branch": {
          "id": 1,
          "name": "Perpustakaan Pusat"
        },
        "location": "Rak A-10",
        "status": "borrowed",
        "condition": "good",
        "loan": {
          "member": "Ahmad Suryadi",
          "due_date": "2024-01-27"
        }
      }
    ]
  }
}
```

### 5.3 Create Collection
```
POST /collections
```
**Auth Required:** Yes (Catalog Staff, Admin)

**Request:**
```json
{
  "title": "Algoritma dan Pemrograman",
  "authors": ["Rinaldi Munir"],
  "isbn": "978-623-02-3456-7",
  "publisher": "Informatika",
  "year": 2022,
  "edition": "Edisi 5",
  "pages": 450,
  "language": "id",
  "classification_id": 1,
  "abstract": "Buku ini membahas...",
  "subjects": [1, 5, 10],
  "cover_image": "base64_image...",
  "items": [
    {
      "branch_id": 1,
      "location": "Rak A-10",
      "condition": "good"
    },
    {
      "branch_id": 1,
      "location": "Rak A-10",
      "condition": "good"
    }
  ]
}
```

**Response (201):**
```json
{
  "success": true,
  "message": "Collection created successfully",
  "data": {
    "id": 1,
    "title": "Algoritma dan Pemrograman",
    ...
  }
}
```

### 5.4 Update Collection
```
PUT/PATCH /collections/{id}
```
**Auth Required:** Yes (Catalog Staff, Admin)

**Request:** (Same as Create, all fields optional)

**Response (200):**
```json
{
  "success": true,
  "message": "Collection updated successfully",
  "data": {...}
}
```

### 5.5 Delete Collection
```
DELETE /collections/{id}
```
**Auth Required:** Yes (Catalog Staff, Admin)

**Response (200):**
```json
{
  "success": true,
  "message": "Collection deleted successfully"
}
```

### 5.6 Add Item to Collection
```
POST /collections/{id}/items
```
**Auth Required:** Yes (Catalog Staff, Admin)

**Request:**
```json
{
  "branch_id": 1,
  "location": "Rak A-15",
  "condition": "good"
}
```

**Response (201):**
```json
{
  "success": true,
  "message": "Item added successfully",
  "data": {
    "id": 103,
    "barcode": "B00123458",
    "call_number": "005.1 MUN a 003",
    ...
  }
}
```

---

## 6. Circulation Endpoints

### 6.1 Create Loan (Checkout)
```
POST /loans
```
**Auth Required:** Yes (Circulation Staff, Admin)

**Request:**
```json
{
  "member_id": 1,
  "item_ids": [101, 102, 103],
  "branch_id": 1
}
```

**Response (201):**
```json
{
  "success": true,
  "message": "Loan created successfully",
  "data": {
    "loans": [
      {
        "id": 201,
        "item": {
          "id": 101,
          "title": "Algoritma dan Pemrograman",
          "barcode": "B00123456"
        },
        "member": {
          "id": 1,
          "name": "Ahmad Suryadi",
          "member_no": "M2024001"
        },
        "loan_date": "2024-01-20",
        "due_date": "2024-01-27",
        "branch": {
          "id": 1,
          "name": "Perpustakaan Pusat"
        }
      }
    ],
    "warnings": [
      "Member has reached maximum loan limit for this type"
    ]
  }
}
```

**Error Response (422):**
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "item_ids.2": "Item 103 is not available"
  }
}
```

### 6.2 Return Loan (Check-in)
```
POST /loans/return
```
**Auth Required:** Yes (Circulation Staff, Admin)

**Request:**
```json
{
  "item_ids": [101, 102],
  "branch_id": 1,
  "condition": "good"
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Items returned successfully",
  "data": {
    "returns": [
      {
        "loan_id": 201,
        "item": {
          "id": 101,
          "title": "Algoritma dan Pemrograman",
          "barcode": "B00123456"
        },
        "loan_date": "2024-01-20",
        "due_date": "2024-01-27",
        "return_date": "2024-01-27",
        "days_overdue": 0,
        "fine": 0
      },
      {
        "loan_id": 202,
        "item": {
          "id": 102,
          "title": "Struktur Data",
          "barcode": "B00123459"
        },
        "loan_date": "2024-01-15",
        "due_date": "2024-01-22",
        "return_date": "2024-01-27",
        "days_overdue": 5,
        "fine": 5000
      }
    ],
    "total_fine": 5000
  }
}
```

### 6.3 Renew Loan
```
POST /loans/{id}/renew
```
**Auth Required:** Yes (Circulation Staff, Admin)

**Request:**
```json
{
  "branch_id": 1
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Loan renewed successfully",
  "data": {
    "id": 201,
    "previous_due_date": "2024-01-27",
    "new_due_date": "2024-02-03",
    "renewal_count": 1
  }
}
```

**Error Response (422):**
```json
{
  "success": false,
  "message": "Cannot renew loan",
  "errors": {
    "reason": "Item has been reserved by another member"
  }
}
```

### 6.4 List Active Loans
```
GET /loans/active
```
**Auth Required:** Yes

**Query Params:**
- `page`, `per_page`
- `filter[branch_id]`: Filter by branch
- `filter[member_id]`: Filter by member
- `filter[is_overdue]`: Filter overdue only

**Response (200):**
```json
{
  "success": true,
  "data": [...],
  "meta": {...}
}
```

### 6.5 Get Loan Detail
```
GET /loans/{id}
```
**Auth Required:** Yes

**Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 201,
    "item": {...},
    "member": {...},
    "loan_branch": {...},
    "return_branch": null,
    "loan_date": "2024-01-20",
    "due_date": "2024-01-27",
    "return_date": null,
    "fine": 0,
    "status": "active",
    "renewal_count": 0,
    "processed_by": {
      "id": 10,
      "name": "Staff Sirkulasi"
    }
  }
}
```

---

## 7. Reservation Endpoints

### 7.1 Create Reservation
```
POST /reservations
```
**Auth Required:** Yes (Staff, Admin, Member)

**Request:**
```json
{
  "item_id": 101,
  "branch_id": 1
}
```

**Response (201):**
```json
{
  "success": true,
  "message": "Reservation created successfully",
  "data": {
    "id": 1,
    "item": {...},
    "member": {...},
    "branch": {...},
    "reservation_date": "2024-01-27",
    "expiry_date": "2024-02-05",
    "status": "pending",
    "queue_position": 1
  }
}
```

### 7.2 List Reservations
```
GET /reservations
```
**Auth Required:** Yes

**Query Params:**
- `page`, `per_page`
- `filter[status]`: Filter by status (pending, ready, expired, cancelled)
- `filter[member_id]`: Filter by member
- `filter[branch_id]`: Filter by branch

**Response (200):**
```json
{
  "success": true,
  "data": [...],
  "meta": {...}
}
```

### 7.3 Cancel Reservation
```
DELETE /reservations/{id}
```
**Auth Required:** Yes

**Response (200):**
```json
{
  "success": true,
  "message": "Reservation cancelled successfully"
}
```

---

## 8. Search Endpoints

### 8.1 Search Collections
```
GET /search/collections
```
**Auth Required:** Optional (Public access for OPAC)

**Query Params:**
- `q`: Search query
- `page`, `per_page`
- `filter[type]`: Filter by collection type
- `filter[branch_id]`: Filter by branch
- `filter[author]`: Filter by author
- `filter[publisher]`: Filter by publisher
- `filter[year]`: Filter by year
- `filter[subject]`: Filter by subject

**Response (200):**
```json
{
  "success": true,
  "data": [...],
  "meta": {
    "page": 1,
    "per_page": 15,
    "total": 42,
    "from": 1,
    "to": 15,
    "query": "algoritma",
    "elapsed_time": "0.15s"
  }
}
```

### 8.2 Advanced Search
```
POST /search/advanced
```
**Auth Required:** Optional (Public access for OPAC)

**Request:**
```json
{
  "title": "algoritma",
  "author": "munir",
  "isbn": "978-623-02-3456-7",
  "publisher": "informatika",
  "year_from": 2020,
  "year_to": 2024,
  "subjects": [1, 5],
  "branch_ids": [1, 2],
  "available_only": true
}
```

**Response (200):**
```json
{
  "success": true,
  "data": [...],
  "meta": {...}
}
```

---

## 9. Report Endpoints

### 9.1 Loan Report
```
GET /reports/loans
```
**Auth Required:** Yes (Admin, Report Viewer)

**Query Params:**
- `date_from`: Start date
- `date_to`: End date
- `branch_id`: Filter by branch
- `format`: Format (json, pdf, xlsx)

**Response (200):**
```json
{
  "success": true,
  "data": {
    "period": {
      "from": "2024-01-01",
      "to": "2024-01-31"
    },
    "summary": {
      "total_loans": 1234,
      "total_items": 1456,
      "total_members": 456
    },
    "loans": [...]
  }
}
```

### 9.2 Overdue Report
```
GET /reports/overdue
```
**Auth Required:** Yes (Admin, Report Viewer)

**Query Params:**
- `date_from`, `date_to`, `branch_id`, `format`

**Response (200):**
```json
{
  "success": true,
  "data": {
    "period": {...},
    "summary": {
      "total_overdue": 56,
      "total_items": 67,
      "total_fines": 345000
    },
    "overdue_loans": [...]
  }
}
```

### 9.3 Collection Report
```
GET /reports/collections
```
**Auth Required:** Yes (Admin, Report Viewer)

**Query Params:**
- `branch_id`: Filter by branch
- `format`: Format

**Response (200):**
```json
{
  "success": true,
  "data": {
    "summary": {
      "total_collections": 5432,
      "total_items": 15432,
      "by_type": {
        "book": 12000,
        "journal": 2000,
        "thesis": 1432
      },
      "by_status": {
        "available": 12000,
        "borrowed": 2500,
        "lost": 100,
        "damaged": 50
      }
    },
    "collections": [...]
  }
}
```

### 9.4 Dashboard Statistics
```
GET /reports/dashboard
```
**Auth Required:** Yes

**Response (200):**
```json
{
  "success": true,
  "data": {
    "today": {
      "loans": 45,
      "returns": 38,
      "new_members": 5,
      "fines_collected": 150000
    },
    "this_month": {
      "loans": 1234,
      "returns": 1156,
      "new_members": 45,
      "fines_collected": 2345000
    },
    "overdue": {
      "count": 56,
      "items": 67,
      "total_fines": 345000
    },
    "expiring_soon": [
      {
        "member": "Ahmad Suryadi",
        "item": "Algoritma dan Pemrograman",
        "due_date": "2024-01-28",
        "days_left": 1
      }
    ]
  }
}
```

---

## 10. Settings Endpoints

### 10.1 Get Settings
```
GET /settings
```
**Auth Required:** Yes

**Response (200):**
```json
{
  "success": true,
  "data": {
    "library": {
      "name": "Perpustakaan Kampus",
      "address": "Jl. Pendidikan No. 1",
      "phone": "021-123456",
      "email": "lib@kampus.ac.id",
      "logo": "https://..."
    },
    "loan_rules": {
      "student": {
        "loan_period": 7,
        "max_loans": 3,
        "fine_per_day": 1000
      },
      "lecturer": {
        "loan_period": 14,
        "max_loans": 5,
        "fine_per_day": 1000
      },
      "staff": {
        "loan_period": 7,
        "max_loans": 3,
        "fine_per_day": 1000
      }
    },
    "renewal": {
      "max_renewals": 2
    },
    "reservation": {
      "max_reservations": 3,
      "pickup_days": 3
    },
    "fine": {
      "suspend_threshold": 50000
    }
  }
}
```

### 10.2 Update Settings
```
PUT/PATCH /settings
```
**Auth Required:** Yes (Super Admin only)

**Request:**
```json
{
  "library": {
    "name": "Perpustakaan Kampus",
    ...
  },
  "loan_rules": {
    "student": {
      "loan_period": 7,
      "max_loans": 3,
      "fine_per_day": 1000
    },
    ...
  }
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Settings updated successfully",
  "data": {...}
}
```

---

## 11. Error Responses

### 11.1 Validation Error (422)
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "email": ["The email field is required."],
    "password": ["The password must be at least 8 characters."]
  }
}
```

### 11.2 Not Found (404)
```json
{
  "success": false,
  "message": "Resource not found"
}
```

### 11.3 Unauthorized (401)
```json
{
  "success": false,
  "message": "Unauthenticated"
}
```

### 11.4 Forbidden (403)
```json
{
  "success": false,
  "message": "You do not have permission to perform this action"
}
```

### 11.5 Server Error (500)
```json
{
  "success": false,
  "message": "Internal server error",
  "error": "Error details (in debug mode only)"
}
```

---

*End of API Specification*
