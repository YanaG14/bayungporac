<?php session_start();?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Bayung Porac Archive - Admin</title>

<link rel="icon" type="image/png" href="js/img/municipalLogo.png">

<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>

#loader{
position:fixed;
left:0;
top:0;
width:100%;
height:100%;
z-index:9999;
 
}

/* Navbar Gradient Animation */
@keyframes gradientMove{
0%{background-position:left;}
100%{background-position:right;}
}

.animated-navbar{
background: linear-gradient(to right,#052e16,#22c55e);
background-size:200% 200%;
animation:gradientMove 5s ease infinite alternate;
}

/* Login Card Animation */
@keyframes fadeFloat{
0%{
opacity:0;
transform:translateY(40px);
}
100%{
opacity:1;
transform:translateY(0);
}
}

.login-card{
animation:fadeFloat 1.2s ease-out;
}

</style>

</head>

<body class="bg-[url('img/pic1.jpg')] bg-cover bg-center h-screen flex flex-col font-sans overflow-hidden">

<div id="loader"></div>

<!-- Navbar -->
<nav class="animated-navbar shadow-md">

<div class="px-4 py-6">

<div class="flex items-center space-x-3">

<img src="js/img/municipalLogo.png" class="w-16 h-16">

<span class="text-white font-semibold text-2xl">
MUNICIPALITY OF PORAC
</span>

</div>

</div>

</nav>


<!-- Login Section -->

<!-- Login Section -->
<div class="flex-1 flex items-center justify-center px-4">
    <div class="login-card w-full max-w-md bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl shadow-xl p-8">

        <h2 class="text-2xl text-center text-white mb-6">
            Log into Bayung Porac Archive
        </h2>

        <!-- Inline PHP Error Message -->
    
        <form action="admin_login.php" method="POST">

            <!-- Email -->
            <div class="mb-5">
                <label class="block text-white mb-2">Email</label>
                <input
                    type="email"
                    id="materialFormCardEmailEx"
                    name="admin_user"
                    class="w-full px-4 py-3 bg-black/30 border border-white/50 rounded-lg text-white placeholder-white placeholder:text-sm outline-none transition duration-300 hover:bg-white/20 hover:border-white-400 focus:ring-2 focus:ring-green-500"
                    placeholder="Enter your email">
            </div>

         <div class="mb-5">
<label class="block text-white mb-2">
Password
</label>

<div class="relative">

<input
type="password"
id="materialFormCardPasswordEx"
name="admin_password"
class="w-full px-4 py-3 pr-12 bg-black/30 border border-white/50 rounded-lg text-white placeholder-white placeholder:text-sm outline-none transition duration-300 hover:bg-white/20 hover:border-white-400 focus:ring-2 focus:ring-green-500"
placeholder="Enter your password">

<button
type="button"
onclick="togglePassword()"
class="absolute right-4 top-1/2 -translate-y-1/2 text-white hover:text-gray-300">

<i id="toggleIcon" class="fa-solid fa-eye"></i>

</button>

</div>
</div>


  <!-- Inline Error Message Above Button -->
      <?php if(isset($_SESSION['error_msg']) && $_SESSION['error_msg'] !== ""): ?>
        <div class="bg-red-100 text-red-600 p-3 rounded mb-4 text-center">
          <?php echo $_SESSION['error_msg']; unset($_SESSION['error_msg']); ?>
        </div>
      <?php endif; ?>
      <?php
if(isset($_SESSION['error_msg']) && $_SESSION['error_msg'] !== ""){
    echo '<div class="bg-red-100 text-red-600 p-3 rounded mb-4 text-center">'.$_SESSION['error_msg'].'</div>';
    unset($_SESSION['error_msg']);
}
?>
<?php if(isset($_SESSION['admin_otp_modal']) || isset($_SESSION['user_otp_modal'])): ?>
<script>
document.addEventListener("DOMContentLoaded", function(){
    const modal = document.getElementById('otpModal');
    if(modal){
        modal.classList.remove('hidden');
    }
});
</script>
<?php 
unset($_SESSION['admin_otp_modal']);
unset($_SESSION['user_otp_modal']);
endif; ?>
            <button
                type="submit"
                name="adminlog"
                id="login"
                class="w-full bg-yellow-500/90 hover:bg-yellow-400 text-white font-semibold py-3 rounded-lg border border-white-300 transition duration-300 shadow-md">
                Log in
            </button>

        </form>

</div>

</div>

<!-- OTP MODAL -->
<div id="otpModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
  <div class="bg-white rounded-xl p-6 w-[90%] max-w-sm text-center shadow-lg">

    <h2 class="text-xl font-semibold mb-3 text-red-600">
      Account Not Verified
    </h2>

    <p class="text-gray-600 mb-4">
      Please enter the OTP sent to your email.
    </p>

    <form method="POST" action="verify_otp.php">
      <input type="hidden" name="email" value="<?php echo $_SESSION['otp_email'] ?? ''; ?>">

      <input type="text" name="otp" maxlength="6" required
        class="w-full border border-gray-300 rounded-lg px-4 py-2 mb-4 text-center text-lg tracking-widest focus:ring-2 focus:ring-green-500">

      <button type="submit" name="verify"
        class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700">
        Verify OTP
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

$("#login").on("click", function(){

uservalidate();
passvalidate();

if(uservalidate()===true && passvalidate()===true){}

});

function uservalidate(){

if($('#materialFormCardEmailEx').val()===''){

$('#materialFormCardEmailEx').addClass("border-red-500");
return false;

}else{

$('#materialFormCardEmailEx').removeClass("border-red-500").addClass("border-green-500");
return true;

}

}

function passvalidate(){

if($('#materialFormCardPasswordEx').val()===''){

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
},1000);

});


function togglePassword(){

const passwordField = document.getElementById("materialFormCardPasswordEx");
const icon = document.getElementById("toggleIcon");

if(passwordField.type === "password"){
    passwordField.type = "text";
    icon.classList.remove("fa-eye");
    icon.classList.add("fa-eye-slash");
}else{
    passwordField.type = "password";
    icon.classList.remove("fa-eye-slash");
    icon.classList.add("fa-eye");
}

}
</script>


</body>
</html>