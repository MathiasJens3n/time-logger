#include <WiFi.h>

// WiFi credentials
const char* ssid = "";
const char* password = "";

// Button configuration
#define DEBOUNCE_TIME 200
#define TIME_WINDOW 3000

const int buttons[] = {13, 12, 14, 27};
const int numButtons = sizeof(buttons) / sizeof(buttons[0]);

unsigned long lastPressTime = 0;
unsigned long lastDebounceTime = 0;
int sequence[20];
int sequenceIndex = 0;

void setup() {
    Serial.begin(115200);
    for (int i = 0; i < numButtons; i++) {
        pinMode(buttons[i], INPUT_PULLUP);
    }

    connectToWiFi();
}

void loop() {
    if (WiFi.status() != WL_CONNECTED) {
        Serial.println("⚠️ WiFi Disconnected! Reconnecting...");
        connectToWiFi();
        delay(5000);
        return;
    }

    checkButtons();
    
    if (sequenceIndex > 0 && millis() - lastPressTime > TIME_WINDOW) {
        evaluateSequence();
    }
}

void checkButtons() {
    unsigned long currentTime = millis();
    if (currentTime - lastDebounceTime < DEBOUNCE_TIME) return;

    for (int i = 0; i < numButtons; i++) {
        if (digitalRead(buttons[i]) == LOW) {
            recordButton(i + 1, currentTime);
        }
    }
}

void recordButton(int buttonNumber, unsigned long currentTime) {
    if (sequenceIndex < 20) sequence[sequenceIndex++] = buttonNumber;
    lastPressTime = lastDebounceTime = currentTime;
}

void evaluateSequence() {
    if (sequenceIndex == 1) {
        Serial.print("Button ");
        Serial.print(sequence[0]);
        Serial.println(" pressed!");
    } else {
        Serial.println("Multiple buttons pressed in sequence! Executing special action...");
    }

    sequenceIndex = 0;
}

// 📶 WiFi Connection Function
void connectToWiFi() {
    Serial.print("🔄 Connecting to WiFi...");
    WiFi.begin(ssid, password);

    int attempts = 0;
    while (WiFi.status() != WL_CONNECTED && attempts < 15) {
        delay(1000);
        Serial.print(".");
        attempts++;
    }

    if (WiFi.status() == WL_CONNECTED) {
        Serial.println("\n✅ WiFi Connected!");
        Serial.print("📡 IP Address: ");
        Serial.println(WiFi.localIP());
    } else {
        Serial.println("\n❌ Failed to connect. Retrying in 5 seconds...");
    }
}

