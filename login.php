<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Bayung Porac Archive</title>

<link rel="icon" type="image/png" href="js/img/municipalLogo.png">

<script src="https://cdn.tailwindcss.com"></script>

<style>
#loader{
position:fixed;
left:0;
top:0;
width:100%;
height:100%;
z-index:9999;

}
</style>

</head>
<body class="bg-[url('img/pic1.jpg')] bg-cover bg-center h-screen flex flex-col font-sans overflow-hidden">
<body class="bg-gray-100 font-sans">

<div id="loader"></div>

<!-- Navbar -->
<style>
/* Gradient animation */
@keyframes gradientMove {
  0% {
    background-position: left;
  }
  100% {
    background-position: right;
  }
}

/* Animated gradient navbar */
.animated-navbar {
  background: linear-gradient(to right, #14532d, #16a34a);
  background-size: 200% 200%;
  animation: gradientMove 5s ease infinite alternate;
}
</style>

<style>
/* Gradient animation */
@keyframes gradientMove {
  0% {
    background-position: left;
  }
  100% {
    background-position: right;
  }
}

/* Strong green gradient */
.animated-navbar {
  background: linear-gradient(to right, #052e16, #22c55e);
  background-size: 200% 200%;
  animation: gradientMove 5s ease infinite alternate;
}
</style>

<nav class="animated-navbar shadow-md">
  <div class="px-4 py-6">

    <div class="flex items-center justify-start">

      <div class="flex items-center space-x-3">
        <img src="js/img/municipalLogo.png" class="w-16 h-16">

        <span class="text-white font-semibold text-2xl">
          MUNICIPALITY OF PORAC
        </span>
      </div>

    </div>

  </div>
</nav>
<!-- Login Section -->
<style>
/* Keyframe animation */
@keyframes fadeFloat {
  0%{
    opacity:0;
    transform: translateY(40px);
  }
  100%{
    opacity:1;
    transform: translateY(0);
  }
}

/* Apply animation to the login card */
.login-card{
  animation: fadeFloat 1.2s ease-out;
}
</style>


<div class="flex-1 flex items-center justify-center px-4">

<div class="login-card w-full max-w-md bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl shadow-xl p-8">
  

<h2 class="text-2xl font text-center text-white mb-6">
Log into Bayung Porac Archive
</h2>

<form action="userlogin/login.php" method="POST">



<!-- Email -->
<div class="mb-5">

<label class="block text-2x1 text-white mb-2">
Email
</label>

<input
type="email"
id="materialFormCardEmailEx"
name="email_address"
class="w-full px-4 py-3 bg-black/30 border border-white/50 rounded-lg text-white placeholder-white placeholder:text-sm outline-none transition duration-300 hover:bg-white/20 hover:border-white-400 focus:ring-2 focus:ring-green-500"
placeholder="Enter your email">

</div>

<!-- Password -->
<div class="mb-5">

<label class="block text-2x1 text-white mb-2">
Password
</label>

<input
type="password"
id="materialFormCardPasswordEx"
name="user_password"
class="w-full px-4 py-3 bg-black/30 border border-white/50 rounded-lg text-white placeholder-white placeholder:text-sm outline-none transition duration-300 hover:bg-white/20 hover:border-white-400 focus:ring-2 focus:ring-green-500"
placeholder="Enter your password">

</div>
<!-- Error Message -->
<?php
$error = '';
if(isset($_GET['error'])){
    switch($_GET['error']){
        case 'empty':
            $error = 'Please fill in all fields.';
            break;
        case 'invalid':
            $error = 'Invalid email or password.';
            break;
        case 'archived':
            $error = 'Your account has been archived. You cannot login.';
            break;
    }
}
?>
<?php if($error != ''): ?>
<div class="mb-5 p-3 text-white text-center rounded">
    <?= htmlspecialchars($error) ?>
</div>
<?php endif; ?>
<button
type="submit"
name="login"
id="login"
class="w-full bg-yellow-500/90 hover:bg-yellow-400 text-white font-semibold py-3 rounded-lg border border-white-300 transition duration-300 shadow-md">

Log in

</button>

</form>

</div>

</div>

<!-- Footer -->
<footer class="text-center py-4">

<p class="text-gray-200">
&#169; All Rights Reserved. Developed by the PSU IT Interns.
</p>

</footer>

<!-- Scripts -->
<script src="js/jquery-3.4.0.min.js"></script>

<script>

$("#login").on("click", function () {

uservalidate();
passvalidate();

if(uservalidate() === true && passvalidate() === true){}

});

function uservalidate(){

if($('#materialFormCardEmailEx').val() == ''){

$('#materialFormCardEmailEx').addClass("border-red-500");
return false;

}else{

$('#materialFormCardEmailEx').removeClass("border-red-500").addClass("border-green-500");
return true;

}

}

function passvalidate(){

if($('#materialFormCardPasswordEx').val() == ''){

$('#materialFormCardPasswordEx').addClass("border-red-500");
return false;

}else{

$('#materialFormCardPasswordEx').removeClass("border-red-500").addClass("border-green-500");
return true;

}

}

$(window).on('load', function(){

setTimeout(function(){
$('#loader').fadeOut('slow');
});

});

</script>

</body>
</html>