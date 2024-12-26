# Gebruik de officiële Eclipse Temurin 23 image
FROM eclipse-temurin:23-jdk-alpine

# Installeer Maven
RUN apk add --no-cache maven

# Stel de werkdirectory in
WORKDIR /app

# Kopieer de pom.xml en download de afhankelijkheden
COPY pom.xml .
RUN mvn dependency:go-offline -B

# Kopieer de broncode naar de container
COPY src ./src

# Compileer en bouw de applicatie
RUN mvn clean package -DskipTests

# Exposeer de poort waarop de applicatie draait
EXPOSE 8080

# Start de Spring Boot-applicatie
ENTRYPOINT ["java", "-jar", "/app/target/facturatie-0.0.1-SNAPSHOT.jar"]
