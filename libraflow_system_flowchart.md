# LibraFlow Library Management System - Complete System Flowchart

```
                                ┌─────────────────────────────────────────────────────────┐
                                │                    LIBRARY MANAGEMENT SYSTEM              │
                                │                      (LibraFlow)                         │
                                └─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┐
│                                                    AUTHENTICATION & ACCESS                                            │
└─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┘

                                       ┌─────────────────────┐
                                       │     WELCOME PAGE     │
                                       │   / (Public Route)   │
                                       └─────────────────────┘
                                              │
                    ┌──────────────────────────┼──────────────────────────┐
                    │                          │                          │
            ┌───────▼───────┐          ┌───────▼───────┐      ┌────────────▼─────────┐
            │  USER REGISTRATION │        │  USER LOGIN   │      │   GUEST ACCESS       │
            │  /register       │        │  /login       │      │   (View Only)        │
            └───────┬─────────┘          └───────┬───────┘      └────────────┬─────────┘
                    │                          │                            │
                    └──────────┬───────────────┼────────────┬───────────────┘
                               │               │           │
                               ▼               ▼           ▼
                    ┌─────────────────────────────────────────────────────────┐
                    │                  DASHBOARD ROUTE                         │
                    │                    /dashboard                            │
                    │          (Auto-redirect based on auth state)             │
                    └─────────────────────┬─────────────────────────────────────┘
                                          │
                                   ┌──────▼──────┐
                                   │  USER LOGIN │
                                   │   SUCCESS   │
                                   └──────┬──────┘
                                          │
                           ┌──────────────┼──────────────┐
                           │              │              │
                           ▼              ▼              ▼
                    ┌─────────────────────────────────────────────────────────┐
                    │              ROLE-BASED DASHBOARD                       │
                    │  (Student/Teacher)                 │  (Admin User)     │
                    └─────────────┬───────────────────────┼───────────────────┘
                                  │                       │
                                  ▼                       ▼

┌─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┐
│                                                STUDENT/TEACHER WORKFLOW                                              │
└─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┘

                    ┌─────────────────────────────────────────────────────────┐
                    │                    MAIN NAVIGATION                      │
                    │  ┌─────────┐ ┌─────────┐ ┌─────────┐ ┌──────────┐      │
                    │  │  Books  │ │ Profile │ │Settings │ │My History│      │
                    │  │  /books │ │/profile │ │/settings│ │/my-borrow│      │
                    │  └─────────┘ └─────────┘ └─────────┘ └──────────┘      │
                    └─────────────────────┬───────────────────────────────────┘
                                          │
                           ┌──────────────┼──────────────┐
                           │              │              │
                           ▼              ▼              ▼
                    ┌─────────────┐ ┌─────────────┐ ┌──────────────┐
                    │  BOOK BROWSE│ │   PROFILE   │ │ BORROWING    │
                    │   & SEARCH  │ │  MANAGEMENT │ │   HISTORY    │
                    └──────┬──────┘ └──────┬──────┘ └───────┬───────┘
                           │               │                │
                           └───────────────┼────────────────┘
                                           │
                                           ▼
                            ┌────────────────────────────────────┐
                            │         BOOK BORROWING FLOW        │
                            │  ┌─────────────┐ ┌─────────────┐  │
                            │  │  VIEW BOOK  │ │  CHECK AVAIL│  │
                            │  │ DETAILS     │ │    STATUS   │  │
                            │  └──────┬──────┘ └──────┬──────┘  │
                            │         │               │         │
                            │         ▼               ▼         │
                            │  ┌─────────────────────────────────┐│
                            │  │      AVAILABLE?               ││
                            │  └──────┬────────────────────────┘│
                            │         │                          │
                            │   ┌─────▼─────┐              ┌─────▼─────┐
                            │   │   YES     │              │    NO     │
                            │   │           │              │           │
                            │   ▼           ▼              ▼           │
                            │  ┌─────┐ ┌─────────┐  ┌─────────┐ ┌─────┐│
                            │  │ BORROW│ │ RESERVE │  │  JOIN  │ │VIEW ││
                            │  │ BOOK │ │  BOOK  │  │RESERVAT│ │SIMIL││
                            │  └─────┘ └────────┘  │   LIST  │ │AR   ││
                            │        │              └─────────┘ └─────┘│
                            │        ▼                    │           │
                            │  ┌──────────────────────┐   │           │
                            │  │   BORROWING FORM     │   │           │
                            │  │    CONFIRMATION      │   │           │
                            │  └──────┬───────────────┘   │           │
                            │         │                   │           │
                            │         ▼                   │           │
                            │  ┌──────────────────────┐   │           │
                            │  │     BORROWING        │   │           │
                            │  │     RECORD CREATED   │   │           │
                            │  │   Due Date + Fine    │   │           │
                            │  └───────┬──────────────┘   │           │
                            │          │                  │           │
                            │          ▼                  │           │
                            │  ┌──────────────────────┐   │           │
                            │  │  BOOK STATUS UPDATED │   │           │
                            │  │   Available → Borrowed│   │           │
                            │  └──────────────────────┘   │           │
                            │                              │           │
                            └──────────────────────────────┘           │
                                                                   │
                                                                   ▼
                                                            ┌───────────────┐
                                                            │ BOOK RETURN   │
                                                            │    FLOW       │
                                                            └───────┬───────┘
                                                                    │
                                                            ┌───────▼──────┐
                                                            │ RETURN BOOK  │
                                                            │  AT LIBRARY  │
                                                            └──────┬───────┘
                                                                   │
                                                            ┌───────▼──────┐
                                                            │ CHECK FINE   │
                                                            │   STATUS     │
                                                            └──────┬───────┘
                                                                   │
                                                            ┌───────▼──────┐
                                                            │    FINE?     │
                                                            └──────┬───────┘
                                                                   │
                                                            ┌───────▼───────┐
                                                            │ YES: PAY FINE │
                                                            │ NO:  COMPLETE │
                                                            │    RETURN     │
                                                            └───────┬───────┘
                                                                    │
                                                            ┌───────▼───────┐
                                                            │BORROWING RECORD│
                                                            │   UPDATED      │
                                                            │   DUE DATE     │
                                                            │   FINE CLEARED │
                                                            └─────────┬─────┘
                                                                     │
                                                            ┌───────▼───────┐
                                                            │BOOK STATUS    │
                                                            │BORROWED →     │
                                                            │AVAILABLE      │
                                                            └───────────────┘

┌─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┐
│                                                     ADMIN WORKFLOW                                                   │
└─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┘

                    ┌─────────────────────────────────────────────────────────┐
                    │                    ADMIN DASHBOARD                      │
                    │  ┌─────────┐ ┌─────────┐ ┌─────────┐ ┌──────────┐      │
                    │  │  Users  │ │Announce │ │Borrowing│ │ Settings │      │
                    │  │Management│ │ ments   │ │  Mgmt   │ │   Admin  │      │
                    │  │/admin/users│ │/admin/  │ │/admin/  │ │/admin/   │      │
                    │  │         │ │announcem │ │ borrow  │ │settings  │      │
                    │  └─────────┘ │   ents   │ │         │ │          │      │
                    │              └─────────┘ └─────────┘ └──────────┘      │
                    └─────────────────────┬───────────────────────────────────┘
                                          │
                           ┌──────────────┼──────────────┐
                           │              │              │
                           ▼              ▼              ▼
                    ┌─────────────┐ ┌─────────────┐ ┌─────────────┐
                    │    USER     │ │ ANNOUNCEMENT│ │  BORROWING  │
                    │ MANAGEMENT  │ │  MANAGEMENT │ │  MANAGEMENT │
                    └──────┬──────┘ └──────┬──────┘ └───────┬───────┘
                           │               │                │
                           └───────────────┼────────────────┘
                                           │
                                           ▼
                            ┌────────────────────────────────────┐
                            │        USER MANAGEMENT FLOW       │
                            │  ┌─────────────┐ ┌─────────────┐  │
                            │  │   LIST ALL  │ │  SEARCH &   │  │
                            │  │   USERS     │ │   FILTER    │  │
                            │  └──────┬──────┘ └──────┬──────┘  │
                            │         │               │         │
                            │         ▼               ▼         │
                            │  ┌─────────────────────────────┐   │
                            │  │        USER ACTIONS        │   │
                            │  │  ┌──────┐ ┌──────┐ ┌─────┐ │   │
                            │  │  │EDIT  │ │DELETE│ │VIEW │ │   │
                            │  │  │USER  │ │ USER │ │HIST │ │   │
                            │  │  │INFO  │ │ USER │ │ORY  │ │   │
                            │  │  └──────┘ └──────┘ └─────┘ │   │
                            │  └──────┬──────────────────────┘   │
                            │         │                          │
                            │         ▼                          │
                            │  ┌─────────────────────────────────┐ │
                            │  │     UPDATE OPERATIONS          │ │
                            │  │  • Student ID Update           │ │
                            │  │  • RFID Assignment             │ │
                            │  │  • Barcode Assignment          │ │
                            │  │  • User Role Management        │ │
                            │  └─────────────────────────────────┘ │
                            └──────────────────────────────────────┘

                                           │
                                           ▼
                            ┌────────────────────────────────────┐
                            │      BOOK MANAGEMENT FLOW          │
                            │  ┌─────────────┐ ┌─────────────┐  │
                            │  │   ADD NEW   │ │    EDIT     │  │
                            │  │    BOOK     │ │   BOOK      │  │
                            │  │             │ │  DETAILS    │  │
                            │  └──────┬──────┘ └──────┬──────┘  │
                            │         │               │         │
                            │         ▼               ▼         │
                            │  ┌─────────────────────────────┐   │
                            │  │      BOOK OPERATIONS        │   │
                            │  │  • Category Assignment      │   │
                            │  │  • Author Assignment        │   │
                            │  │  • Location Setting         │   │
                            │  │  • Quantity Management      │   │
                            │  │  • Status Updates           │   │
                            │  └─────────────────────────────┘   │
                            └────────────────────────────────────┘

                                           │
                                           ▼
                            ┌────────────────────────────────────┐
                            │     BORROWING MANAGEMENT FLOW      │
                            │  ┌─────────────┐ ┌─────────────┐  │
                            │  │ ADMIN BORROW│ │ BORROWING   │  │
                            │  │    FOR USER │ │   REPORT    │  │
                            │  │             │ │             │  │
                            │  └──────┬──────┘ └──────┬──────┘  │
                            │         │               │         │
                            │         ▼               ▼         │
                            │  ┌─────────────────────────────┐   │
                            │  │     BORROWING OPERATIONS   │   │
                            │  │  • User Search             │   │
                            │  │  • Barcode Lookup          │   │
                            │  │  • Book Selection          │   │
                            │  │  • Fine Management         │   │
                            │  │  • Return Processing       │   │
                            │  └─────────────────────────────┘   │
                            └────────────────────────────────────┘

                                           │
                                           ▼
                            ┌────────────────────────────────────┐
                            │     SYSTEM SETTINGS FLOW           │
                            │  ┌─────────────┐ ┌─────────────┐  │
                            │  │  ANNOUNCEMENT│ │   SYSTEM    │  │
                            │  │  MANAGEMENT  │ │  SETTINGS   │  │
                            │  │             │ │             │  │
                            │  └──────┬──────┘ └──────┬──────┘  │
                            │         │               │         │
                            │         ▼               ▼         │
                            │  ┌─────────────────────────────┐   │
                            │  │      ADMIN OPERATIONS       │   │
                            │  │  • Create Announcements     │   │
                            │  │  • Update Library Hours     │   │
                            │  │  • Manage Library Location  │   │
                            │  │  • Fine Rate Configuration  │   │
                            │  │  • User Role Management     │   │
                            │  └─────────────────────────────┘   │
                            └────────────────────────────────────┘

                                           │
                                           ▼
                            ┌────────────────────────────────────┐
                            │    RFID & BARCODE MANAGEMENT       │
                            │  ┌─────────────┐ ┌─────────────┐  │
                            │  │   RFID      │ │   BARCODE   │  │
                            │  │   SCAN      │ │   SCAN      │  │
                            │  │             │ │             │  │
                            │  └──────┬──────┘ └──────┬──────┘  │
                            │         │               │         │
                            │         ▼               ▼         │
                            │  ┌─────────────────────────────┐   │
                            │  │      DEVICE OPERATIONS      │   │
                            │  │  • User Lookup              │   │
                            │  │  • Book Lookup              │   │
                            │  │  • Quick Borrow/Return      │   │
                            │  │  • Device Assignment        │   │
                            │  └─────────────────────────────┘   │
                            └────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┐
│                                                  DATABASE MODELS                                                     │
└─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┘

                    ┌─────────────────────────────────────────────────────────┐
                    │                      CORE MODELS                         │
                    │  ┌─────────┐ ┌─────────┐ ┌─────────┐ ┌──────────┐      │
                    │  │  User   │ │  Book   │ │Category │ │  Author  │      │
                    │  │ Model   │ │ Model   │ │ Model   │ │  Model   │      │
                    │  │-Role Mgmt│ │-Details │ │-Books   │ │-Books    │      │
                    │  │-Auth    │ │-Status  │ │Count    │ │         │      │
                    │  │-Profile │ │-Location│ │        │ │         │      │
                    │  └────┬────┘ └────┬────┘ └────┬────┘ └────┬────┘      │
                    │       │            │            │            │          │
                    │       │            │            │            │          │
                    │       └─────┬──────┼─────┬──────┼────────────┘          │
                    │             │      │     │      │                      │
                    │             ▼      ▼     ▼      ▼                      │
                    │  ┌──────────────────────────────────────────────┐        │
                    │  │              RELATED MODELS                 │        │
                    │  │  ┌─────────────┐ ┌─────────────┐ ┌────────┐ │        │
                    │  │  │  Borrowing  │ │BookReservat │ │System  │ │        │
                    │  │  │   Model     │ │ ion Model   │ │Settings│ │        │
                    │  │  │-Due Date    │ │-Status      │ │ Model  │ │        │
                    │  │  │-Fine Calc   │ │-Queue       │ │-Config │ │        │
                    │  │  │-Status      │ │-User Link   │ │-Values │ │        │
                    │  │  │-History     │ │-Book Link   │ │        │ │        │
                    │  │  └─────────────┘ └─────────────┘ └────────┘ │        │
                    │  └──────────────────────────────────────────────┘        │
                    └─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┐
│                                                  KEY FEATURES                                                       │
└─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┘

                    ┌─────────────────────────────────────────────────────────┐
                    │                    SYSTEM CAPABILITIES                   │
                    │  • Multi-role authentication (Student/Teacher/Admin)    │
                    │  • Real-time book availability tracking                 │
                    │  • Advanced borrowing workflow with confirmation         │
                    │  • Fine calculation and management system                │
                    │  • Book reservation and queue management                 │
                    │  • RFID & Barcode technology integration                 │
                    │  • User profile and data management                      │
                    │  • Admin dashboard with comprehensive controls           │
                    │  • Announcements and system notifications                │
                    │  • Borrowing history and reporting                       │
                    │  • Self-service features (renew, extend)                 │
                    │  • System settings and configuration management          │
                    └─────────────────────────────────────────────────────────┘
```

## System Architecture Overview

### **Technology Stack:**
- **Backend:** Laravel (PHP Framework)
- **Database:** MySQL
- **Frontend:** Bootstrap, Blade Templates, JavaScript
- **Authentication:** Laravel Breeze/Jetstream
- **UI Components:** Bootstrap Icons
- **Additional:** PWA Support, Service Workers

### **Core Features:**
1. **Multi-Role System** - Student, Teacher, Admin roles with different permissions
2. **Book Management** - Complete CRUD operations for books, authors, categories
3. **Borrowing System** - Full lifecycle from borrow to return with fine management
4. **Reservation System** - Queue-based book reservation with status tracking
5. **Admin Tools** - User management, announcements, system settings
6. **Technology Integration** - RFID and Barcode scanning capabilities
7. **Reporting & Analytics** - Borrowing history, reports, fine management

### **Key User Flows:**
1. **Authentication Flow** - Registration → Login → Role-based Dashboard
2. **Student/Teacher Flow** - Browse Books → Borrow/Reserve → View History
3. **Admin Flow** - Manage Users → Manage Books → Handle Borrowings → Configure System
4. **Device Flow** - RFID/Barcode → Quick Lookup → Fast Transaction
