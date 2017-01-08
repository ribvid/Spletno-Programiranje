import { FlowRouter } from 'meteor/kadira:flow-router';
import { BlazeLayout } from 'meteor/kadira:blaze-layout';

// Import to load these templates
import '../ui/loginRegister.js';
import '../ui/dashboard.js';

/*
var loginRegister = FlowRouter.group({
	prefix: "/",
	name: "loginRegister"
});

loginRegister.route('/', {
	action: function() {
		BlazeLayout.render("mainLayout", {content: "loginRegister"});
	},
	triggersEnter: [function() {
		Meteor.defer(function() {
			$('body').addClass('join');
		});
	}]
});
*/
FlowRouter.route('/', {
	triggersEnter: [function() {
		Meteor.defer(function() {
			$('body').addClass('join');
		});
	}],
	action: function() {
		BlazeLayout.render("mainLayout", {content: "loginRegister"});
	}
});

FlowRouter.route('/dashboard', {
	triggersEnter: [function() {
		Meteor.defer(function() {
			$('body').removeClass('join');
		});
	}],
	action: function() {
		BlazeLayout.render("mainLayout", {content: "dashboard"});
	}
});
