<?php
/**
 * Template Name: Jobs Template
 */
?>

<?php get_header(); ?>
    <?php get_template_part( 'templates/parts/popup', 'alarm' ); ?>
    <?php get_template_part( 'templates/parts/popup', 'helper' ); ?>

    <div class="bar-wrapper row">
        <div class="container row">
            Geen vacature meer missen?
            <a class="button primary dark open-alarm">Alarm instellen</a>
        </div>
    </div>

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

        <div class="job">
            <img src="<?php bloginfo('template_directory'); ?>/assets/images/icons/icon_agile.svg" class="icon">
            <div class="job-details">
                <h3>Digital Project Manager</h3>
                <div class="tags">
                    <div class="tag agile">Agile</div>
                    <div class="tag dev">Software development</div>
                </div>
                <div class="location">
                    <img src="<?php bloginfo('template_directory'); ?>/assets/images/icons/map-pin.svg">
                    Hilversum
                </div>
            </div>
        </div>

        <div class="job">
            <img src="<?php bloginfo('template_directory'); ?>/assets/images/icons/icon_coding.svg" class="icon">
            <div class="job-details">
                <h3>Front-end developer in Den Bosch</h3>
                <div class="tags">
                    <div class="tag dev">Angular</div>
                    <div class="tag dev">Gulp</div>
                    <div class="tag dev">Webpack</div>
                    <div class="tag agile">Scrum</div>
                </div>
                <div class="location">
                    <img src="<?php bloginfo('template_directory'); ?>/assets/images/icons/map-pin.svg">
                    's-Hertogenbosch
                </div>
            </div>
        </div>

        <div class="job">
            <img src="<?php bloginfo('template_directory'); ?>/assets/images/icons/icon_coding.svg" class="icon">
            <div class="job-details">
                <h3>Full Stack Developer (PHP)</h3>
                <div class="tags">
                    <div class="tag dev">Symfony</div>
                    <div class="tag dev">MySQL</div>
                    <div class="tag dev">jQuery</div>
                    <div class="tag agile">Scrum</div>
                </div>
                <div class="location">
                    <img src="<?php bloginfo('template_directory'); ?>/assets/images/icons/map-pin.svg">
                    Amsterdam
                </div>
            </div>
        </div>

        <div class="job-helper row">
            <span>Hulp nodig bij het vinden van een passende vacature?</span>
            <a class="button primary dark open-helper">Help mij!</a>
        </div>

        <div class="job">
            <img src="<?php bloginfo('template_directory'); ?>/assets/images/icons/icon_infra.svg" class="icon">
            <div class="job-details">
                <h3>Linux Engineer Consultant</h3>
                <div class="tags">
                    <div class="tag infra">Kubernetes</div>
                    <div class="tag dev">Python</div>
                    <div class="tag dev">Ruby</div>
                    <div class="tag dev">Erlang</div>
                </div>
                <div class="location">
                    <img src="<?php bloginfo('template_directory'); ?>/assets/images/icons/map-pin.svg">
                    Amsterdam
                </div>
            </div>
        </div>

        <div class="job">
            <img src="<?php bloginfo('template_directory'); ?>/assets/images/icons/icon_agile.svg" class="icon">
            <div class="job-details">
                <h3>Product Owner Noun</h3>
                <div class="tags">
                    <div class="tag infra">Scrum</div>
                    <div class="tag">Detachering</div>
                </div>
                <div class="location">
                    <img src="<?php bloginfo('template_directory'); ?>/assets/images/icons/map-pin.svg">
                    Amsterdam
                </div>
            </div>
        </div>

        <a href="/vacature">
            <div class="job">
                <img src="<?php bloginfo('template_directory'); ?>/assets/images/icons/icon_design.svg" class="icon">
                <div class="job-details">
                    <h3>Senior UX/UI Designer</h3>
                    <div class="tags">
                        <div class="tag design">UX</div>
                        <div class="tag design">UI</div>
                        <div class="tag design">Workshops</div>
                        <div class="tag">Agency</div>
                    </div>
                    <div class="location">
                        <img src="<?php bloginfo('template_directory'); ?>/assets/images/icons/map-pin.svg">
                        Amsterdam
                    </div>
                </div>
            </div>
        </a>

        <div class="job-helper large">
            <div>
                <h3>Geen passende vacature kunnen vinden?</h3>
                <a class="button primary dark open-helper">Help mij met zoeken!</a>
            </div>
        </div>
    </section>

    <script type="text/javascript">
        jQuery(document).ready(function () {
            jQuery('.page-footer-wrapper').css('bottom', '56px');
        });
    </script>

<?php get_footer(); ?>