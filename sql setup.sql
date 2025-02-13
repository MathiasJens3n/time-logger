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

ikke tested off top of head

DELIMITER //
/* Event GET, returns events from the device with that ip*/
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




