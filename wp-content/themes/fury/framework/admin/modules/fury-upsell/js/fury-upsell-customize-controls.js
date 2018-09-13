( function( api ) {

	// Extends our custom "fury-theme-info" section.
	api.sectionConstructor['fury-theme-info'] = api.Section.extend(
		{

            // No events for this type of section.
			attachEvents: function () {},

            // Always make the section active.
			isContextuallyActive: function () {
				return true;
			}
		}
	);

	// Extends our custom "fury-upsell-slider-sections" section.
	api.sectionConstructor['fury-upsell-slider-sections'] = api.Section.extend(
        {

            // No events for this type of section.
			attachEvents: function () {},

            // Always make the section active.
			isContextuallyActive: function () {
				return true;
			}
		}
	);

} )( wp.customize );
