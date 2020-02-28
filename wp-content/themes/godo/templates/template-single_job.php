<?php
/**
 * Template Name: Single job Template
 */
?>

<?php get_header(); ?>

<?php
    global $job;
    $job = getJob($_GET['id'])[0];
    $description = $job['publicDescription'];

    $startBenefits = explode('<strong>Benefits', $description);
    $endBenefits = preg_split('@(?<=</ul>)@', $startBenefits[1]);
    $benefits = $endBenefits[0];

    $startNotes = preg_split('@(?<=<strong>Notes)@', $description);
    $notes = $startNotes[1];

    $desc = $startBenefits[0];

    $category = getJobCategory($job);
    $recruiter = getRecruiterByName($job['owner']);
    $recruiterText = get_post_meta( $recruiter->ID, '__listingDesc', true );
    $recruiterPhone = get_post_meta( $recruiter->ID, '__phone', true );
    $recruiterEmail = get_post_meta( $recruiter->ID, '__email', true );
    $recruiterImage =wp_get_attachment_image_src( get_post_thumbnail_id( $recruiter->ID ))[0];
    $recruiterSubtitle = get_post_meta( $recruiter->ID, '__subtitle', true );
    $intPhone = preg_replace('/(\+)|(\(0\))|\s/','',$recruiterPhone);

    $whatsappText = "Hallo " . $recruiter->post_title . ", ik zou graag wat meer willen weten over de vacature " . $job['title'];
    if($job['address']['address1']) {
        $whatsappText .= " in " . $job['address']['address1'];
    }
?>

<?php
    include(locate_template('templates/parts/popup-apply.php'));
