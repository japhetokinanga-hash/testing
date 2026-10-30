<?php
require_once __DIR__ . '/includes/functions.php';
$errors = []; $old = ['name'=>'','email'=>'','role'=>'Farmer','message'=>''];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old = ['name'=>trim($_POST['name'] ?? ''), 'email'=>trim($_POST['email'] ?? ''),
            'role'=>$_POST['role'] ?? 'Other', 'message'=>trim($_POST['message'] ?? '')];
    if (!csrf_ok()) $errors[] = 'Your session expired. Please try again.';
    if (!empty($_POST['website'])) { header('Location: contact.php'); exit; } // honeypot
    if ($old['name'] === '' || mb_strlen($old['name']) > 100) $errors[] = 'Please enter your full name.';
    if (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Please enter a valid email address.';
    if (!in_array($old['role'], ['Farmer','Buyer','Partner or organisation','Other'], true)) $old['role'] = 'Other';
    if ($old['message'] === '' || mb_strlen($old['message']) > 2000) $errors[] = 'Please write a message (up to 2000 characters).';
    if (!$errors) {
        db()->prepare('INSERT INTO contact_messages (full_name,email,role,message) VALUES (?,?,?,?)')
            ->execute([$old['name'],$old['email'],$old['role'],$old['message']]);
        flash('Thank you! We will reply within two working days.');
        header('Location: contact.php'); exit;
    }
}
$flash = flash();
$title = 'Contact Us'; $bodyClass = 'contact'; $current = 'contact.php';
require __DIR__ . '/includes/header.php';
?>
<main>

    <section class="contact-intro">
        <h2>Contact Us</h2>
        <p>
            We'd love to hear from you! Whether you are a farmer with
            a question, a buyer looking for produce, or a partner who
            wants to work with us, get in touch using the details
            below.
        </p>
    </section>

    <section class="contact-grid">

        <div class="contact-card">
            <h3>Reach Us</h3>
            <ul>
                <li><strong>Email:</strong> <a href="mailto:info@shambapulse.com">info@shambapulse.com</a></li>
                <li><strong>Phone:</strong> <a href="tel:+25480557427">+254 805 574 27</a></li>
                <li><strong>WhatsApp:</strong> <a href="https://wa.me/25480557427">+254 805 574 27</a></li>
                <li><strong>Address:</strong> ShambaPulse Ltd, Nairobi, Kenya</li>
            </ul>
        </div>

        <div class="contact-card">
            <h3>Office Hours</h3>
            <ul>
                <li><strong>Monday to Friday:</strong> 8:00 AM to 5:00 PM</li>
                <li><strong>Saturday:</strong> 9:00 AM to 1:00 PM</li>
                <li><strong>Sunday:</strong> Closed</li>
            </ul>
            <p class="note">All times are East Africa Time (EAT).</p>
        </div>

        <div class="contact-card">
            <h3>USSD Help</h3>
            <p>
                No internet? You can reach ShambaPulse services from
                any phone by dialling our USSD code. See the
                <a href="ussd.php">USSD page</a> for the steps.
            </p>
        </div>

    </section>

    <section class="contact-form-section">
        <h2>Send Us a Message</h2>
        <?php if ($flash): ?><p class="notice"><?= e($flash[0]) ?></p><?php endif; ?>
        <?php foreach ($errors as $err): ?><p class="notice error"><?= e($err) ?></p><?php endforeach; ?>
        <form action="contact.php" method="post">
            <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
            <input type="text" name="website" tabindex="-1" autocomplete="off" style="position:absolute;left:-9999px" aria-hidden="true">
            <label for="name">Full name</label>
            <input type="text" id="name" name="name" value="<?= e($old['name']) ?>" required maxlength="100">

            <label for="email">Email address</label>
            <input type="email" id="email" name="email" value="<?= e($old['email']) ?>" required>

            <label for="subject">I am a</label>
            <select id="subject" name="role">
<?php foreach (['Farmer','Buyer','Partner or organisation','Other'] as $r): ?>
                <option<?= $old['role'] === $r ? ' selected' : '' ?>><?= e($r) ?></option>
<?php endforeach; ?>
            </select>

            <label for="message">Message</label>
            <textarea id="message" name="message" rows="5" required maxlength="2000"><?= e($old['message']) ?></textarea>

            <button type="submit" class="hero-button">Send Message</button>
        </form>
    </section>

    <section class="faq">
        <h2>Frequently Asked Questions</h2>

        <details>
            <summary>Is ShambaPulse free to use?</summary>
            <p>Basic alerts and price information are free. Details of any paid features will be shared as they launch.</p>
        </details>

        <details>
            <summary>Do I need a smartphone?</summary>
            <p>No. You can use our USSD service from any mobile phone, with no internet needed.</p>
        </details>

        <details>
            <summary>Which languages do you support?</summary>
            <p>English and Kiswahili.</p>
        </details>

        <details>
            <summary>How fast will you reply?</summary>
            <p>We aim to respond to every message within two working days.</p>
        </details>
    </section>

</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
