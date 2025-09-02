-- PWD Applications Table
CREATE TABLE IF NOT EXISTS pwd_applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    resident_id INT NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    middle_name VARCHAR(100) NOT NULL,
    birth_date DATE NOT NULL,
    birth_place VARCHAR(255) NOT NULL,
    gender ENUM('Male', 'Female') NOT NULL,
    civil_status ENUM('Single', 'Married', 'Widowed', 'Divorced', 'Separated') NOT NULL,
    nationality VARCHAR(50) NOT NULL,
    religion VARCHAR(100) NOT NULL,
    educational_attainment VARCHAR(100) NOT NULL,
    occupation VARCHAR(100) NOT NULL,
    monthly_income VARCHAR(50) NOT NULL,
    disability_type VARCHAR(100) NOT NULL,
    disability_cause VARCHAR(50) NOT NULL,
    disability_date DATE NOT NULL,
    address TEXT NOT NULL,
    barangay VARCHAR(100) NOT NULL,
    city VARCHAR(100) NOT NULL,
    province VARCHAR(100) NOT NULL,
    zip_code VARCHAR(10) NOT NULL,
    contact_number VARCHAR(20) NOT NULL,
    emergency_contact_name VARCHAR(100) NOT NULL,
    emergency_contact_relationship VARCHAR(50) NOT NULL,
    emergency_contact_number VARCHAR(20) NOT NULL,
    emergency_contact_address TEXT NOT NULL,
    status ENUM('pending', 'approved', 'rejected', 'processing') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (resident_id) REFERENCES residents(id) ON DELETE CASCADE
);

-- Index for better performance
CREATE INDEX idx_pwd_applications_resident_id ON pwd_applications(resident_id);
CREATE INDEX idx_pwd_applications_status ON pwd_applications(status);
CREATE INDEX idx_pwd_applications_created_at ON pwd_applications(created_at);
