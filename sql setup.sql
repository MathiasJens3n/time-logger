-- Create the database
CREATE DATABASE TimeLogger;
-- Use the database
USE TimeLogger;
-- Create the Device table
CREATE TABLE Device (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(255),
    IP VARCHAR(15)
);
-- Create the Event table
CREATE TABLE Event (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    DeviceId INT,
    Name VARCHAR(255),
    ButtonNumber INT,
    Status BOOL,
    FOREIGN KEY (DeviceId) REFERENCES Device(Id)
);
-- Create the TimeRegistration table
CREATE TABLE TimeRegistration (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    EventId INT,
    DeviceId INT,
    StartTime DATETIME,
    EndTime DATETIME,
    Status BOOL,
    FOREIGN KEY (EventId) REFERENCES Event(Id),
    FOREIGN KEY (DeviceId) REFERENCES Device(Id)
);
-- Create the Network table
CREATE TABLE Network (
    IP VARCHAR(15),
    DateAndTime DATETIME,
    SSID VARCHAR(255),
    DeviceName VARCHAR(255),
    Password VARCHAR(255),
    PRIMARY KEY (IP, DateAndTime),
    FOREIGN KEY (IP) REFERENCES Device(IP)
);