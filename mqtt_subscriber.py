#!/usr/bin/env python3
import paho.mqtt.client as mqtt
import mysql.connector
from datetime import datetime

# --- Tietokantayhteys ---
try:
    db = mysql.connector.connect(
        host="localhost",
        user="example_user",
        password="12345",
        database="example_db"
    )
    cursor = db.cursor()
    print("Tietokantaan yhdistetty")
except Exception as e:
    print("Tietokantavirhe:", e)
    exit(1)

# --- MQTT-callbackit ---
def on_connect(client, userdata, flags, rc):
    if rc == 0:
        print("MQTT-yhteys OK – tilataan topic 'chat'")
        client.subscribe("chat")
    else:
        print(f"MQTT-yhteys epäonnistui (koodi {rc})")

def on_message(client, userdata, msg):
    viesti = msg.payload.decode("utf-8", errors="ignore")
    nyt = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
    print(f"{nyt} → {viesti}")

    try:
        cursor.execute(
            "INSERT INTO chat_messages (timestamp, message) VALUES (%s, %s)",
            (nyt, viesti)
        )
        db.commit()
    except Exception as e:
        print("DB-tallennusvirhe:", e)
        db.rollback()

# --- Käynnistä client ---
client = mqtt.Client(client_id="db_subscriber")
client.on_connect = on_connect
client.on_message = on_message

print("Yhdistetään localhost:1883...")
client.connect("localhost", 1883, keepalive=60)

# Taustasäie hoitaa verkon
client.loop_start()

print("Subscriber käynnissä – viestit tallennetaan tietokantaan")
print("Paina Ctrl+C lopettaaksesi\n")

try:
    while True:
        pass
except KeyboardInterrupt:
    print("\nLopetetaan...")
    client.loop_stop()
    client.disconnect()
    db.close()
