//
const CCform = document.getElementById("Culinary");

const submitButton = document.getElementById("submitButton");
const resetButton = document.getElementById("resetButton");

const firstName = document.getElementById("firstName");
const lastName = document.getElementById("lastName");
const password = document.getElementById("password");
const idNumber = document.getElementById("idNumber");
const phoneNumber = document.getElementById("phoneNumber");
const email = document.getElementById("email");
const emailConfirmation = document.getElementById("emailConfirmation");
const transactionType = document.getElementById("transactionType");

const validFirstName = ["Zion", "John"];
const validLastName = ["Scott", "Doe"];
const validPassword = ["&UF73", "$7H5U"];
const validID = ["2468", "1359"];
const validPhoneNumber = ["555-555-5555", "555-555-5555 ext555"];
const validEmail = ["zds@njit.edu", "nba@gmail.com"];

let validAccount = false;

//Regex variables to check against
const name_regex = /^[A-Za-z'-]+$/; 
const password_regex = /^(?=.{1,5}$)(?!.*\s)(?=.*[A-Z])(?=.*\d)[^A-Za-z0-9]\S{0,4}$/;
const id_regex = /^\d{4}$/;
const phoneNumber_regex = /^\d{3}[- ]?\d{3}[- ]?\d{4}(\s?(ext|x)\s?\d+)?$/i;
const email_regex = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{1,3}$/;



//Submit button checks if all fields have correct formatting
submitButton.addEventListener("click", function(){
    if (!name_regex.test(firstName.value)) {
        alert("Caterer's first name does not fit requirements. The name should contain valid characters for an individuals name");
    }
    else if(!name_regex.test(lastName.value)){
        alert("Caterer's last name does not fit requirements. The name should contain valid characters for an individuals name");
    }
    else if(!password_regex.test(password.value)){
        alert("Caterer's password does not fit requirements. The user password should contain a max of 5 characters and have at least 1 uppercase letter, one special character, one numeric character and should start with a special character.");
    }
    else if(!id_regex.test(idNumber.value)){
        alert("Caterer's ID does not fit requirements. The user ID field should contain a 4 digit number.");
    }
    else if(!phoneNumber_regex.test(phoneNumber.value)){
        alert("Caterer's phone number does not fit requirements. The user phone number should consist of 10 digits which can be delineated either by spaces or dashes and contain the caterers extension number.");
    }
    else if(emailConfirmation.checked && !email_regex.test(email.value)){
        alert("Caterer's e-mail does not fit requirements. The email address must contain an @ followed by a period and an email domain.");
    }
    else{
        for (let i = 0; i < validFirstName.length; i++){
            if(validFirstName[i] == firstName.value && 
                validLastName[i] == lastName.value && 
                validPassword[i] == password.value && 
                validID[i] == idNumber.value &&
                validPhoneNumber[i] == phoneNumber.value
            ){
                if(emailConfirmation.checked && validEmail[i] == email.value){
                    validAccount = true;
                    break;
                }
                else if(emailConfirmation.checked && validEmail[i] != email.value){
                    continue;
                }
                else {
                    validAccount = true;
                    break;
                }
            }
        }

        if(validAccount){
            alert("Welcome to Culinary Connoisseurs " + firstName.value + " " + lastName.value + ". You want to " + transactionType.value)
        }
        else{
            alert(firstName.value + " " + lastName.value + ", your account was not found")
        }
    }
});

//Reset button resets the form to blank textboxes
resetButton.addEventListener("click", function(){
    CCform.reset();
});
