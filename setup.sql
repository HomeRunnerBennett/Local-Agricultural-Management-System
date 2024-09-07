-- Create the database
CREATE DATABASE IF NOT EXISTS LAMS;
USE LAMS;

-- Create the primary tables first (those without foreign keys)
CREATE TABLE IF NOT EXISTS Farmer (
    farmer_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    age INT,
    gender ENUM('Male', 'Female', 'Other'),
    location VARCHAR(255),
    farm_size DECIMAL(10, 2),
    mobile_phone VARCHAR(15)
);

CREATE TABLE IF NOT EXISTS ExtensionOfficer (
    officer_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    area_of_responsibility VARCHAR(255),
    contact_info VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS Administrator (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    contact_info VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS Crop (
    crop_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    planting_season VARCHAR(50),
    harvesting_season VARCHAR(50)
);

CREATE TABLE IF NOT EXISTS WeatherForecast (
    forecast_id INT AUTO_INCREMENT PRIMARY KEY,
    date DATE NOT NULL,
    temperature DECIMAL(5, 2),
    precipitation DECIMAL(5, 2),
    wind_speed DECIMAL(5, 2),
    humidity DECIMAL(5, 2)
);

CREATE TABLE IF NOT EXISTS UserAccount (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('Farmer', 'ExtensionOfficer', 'Administrator') NOT NULL,
    admin_id INT,
    FOREIGN KEY (admin_id) REFERENCES Administrator(admin_id)
);

-- Now create tables that reference other tables
CREATE TABLE IF NOT EXISTS MarketPrice (
    price_id INT AUTO_INCREMENT PRIMARY KEY,
    crop_id INT,
    date DATE NOT NULL,
    price_per_unit DECIMAL(10, 2),
    FOREIGN KEY (crop_id) REFERENCES Crop(crop_id)
);

CREATE TABLE IF NOT EXISTS FarmActivity (
    activity_id INT AUTO_INCREMENT PRIMARY KEY,
    description TEXT NOT NULL,
    activity_date DATE,
    weather_forecast_id INT,
    FOREIGN KEY (weather_forecast_id) REFERENCES WeatherForecast(forecast_id)
);

CREATE TABLE IF NOT EXISTS HarvestData (
    harvest_id INT AUTO_INCREMENT PRIMARY KEY,
    crop_id INT,
    farmer_id INT,
    harvest_date DATE NOT NULL,
    quantity DECIMAL(10, 2),
    quality VARCHAR(50),
    FOREIGN KEY (crop_id) REFERENCES Crop(crop_id),
    FOREIGN KEY (farmer_id) REFERENCES Farmer(farmer_id)
);

CREATE TABLE IF NOT EXISTS Communication (
    communication_id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT,
    receiver_id INT,
    message TEXT NOT NULL,
    date_sent TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES UserAccount(user_id),
    FOREIGN KEY (receiver_id) REFERENCES UserAccount(user_id)
);

CREATE TABLE IF NOT EXISTS FarmIssue (
    issue_id INT AUTO_INCREMENT PRIMARY KEY,
    farmer_id INT,
    activity_id INT,
    extension_officer_id INT,
    issue_description TEXT NOT NULL,
    issue_date DATE NOT NULL,
    FOREIGN KEY (farmer_id) REFERENCES Farmer(farmer_id),
    FOREIGN KEY (activity_id) REFERENCES FarmActivity(activity_id),
    FOREIGN KEY (extension_officer_id) REFERENCES ExtensionOfficer(officer_id)
);

CREATE TABLE IF NOT EXISTS PestManagement (
    pest_id INT AUTO_INCREMENT PRIMARY KEY,
    pest_name VARCHAR(100) NOT NULL,
    control_methods TEXT
);
