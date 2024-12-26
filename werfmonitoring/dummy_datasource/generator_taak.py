import paho.mqtt.client as mqtt
import json
import time
import random
import math

# MQTT Broker Configuration
MQTT_BROKER = "mosquitto"
MQTT_PORT = 1883


# MQTT Client
class MQTTClient:
    def __init__(self, broker, port):
        self.client = mqtt.Client()
        self.client.on_connect = self.on_connect
        self.client.connect(broker, port, 60)

    def on_connect(self, client, userdata, flags, rc):
        print(f"Connected to MQTT broker with code {rc}")

    def publish(self, topic, payload):
        self.client.publish(topic, json.dumps(payload))

    def loop(self):
        self.client.loop_start()

# Decibel Sensor
class DecibelSensor:
    def __init__(self, sensor_id, initial_decibel=50, min_value=40, max_value=120.0):
        self.sensor_id = sensor_id
        self.decibel = initial_decibel
        self.min_value = min_value
        self.max_value = max_value

    def measure(self):
        noise = random.uniform(-0.5, 0.5)
        return max(self.min_value, min(self.max_value, self.decibel + noise))

    def update_decibel(self, delta):
        delta = random.uniform(-delta,  delta)
        self.decibel = max(self.min_value, min(self.max_value, self.decibel + delta))

# Heart Rate Sensor
class HeartRateSensor:
    def __init__(self, sensor_id, initial_heart_rate=80.0, min_value=40.0, max_value=200.0):
        self.sensor_id = sensor_id
        self.heart_rate = initial_heart_rate
        self.min_value = min_value
        self.max_value = max_value

    def measure(self):
        noise = random.uniform(-1, 1)
        return max(self.min_value, min(self.max_value, self.heart_rate + noise))

    def update_heart_rate(self, delta):
        delta = random.uniform(-delta, delta)
        self.heart_rate = max(self.min_value, min(self.max_value, self.heart_rate + delta))

# GPS Tracker
class GPSTracker:
    # Latitude and Longitude are in the range [0, 10]
    # Latitude and Longitude do not map to real-world coordinates or scale
    def __init__(self, sensor_id, latitude_min=0, longitude_min=0, latitude_max=5, longitude_max=5):
        self.sensor_id = sensor_id
        self.latitude_min = latitude_min
        self.longitude_min = longitude_min
        self.latitude_max = latitude_max
        self.longitude_max = longitude_max
        self.latitude = random.uniform(latitude_min, latitude_max)
        self.longitude = random.uniform(longitude_min, longitude_max)

    def get_location(self):
        return self.latitude, self.longitude

    def update_location(self, delta):
        latitude_step = random.uniform(-delta, delta)
        longitude_step = random.uniform(-delta, delta)
        self.latitude = max(self.latitude_min, min(self.latitude_max, self.latitude + latitude_step))
        self.longitude = max(self.longitude_min, min(self.longitude_max, self.longitude + longitude_step))

# Volume Sensor
class VolumeSensor:
    def __init__(self, sensor_id, initial_volume=65.0, min_value=0.0, max_value=80.0):
        self.sensor_id = sensor_id
        self.volume = initial_volume
        self.min_value = min_value
        self.max_value = max_value

    def measure(self):
        noise = random.uniform(-0.1, 0.1)
        return max(self.min_value, min(self.max_value, self.volume + noise))

    def decrease_volume(self):
        if self.volume > self.min_value:
            delta = random.uniform(0.2, 0.7)
            self.volume = max(self.min_value, min(self.max_value, self.volume - delta))

    def increase_volume(self):
        if self.volume < self.max_value:
            delta = random.uniform(1.0, 3.0)
            self.volume = max(self.min_value, min(self.max_value, self.volume + delta))

# Decibel Actuator
class DecibelActuator():
    def __init__(self, actuator_id, state="Off"):
        self.actuator_id = actuator_id
        self.state = state

    def turn_on(self):
        self.state = "On"

    def turn_off(self):
        self.state = "Off"

# Heart Rate Actuator
class HeartRateActuator():
    def __init__(self, actuator_id, state="Off"):
        self.actuator_id = actuator_id
        self.state = state

    def turn_on(self):
        self.state = "On"

    def turn_off(self):
        self.state = "Off"

