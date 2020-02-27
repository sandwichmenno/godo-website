<?php
/**
 * Template Name: Jobs Template
 */
?>

<?php get_header(); ?>
<?php
    $theme_settings = get_option( 'theme_settings' );
    $categoryFilter = ["Front-end developer", "Back-end developer", "Product Owner", "Content manager"];
    $locationFilter = ["Amsterdam", "Randstad"];
?>

<section class="page-section hero-wrapper">
    <div class="hero no-image" style="background: #f4f4f4">
        <div class="container">
            <h1>Onze vacatures</h1>
            <form id="filter" class="row">
                <select id="filterCategory" name="position" onchange="createFilter('category')">
                    <option value="all" selected>Alle functies</option>
                    <?php foreach($categoryFilter as $filter) { ?>
                        <option value="<?php echo $filter; ?>"><?php echo $filter; ?></option>
                    <?php } ?>
                </select>

                <select id="filterLocation" name="location" onchange="createFilter('location')">
                    <option value="all" selected>Alle locaties</option>
                    <?php foreach($locationFilter as $filter) { ?>
                        <option value="<?php echo $filter; ?>"><?php echo $filter; ?></option>
                    <?php } ?>
                </select>
            </form>
        </div>
    </div>
</section>

<section class="job-section">
    <div class="container" id="job">
        <div id="loader"><img src="<?php bloginfo('template_directory'); ?>/assets/images/loader.svg"></div>
        <div id="jobs"></div>
    </div>
</section>

<script type="text/javascript">
    const dir = "<?php bloginfo('template_directory'); ?>";
    let jobs;
    let filter = {category: null, param: null, location: null};

    jQuery(document).ready(function () {
        const data = { action: 'getJobs' };
        const category = getUrlParameter('category');

        jQuery.ajax({
            type: 'POST',
            url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
            dataType: 'json',
            data: data,
            beforeSend: function() {
                jQuery('#loader').show();
            },
            complete: function(){
                jQuery('#loader').hide();
            },
            success: function(response){
                jobs = response;
                if(category) {
                    filter.category = category;
                    generateList(filter);
                } else {
                    generateList({});
                }
            },
        });
    });

    function createFilter(type) {
        const category = jQuery('#filterCategory').val();
        const location = jQuery('#filterLocation').val();

        switch(type) {
            case 'category':
                filter.category = category !== "all" ? category : null;
                break;

            case 'location':
                filter.location = location !== "all" ? location : null;
                break;
        }

        generateList(filter);
    }

    function generateList(filter) {
        let arr = [];

        jQuery("#jobs").empty();

        jobs.forEach(function (job, index) {
            let hasCategory = false;
            let hasLocation = false;

            if(filter.category) {
                Object.values(job.categories.data).map(category => {
                    if (~category.name.indexOf(filter.category)) {
                        hasCategory = true;
                    }
                });
            } else {
                hasCategory = true;
            }

            if(filter.location) {
                if(job.address.address1) {
                    if (~job.address.address1.indexOf(filter.location)) {
                        hasLocation = true;
                    }
                }
            } else {
                hasLocation = true;
            }

            if (hasCategory && hasLocation) {
                arr.push(job);
            }
        });

        if(arr.length > 0) {
            jQuery.each(arr, function (index, job) {
                const category = getCategory(job);
                const tags = generateTags(job, 6);
                const location = locationComponent(job);

                const jobComponent = '<a href="/vacature?id=' + job.id + '"><div class="job">' +
                    '<img src="' + dir + '/assets/images/icons/icon_' + category + '.svg" class="icon">' +
                    '<div class="job-details">' +
                    '<h3>' + job.title + '</h3>' +
                    '<div class="tags">' + tags + '</div>' +
                    location +
                    '</div></div></a>';

                jQuery("#jobs").append(jobComponent);
            });
        } else {
            jQuery("#jobs").append("<h3>Er zijn geen vacatures gevonden.</h3>")
        }
    }

    function getCategory(job) {
        const jobTypes = ['design', 'dev', 'agile', 'infra', 'support'];
        const catName = job.categories.total > 0 ? job['categories']['data'][0]['name'] : '';

        let category = '';

        jQuery.each( jobTypes, function( index, jobType ) {
            if (catName.toLowerCase().indexOf(jobType) >= 0) {
                category = jobType;
            }
        });

        if(category === '') { category = 'misc'; }

        return category;
    }

    function generateTags(job, maxLength) {
        let tags = "";
        let total = 0;

        jQuery.each( job.skills.data, function( index, skill ) {
            if(total > maxLength-1) { return false; }

            tags += '<div class="tag">'+ skill.name +'</div>';
            total++;
        });

        return tags;
    }

    function locationComponent(job) {
        const address = job.address.address1;
        const locationComponent = '<div class="location">' +
            '<img src="'+ dir +'/assets/images/icons/map-pin.svg"> '+ address +
            '</div>';

        if(address) {
            return locationComponent;
        }

        return "";
    }

    function getUrlParameter(sParam) {
        let sPageURL = window.location.search.substring(1),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;

        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');

            if (sParameterName[0] === sParam) {
                return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
            }
        }
    };
</script>

<?php get_footer(); ?>