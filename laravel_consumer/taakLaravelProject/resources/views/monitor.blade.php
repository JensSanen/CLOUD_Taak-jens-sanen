<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Werf monitoring</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">
    <div class="container my-5">
        <div class="d-flex justify-content-start mb-4">
            <button class="btn btn-secondary me-3" onclick="window.location.href = '/projects'">Projecten</button>
            <button class="btn btn-secondary me-3" onclick="window.location.href = '/stock'">Voorraad</button>
            <button class="btn btn-secondary me-3" onclick="window.location.href = '/monitoring'">Werf Monitoring</button>
        </div>
        <h1 class="text-center mb-4">Werf monitoring</h1>

        <!-- Grafana iframe -->
        <div class="text-center">
            <iframe
                src="http://localhost:3000/public-dashboards/b1c000738db14f739720ba3897ce465f?orgId=1&from=now-5m&to=now&timezone=browser&refresh=auto"
                width="100%"
                height="1500px"
                frameborder="0">
            </iframe>
        </div>
    </div>
</body>
</html>
