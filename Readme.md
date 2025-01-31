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


Om het project te starten, voer het commando `docker-compose up -d` uit in de terminal.
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

6. **DELETE** `/api/workedHours/{whId}/?pwd=admin`  
   ➝ Verwijdert een geboekt uur op basis van het ID (`{whId}`).  
   Dit endpoint is beveiligd met een wachtwoord, dat als queryparameter moet worden meegegeven. Het wachtwoord ingesteld op **"admin"**.  

---

## **Calculatie Service**

De **Calculatie Service** is een **gRPC-service**, ontwikkeld in **Python**, die communiceert met een externe **MariaDB-database**. Er is bewust gekozen voor gRPC om de berekeningen efficiënt uit te voeren. De service draait binnen een **Docker-container** op **poort 30002** en maakt gebruik van gRPC voor snelle en efficiënte communicatie.

### **Functionaliteit**  
Deze service stelt gebruikers in staat om **meetstaatberekeningen** uit te voeren, op te slaan, op te vragen, bij te werken en te verwijderen.

### **gRPC Requests & Responses**  
De service is gebaseerd op een **.proto**-bestand, waarin **vijf verzoeken** (requests) en **twee antwoorden** (responses) zijn gedefinieerd. De volgende vijf gRPC-methoden kunnen worden aangeroepen:

### **Requests**  
1. **CalculatePriceRequest**  
   ➝ Voert een meetstaatberekening uit en slaat deze op in de database.  

2. **GetProjectCalculationsRequest**  
   ➝ Haalt alle meetstaatberekeningen op voor een specifiek project (`projectId`).  

3. **GetCalculationRequest**  
   ➝ Haalt de details op van een specifieke meetstaatberekening (`calculationId`).  

4. **DeleteCalculationRequest**  
   ➝ Verwijdert een meetstaatberekening op basis van het ID (`calculationId`).  

5. **UpdateCalculationRequest**  
   ➝ Werkt een bestaande meetstaatberekening bij met nieuwe gegevens.  

### **Responses**  
1. **ConfirmCalculationResponse**  
   ➝ Bevestigt een uitgevoerde berekening en retourneert het artikel-ID en een beschrijving.  

2. **GetCalculationResponse**  
   ➝ Retourneert alle details van een specifieke meetstaatberekening, inclusief het totaalbedrag.  

### **gRPC Endpoints (RPC-methoden)**  
De **CalculationService** bevat de volgende **5 RPC-methoden**:

1. **CalculateProject (stream CalculatePriceRequest) → (stream ConfirmCalculationResponse)**  
   ➝ Voert berekeningen uit voor een project en slaat de resultaten op in de database.  

2. **GetProjectCalculations (GetProjectCalculationsRequest) → (stream GetCalculationResponse)**  
   ➝ Haalt alle meetstaatberekeningen op voor een project.  

3. **GetCalculation (GetCalculationRequest) → (GetCalculationResponse)**  
   ➝ Haalt een specifieke meetstaatberekening op op basis van het `calculationId`.  

4. **DeleteCalculation (DeleteCalculationRequest) → (ConfirmCalculationResponse)**  
   ➝ Verwijdert een meetstaatberekening en retourneert een bevestiging.  

5. **UpdateCalculation (UpdateCalculationRequest) → (ConfirmCalculationResponse)**  
   ➝ Werkt een meetstaatberekening bij en retourneert een bevestiging.  

Omdat het niet lukte om gRPC-verzoeken direct vanuit een controller in mijn Laravel-project uit te voeren vanwege problemen met het installeren van de gRPC- en Protobuf-extensies, heb ik een **REST API in Python** ontwikkeld als tussenlaag. Deze API biedt endpoints die inkomende verzoeken verwerken, deze doorsturen naar de gRPC-server en de respons terugsturen. Deze service draait in een **Docker-container op poort 30006**.

---

## **Overzicht API**

De **Overzicht API** (genaamd facturatie-api omdat het initiële idee was om een factuur te genereren) is een **SOAP API**, ontwikkeld in **Java**, die informatie retourneert in **XML-formaat**. Er is bewust gekozen voor SOAP omdat deze technologie goed beveiligde informatieoverdracht mogelijk maakt. De service draait in een **Docker-container** op **poort 30004**.

### **Functionaliteit**  
Deze service haalt alle informatie van een project op. De API doet hiervoor een beroep op de bovengenoemde services door via de REST API's een **GET request** te versturen en deze informatie te verwerken. Voor de gRPC-service worden de stubs in Java opnieuw gegenereerd, zodat er direct met gRPC gecommuniceerd kan worden zonder de tussenlaag via REST.

### **Endpoint**  
1. **/api/invoice**  
   ➝ Dit endpoint haalt alle projectinformatie op. Hiervoor dient een XML **GetInvoiceRequest** te worden verstuurd volgens het WSDL-schema met een `projectId`. De service retourneert vervolgens een **XML GetInvoiceResponse**.






