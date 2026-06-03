define([
	'uiComponent',
	'ko'
], function (Component, ko){
'use strict';
	return Component.extend({
		defaults: {
		template: 'Test22_Test22/greeting'
		},

		initialize: function() {
		this._super();
		this.name = ko.observable('');
		this.message = ko.observable('Please enter your name.');
		this.charCount = ko.pureComputed(function () {
			return this.name().length;
		}, this);
			return this;
		},

		sayHello: function () {
		var currentName = this.name().trim();
			if (currentName){
			this.message('Hi ' + currentName + '!');
			} else {
				this.message('Please enter your name first.');
		}
}
});
});
