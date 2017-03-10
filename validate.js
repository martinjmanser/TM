function validateForm() {
    var a = document.forms["reflection_form"]["reflection_body"].value;
    if (a == null || a == "") {
        alert("You have to actually enter a reflection!");
        return false;
    }
}

function validateCommentForm() {
    var a = document.forms["comment_form"]["comment_body"].value;
    if (a == null || a == "") {
        alert("You have to actually enter a comment to submit!");
        return false;
    }
}

function validateConvoCommentForm() {
    var a = document.forms["convo_comment_form"]["convo_comment_body"].value;
    if (a == null || a == "") {
        alert("You have to actually enter a comment to submit!");
        return false;
    }
}

function validateSubmitChallenge() {
    var a = document.forms["submit_form"]["challenge_name"].value;
    if (a == null || a == "") {
        alert("You have to actually enter a challenge name to submit!");
        return false;
    }
}

function validateSignUpForm() {
    if(!validateEmail()) {
		alert("Please enter a valid email address.");
		return false;
	}
}

function validateMini1() {
    var mini_1 = document.forms["signup_form"]["mini_1"];
    var checked = false
    for (i = 0; i < mini_1.length; i++) {
        if (mini_1[i].checked) {
            checked = true;
        }
    }
    return checked;
}

function validateMini2() {
    var mini_2 = document.forms["signup_form"]["mini_2"];
    var checked = false
    for (i = 0; i < mini_2.length; i++) {
        if (mini_2[i].checked) {
            checked = true;
        }
    }
    return checked;
}

function validateMini3() {
    var mini_3 = document.forms["signup_form"]["mini_3"];
    var checked = false
    for (i = 0; i < mini_3.length; i++) {
        if (mini_3[i].checked) {
            checked = true;
        }
    }
    return checked;
}


function validateGender() {
    var gender = document.forms["signup_form"]["gender"];
    var checked = false
    for (i = 0; i < gender.length; i++) {
   		if (gender[i].checked) {
   			checked = true;
    	}
	}
	return checked;
}

function validateAge() {
    var age = document.forms["signup_form"]["age"];
    var checked = false
    for (i = 0; i < age.length; i++) {
   		if (age[i].checked) {
   			checked = true;
    	}
	}
	return checked;
}

function validateEmployment() {
    var employment = document.forms["signup_form"]["employment"];
    var checked = false
    for (i = 0; i < employment.length; i++) {
   		if (employment[i].checked) {
   			checked = true;
    	}
	}
	return checked;
}

function validateEducation() {
    var education = document.forms["signup_form"]["education"];
    var selected = education.options[education.selectedIndex].value;

    return selected != '';
}

function validateMajor() {
    var major = document.forms["signup_form"]["major"];
    var selected = major.options[major.selectedIndex].value;

    return selected != '';
}

function validateEmail() {
    var email = document.forms["signup_form"]["user-email"].value;
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
   
    if (email == null || email == "" || !re.test(email)) {
        return false;
    } else {
    	return true;
    }
}