# **Cloudcomputing Taak - Jens Sanen**

## **Overzicht**  
Dit project is een platform voor de **bouwsector**, ontworpen om aannemers te helpen bij het **beheer van projecten en magazijnvoorraad**. Het systeem biedt diverse functionaliteiten, waaronder:  

- **Projectbeheer**: Gebruikers kunnen projecten aanmaken, aanpassen en verwijderen. Elk project kan worden onderverdeeld in verschillende fases.  
- **Weersvoorspelling**: Voor elk project wordt de **weersvoorspelling** voor de komende zes dagen op de locatie weergegeven.  
- **Uurregistratie**: De gewerkte uren van werknemers kunnen per project worden bijgehouden.  
- **Calculatie & Meetstaatbeheer**: Per project kan een meetstaat worden beheerd voor nauwkeurige calculaties.  
- **Globaal projectoverzicht**: Een totaaloverzicht van alle projecten is beschikbaar.  
- **Werfmonitoring**: Een dashboard maakt het mogelijk om werven **op afstand** te monitoren.  

## **Technologieën**  
Het platform is opgebouwd uit **verschillende microservices**, elk met hun eigen programmeertaal, communicatietechniek en data-uitwisselingsformaat:  

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

## **Conclusie**  
Dit platform biedt een **geïntegreerde oplossing** voor de bouwsector, waarbij projectbeheer, calculatie en monitoring worden gecombineerd. Door gebruik te maken van **diverse technologieën en communicatiemethoden** is het systeem flexibel en efficiënt in de verwerking van gegevens.
