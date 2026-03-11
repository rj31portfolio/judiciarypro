<?php
$pageKey = 'contact';
$seoDefaults = [
    'meta_title' => 'Contact JudiciaryPRO',
    'meta_description' => 'Contact JudiciaryPRO for admissions and guidance.',
];
$bodyClass = 'page page-template';
include __DIR__ . '/includes/header.php';
?>
<section>
    <div class="lgx-banner lgx-banner-inner">
        <div class="lgx-inner">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="lgx-heading-area">
                            <div class="lgx-heading lgx-heading-white">
                                <h2 class="heading-title">Get In Touch</h2>
                            </div>
                            <ul class="breadcrumb">
                                <li><a href="index"><i class="icon-home6"></i>Home</a></li>
                                <li class="active">Contact us</li>
                            </ul>
                        </div>
                    </div>
                </div><!--//.ROW-->
            </div><!-- //.CONTAINER -->
        </div><!-- //.INNER -->
    </div>
</section>
<section class="jp-contact-section">
    <div class="container">
        <div class="jp-contact-head">
            <h1 class="jp-contact-title">Our Branches</h1>
            <p class="jp-contact-subtitle">Find the nearest JudiciaryPRO center and reach out for counselling or admissions support.</p>
        </div>
        <div class="jp-branch-grid">
            <div class="jp-branch-card">
                <div class="jp-branch-map">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3507.5355045642664!2d77.02836107456596!3d28.463415391742025!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390d1835c98db99b%3A0x10ef09db8081083b!2sJudiciaryPRO%20%5BCivil%20Judge%2C%20CLAT%20%26%20LLM%5D!5e0!3m2!1sen!2sin!4v1771587078641!5m2!1sen!2sin" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
                <div class="jp-branch-info">
                    <h3>Gurugram Branch</h3>
                    <p>10/2 New Railway Road, Opp. Bhargava Palace, Gurugram - 122001</p>
                    <ul>
                        <li><strong>Phone:</strong> +91 8447777020</li>
                        <li><strong>Email:</strong> help.judiciarypro@gmail.com</li>
                    </ul>
                </div>
            </div>
            <div class="jp-branch-card">
                <div class="jp-branch-map">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3507.297777144951!2d77.0449235!3d28.4705776!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390d19d9c73a1795%3A0x75c290edc9622924!2sJudiciaryPRO%20(Sector%2014%20-%20CLAT%2FPCSJ)!5e0!3m2!1sen!2sin!4v1772860954759!5m2!1sen!2sin" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
                <div class="jp-branch-info">
                    <h3>Gurugram Branch</h3>
                    <p>Third Floor, C-1, Block M, Old DLF Colony, Sector 14, Gurugram, Haryana 122001</p>
                    <ul>
                        <li><strong>Phone:</strong> +91 9355688886</li>
                        <li><strong>Email:</strong> help.judiciarypro@gmail.com</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="jp-enquiry-section">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-md-8 col-md-offset-2">
                <div class="jp-enquiry-card">
                    <h2>Send an Enquiry</h2>
                    <p>Share your details and our team will get back to you shortly.</p>
                    <form method="POST" class="lgx-contactform" action="assets/php/contact.php">
                        <div class="form-group">
                            <input type="text" name="lgxname" class="form-control lgxname" id="lgxname" placeholder="Enter Your Name" required>
                        </div>
                        <div class="form-group">
                            <input type="email" name="lgxemail" class="form-control lgxemail" id="lgxemail" placeholder="Enter email" required>
                        </div>
                        <div class="form-group">
                            <input type="text" name="lgxsubject" class="form-control lgxsubject" id="lgxsubject" placeholder="Subject" required>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control lgxmessage" name="lgxmessage" id="lgxmessage" rows="5" placeholder="We expect drop some line from you..." required></textarea>
                        </div>
                        <div class="form-group">
                            <label class="lgxsendme-area">
                                <input name="lgxsendme" value="on" type="checkbox"> Copy Me
                            </label>
                        </div>
                        <button type="submit" name="submit" value="contact-form" class="lgx-btn lgx-btn-big hvr-glow hvr-radial-out lgxsend lgx-send"><span>Send Message</span></button>
                    </form>
                    <div id="lgx-form-modal" class="modal fade lgx-form-modal" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content lgx-modal-content">
                                <div class="modal-header lgx-modal-header">
                                    <button type="button" class="close brand-color-hover" data-dismiss="modal" aria-label="Close">
                                        <i class="fa fa-power-off"></i>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="alert lgx-form-msg" role="alert"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>