?>

    <div class="bar-wrapper row">
        <div class="container row">
            <a class="button primary dark open-apply">Ik wil deze baan!</a><div class="share-dropdown">
                <a class="button primary dark open-share top">Deze vacature delen</a>
                <ul class="shareButtons top">
                    <li><a href="#" onClick="MyWindow=window.open('http://www.linkedin.com/shareArticle?mini=true&url='+ window.location.href,'LinkedInWindow','width=600,height=300'); return false;"><img src="<?php bloginfo('template_directory'); ?>/assets/images/icons/socials/linkedin.svg"> LinkedIn</a></li>
                    <li><a href="#" onclick="javascript:window.location='mailto:?body=' + window.location;"><img src="<?php bloginfo('template_directory'); ?>/assets/images/icons/socials/email.svg"> Email</a></li>
                    <li><a href="#" onClick="MyWindow=window.open('https://wa.me/?text='+ window.location.href,'LinkedInWindow','width=600,height=300'); return false;"> <img src="<?php bloginfo('template_directory'); ?>/assets/images/icons/socials/whatsapp.svg"> WhatsApp</a></li>
                    <li><a href="#" onClick="MyWindow=window.open('https://telegram.me/share/url?url='+ window.location.href,'LinkedInWindow','width=600,height=300'); return false;"><img src="<?php bloginfo('template_directory'); ?>/assets/images/icons/socials/telegram.svg"> Telegram</a></li>
                    <li><a href="#" onClick="MyWindow=window.open('https://www.facebook.com/sharer/sharer.php?u=' + window.location.href,'FacebookWindow','width=600,height=300'); return false;"><img src="<?php bloginfo('template_directory'); ?>/assets/images/icons/socials/facebook.svg"> Facebook</a></li>
                    <li><a href="#" onClick="MyWindow=window.open('https://twitter.com/intent/tweet?text=' + window.location.href,'TwitterWindow','width=600,height=300'); return false;"><img src="<?php bloginfo('template_directory'); ?>/assets/images/icons/socials/twitter.svg"> Twitter</a></li>
                </ul>
            </div>
        </div>
    </div>

    <section class="page-section hero-wrapper job-header">
        <div class="hero" style="background: #f4f4f4 url('<?php bloginfo('template_directory'); ?>/assets/images/heros/jobs/job_<?php echo $category; ?>.jpg') no-repeat center center; background-size: cover;"></div>
        <div class="container row">
            <div class="hero-title">
                <h3><?php echo $job['title']; ?></h3>

                <div class="location">
                    <?php if($job['address']['address1']) { ?>
                            <img src="<?php bloginfo('template_directory'); ?>/assets/images/icons/map-pin-white.svg">
                            <?php echo $job['address']['address1']; ?>
                    <?php } ?>
                </div>

                <div class="row">
                    <a class="button primary open-apply">Solliciteren</a>
                    <div class="share-dropdown">
                        <a class="button primary open-share">Delen</a>
                        <ul class="shareButtons bottom">
                            <li><a href="#" onClick="MyWindow=window.open('http://www.linkedin.com/shareArticle?mini=true&url='+ window.location.href,'LinkedInWindow','width=600,height=300'); return false;"><img src="<?php bloginfo('template_directory'); ?>/assets/images/icons/socials/linkedin.svg"> LinkedIn</a></li>
                            <li><a href="#" onclick="javascript:window.location='mailto:?body=' + window.location;"><img src="<?php bloginfo('template_directory'); ?>/assets/images/icons/socials/email.svg"> Email</a></li>
                            <li><a href="#" onClick="MyWindow=window.open('https://wa.me/?text='+ window.location.href,'LinkedInWindow','width=600,height=300'); return false;"> <img src="<?php bloginfo('template_directory'); ?>/assets/images/icons/socials/whatsapp.svg"> WhatsApp</a></li>
                            <li><a href="#" onClick="MyWindow=window.open('https://telegram.me/share/url?url='+ window.location.href,'LinkedInWindow','width=600,height=300'); return false;"><img src="<?php bloginfo('template_directory'); ?>/assets/images/icons/socials/telegram.svg"> Telegram</a></li>
                            <li><a href="#" onClick="MyWindow=window.open('https://www.facebook.com/sharer/sharer.php?u=' + window.location.href,'FacebookWindow','width=600,height=300'); return false;"><img src="<?php bloginfo('template_directory'); ?>/assets/images/icons/socials/facebook.svg"> Facebook</a></li>
                            <li><a href="#" onClick="MyWindow=window.open('https://twitter.com/intent/tweet?text=' + window.location.href,'TwitterWindow','width=600,height=300'); return false;"><img src="<?php bloginfo('template_directory'); ?>/assets/images/icons/socials/twitter.svg"> Twitter</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="page-section" style="padding: 0;">
        <div class="container">
            <?php echo $desc; ?>
        </div>
    </section>

    <section class="page-section gray">
        <div class="container">
            <h2>Wat moet je kunnen</h2>
            <div class="tags">
                <?php foreach($job['skills']['data'] as $key => $skill) {
                    ?>
                    <div class="tag"><?php echo $skill['name']; ?></div>
                <?php } ?>
            </div>
        </div>
    </section>

    <section class="page-section">
        <div class="container">
            <h2>Dit krijg je er voor terug</h2>
            <?php echo $benefits; ?>
        </div>
    </section>

    <section class="page-section dark-gray" style="color: #fff; padding: 24px 0;">
        <div class="container">
            <?php echo $notes; ?>
        </div>
    </section>

    <section class="page-section gray" id="recruiter">
        <div class="container">
            <h2>Nog vragen?</h2>
            <div class="row" style="justify-content: space-between">
                <div class="block image"><img src="<?php echo $recruiterImage; ?>"/></div>
                <div class="content">
                    <h4><?php echo $recruiter->post_title; ?></h4>
                    <?php echo $recruiterSubtitle ?>
                    <p><?php echo $recruiterText; ?></p>
                </div>
            </div>

            <div class="row buttons">
                <a href="tel:<?php echo $recruiterPhone ?>" class="button primary dark"><?php echo $recruiterPhone; ?></a>
                <a href="mailto:<?php echo $recruiterEmail ?>" class="button primary dark"><?php echo $recruiterEmail; ?></a>
                <a href="#" onClick="MyWindow=window.open('https://wa.me/<?php echo $intPhone ?>?text=<?php echo $whatsappText ?>','LinkedInWindow','width=600,height=300'); return false;" class="button primary dark">Stuur mij een appje</a>
            </div>
        </div>
    </section>

    <script type="text/javascript">
        jQuery(document).ready(function () {
            jQuery('.page-footer-wrapper').css('bottom', '56px');
        });

        jQuery('.open-share').click(function (e) {
            jQuery('.shareButtons').hide();

            if(jQuery(this).hasClass('top')) {
                jQuery('.shareButtons.top').toggle();
            } else {
                jQuery('.shareButtons.bottom').toggle();
            }
        });

        jQuery(document).mouseup(function (e){
            const wrapper = jQuery(".open-share");

            if (!wrapper.is(e.target) && wrapper.has(e.target).length === 0) {
                jQuery('.shareButtons').hide();
            }
        });
    </script>

<?php get_footer(); ?>