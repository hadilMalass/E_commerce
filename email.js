function sendMail(){
    let parms={
        first_name:document.getElementById("first_name").value,
        last_name:document.getElementById("last_name").value,
        password:document.getElementById("password").value,

        email:document.getElementById("email").value,
    }
    emailjs.send("service_7o15879","template_8d84ksg",parms).then(alert("email sent!!"))
}