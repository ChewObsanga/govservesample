# Caloocan City Social Services - PWD Registration System

A web-based system for managing Persons with Disabilities (PWD) registration and applications for Caloocan City Social Services.

## Features

- **PWD Registration Form**: Multi-page form with document upload functionality
- **User Management**: Resident and admin user accounts
- **Application Tracking**: Status tracking for PWD applications
- **Document Management**: File upload and storage system
- **Responsive Design**: Mobile-friendly interface

## Technology Stack

- **Backend**: PHP 7.4+
- **Database**: MySQL
- **Frontend**: HTML5, CSS3, JavaScript
- **Styling**: Tailwind CSS
- **Server**: XAMPP (Apache + MySQL)

## Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/govservesample.git
   cd govservesample
   ```

2. **Set up XAMPP**
   - Install XAMPP
   - Start Apache and MySQL services
   - Place project in `htdocs` folder

3. **Database Setup**
   - Create a MySQL database
   - Import the database schema
   - Update `config/database.php` with your database credentials

4. **Configuration**
   - Copy `config/database.php.example` to `config/database.php`
   - Update database connection settings

## Usage

1. Access the system through your web browser
2. Register as a resident or admin user
3. Login with your credentials
4. Fill out the PWD registration form
5. Upload required documents
6. Track application status

## File Structure

```
govservesample/
├── admin/              # Admin panel files
├── config/             # Configuration files
├── residents/          # Resident portal files
├── assets/             # CSS, JS, images
├── uploads/            # Uploaded files
└── README.md          # This file
```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Submit a pull request

## License

This project is licensed under the MIT License.

## Support

For support, please contact the development team or create an issue in the repository.
