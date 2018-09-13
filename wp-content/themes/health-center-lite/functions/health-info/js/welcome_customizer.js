jQuery(document).ready(function() {
    var health_aboutpage = healthLiteWelcomeScreenObject.aboutpage;
    var health_nr_actions_required = healthLiteWelcomeScreenObject.nr_actions_required;

    /* Number of required actions */
    if ((typeof health_aboutpage !== 'undefined') && (typeof health_nr_actions_required !== 'undefined') && (health_nr_actions_required != '0')) {
        jQuery('#accordion-section-themes .accordion-section-title').append('<a href="' + health_aboutpage + '"><span class="health-actions-count">' + health_nr_actions_required + '</span></a>');
    }

    /* Upsell in Customizer (Link to Welcome page) */
    if ( !jQuery( ".health-upsells" ).length ) {
        jQuery('#customize-theme-controls > ul').prepend('<li class="accordion-section health-upsells">');
    }
    if (typeof health_aboutpage !== 'undefined') {
        jQuery('.health-upsells').append('<a style="width: 80%; margin: 5px auto 5px auto; display: block; text-align: center;" href="' + health_aboutpage + '" class="button" target="_blank">{themeinfo}</a>'.replace('{themeinfo}', healthLiteWelcomeScreenCustomizerObject.themeinfo));
    }
    if ( !jQuery( ".health-upsells" ).length ) {
        jQuery('#customize-theme-controls > ul').prepend('</li>');
    }
});