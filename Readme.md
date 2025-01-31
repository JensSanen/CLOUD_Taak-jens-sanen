# **Cloudcomputing Taak - Jens Sanen**

## **Overzicht**  
Dit project is een **platform voor de bouwsector**, ontworpen om aannemers te ondersteunen bij het **beheer van projecten en magazijnvoorraad**. Het systeem biedt verschillende functionaliteiten, waaronder:  

- **Projectbeheer**: Gebruikers kunnen projecten aanmaken, bewerken en verwijderen. Elk project kan worden onderverdeeld in verschillende fasen.  
- **Weersvoorspelling**: Voor elk project wordt de weersvoorspelling voor de komende zes dagen op de locatie weergegeven.  
- **Uurregistratie**: Werknemers kunnen hun gewerkte uren per project registreren.  
- **Calculatie & Meetstaatbeheer**: Per project kan een meetstaat worden beheerd voor nauwkeurige calculaties.  
- **Globaal projectoverzicht**: Een dashboard geeft een totaaloverzicht van alle projecten.  
- **Werfmonitoring**: Real-time monitoring van werven via een dashboard.  
- **Stockbeheer**: Overzicht van alle producten in het magazijn, inclusief informatie over leveranciers.  

Om het project op te starten dient `docker-compose up -d` in de terminal gerunt te worden. 
Het project is een **Laravel-applicatie** die draait in een **Docker-container** op **poort 8000**. Het hoofdscherm is te bereiken via:  
🔗 [http://localhost:8000/projects](http://localhost:8000/projects)

---

## **Technologieën**  
Het platform is opgebouwd uit **verschillende microservices**, waarbij elke service een specifieke programmeertaal, communicatietechniek en data-uitwisselingsformaat gebruikt:  

| **Functionaliteit**               | **Programmeertaal** | **Communicatietechniek** | **Dataformaat**  |
|-----------------------------------|---------------------|--------------------------|------------------|
| **Werfplanning**                  | C#                  | REST                     | JSON             |
| **Uurregistratie**                | Laravel (PHP)       | REST                     | JSON             |
| **Calculatie**                    | Python              | gRPC                     | Protobuf         |
| **Overzicht**                     | Java                | SOAP                     | XML              |
| **Stockbeheer**                   | Node.js             | GraphQL                  | JSON             |
| **Werfmonitoring**                | Python              | MQTT                     | -                |
| **Coördinaten bepalen (extern)**  | -                   | REST                     | JSON             |
| **Weersvoorspelling (extern)**    | -                   | REST                     | JSON             |

---

## **Werfplanning API**  

De **Werfplanning API** is een REST-service, ontwikkeld in **C#**, die verbonden is met een **externe MariaDB-database**.  
Oorspronkelijk werd deze service ontwikkeld in **Python** als proof of concept om de functionaliteit vooraf te testen. 
De API draait in een **Docker-container** op **poort 30007**.

### **Endpoints**  
Deze API bevat **10 endpoints** voor het beheren van projecten en fasen.

### **Projecten**  
1. **GET** `/api/projects/`  
   ➝ Haalt een lijst op van alle projecten.  

2. **GET** `/api/projects/{projectId}/`  
   ➝ Haalt de details op van een specifiek project (`{projectId}`).  

3. **POST** `/api/projects/`  
   ➝ Voegt een nieuw project toe. De projectinformatie wordt als JSON-payload meegestuurd.  

4. **PUT** `/api/projects/{projectId}/`  
   ➝ Werkt een bestaand project bij. De volledige projectinformatie wordt als JSON-payload meegestuurd, inclusief de gewijzigde velden.  

5. **DELETE** `/api/projects/{projectId}/`  
   ➝ Verwijdert een project op basis van `{projectId}`.  

### **Fasen binnen een project**  
6. **GET** `/api/projects/{projectId}/phases/`  
   ➝ Haalt alle fasen op die behoren tot een project (`{projectId}`).  

7. **GET** `/api/projects/{projectId}/phases/{phaseId}/`  
   ➝ Haalt de details op van een specifieke fase (`{phaseId}`) binnen een project (`{projectId}`).  

8. **POST** `/api/projects/{projectId}/phases/`  
   ➝ Voegt een nieuwe fase toe aan een project. De fase-informatie wordt als JSON-payload meegestuurd.  

9. **PUT** `/api/projects/{projectId}/phases/{phaseId}/`  
   ➝ Werkt een bestaande fase bij binnen een project. De volledige fase-informatie wordt als JSON-payload meegestuurd, inclusief de gewijzigde velden.  

10. **DELETE** `/api/projects/{projectId}/phases/{phaseId}/`  
    ➝ Verwijdert een specifieke fase (`{phaseId}`) binnen een project (`{projectId}`).  

---

## **Uurregistratie API**  

De **Uurregistratie API** is een REST-service, ontwikkeld in **Laravel (PHP)**, die verbonden is met een **externe MariaDB-database**.  
Oorspronkelijk werd deze service ontwikkeld in **Python** als proof of concept om de functionaliteit vooraf te testen.  
De API draait in een **Docker-container** op **poort 30008**.  

### **Endpoints**  
Deze API bevat **6 endpoints** voor het beheren van werknemers en hun gewerkte uren binnen projecten.  

### **Werknemers**  
1. **GET** `/api/workers/`  
   ➝ Haalt een lijst op van alle geregistreerde werknemers.  

2. **GET** `/api/workers/{workerId}/`  
   ➝ Haalt de details op van een specifieke werknemer (`{workerId}`).  

### **Gewerkte uren**  
3. **GET** `/api/projects/{projectId}/workedHours/`  
   ➝ Haalt het totaal aantal gewerkte uren op van alle werknemers binnen een project (`{projectId}`).  

4. **GET** `/api/projects/{projectId}/workedHours/{workerId}/`  
   ➝ Haalt alle geboekte uren op van een specifieke werknemer (`{workerId}`) binnen een project (`{projectId}`).  

5. **POST** `/api/projects/{projectId}/workedHours/`  
   ➝ Voegt een nieuw geboekt uur toe aan een project (`{projectId}`). De details van de boeking worden als JSON-payload meegestuurd.  

6. **DELETE** `/api/workedHours/{whId}/`  
   ➝ Verwijdert een geboekt uur op basis van het ID (`{whId}`).  

---




