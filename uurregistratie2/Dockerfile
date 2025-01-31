FROM bitnami/laravel:11-debian-12

WORKDIR /app/uurregistratieLaravelProject

COPY ./uurregistratieLaravelProject /app/uurregistratieLaravelProject

# Installeer Composer dependencies, inclusief Guzzle
RUN composer install --no-dev --optimize-autoloader && \
    composer require guzzlehttp/guzzle

# Expose de juiste poort
EXPOSE 8000

# Start Laravel server automatisch
ENTRYPOINT ["/bin/sh", "-c", "php artisan serve --host=0.0.0.0"]
