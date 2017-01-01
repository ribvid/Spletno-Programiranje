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
			}, function(error) {
				if (error) {
					document.getElementById("registerError").innerHTML = error.reason;
				} else {
					// code
				}
			});
		}
	});

	Template.login.events({
		'submit form': function(event) {
			event.preventDefault();
			var usernameVar = event.target.loginUsername.value;
			var passwordVar = event.target.loginPassword.value;

			Meteor.loginWithPassword(usernameVar, passwordVar, function(error) {
				if (error) {
					event.target.reset();
					event.target.loginPassword.focus();
					event.target.loginUsername.focus();
					document.getElementById("loginError").innerHTML = error.reason;
				} else {
					console.log("everything ok");
				}
			});
		}
	});

	Template.dashboard.events({
		'click .logout': function(event) {
			event.preventDefault();
			Meteor.logout();
			Meteor.onLoginFailure();
		}
	});
}