<?php
/**
 * Template Name: Jobs Template
 */
?>

<?php get_header(); ?>

<section class="page-section hero-wrapper">
    <div class="hero no-image" style="background: #f4f4f4">
        <div class="container">
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
    </div>
</section>

<section class="job-section">
    <div class="container">
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
    </div>
</section>

<script type="text/javascript">
    jQuery(document).ready(function () {
        const data = { action: 'getJobs' };

        jQuery.ajax({
            type: 'POST',
            url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
            dataType: 'json',
            data: data,
            success: function(jobs){
                console.log(jobs);
                let length = jobs.length;

                for(let i=0; i<length; i++){
                    let id = jobs[i].id;
                    let title = jobs[i].title;

                    var tr_str = "<tr>" +
                        "<td align='center'>" + (i+1) + "</td>" +
                        "<td align='center'>" + username + "</td>" +
                        "<td align='center'>" + name + "</td>" +
                        "<td align='center'>" + email + "</td>" +
                        "</tr>";

                    $("#userTable tbody").append(tr_str);
                }
            },
        });
    });
</script>

<?php get_footer(); ?>