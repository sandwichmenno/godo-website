<?php get_header(); ?>

<section class="page-section hero-wrapper">
    <div class="hero" style="background: #f4f4f4 url('<?php bloginfo('template_directory'); ?>/assets/images/heros/hero_front.jpg') no-repeat center center; background-size: cover;"></div>
    <div class="container row">
        <div class="hero-title"><h1>Work. Set. Match</h1></div>
        <p class="hero-subtitle">We helpen mensen en bedrijven groeien door het maken van de juiste match</p>
        <a href="/over-godo" class="button primary">Meer over GoDo</a>
        <a href="/" class="button primary">Neem contact op</a>
    </div>
</section>

<section class="page-section gray">
    <div class="container row">
        <div class="block intro">
            <h2>Vind jouw volgende stap</h2>
            <p>We zetten ons netwerk in om je te matchen met een job die past bij jouw volgende stap. We willen veel van je weten, zodat we je op het goede moment kunnen introduceren bij interessante opdrachtgevers, waar we exclusief voor werken. Want van een goede match wordt iedereen beter.</p>
        </div>
    </div>

    <div class="job-categories slider">
        <div class="slider-wrapper">
            <div class="slider-item">
                <div class="block">
                    <img src="<?php bloginfo('template_directory'); ?>/assets/images/icons/icon_agile.svg" class="icon"/>
                    <h2>Agile Jobs</h2>
                </div>
            </div>

            <div class="slider-item">
                <div class="block">
                    <img src="<?php bloginfo('template_directory'); ?>/assets/images/icons/icon_design.svg" class="icon"/>
                    <h2>Design Jobs</h2>
                </div>
            </div>


            <div class="slider-item">
                <div class="block">
                    <img src="<?php bloginfo('template_directory'); ?>/assets/images/icons/icon_coding.svg" class="icon"/>
                    <h2>Development Jobs</h2>
                </div>
            </div>

            <div class="slider-item">
                <div class="block">
                    <img src="<?php bloginfo('template_directory'); ?>/assets/images/icons/icon_infra.svg" class="icon"/>
                    <h2>Infra Jobs</h2>
                </div>
            </div>


            <div class="slider-item">
                <div class="block">
                    <img src="<?php bloginfo('template_directory'); ?>/assets/images/icons/icon_misc1.svg" class="icon"/>
                    <h2>Overige Jobs</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="container more-jobs">
        <a href="/" class="button primary dark">Bekijk alle vacatures</a>
    </div>
</section>

<section class="page-section">
    <div class="block image">
        <img src="<?php bloginfo('template_directory'); ?>/assets/images/temp/hello.jpg"/>
        <div class="block-content row">
            <h2>We zijn matchmakers die nauw samenwerken met onze opdrachtgevers. </h2>
            <p>Door actief contact te houden met ons grote netwerk vinden we snel de juiste kandidaat voor je. Mensen met ervaring die toe zijn aan een volgende stap. Aan stalken doen we niet, wel aan hard werken en doorzetten.</p>
            <a href="/" class="button secondary">Neem contact op</a>
        </div>
    </div>
</section>

<section class="page-section">
    <div class="showcase slider">
        <div class="slider-wrapper">
            <div class="slider-item">
                <div class="block image">
                    <img src="<?php bloginfo('template_directory'); ?>/assets/images/temp/office1.jpg"/>
                </div>
            </div>

            <div class="slider-item">
                <div class="block image">
                    <img src="<?php bloginfo('template_directory'); ?>/assets/images/temp/kerst_2018_1.jpg"/>
                </div>
            </div>


            <div class="slider-item">
                <div class="block image">
                    <img src="<?php bloginfo('template_directory'); ?>/assets/images/temp/kerst_2018_2.jpg"/>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    jQuery('.job-categories .slider-wrapper').slick({
        dots: true,
        infinite: true,
        arrows: false,
        speed: 300,
        slidesToShow: 5,
        slidesToScroll: 5,
        adaptiveWidth: true,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 4,
                    slidesToScroll: 4
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            }
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
        ]
    });

    jQuery('.showcase .slider-wrapper').slick({
        dots: true,
        infinite: true,
        arrows: false,
        speed: 300,
        slidesToShow: 3,
        slidesToScroll: 3,
        adaptiveWidth: true,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
        ]
    });
</script>

<?php get_footer(); ?>