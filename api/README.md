# Organization Relations API

A PHP-based project to manage organizations and their relations (parent, sister, and child). 
This project provides a REST API to retrieve and manage organization data.

## 🚀 Getting Started

### Prerequisites

Before running the project, ensure you have the following installed and configured:

- **Apache 2.4**
- **PHP 5.6**
- **MySQL 5.7**

### 📂 Project Structure

/api  
├── index.php                  // Entry point  
├── db.php                     // Database connection  
├── controllers/               // API controllers  
│   ├── OrganizationController.php  
│   ├── RelationController.php  
└── models/                    // Models for data handling  
    ├── Organization.php  
    ├── Relation.php  


## ⚙️ Setup Instructions

### 1. Database Setup

Create the database in MySQL:
```sql
CREATE DATABASE organizations;
```

Create the organizations table:
```sql
CREATE TABLE organizations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) UNIQUE NOT NULL
);
```

Create the relations table:
```sql
CREATE TABLE relations (
    parent_id INT NOT NULL,
    child_id INT NOT NULL,
    FOREIGN KEY (parent_id) REFERENCES organizations(id),
    FOREIGN KEY (child_id) REFERENCES organizations(id),
    UNIQUE (parent_id, child_id)
);
```

### 2. Project Configuration

- Copy the `api` folder to the server's `www` directory (or any directory configured for your server).  
- Open the `db.php` file and update the database settings to match your MySQL configuration.

### 3. Access the API

- By default, the project will be accessible at:  
  `http://localhost/api/index.php`

- If your server is configured differently, update the `switch` statement in `index.php` to match your setup.


## 📋 API Endpoints

### 1. Get Relations

Retrieve the relations (parent, sister, child) of an organization.

- **Endpoint**:  
  `GET http://localhost/api/index.php/relations/`

- **Parameters**:
  - **Required**:  
    `org_name` – Name of the organization.
  - **Optional**:  
    `page` – Desired page number (default is 1).
    `page_size` – Number of records per page (default is 100, maximum is 100).

- **Example**:
  ```http
  GET http://localhost/api/index.php/relations?org_name=Organization1&page=1&page_size=50
  ```

### 2. Add Organizations

Add a new organization to the database.

- **Endpoint**:  
  `POST http://localhost/api/index.php/organizations/`

- **Example Payload**:
  ```json
  {
    "org_name": "Paradise Island",
    "daughters": [
        {
            "org_name": "Banana tree",
            "daughters": [
                {
                    "org_name": "Yellow Banana"
                },
                {
                    "org_name": "Brown Banana"
                },
                {
                    "org_name": "Black Banana"
                }
            ]
        },
        {
            "org_name": "Big banana tree",
            "daughters": [
                {
                    "org_name": "Yellow Banana"
                },
                {
                    "org_name": "Brown Banana"
                },
                {
                    "org_name": "Green Banana"
                },
                {
                    "org_name": "Black Banana",
                    "daughters": [
                        {
                            "org_name": "Phoneutria Spider"
                        }
                    ]
                }
            ]
        }
    ]
  }
  ```

## 🛠 Created With

This project was developed and tested using **EASYPHP DEVSERVER 17.0**.

### HTTP Server
- Apache 2.4.25 x86
- PHP 5.6.30 x86
- Port: 80

### Database Server
- MySQL 5.7.17 x86
- Port: 3306

---

## 💡 Notes
- Default pagination supports up to **100 records per page**.
- Customize the project and server settings as needed for your environment.
