<?php
/**
 * Template Name: Single job test Template
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
?>

<?php include(locate_template('templates/parts/popup-apply.php')); ?>

    <div class="bar-wrapper row">
        <div class="container row">
            <a class="button primary dark open-apply">Ik wil deze baan!</a>
            <a class="button primary dark open-alarm">Deze vacature delen</a>
        </div>
    </div>

    <section class="page-section hero-wrapper job-header">
        <div class="hero" style="background: #f4f4f4 url('<?php bloginfo('template_directory'); ?>/assets/images/heros/jobs/job_<?php echo $category; ?>.jpg') no-repeat center center; background-size: cover;"></div>
        <div class="container row">
            <div class="hero-title">
                <h3><?php echo $job['title']; ?></h3>
                <div class="location">
                    <img src="<?php bloginfo('template_directory'); ?>/assets/images/icons/map-pin-white.svg">
                    <?php echo $job['address']['address1']; ?>
                </div>
                <div class="row">
                    <a class="button primary open-apply">Solliciteren</a>
                    <a class="button primary open-alarm">Delen</a>
                </div>
            </div>
        </div>
    </section>

    <section class="page-section">
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

    <section class="page-section dark-gray" style="color: #fff;">
        <div class="container">
            <?php echo $notes; ?>
        </div>
    </section>

    <section class="page-section gray">
        <div class="container">
            <div class="row" style="justify-content: space-between">
                <div class="block" style="flex: 0 0 50%;">
                    <h2>Nog vragen?</h2>
                    <h4><?php echo $recruiter->post_title; ?></h4>
                    <span><?php echo $recruiterSubtitle ?></span>
                </div>

                <div class="block image" style="flex: 0 0 45%;"><img src="<?php echo $recruiterImage; ?>"/></div>
            </div>
            <p><?php echo $recruiterText; ?></p>

            <div class="row" style="flex-direction: column;">
                <a href="tel:<?php echo $recruiterPhone ?>" class="button primary dark"><?php echo $recruiterPhone; ?></a>
                <a href="mailto:<?php echo $recruiterEmail ?>" class="button primary dark" style="margin: 15px 0;"><?php echo $recruiterEmail; ?></a>
                <a class="button primary dark">Stuur mij een appje</a>
            </div>
        </div>
    </section>

    <section class="page-section">
        <div class="container"><h2>Meer vacatures zoals deze</h2></div>
        <div class="job">
            <img src="<?php bloginfo('template_directory'); ?>/assets/images/icons/icon_design.svg" class="icon">
            <div class="job-details">
                <h3>Digital product designer</h3>
                <div class="tags">
                    <div class="tag design">UI</div>
                    <div class="tag design">UX</div>
                    <div class="tag design">Concepting</div>
                </div>
                <div class="location">
                    <img src="<?php bloginfo('template_directory'); ?>/assets/images/icons/map-pin.svg">
                    Amersfoort
                </div>
            </div>
        </div>
    </section>

    <script type="text/javascript">
        jQuery(document).ready(function () {
            jQuery('.page-footer-wrapper').css('bottom', '56px');
        });
    </script>

<?php get_footer(); ?>