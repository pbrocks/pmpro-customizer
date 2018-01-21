/* globals wp */
/* exported wpse286375controls */
var wpse286375controls = ( function( api ) {
	'use strict';
	var component = {
		defaultParams: {
			label: '',
			description: ''
		}
	};

	/**
	 * Init.
	 *
	 * @param {object} defaultParams - Default params for control, especially translated strings.
	 * @returns {void}
	 */
	component.init = function( defaultParams ) {
		component.defaultParams = defaultParams || {};
		api.bind( 'ready', component.addControl );
	};

	/**
	 * Set the active states for the device controls.
	 *
	 * @returns {void}
	 */
	component.addControl = function() {
		api.control.add( new api.Control( 'special_page', _.extend(
			{},
			component.defaultParams,
			{
				type: 'dropdown-pages',
				section: 'static_front_page',
			}
		) ) );
	};

	return component;
} )( wp.customize );