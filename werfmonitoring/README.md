# Taak Dashboard: Jens Sanen

## Onderwerp

In mijn taak heb ik een dashboard ontwikkeld dat een fictieve werfsituatie simuleert in Grafana. De data wordt gegenereerd door het Python-script `generator_taak` en via het MQTT-protocol doorgestuurd naar een InfluxDB-database, waar deze wordt opgeslagen.

In deze fictieve werksituatie zijn er vier arbeiders (`workers`) en twee graafmachines (`machines`). De arbeiders dragen een smartwatch die is uitgerust met een decibelsensor, een hartslagmeter en een gps-tracker. Daarnaast bevat de smartwatch twee actuatoren: één voor decibelwaarschuwingen en één voor hartslagwaarschuwingen. Deze actuatoren simuleren waarschuwingsberichten die de arbeider ontvangt wanneer bepaalde drempelwaarden worden overschreden.

De graafmachines zijn voorzien van een gps-tracker en een volumesensor die het brandstofniveau bewaakt. Als het brandstofvolume onder de 10 liter komt, schakelt de machine zichzelf uit en wordt de brandstof bijgetankt. Daarnaast schakelt een graafmachine automatisch uit wanneer een arbeider te dicht in de buurt komt.

## Generator File

De generator file is opgebouwd uit verschillende compacte klassen die zowel de sensoren en actuatoren als de smartwatches, arbeiders, machines en de logica voor het simuleren van gebeurtenissen op de werf representeren.  

- **Sensor-klassen**: Klein van opzet, met functies om meetwaarden te updaten en uit te lezen.  
- **Actuator-klassen**: Bevatten functies om de status naar "On" of "Off" te schakelen.  
- **Overige klassen**: Deze bevatten wrapper-functies voor de sensoren en actuatoren, samen met extra logica om te bepalen hoe een actuator moet reageren op een specifieke meetwaarde.

## MQTT

Alle meetwaarden, samen met hun metadata, worden gepubliceerd op het topic `construction_site`. Dit topic is hiërarchisch opgedeeld als volgt:  
`construction_site/site/device/device_id/type/component_id/meetwaarde`

De betekenis van de componenten:  
- `site`: De naam van de werf.  
- `device`: Geeft aan of het een smartwatch of machine betreft.  
- `device_id`: Het unieke ID van het device.  
- `type`: Bepaalt of het een sensor of actuator betreft.  
- `component_id`: Het unieke ID van de sensor of actuator.  
- `meetwaarde`: De meetwaarde die wordt gepubliceerd.

## Grafana Dashboard

Het Grafana-dashboard bevat zeven panelen die verschillende aspecten van de werfsimulatie visualiseren:  

1. **Decibelsensoren**: Toont de meetwaarden van de decibelsensoren in de tijd, inclusief drempelwaarden aangegeven met een rode stippellijn. Groene en rode zones geven aan of de corresponderende actuator uit of aan staat.  
2. **Hartslagmeters**: Geeft de hartslagmetingen weer, eveneens met drempelwaarden en kleurzones voor de status van de actuator.  

De eerste twee panelen bieden inzicht in de meetwaarden van de sensoren, terwijl:  
3. **Decibelactuatoren** en 4. **Hartslagactuatoren** de status van de actuatoren in de tijd visualiseren. Samen tonen deze vier panelen hoe sensormeetwaarden evolueren en hoe actuatoren daarop reageren.  

5. **GPS-trackers**: Geeft de laatste bekende posities van zowel arbeiders als machines weer. Dit paneel visualiseert ook gevarenzones rond de machines, waarbij een grotere radius wordt gebruikt om aan te geven wanneer een arbeider te dicht in de buurt komt.  

6. **Brandstofniveaus**: Laat de actuele brandstofinhoud van elke machine zien.  
7. **Machinestatus**: Geeft aan of machines in de tijd worden uitgeschakeld of bijgetankt.  

Met deze panelen biedt het dashboard een compleet overzicht van de simulatie, inclusief sensordata, actuatorreacties, locatie-informatie en machinestatussen.