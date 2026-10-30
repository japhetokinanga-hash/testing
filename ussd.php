<?php
$title = 'USSD and SMS'; $bodyClass = 'inner'; $current = 'ussd.php';
$tagline = 'Weather alerts, market prices and farm advisories for smallholder farmers and pastoralists in Nyanza, Kenya.';
require __DIR__ . '/includes/header.php';
?>
<main>

    <section class="page-intro">
        <h2>USSD and SMS: for a basic phone</h2>
        <p>
            A farmer does not need a smartphone to use ShambaPulse. The
            USSD menu works in English and Kiswahili, and alerts arrive
            as ordinary SMS messages.
        </p>
    </section>

    <section class="split">
        <div class="content">
            <h2>The five-option menu</h2>
            <p>The menu offers five choices, in both languages:</p>
            <ol>
                <li>Weather forecast</li>
                <li>Market prices</li>
                <li>Advisories</li>
                <li>Subscribe to alerts</li>
                <li>Opt out of alerts</li>
            </ol>
            <p>
                Registration happens through option 4, Subscribe to
                alerts: a new farmer chooses a ward and a language, then
                answers the Data Protection Act 2019 consent prompt. If
                consent is declined, nothing is stored. A farmer's home
                ward is the default, and further wards can be added.
            </p>
            <p>
                Simulated SMS in a demonstration is a demo outcome, not a
                fallback for a failed delivery. In the live system, a
                failed delivery is recorded as failed, not hidden.
            </p>
        </div>

        <aside class="side">
            <h3>Why USSD</h3>
            <ul>
                <li>Works on a basic phone</li>
                <li>No internet needed</li>
                <li>English and Kiswahili menus</li>
                <li>Subscribe and opt out in the same menu</li>
            </ul>
        </aside>
    </section>

    <section class="phone-demo">
        <h2>What the menu looks like</h2>
        <p class="lead">A sample of the menu screen in both languages.</p>
        <div class="phone-row">
            <div class="phone">
                <span class="phone-label">English</span>
<pre>ShambaPulse
1. Weather forecast
2. Market prices
3. Advisories
4. Subscribe to alerts
5. Opt out of alerts</pre>
            </div>
            <div class="phone">
                <span class="phone-label">Kiswahili</span>
<pre>ShambaPulse
1. Hali ya hewa
2. Bei za soko
3. Ushauri
4. Jisajili kupata tahadhari
5. Acha kupokea tahadhari</pre>
            </div>
        </div>
    </section>

    <section class="steps">
        <h2>How to subscribe</h2>
        <ol>
            <li>
<h3>Dial the code (<?= e(USSD_CODE) ?>)</h3>
                <p>Dial the ShambaPulse USSD code (<?= e(USSD_CODE) ?>) from any phone.</p>
            </li>
            <li>
                <h3>Choose option 4</h3>
                <p>Select "Subscribe to alerts" from the menu.</p>
            </li>
            <li>
                <h3>Pick ward and language</h3>
                <p>Choose your home county and whether you want English or Kiswahili.</p>
            </li>
            <li>
                <h3>Give consent</h3>
                <p>Answer the Data Protection Act 2019 consent prompt. If you decline, nothing is stored.</p>
            </li>
        </ol>
    </section>

    <section class="uses">
        <h2>What you can do</h2>
        <ul class="card-row">
            <li>
                <h3>Weather</h3>
                <p>Look up the forecast for a ward, in English or Kiswahili.</p>
            </li>
            <li>
                <h3>Check</h3>
                <p>Look up prices and advisories without waiting for an alert, and subscribe through option 4 with consent recorded.</p>
            </li>
            <li>
                <h3>Control</h3>
                <p>Subscribe to a ward, or opt out, at any time from the same menu.</p>
            </li>
        </ul>
    </section>

    <section class="faq">
        <h2>Common questions</h2>

        <details>
            <summary>Does it cost money to use USSD?</summary>
            <p>Charges depend on your network and the final service setup. This answer will be confirmed when the service goes live.</p>
        </details>

        <details>
            <summary>What if I change my mind about consent?</summary>
            <p>Choose option 5, "Opt out of alerts", from the same menu at any time.</p>
        </details>

        <details>
            <summary>Can I follow more than one ward?</summary>
            <p>Yes. Your home ward is the default, and further wards can be added.</p>
        </details>
    </section>

    <section class="cta">
        <h2>Need help getting started?</h2>
        <p>Our team can walk you through the menu.</p>
        <a href="contact.php" class="hero-button">Contact Us</a>
    </section>

</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
