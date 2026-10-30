<?php
$title = 'Prices'; $bodyClass = 'inner'; $current = 'prices.php';
$tagline = 'Weather alerts, market prices and farm advisories for smallholder farmers and pastoralists in Nyanza, Kenya.';
require __DIR__ . '/includes/header.php';
?>
<main>

    <section class="page-intro">
        <h2>Market prices</h2>
        <p>
            Price information is kept distinct from demand signals.
            Every price on this page shows the commodity, the unit, the
            market and the date, so a farmer can tell what the number
            actually means.
        </p>
    </section>

    <section class="split">
        <div class="content">
            <h2>Latest prices</h2>
            <p>
                Prices come from the ShambaPulse database. Each record keeps its
                unit, market, date and source, so a number is never shown without
                its meaning.
            </p>

            <table>
                <thead>
                    <tr>
                        <th>Commodity</th>
                        <th>Unit</th>
                        <th>Market</th>
                        <th>Date</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
<?php foreach (latest_prices(30) as $p): ?>
                    <tr><td><?= e($p['commodity']) ?></td><td><?= e($p['unit']) ?></td><td><?= e($p['market']) ?></td><td><?= date('d/m/Y', strtotime($p['price_date'])) ?></td><td>Ksh.<?= number_format($p['price_kes']) ?></td></tr>
<?php endforeach; ?>
                </tbody>
            </table>

            <p>
                Prices are shown with their unit, source and date in the
                full system. A price without a unit is not useful, so the
                unit is never left out.
            </p>
        </div>

        <aside class="side">
            <h3>Why the unit matters</h3>
            <p>
                A price for "maize" means nothing until you know whether
                it is per kilogram, per 90 kg bag, or per debe.
                ShambaPulse records the unit with every price, and
                duplicate feed imports are blocked by a stable key so the
                same price is not counted twice.
            </p>
        </aside>
    </section>

    <section class="reading">
        <h2>How to read a price</h2>
        <ul class="card-row">
            <li>
                <h3>Commodity</h3>
                <p>What is being sold, such as maize, beans or milk.</p>
            </li>
            <li>
                <h3>Unit</h3>
                <p>How it is measured: a 90 kg bag, a litre, a debe.</p>
            </li>
            <li>
                <h3>Market</h3>
                <p>Where the price was recorded. Prices differ from market to market.</p>
            </li>
            <li>
                <h3>Date</h3>
                <p>When it was recorded. An old price may no longer hold.</p>
            </li>
        </ul>
    </section>

    <section class="compare">
        <h2>Compare before you sell</h2>
        <ul class="card-row">
            <li>
                <h3>By market</h3>
                <p>Farmers compare prices across nearby markets before deciding where to sell.</p>
            </li>
            <li>
                <h3>By commodity</h3>
                <p>Each commodity has its own unit, so prices are compared like with like.</p>
            </li>
            <li>
                <h3>By date</h3>
                <p>The date beside every price shows how fresh the information is.</p>
            </li>
        </ul>
    </section>

    <section class="steps">
        <h2>Tips before you sell</h2>
        <ol>
            <li>
                <h3>Check the unit</h3>
                <p>Make sure you are comparing the same bag, litre or debe.</p>
            </li>
            <li>
                <h3>Check two markets</h3>
                <p>A short trip can sometimes mean a better price, but count transport costs.</p>
            </li>
            <li>
                <h3>Check the date</h3>
                <p>Use the freshest price you can find before agreeing to sell.</p>
            </li>
        </ol>
    </section>

    <section class="cta">
        <h2>Check prices from any phone</h2>
        <p>Choose "Market prices" in the USSD menu, no internet needed.</p>
        <a href="ussd.php" class="hero-button">See the USSD menu</a>
    </section>

</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
