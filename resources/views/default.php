<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Demo page / Демонстрационная страница -->
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width" />
    <meta name="description" content="micro-framework HLEB" />
    <meta name="theme-color" content="#ff786c">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/default.css">
    <script src="js/default.js"></script>
    <title>Task page</title>
</head>

<body>
    <div id="hl-cont" align="center">
        <img class="container" src="/svg/new logo.svg" width="400" height="400" class="hl-block" alt="HL">
        <button type="button" class="btn btn-dark button" onclick=" setTimeUNIX() ">Get UNIX time</button>
        <button type="button" class="btn btn-dark button" onclick=" setTimeMySQL()">Get MySQL time</button>
        <p id="time"></p>
    </div>
    <br>
    <div class="hl-block">v2</div>
</body>

</html>