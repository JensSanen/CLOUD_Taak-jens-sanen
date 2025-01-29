FROM bitnami/laravel:11-debian-12

# Zet de werkdirectory correct
WORKDIR /app/uurregistratieLaravelProject

# Kopieer de Laravel-app naar de container
COPY ./uurregistratieLaravelProject /app/uurregistratieLaravelProject

# Start Laravel server automatisch
ENTRYPOINT ["/bin/sh", "-c", "php artisan serve --host=0.0.0.0"]
