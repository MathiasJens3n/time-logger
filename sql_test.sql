-- Test AddToNetwork Procedure
CALL AddToNetwork('192.168.0.1', 'Device 1', 'SSID123', 'password123');

-- Verify the insertion in Network table
SELECT * FROM Network WHERE IP = '192.168.0.1';


-- Test GetNetworkCredentials Procedure
CALL GetNetworkCredentials('192.168.0.1');

-- Verify the retrieved data
SELECT * FROM Network WHERE IP = '192.168.0.1';


-- Insert a sample device and event first
CALL AddDevice('Device 1', '192.168.0.1', @newDeviceId);
CALL InsertEvent(@newDeviceId, 1, TRUE, @response);

-- Test GetEventDetailsByIP Procedure
CALL GetEventDetailsByIP('192.168.0.1');

-- Verify the event details
SELECT * FROM Event WHERE DeviceId = @newDeviceId;

-- Test InsertEvent Procedure (Valid DeviceId)
CALL InsertEvent(@newDeviceId, 1, TRUE, @response);
SELECT @response;  -- Should output 'OK'

-- Test InsertEvent Procedure (Invalid DeviceId)
CALL InsertEvent(999, 1, TRUE, @response);  -- Assuming DeviceId 999 doesn't exist
SELECT @response;  -- Should output 'Bad Request: DeviceId not found'


-- Test UpdateEventStatus Procedure (Valid EventId and DeviceId)
CALL UpdateEventStatus(1, @newDeviceId, FALSE, @result);
SELECT @result;  -- Should output 'OK'

-- Test UpdateEventStatus Procedure (Invalid EventId and DeviceId combination)
CALL UpdateEventStatus(999, @newDeviceId, FALSE, @result);
SELECT @result;  -- Should output 'Bad Request: EventId and DeviceId do not match.'


-- Insert sample Time Registration
CALL InsertTimeRegistration(1, @newDeviceId, '2025-02-14 09:00:00', '2025-02-14 17:00:00', TRUE);

-- Test GetTimeRegistrationByIP Procedure
CALL GetTimeRegistrationByIP('192.168.0.1');

-- Verify the time registrations
SELECT * FROM TimeRegistration WHERE DeviceId = @newDeviceId;

-- Test InsertTimeRegistration Procedure
CALL InsertTimeRegistration(1, @newDeviceId, '2025-02-14 08:00:00', '2025-02-14 16:00:00', TRUE);

-- Verify the inserted time registration
SELECT * FROM TimeRegistration WHERE DeviceId = @newDeviceId;

-- Test UpdateTimeRegistration Procedure
CALL UpdateTimeRegistration(1, @newDeviceId, '2025-02-14 09:00:00', '2025-02-14 17:30:00', FALSE);

-- Verify the updated time registration
SELECT * FROM TimeRegistration WHERE DeviceId = @newDeviceId;

-- Test AddDevice Procedure
CALL AddDevice('New Device', '192.168.0.2', @newDeviceId);
SELECT @newDeviceId;  -- Should output the ID of the newly inserted device