# Smart Watch Actuator
class SmartWatch:
    def __init__(self, smartwatch_id, initial_decibel, initial_heart_rate, decibel_threshold=85.0, heart_rate_threshold=150.0):
        self.smartwatch_id = smartwatch_id
        self.decibel_threshold = decibel_threshold
        self.heart_rate_threshold = heart_rate_threshold
        self.decibel_sensor = DecibelSensor(sensor_id=f'{smartwatch_id}_decibel_sensor', initial_decibel=initial_decibel)
        self.decibel_actuator = DecibelActuator(actuator_id=f'{smartwatch_id}_decibel_actuator')
        self.heart_rate_sensor = HeartRateSensor(sensor_id=f'{smartwatch_id}_heart_rate_sensor', initial_heart_rate=initial_heart_rate)
        self.heart_rate_actuator = HeartRateActuator(actuator_id=f'{smartwatch_id}_heart_rate_actuator')
        self.gps_tracker = GPSTracker(sensor_id=f'{smartwatch_id}_GPS_tracker')

    def update_decibels(self, delta):
        self.decibel_sensor.update_decibel(delta)

    def measure_decibels(self):
        return self.decibel_sensor.measure()

    def update_heart_rate(self, delta):
        self.heart_rate_sensor.update_heart_rate(delta)

    def measure_heart_rate(self):
        return self.heart_rate_sensor.measure()

    def update_location(self, delta=0.30):
        self.gps_tracker.update_location(delta)

    def get_location(self):
        return self.gps_tracker.get_location()

    def monitor_decibels(self):
        current_decibel = self.measure_decibels()

        if current_decibel >= self.decibel_threshold:
            self.decibel_actuator.turn_on()
        else:
            self.decibel_actuator.turn_off()

    def monitor_heart_rate(self):
        current_heart_rate = self.measure_heart_rate()

        if current_heart_rate >= self.heart_rate_threshold:
            self.heart_rate_actuator.turn_on()
        else:
            self.heart_rate_actuator.turn_off()

# Worker class
class Worker:
    def __init__(self, worker_id, initial_decibel, initial_heart_rate):
        self.worker_id = worker_id
        self.smartwatch = SmartWatch(
            smartwatch_id=f"{worker_id}_smartwatch",
            initial_decibel=initial_decibel,
            initial_heart_rate=initial_heart_rate)

    def update_decibels(self, delta):
        self.smartwatch.update_decibels(delta)

    def update_heart_rate(self, delta):
        self.smartwatch.update_heart_rate(delta)

    def update_location(self):
        self.smartwatch.update_location()

    def monitor_decibels(self):
        self.smartwatch.monitor_decibels()

    def monitor_heart_rate(self):
        self.smartwatch.monitor_heart_rate()

# Machine class
class Machine:
    def __init__(self, machine_id, init_volume=65.0, volume_threshold=10.0):
        self.machine_id = machine_id
        self.volume_threshold = volume_threshold
        self.gps_tracker = GPSTracker(sensor_id=f'{machine_id}_GPS_tracker')
        self.volume_sensor = VolumeSensor(sensor_id=f'{machine_id}_volume_sensor', initial_volume=init_volume)
        self.state = "On"
        self.fueling = False

    def update_volume(self):
        if self.state == "On":
            self.volume_sensor.decrease_volume()

        if self.fueling:
            self.volume_sensor.increase_volume()

    def monitor_volume(self):
        current_volume = self.volume_sensor.measure()

        if current_volume <= self.volume_threshold:
            self.turn_off()
            self.fueling = True

        if self.fueling and current_volume >= self.volume_sensor.max_value - 5:
            self.fueling = False
            self.turn_on()

    def update_location(self, delta=0.50):
        if not self.fueling:
            self.gps_tracker.update_location(delta)

    def get_location(self):
        return self.gps_tracker.get_location()

    def turn_on(self):
        if not self.fueling:
            self.state = "On"

    def turn_off(self):
        self.state = "Off"


# Construction Site Logic
class ConstructionSiteLogic:
    def __init__(self, workers, machines):
        self.workers = workers
        self.machines = machines
        self.max_distance = 1

    def calculate_distance(self, worker_location, machine_location):
        worker_latitude, worker_longitude = worker_location
        machine_latitude, machine_longitude = machine_location
        # Use Pythagoras to find the distance between the worker and the machine
        return math.sqrt((machine_latitude - worker_latitude)**2 + (machine_longitude - worker_longitude)**2) 

    def process(self):
        for worker in self.workers:
            worker.smartwatch.monitor_decibels()
            worker.smartwatch.monitor_heart_rate()

        for machine in self.machines:
            machine.monitor_volume()

            for worker in self.workers:
                distance = self.calculate_distance(worker.smartwatch.get_location(), machine.get_location())

                if distance <= self.max_distance:
                    machine.turn_off()
                    break # Stop the loop so other workers don't turn it on again
                else:
                    machine.turn_on()

        self.__simulate_physics_step()
    
    def __simulate_physics_step(self):
        for worker in self.workers:
            worker.update_decibels(4)
            worker.update_heart_rate(5)
            worker.update_location()

        for machine in self.machines:
            machine.update_location()
            machine.update_volume()


