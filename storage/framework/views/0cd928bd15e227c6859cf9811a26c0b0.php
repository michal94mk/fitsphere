<?php $__env->startSection('title', 'Formularz kontaktowy - FitSphere'); ?>
<?php $__env->startSection('email-title', '📧 Nowa wiadomość z formularza kontaktowego'); ?>

<?php $__env->startSection('content'); ?>
    <p>Otrzymałeś nową wiadomość z formularza kontaktowego na stronie <strong>FitSphere</strong>.</p>

    <div class="info-box">
        <p><strong>👤 Dane nadawcy:</strong></p>
        <ul>
            <li><strong>Imię:</strong> <?php echo e($contactData['name']); ?></li>
            <li><strong>Email:</strong> <?php echo e($contactData['email']); ?></li>
        </ul>
    </div>

    <div class="highlight-box">
        <p><strong>💬 Treść wiadomości:</strong></p>
        <p><?php echo e($contactData['message']); ?></p>
    </div>

    <div class="text-center">
        <a href="mailto:<?php echo e($contactData['email']); ?>" class="cta-button">📧 Odpowiedz bezpośrednio</a>
    </div>

    <p class="text-muted">Ta wiadomość została wysłana automatycznie z formularza kontaktowego FitSphere.</p>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('emails.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Laravel\fitsphere\resources\views/emails/contact.blade.php ENDPATH**/ ?>