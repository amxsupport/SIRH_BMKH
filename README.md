# SIRH BMKH (Système d'Information des Ressources Humaines)

A comprehensive Human Resources Information System designed for BMKH (Béni Mellal-Khénifra Health Region).

## Overview

SIRH BMKH is a web-based application that manages all aspects of human resources for healthcare institutions, including employee management, leave tracking, intern supervision, and retirement planning.

## Features

- **Employee Management**
  - Personal and professional information tracking
  - Document generation (attestations, reports)
  - Retirement notifications
  - Statistical analysis and reporting

- **Leave Management**
  - Multiple leave types (annual, sick, maternity, etc.)
  - Leave balance tracking
  - Automated holiday calendar
  - Multi-level approval workflow
  - Document attachment support

- **Intern Management**
  - Intern registration and tracking
  - Educational institution partnerships
  - Supervisor assignment
  - Progress monitoring

- **User Management**
  - Role-based access control (Admin, User, Approver)
  - Secure authentication
  - Password reset functionality
  - Activity logging

## Technical Requirements

- PHP 8.2 or higher
- MySQL/MariaDB 10.4 or higher
- XAMPP/Apache web server
- Modern web browser

## Installation

1. Clone the repository:
   ```bash
   git clone git@github.com:amxsupport/SIRH_BMKH.git
   ```

2. Set up the database:
   - Import the latest SQL file from the database directory
   - Configure database connection in config/database.php

3. Configure web server:
   - Point your web server to the project directory
   - Ensure proper permissions are set

4. Default admin credentials:
   - Username: admin
   - Email: admin@example.com
   - Password: password (change upon first login)

## Project Structure

```
SIRH_BMKH/
├── assets/          # Static resources (CSS, JS, images)
├── config/          # Configuration files
├── controllers/     # Application controllers
├── database/        # Database migrations and seeds
├── lib/            # Third-party libraries
├── logs/           # Application logs
├── models/         # Data models
├── scripts/        # Utility scripts
└── views/          # View templates
```

## Key Technologies

- PHP (Backend)
- MySQL (Database)
- JavaScript (Frontend interactivity)
- TCPDF (PDF generation)
- Bootstrap (UI framework)

## Configuration

1. Database Settings (config/database.php):
   ```php
   $db_config = [
       'host' => 'localhost',
       'dbname' => 'sirh_bmkh',
       'username' => 'root',
       'password' => ''
   ];
   ```

2. Application Settings:
   - Configure email settings for notifications
   - Set up document upload directories
   - Configure PDF generation settings

## Security Features

- Password hashing
- Session management
- SQL injection prevention
- XSS protection
- CSRF protection
- Role-based access control

## Reporting Features

- Employee statistics
- Leave management reports
- Intern progress tracking
- Custom report generation
- Export to Excel/PDF

## Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a new Pull Request

## License

Copyright © 2025 SIRH. All rights reserved.

## Support

For support and inquiries, please contact the development team or create an issue in the repository.