# Main Simulation Loop
def simulation_loop():
    # Initialize components
    worker1 = Worker(worker_id="worker_1", initial_decibel=50.0, initial_heart_rate=80.0)
    worker2 = Worker(worker_id="worker_2", initial_decibel=90, initial_heart_rate=60)
    worker3 = Worker(worker_id="worker_3", initial_decibel=70, initial_heart_rate=160)
    worker4 = Worker(worker_id="worker_4", initial_decibel=100, initial_heart_rate=120)
    workers = [worker1, worker2, worker3, worker4]

    machines1 = Machine(machine_id="machine_1", init_volume=65.0, volume_threshold=10.0)
    machines2 = Machine(machine_id="machine_2", init_volume=40, volume_threshold=10.0)
    machines = [machines1, machines2]

    csl = ConstructionSiteLogic(workers, machines)

    mqtt_client = MQTTClient(MQTT_BROKER, MQTT_PORT)
    mqtt_client.loop()

    # Simulation loop
    while True:
        site = "Diepenbeek"

        csl.process()

        for worker in workers:
            smartwatch = worker.smartwatch
            decibel_sensor = smartwatch.decibel_sensor
            heart_rate_sensor = smartwatch.heart_rate_sensor
            gps_tracker = smartwatch.gps_tracker

            mqtt_client.publish(
                f"construction_site/{site}/"
                f"smartwatch/{smartwatch.smartwatch_id}/"
                f"sensor/{decibel_sensor.sensor_id}/"
                "decibel_value",
                {
                    "value": decibel_sensor.measure(),
                    "unit": "dB",
                }
            )

            mqtt_client.publish(
                f"construction_site/{site}/"
                f"smartwatch/{smartwatch.smartwatch_id}/"
                f"sensor/{heart_rate_sensor.sensor_id}/"
                "heart_rate", 
                {
                    "value": heart_rate_sensor.measure(),
                    "unit": "bpm",
                }
            )

            mqtt_client.publish(
                f"construction_site/{site}/"
                f"smartwatch/{smartwatch.smartwatch_id}/"
                f"sensor/{gps_tracker.sensor_id}/"
                "location",
                {
                    "latitude": gps_tracker.get_location()[0],
                    "longitude": gps_tracker.get_location()[1],
                }
            )

            decibel_actuator = smartwatch.decibel_actuator
            heart_rate_actuator = smartwatch.heart_rate_actuator

            mqtt_client.publish(
                f"construction_site/{site}/"
                f"smartwatch/{smartwatch.smartwatch_id}/"
                f"actuator/{decibel_actuator.actuator_id}/status",
                {
                    "state": decibel_actuator.state,
                }
            )

            mqtt_client.publish(
                f"construction_site/{site}/"
                f"smartwatch/{smartwatch.smartwatch_id}/"
                f"actuator/{heart_rate_actuator.actuator_id}/status",
                {
                    "state": heart_rate_actuator.state,
                }
            )

        # Publish data for each machine
        for machine in machines:
            volume_sensor = machine.volume_sensor
            gps_tracker = machine.gps_tracker

            mqtt_client.publish(
                f"construction_site/{site}/"
                f"machine/{machine.machine_id}/"
                f"sensor/{volume_sensor.sensor_id}/volume",
                {
                    "value": volume_sensor.measure(),
                    "unit": "L",
                }
            )

            mqtt_client.publish(
                f"construction_site/{site}/"
                f"machine/{machine.machine_id}/"
                f"sensor/{gps_tracker.sensor_id}/location",
                {
                    "latitude": gps_tracker.get_location()[0],
                    "longitude": gps_tracker.get_location()[1],
                }
            )

            mqtt_client.publish(
                f"construction_site/{site}/"
                f"machine/{machine.machine_id}/"
                f"actuator/{machine.machine_id}/status",
                {
                    "state": machine.state,
                    "fueling": machine.fueling,
                }
            )

        # Wait 1 second
        time.sleep(1)

if __name__ == "__main__":
    simulation_loop()
