import { Template } from 'meteor/templating';

import { Posts } from '../api/posts.js';

import './dashboard.html';

Template.dashboard.helpers({
	canShow() {
		if (!!Meteor.user() == false) {
			FlowRouter.go("/");
		} else {
			return !!Meteor.user();
		}
	},
	posts() {
		return Posts.find({}, { sort: { createdAt: -1 } });
	},
});

Template.dashboard.events({
	'submit .new-post'(event, template) {
		event.preventDefault();

		const target = event.target;
		const text = target.text.value;

		var image = document.getElementById("image").files[0];
		if (image) {
			var reader = new FileReader();
			console.log(reader.result);
		} else {
			Posts.insert({
				type: 0,
				content: text,
				pub_date: new Date(),
				owner: Meteor.userId(),
				author: Meteor.user().username,
				shares: 0,
				favorites: 0,
			});
		}


		target.text.value = '';
	},
	'click .logout'(event) {
		event.preventDefault();
		Meteor.logout();
		FlowRouter.go("/");
	}
});