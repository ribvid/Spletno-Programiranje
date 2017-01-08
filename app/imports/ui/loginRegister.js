import { Template } from 'meteor/templating';
 
import './loginRegister.html';

// Ce je uporabnik ze prijavljen, ga preusmeri na dashboard.
Accounts.onLogin(function() {
	var path = FlowRouter.current().path;
	if(path === "/") {
		FlowRouter.go("/dashboard");
	}
});

Template.register.events({
	'submit form': function(event) {
		event.preventDefault();
		var usernameVar = event.target.registerUsername.value;
		var passwordVar = event.target.registerPassword.value;
		var emailVar = event.target.registerEmail.value;
		var bioVar = event.target.registerBio.value;
		var imageVar = event.target.registerImage.value;

		if (usernameVar.length < 5) {
			document.getElementById("registerError").innerHTML = "Your username needs at least 5 characters!";
			throw new Meteor.Error(403, 'Your username needs at least 5 characters');
		}

		var passwordTest = new RegExp("(?=.{6,}).*", "g");
		if (passwordTest.test(passwordVar) == false) {
			document.getElementById("registerError").innerHTML = "Your password is too weak!";
			throw new Meteor.Error(403, 'Your password is too weak!');
		}

		var emailTest = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		if (emailTest.test(emailVar) == false) {
			document.getElementById("registerError").innerHTML = "Your email is not valid!";
			throw new Meteor.Error(403, 'Your email is not valid!');
		}

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
				FlowRouter.go("/dashboard");
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
				FlowRouter.go("/dashboard");
			}
		});
	}
});