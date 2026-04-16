<?php session_start();?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Bayung Porac Archive - Admin</title>

<link rel="icon" type="image/png" href="js/img/municipalLogo.png">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
@keyframes shake {
  0% { transform: translateX(0); }
  20% { transform: translateX(-6px); }
  40% { transform: translateX(6px); }
  60% { transform: translateX(-6px); }
  80% { transform: translateX(6px); }
  100% { transform: translateX(0); }
}

.shake {
  animation: shake 0.4s;
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
    
       <form id="loginForm">

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
  <?php if(isset($_SESSION['error_msg'])): ?>
<script>
document.addEventListener("DOMContentLoaded", function(){
    const email = document.getElementById("materialFormCardEmailEx");
    const pass = document.getElementById("materialFormCardPasswordEx");

    email.classList.add("border-red-500","shake");
    pass.classList.add("border-red-500","shake");

    setTimeout(()=>{
        email.classList.remove("shake");
        pass.classList.remove("shake");
    },400);
});
</script>
<?php unset($_SESSION['error_msg']); endif; ?>
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
                id="login"
                class="w-full bg-yellow-500/90 hover:bg-yellow-400 text-white font-semibold py-3 rounded-lg border border-white-300 transition duration-300 shadow-md">
                Log in
            </button>
<div class="text-center mt-4">
  <button type="button" onclick="showForgotPassword()" class="text-sm text-white hover:underline">
    Forgot Password?
  </button>
</div>
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

<!-- Forgot Password Modal -->
<div id="forgotPasswordModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
  <div class="bg-white rounded-xl p-6 w-[90%] max-w-md text-center shadow-lg relative">
    <button onclick="closeForgotPassword()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-lg font-bold">&times;</button>
    <h2 class="text-xl font-semibold mb-3 text-red-600">Forgot Password</h2>
    <p class="text-gray-600 mb-4">Enter your registered email to receive a verification OTP.</p>
    <form id="forgotPasswordForm">
     <input type="email" name="email" required
       class="w-full border border-gray-300 rounded-lg px-4 py-2 mb-2 text-center text-sm focus:ring-2 focus:ring-green-500"
       placeholder="Enter your email">
<div id="forgotPasswordMessage" class="text-center text-sm mb-4"></div>
      <button type="submit"
              class="w-full bg-yellow-500 hover:bg-yellow-400 text-white py-2 rounded-lg">
        Send OTP
      </button>
    </form>
  </div>
</div>

<!-- OTP Verification Modal -->
<div id="otpResetModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
  <div class="bg-white rounded-xl p-6 w-[90%] max-w-md text-center shadow-lg relative">
    <button onclick="closeOTP()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-lg font-bold">&times;</button>
    <h2 class="text-xl font-semibold mb-3 text-red-600">Enter OTP</h2>
    <p class="text-gray-600 mb-4">We sent an OTP to your email.</p>
    <form id="otpForm">
      <input type="text" name="otp" maxlength="6" required
             class="w-full border border-gray-300 rounded-lg px-4 py-2 mb-4 text-center text-lg tracking-widest focus:ring-2 focus:ring-green-500"
             placeholder="Enter OTP">
                 <div id="otpMessage" class="text-sm mb-4 text-green-600"></div>
      <button type="submit"
              class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700">
        Verify OTP
      </button>
    </form>
  </div>
</div>
<!-- Reset Password Modal -->
<div id="resetPasswordModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
  <div class="bg-white rounded-xl p-6 w-[90%] max-w-md text-center shadow-lg relative">
    
    <button onclick="closeReset()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-lg font-bold">
      &times;
    </button>

    <h2 class="text-xl font-semibold mb-3 text-red-600">Reset Password</h2>
    <p class="text-gray-600 mb-4">Enter your new password.</p>

    <form id="resetPasswordForm" class="flex flex-col gap-3">

      <!-- Password Field -->
      <div class="relative">
        <input 
          type="password" 
          id="resetPasswordField"
          name="password"
          placeholder="Enter your password"
          class="w-full px-4 py-3 pr-12 bg-white border border-gray-300 rounded-lg text-gray-700 placeholder-gray-400 placeholder:text-sm outline-none transition duration-300 hover:bg-gray-100 focus:ring-2 focus:ring-green-500"
          
          pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}"
          required
        >

        <button
          type="button"
          onclick="toggleResetPassword()"
          class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
          <i id="resetToggleIcon" class="fa-solid fa-eye"></i>
        </button>
      </div>

      <!-- Inline validation message -->
      <p id="resetPasswordHelp" class="text-red-600 text-sm hidden">
        Password must be at least 8 characters, include uppercase, lowercase, number, and a symbol.
      </p>

      <button type="submit"
        class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 mt-2">
        Reset Password
      </button>

    </form>
  </div>
</div>

<!-- SCRIPT -->
<script>
function toggleResetPassword() {
  const input = document.getElementById("resetPasswordField");
  const icon = document.getElementById("resetToggleIcon");

  if (input.type === "password") {
    input.type = "text";
    icon.classList.remove("fa-eye");
    icon.classList.add("fa-eye-slash");
  } else {
    input.type = "password";
    icon.classList.remove("fa-eye-slash");
    icon.classList.add("fa-eye");
  }
}

// LIVE VALIDATION
const resetPasswordField = document.getElementById("resetPasswordField");
const resetHelp = document.getElementById("resetPasswordHelp");

resetPasswordField.addEventListener("input", function () {
  const pattern = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}/;

  if (!pattern.test(this.value)) {
    resetHelp.classList.remove("hidden");
  } else {
    resetHelp.classList.add("hidden");
  }
});
</script>

<!-- Footer -->

<footer class="text-center py-4">

