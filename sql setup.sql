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
);

ikke tested lavet off top of head

/* Checks if the ip is already in network table if not insert itserts it with current time for datetime, SSID, Name and password*/
DELIMITER //

CREATE PROCEDURE AddToNetwork (
    IN p_IP VARCHAR(15),
    IN p_Name VARCHAR(15),
    IN p_SSID VARCHAR(255),
    IN p_Password VARCHAR(255)
)
BEGIN
    DECLARE ip_exists INT;

    -- Check if the IP already exists in the Network table
    SELECT COUNT(*) INTO ip_exists
    FROM Network
    WHERE IP = p_IP;

    -- If the IP does not exist, insert the data
    IF ip_exists = 0 THEN
        INSERT INTO Network (IP, DateAndTime, SSID, DeviceName, Password)
        VALUES (p_IP, NOW(), p_SSID, p_Name, p_Password);
    END IF;
END //

DELIMITER ;

/* Get ssid and password from ip */
DELIMITER //

CREATE PROCEDURE GetNetworkCredentials(
    IN inputIP VARCHAR(15),
    OUT outputSSID VARCHAR(255),
    OUT outputPassword VARCHAR(255)
)
BEGIN
    SELECT SSID, Password
    INTO outputSSID, outputPassword
    FROM Network
    WHERE IP = inputIP;
END //

DELIMITER ;


/* Event GET, returns events from the device with that ip*/
DELIMITER //
    
CREATE PROCEDURE GetEventDetailsByIP(IN inputIP VARCHAR(15))
BEGIN
    SELECT 
        E.Name AS EventName, 
        E.ButtonNumber, 
        E.Status
    FROM 
        Device D
    INNER JOIN 
        Event E ON D.Id = E.DeviceId
    WHERE 
        D.IP = inputIP;
END //

DELIMITER ;

/* Event Post inserts new event if the device exist */
DELIMITER $$

CREATE PROCEDURE InsertEvent(
    IN p_DeviceId INT,
    IN p_ButtonNumber INT,
    IN p_Status BOOL,
    OUT p_Response VARCHAR(255)
)
BEGIN
    DECLARE deviceExists INT;

    -- Check if the device exists in the Device table
    SELECT COUNT(*) INTO deviceExists
    FROM Device
    WHERE Id = p_DeviceId;

    IF deviceExists = 1 THEN
        -- Insert the new event if the device exists
        INSERT INTO Event (DeviceId, Name, ButtonNumber, Status)
        VALUES (p_DeviceId, CONCAT('Event for Device ', p_DeviceId), p_ButtonNumber, p_Status);

        -- Return OK
        SET p_Response = 'OK';
    ELSE
        -- Return Bad Request if the device does not exist
        SET p_Response = 'Bad Request: DeviceId not found';
    END IF;
END$$

DELIMITER ;


/* Update event function, finds the event with the event id and device id and set it to status sent. */
DELIMITER $$

CREATE PROCEDURE UpdateEventStatus (
    IN p_EventId INT, 
    IN p_DeviceId INT, 
    IN p_Status BOOL,
    OUT p_Result VARCHAR(50)
)
BEGIN
    DECLARE v_Exists INT;

    -- Check if the EventId and DeviceId combination exists
    SELECT COUNT(*)
    INTO v_Exists
    FROM Event
    WHERE Id = p_EventId AND DeviceId = p_DeviceId;

    -- If the event exists, update its status
    IF v_Exists > 0 THEN
        UPDATE Event
        SET Status = p_Status
        WHERE Id = p_EventId AND DeviceId = p_DeviceId;

        SET p_Result = 'OK';
    ELSE
        -- If the event does not exist, return an error message
        SET p_Result = 'Bad Request: EventId and DeviceId do not match.';
    END IF;
END$$

DELIMITER ;

/* GET for Time Registration*/
DELIMITER $$

CREATE PROCEDURE GetTimeRegistrationByIP(IN inputIP VARCHAR(15))
BEGIN
    SELECT 
        e.Name AS EventName,
        tr.StartTime,
        tr.EndTime
    FROM 
        TimeRegistration tr
    JOIN 
        Event e ON tr.EventId = e.Id
    JOIN 
        Device d ON tr.DeviceId = d.Id
    WHERE 
        d.IP = inputIP;
END $$

DELIMITER ;

/* POST for Time Registration */
DELIMITER $$

CREATE PROCEDURE InsertTimeRegistration (
    IN p_EventId INT,
    IN p_DeviceId INT,
    IN p_StartTime DATETIME,
    IN p_EndTime DATETIME,
    IN p_Status BOOL
)
BEGIN
    -- Insert the time registration record
    INSERT INTO TimeRegistration (EventId, DeviceId, StartTime, EndTime, Status)
    VALUES (p_EventId, p_DeviceId, p_StartTime, p_EndTime, p_Status);
END $$

DELIMITER ;

/* Update for Time Registration */
DELIMITER $$

CREATE PROCEDURE UpdateTimeRegistration(
    IN p_EventId INT,
    IN p_DeviceId INT,
    IN p_StartTime DATETIME,
    IN p_EndTime DATETIME,
    IN p_Status BOOL
)
BEGIN
    -- Update the TimeRegistration table with the given values
    UPDATE TimeRegistration
    SET StartTime = p_StartTime,
        EndTime = p_EndTime,
        Status = p_Status
    WHERE EventId = p_EventId
      AND DeviceId = p_DeviceId;
END $$
    
DELIMITER ;


