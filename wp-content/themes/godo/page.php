<?php
/**
 * Template Name: Jobs Template
 */
?>

<?php get_header(); ?>

<section class="page-section hero-wrapper">
    <div class="hero no-image" style="background: #f4f4f4">
        <h1>Onze vacatures</h1>
        <form id="filter" class="row">
            <select name="position">
                <option value="all" selected>Alle functies</option>
                <option value="back-end developer">Back-end developer</option>
                <option value="front-end developer">Front-end developer</option>
                <option value="ux designer">Ux Designer</option>
                <option value="visual designer">Visual Designer</option>
            </select>

            <select name="location">
                <option value="all" selected>Alle locaties</option>
                <option value="amersfoort">Amersfoort</option>
                <option value="amsterdam">Amsterdam</option>
                <option value="rotterdam">Rotterdam</option>
                <option value="amsterdam">Utrecht</option>
            </select>
        </form>
    </div>
</section>

<section class="job-section">
    <?php
    $jobs = getJobs();

    foreach ($jobs as $job) {
        $category = getJobCategory($job);

        ?>
        <a href="/vacature?id=<?php echo $job['id']; ?>"><div class="job">
                <img src="<?php bloginfo('template_directory'); ?>/assets/images/icons/icon_<?php echo $category; ?>.svg" class="icon">
                <div class="job-details">
                    <h3><?php echo $job['title'] ?></h3>
                    <div class="tags">
                        <?php foreach($job['skills']['data'] as $key => $skill) {
                            if($key > 6 || $key > count($job['skills']['data'])) { break; }
                            ?>
                            <div class="tag <?php echo $category ?>"><?php echo $skill['name']; ?></div>
                        <?php } ?>
                    </div>
                    <div class="location">
                        <img src="<?php bloginfo('template_directory'); ?>/assets/images/icons/map-pin.svg">
                        <?php echo $job['address']['address1'] ?>
                    </div>
                </div>
            </div></a>
        <?php
    }
    ?>
</section>

<?php get_footer(); ?>