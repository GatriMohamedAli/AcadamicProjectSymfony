const sign_in_btn = document.querySelector("#sign-in-btn");
const sign_up_btn = document.querySelector("#sign-up-btn");
const container = document.querySelector(".cont");


const validateEmail = (email)=>{  var re = /\S+@\S+\.\S+/;  return re.test(email);}

const validatePass=(pass)=>{return pass.length>7 && /^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{6,16}$/.test(pass)}

const validateConfPass=(pass,confPass)=>{return confPass.length ==-1 || pass === confPass}

const validateUsername=(username)=>{return username.length > 0}


const loginUsername=document.getElementById("loginUsername")
const loginPass=document.getElementById("loginPass")
const errorLoginUsername=document.getElementById("errorLoginUsername")
const errorLoginPass=document.getElementById("errorLoginPass")
const signup=document.getElementById("signIn");
//signup.disabled=true;
//signup.style.backgroundColor="red";

let testLoginUsername=false;
let testLoginPass=false;

loginUsername.addEventListener("focusout",ev => {
  if(validateUsername(ev.target.value)==false){
    errorLoginUsername.hidden=false;
  }else{
    errorLoginUsername.hidden=true;
    testLoginUsername=true;
  }
})
/*loginPass.addEventListener("keyup", ev => {
  if(validatePass(ev.target.value)==false){
    errorLoginPass.hidden=false;
  }else{
    errorLoginPass.hidden=true;
    if (testLoginUsername){
      signup.disabled=false;
      signup.style.backgroundColor="#5995fd";
    }
  }
})*/



sign_up_btn.addEventListener("click", () => {
  container.classList.add("sign-up-mode");
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
    }else{
      errorConfPass.hidden=true;
      if (testEmail && testPass && testUsername){
        signup.disabled=false;
        signup.style.backgroundColor="#5995fd";
      }

    }

  })

});

sign_in_btn.addEventListener("click", () => {
  container.classList.remove("sign-up-mode");
});
if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}

