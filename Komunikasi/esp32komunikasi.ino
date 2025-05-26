#define RX2 16       // GPIO16 sebagai RX2 (RS485 input)
#define TX2 17       // GPIO17 sebagai TX2 (meski tidak dipakai, harus didefinisikan)
#define DE_RE_PIN 4  // Pin DE/RE (LOW = Receive, HIGH = Transmit)


// Global array untuk simpan hasil template
uint8_t packet2[128], packet3[128], packet4[128], packet5[128], packet6[128], packet7[128];

const int MAX_PAKET = 128;

void setup() {
  pinMode(DE_RE_PIN, OUTPUT);
  digitalWrite(DE_RE_PIN, LOW);  // Set ke mode terima

  Serial.begin(115200);  // Buat lihat di Serial Monitor
  Serial2.begin(115200, SERIAL_8N1, RX2, TX2);  // UART2, untuk RS485

  Serial.println("ESP32 siap menerima data RS485 via Serial2...");
}

void loop() {
  static uint8_t buffer[MAX_PAKET];
  static int index = 0;

  while (Serial2.available()) {
    uint8_t byteData = Serial2.read();
    if (index < MAX_PAKET) {
      buffer[index++] = byteData;
    }
    delayMicroseconds(500);  // Delay antar byte (tweak kalau perlu)
  }

  if (index > 0) {
    Serial.println("Data diterima:");
    Serial.print("uint8_t data[] = { ");

    for (int i = 0; i < index; i++) {
      Serial.print("0x");
      if (buffer[i] < 0x10) Serial.print("0");
      Serial.print(buffer[i], HEX);
      if (i < index - 1) Serial.print(", ");
      if ((i + 1) % 16 == 0 && i < index - 1) Serial.print("\n                    ");
    }

    Serial.println(" };");
    Serial.println();

    index = 0;  // Reset buffer
  }
}
