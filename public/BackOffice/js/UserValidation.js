

const validateEmail = (email)=>{  var re = /\S+@\S+\.\S+/;  return re.test(email);}

const validatePass=(pass)=>{return pass.length>7 && /^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{6,16}$/.test(pass)}

const validateConfPass=(pass,confPass)=>{return confPass.length ==-1 || pass === confPass}

const validateUsername=(username)=>{return username.length > 0}



const userName=document.getElementById("registration_form_username");
const email=document.getElementById("registration_form_email");
const pass=document.getElementById("registration_form_password");
const confPass=document.getElementById("registration_form_confirm_password");
const signup=document.getElementById("signUp");
const errorEmail=document.getElementById("errorEmail")
const errorPass=document.getElementById("errorPass")
const errorConfPass=document.getElementById("errorConfPass")
const errorUsername=document.getElementById("errorUsername")
signup.disabled=true;
signup.style.backgroundColor="red";
let testEmail=false;
let testPass=false;
let testUsername=false;
let password="";
console.log(email)

window.onload=(ev) => {
if(userName.value.length!=0 && email.value.length!=0){
    testEmail=true;
    testUsername=true;
}
};

userName.addEventListener("focusout", e=>{
    console.log(e.target.value.length);
    if(validateUsername(e.target.value)==false){
        errorUsername.hidden=false;
    }else{
        errorUsername.hidden=true;
        testUsername=true;
    }
})
email.addEventListener("focusout", e => {
    if(validateEmail(e.target.value)==false){
        errorEmail.hidden=false;
    }else{
        errorEmail.hidden=true;
        testEmail=true;
    }


})
console.log(pass);
pass.addEventListener("focusout", e=>{
    if(validatePass(e.target.value)==false){
        errorPass.hidden=false;
    }else{
        errorPass.hidden=true;
        testPass=true;
    }

    password=e.target.value;
})

confPass.addEventListener('keyup',e=>{
    if(validateConfPass(password,e.target.value)==false){
        errorConfPass.hidden=false;
        signup.disabled=true;
        signup.style.backgroundColor="red";
    }else{
        errorConfPass.hidden=true;
        if (testEmail && testPass && testUsername){
            signup.disabled=false;
            signup.style.backgroundColor="#5995fd";
        }

    }

})