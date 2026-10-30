# ShambaPulse

PHP website + admin dashboard, Java USSD/SMS gateway, one MySQL database.

```
database/schema.sql   tables + seed data (counties, wards, prices, sample alerts)
web/                  PHP site (your pages) + admin/ dashboard   -> port 8080
gateway/              Java 17 service: USSD menu, SMS sending, delivery reports -> port 8081
```

How they fit together: the PHP dashboard saves an alert as **queued**; the Java dispatcher
(checks every 10 s) sends it to consented, subscribed farmers in that county, and records each
as **submitted**. The SMS provider's delivery report (POST /sms/delivery) later flips it to
**delivered** or **failed**. If no farmer is eligible the alert returns to **draft**.
Farmers register only through USSD option 4; a row is written only after they consent.

## 1. Database
    mysql -u root -p < database/schema.sql

## 2. Website (PHP 8.1+ with pdo_mysql)
Put your images in `web/img/` (`logo.jpg`, `bcd.jpg`), then:

    export SP_DB_USER=root SP_DB_PASS=yourpassword
    php web/tools/create_admin.php officer "a-strong-password"
    php -S localhost:8080 -t web

Open http://localhost:8080 . Officer login: http://localhost:8080/admin/login.php
(For production use Apache/Nginx + PHP-FPM, HTTPS, and a dedicated DB user instead of root.)

## 3. Java gateway (JDK 17 + Maven)
    cd gateway && mvn package
    export SP_DB_USER=root SP_DB_PASS=yourpassword
    java -jar target/shambapulse-gateway.jar

Test the USSD menu without a phone:

    curl -d "phoneNumber=+254700000001&text=" localhost:8081/ussd
    curl -d "phoneNumber=+254700000001&text=4*1*1*1*1" localhost:8081/ussd   # subscribe (Kisii, Kitutu Central, English, consent)

Without Africa's Talking credentials SMS is **simulated** (printed to the console, stays "submitted").
For real SMS set `AT_USERNAME` and `AT_API_KEY`, then in the Africa's Talking dashboard point the
USSD callback to `https://<your-host>/ussd` and delivery reports to `https://<your-host>/sms/delivery`.
Set your assigned shortcode on the site with `SP_USSD_CODE` (default `*384#`).

## Not built yet
Adding extra wards per farmer, filtering delivery totals by ward, and a live price-feed importer
(the unique key on `prices` is ready for one).
