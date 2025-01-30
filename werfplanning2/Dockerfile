# Gebruik een officiële .NET runtime als basis
FROM mcr.microsoft.com/dotnet/aspnet:8.0 AS base
WORKDIR /app
EXPOSE 80

# Gebruik de SDK om te bouwen
FROM mcr.microsoft.com/dotnet/sdk:8.0 AS build
WORKDIR /src
COPY ["werfplanning2.csproj", "./"]
RUN dotnet restore "werfplanning2.csproj"
COPY . .
RUN dotnet build -c Release -o /app/build

# Publiceren
FROM build AS publish
RUN dotnet publish -c Release -o /app/publish

# Maak een container met alleen de runtime
FROM base AS final
WORKDIR /app
COPY --from=publish /app/publish .
ENTRYPOINT ["dotnet", "werfplanning2.dll"]
