FROM bitnami/laravel:11-debian-12

WORKDIR /app/uurregistratieLaravelProject

COPY ./uurregistratieLaravelProject /app/uurregistratieLaravelProject

# Start Laravel server automatisch
ENTRYPOINT ["/bin/sh", "-c", "php artisan serve --host=0.0.0.0"]
