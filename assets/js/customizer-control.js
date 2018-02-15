( function( api ) {

	api.sectionConstructor['pacific_pro'] = api.Section.extend( {

		// No events for this type of section.
		attachEvents: function () {},

		// Always make the section active.
		isContextuallyActive: function () {
			return true;
		}

	} );

} )( wp.customize );