<p class="text-gray-200">
&#169; All Rights Reserved. Developed by the PSU IT Interns.
</p>

</footer>
<script>
function toggleResetPassword() {
    const passwordField = document.getElementById("resetPasswordField");
    const icon = document.getElementById("resetToggleIcon");
    if(passwordField.type === "password"){
        passwordField.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    } else {
        passwordField.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
}
</script>
<!-- Scripts -->
<script>
document.getElementById('forgotPasswordForm').addEventListener('submit', function(e){
    e.preventDefault();

    const formData = new FormData(this);
    const messageDiv = document.getElementById('forgotPasswordMessage'); // Inline message div

    // Clear previous message
    messageDiv.textContent = '';
    messageDiv.classList.remove('text-red-600','text-green-500');

    fetch('forgot_password.php', { method:'POST', body:formData })
    .then(res => res.json())
    .then(data => {
        if(data.status==='success'){
            otpEmail = data.email;
            messageDiv.textContent = 'OTP sent successfully! Check your email.';
            messageDiv.classList.add('text-green-500');
            closeForgotPassword();
            showOTP();
        } else {
            messageDiv.textContent = data.message;
            messageDiv.classList.add('text-red-600');
        }
    })
    .catch(err => {
        messageDiv.textContent = 'Something went wrong!';
        messageDiv.classList.add('text-red-600');
    });
}); 
</script>


<script>
function showForgotPassword(){ document.getElementById('forgotPasswordModal').classList.remove('hidden'); }
function closeForgotPassword(){ document.getElementById('forgotPasswordModal').classList.add('hidden'); }
function showOTP(){ document.getElementById('otpResetModal').classList.remove('hidden'); }
function closeOTP(){ document.getElementById('otpResetModal').classList.add('hidden'); }
function showReset(){ document.getElementById('resetPasswordModal').classList.remove('hidden'); }
function closeReset(){ document.getElementById('resetPasswordModal').classList.add('hidden'); }

let otpEmail = ''; // to store email for OTP verification

// 1️⃣ Send OTP (Forgot Password)
document.getElementById('forgotPasswordForm').addEventListener('submit', function(e){
    e.preventDefault();
    const formData = new FormData(this);

    fetch('forgot_password.php', { method:'POST', body:formData })
    .then(res=>res.json())
    .then(data=>{
        if(data.status==='success'){
            otpEmail = data.email;
            closeForgotPassword();
            showOTP();
        } else {
      
        }
    });
});

// 2️⃣ Verify OTP
document.getElementById('otpForm').addEventListener('submit', function(e){
    e.preventDefault();
    const formData = new FormData(this);
    formData.append('email', otpEmail);

    fetch('verify_reset_otp.php', { method:'POST', body:formData })
    .then(res=>res.json())
    .then(data=>{
        if(data.status==='success'){
            closeOTP();
            showReset();
        } else {
            alert(data.message);
        }
    });
});

// 3️⃣ Reset Password
// 3️⃣ Reset Password
document.getElementById('resetPasswordForm').addEventListener('submit', function(e){
    e.preventDefault();
    const formData = new FormData(this);
    formData.append('email', otpEmail);

    fetch('reset_password.php', { method:'POST', body:formData })
    .then(res=>res.json())
    .then(data=>{
        if(data.status==='success'){
            closeReset();

           Swal.fire({
    toast: true,
    position: 'top',
    icon: 'success',
    title: 'Password updated successfully.',
    showConfirmButton: false,
    timer: 6000,
    timerProgressBar: false,
});

        } else {
           Swal.fire({
    toast: true,
    position: 'top',
    icon: 'error',
    title: data.message || 'Something went wrong.',
    showConfirmButton: false,
    timer: 2500,
    timerProgressBar: true,
});
        }
    })
    .catch(() => {
      Swal.fire({
    toast: true,
    position: 'top',
    icon: 'error',
    title: 'Network error. Please try again.',
    showConfirmButton: false,
    timer: 2500,
});
    });
});
</script>




<script src="js/jquery-3.4.0.min.js"></script>

<script>

document.getElementById("loginForm").addEventListener("submit", function(e){
    e.preventDefault(); // 🚨 STOP PAGE REFRESH

    const email = document.getElementById("materialFormCardEmailEx");
    const pass = document.getElementById("materialFormCardPasswordEx");

    let hasError = false;

    // EMPTY VALIDATION
    if(email.value === ""){
        email.classList.add("border-red-500","shake");
        setTimeout(()=>email.classList.remove("shake"),400);
        hasError = true;
    }

    if(pass.value === ""){
        pass.classList.add("border-red-500","shake");
        setTimeout(()=>pass.classList.remove("shake"),400);
        hasError = true;
    }

    if(hasError) return;

    const formData = new FormData(this);

    fetch("admin_login.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {

        if(data.status === "error"){
            // ❌ WRONG LOGIN
            email.classList.add("border-red-500","shake");
            pass.classList.add("border-red-500","shake");

            setTimeout(()=>{
                email.classList.remove("shake");
                pass.classList.remove("shake");
            },400);
        }

        else if(data.status === "otp"){
            // 🔐 SHOW OTP MODAL
            otpEmail = data.email;
            document.getElementById('otpModal').classList.remove('hidden');
        }

        else if(data.status === "success"){
            // ✅ ADMIN REDIRECT
            if(data.role === "Records Administrator"){
                window.location.href = "folder_management.php";
            }else{
                window.location.href = "../system-administrator/homepage_management.php";
            }
        }

        else if(data.status === "user_success"){
            // ✅ USER REDIRECT
            window.location.href = "../employee/home.php";
        }

    })
    .catch(() => {
        alert("Something went wrong");
    });
});



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