import { Template } from 'meteor/templating';
 
import './body.html';

if (Meteor.isClient) {
	Template.register.events({
		'submit form': function(event) {
			event.preventDefault();
			var usernameVar = event.target.registerUsername.value;
			var passwordVar = event.target.registerPassword.value;
			var emailVar = event.target.registerEmail.value;
			var bioVar = event.target.registerBio.value;
			var imageVar = event.target.registerImage.value;

			Accounts.createUser({
				username: usernameVar,
				password: passwordVar,
				email: emailVar,
				bio: bioVar,
				profileImage: imageVar
			});
		}
	});

	Template.login.events({
		'submit form': function(event) {
			event.preventDefault();
			var usernameVar = event.target.loginUsername.value;
			var passwordVar = event.target.loginPassword.value;

			Meteor.loginWithPassword(usernameVar, passwordVar);
		}
	});

	Template.dashboard.events({
		'click .logout': function(event) {
			event.preventDefault();
			Meteor.logout();
		}
	});
}