FROM bitnami/laravel:11-debian-12

# Zet de werkdirectory correct
WORKDIR /app/taakLaravelProject

# Kopieer de Laravel-app naar de container
COPY ./taakLaravelProject /app/taakLaravelProject

# Start Laravel server automatisch
ENTRYPOINT ["/bin/sh", "-c", "php artisan serve --host=0.0.0.0"]
