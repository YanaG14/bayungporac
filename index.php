<?php
require_once("include/connection.php");

$slides = mysqli_query($conn,"SELECT * FROM homepage_slides WHERE status='Active' ORDER BY slide_id DESC");
$profiles = mysqli_query($conn,"SELECT * FROM homepage_profiles WHERE status='Active' ORDER BY profile_id DESC");
$featured = mysqli_query($conn,"SELECT * FROM homepage_featured WHERE status='Active' ORDER BY featured_id DESC");
$events = mysqli_query($conn,"SELECT * FROM homepage_events WHERE status='Active' ORDER BY event_id DESC");

$about = mysqli_query($conn,"SELECT * FROM homepage_about LIMIT 1");
$aboutRow = mysqli_fetch_assoc($about);

$slideImages = [];
$slideCaptions = [];

while($row = mysqli_fetch_assoc($slides)){
    $slideImages[] = "uploads/slides/".$row['image'];
    $slideCaptions[] = $row['caption'];
}

$profileData = [];
while($row = mysqli_fetch_assoc($profiles)){
    $profileData[] = $row;
}

$featuredData = [];
while($row = mysqli_fetch_assoc($featured)){
    $featuredData[] = $row;
}

$eventData = [];
while($row = mysqli_fetch_assoc($events)){
    $eventData[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Bayung Porac Archive</title>

<link rel="icon" href="js/img/municipalLogo.png">
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

/* Smooth Reveal */
.reveal {
  opacity: 0;
  transform: translateY(60px);
  transition: all 1s ease;
}
.reveal.show {
  opacity: 1;
  transform: translateY(0);
}

/* SLIDER */
.slide {
  transition: opacity 1.5s ease, transform 1.5s ease;
}

/* GLASS NAV */
.glass {
  backdrop-filter: blur(14px);
  background: rgba(22, 101, 52, 0.7);
}

/* IMAGE ZOOM EFFECT */
.zoom {
  transform: scale(1.1);
}

/* CARD HOVER */
.card:hover {
  transform: translateY(-10px) scale(1.02);
}

</style>
</head>

<body class="bg-gradient-to-br from-green-50 via-white to-green-100 font-sans">

<!-- NAVBAR -->
<nav id="navbar" class="fixed top-0 w-full glass z-50 py-5 transition-all duration-300">
  <div class="max-w-7xl mx-auto px-6 flex justify-between items-center">

    <div class="flex items-center gap-3">
      <img src="js/img/municipalLogo.png" id="logo" class="w-12 h-12 transition-all duration-300">
      <h1 id="title" class="text-white font-semibold text-2xl tracking-wide transition-all duration-300">
        Bayung Porac Archive
      </h1>
    </div>

    <a href="records-administrator/index.php"
       class="bg-white/90 text-green-800 px-5 py-2 rounded-full flex items-center gap-2 shadow hover:scale-105 transition">
      <i class="fas fa-user"></i> Login
    </a>

  </div>
</nav>

<!-- NAV SCROLL -->
<script>
const navbar = document.getElementById("navbar");
const logo = document.getElementById("logo");
const title = document.getElementById("title");

window.addEventListener("scroll", () => {
  if (window.scrollY > 50) {
    navbar.classList.add("py-3","shadow-lg");
    navbar.classList.remove("py-5");

    logo.classList.replace("w-12","w-10");
    logo.classList.replace("h-12","h-10");

    title.classList.replace("text-2xl","text-lg");
  } else {
    navbar.classList.remove("py-3","shadow-lg");
    navbar.classList.add("py-5");

    logo.classList.replace("w-10","w-12");
    logo.classList.replace("h-10","h-12");

    title.classList.replace("text-lg","text-2xl");
  }
});
</script>

<!-- HERO SLIDER -->
<section class="relative w-full pt-20 reveal px-0">

  <div class="relative w-full overflow-hidden shadow-2xl h-[650px] md:h-[720px]">

    <!-- Slides -->
    <?php foreach($slideImages as $index => $img){ ?>
      <img 
        src="<?php echo $img; ?>"
        class="slide absolute inset-0 w-full h-full object-cover transition-all duration-1000 ease-in-out
        <?php echo $index == 0 ? 'opacity-100 scale-100' : 'opacity-0 scale-110'; ?>">
    <?php } ?>

    <!-- Overlay gradient -->
    <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/40 to-black/20"></div>

    <!-- Content -->
    <div class="absolute inset-0 flex items-center">
      <div class="max-w-5xl px-6 md:px-16 text-white">

        <h2 id="caption" class="text-3xl md:text-5xl lg:text-6xl font-bold leading-tight tracking-wide drop-shadow-lg">
          <?php echo $slideCaptions[0] ?? ''; ?>
        </h2>

        <p class="mt-4 text-sm md:text-lg text-gray-200 max-w-2xl">
          Discover updates, announcements, and highlights from the municipality.
        </p>

      </div>
    </div>

  </div>

</section>

<!-- PROFILE -->
<section class="max-w-6xl mx-auto px-6 py-16 reveal">
  <div class="grid md:grid-cols-2 gap-12 items-start">

    <!-- TEXT -->
    <div id="profileText" class="transition-all duration-500">

      <p class="uppercase text-gray-900 font-semibold tracking-wider 
text-sm sm:text-base md:text-lg lg:text-xl transition-all duration-300">
  Municipality Officials
</p>

      <h2 id="name"
      class="text-5xl md:text-6xl font-bold text-green-800 mb-4 leading-tight">
      <?php echo $profileData[0]['name'] ?? ''; ?>
      </h2>

      <p id="description"
      class="text-lg md:text-xl text-gray-600 uppercase tracking-wide">
      <?php echo $profileData[0]['role'] ?? ''; ?>
      </p>

    </div>

    <!-- IMAGE -->
    <div class="flex justify-center">
      <img id="profileImage"
           src="<?php echo isset($profileData[0]) ? 'uploads/profiles/'.$profileData[0]['image'] : ''; ?>"
           class="rounded-2xl shadow-xl w-[420px] h-[520px] object-cover transition duration-700 hover:scale-105">
    </div>

  </div>
</section>

<!-- FEATURED -->
<section class="max-w-6xl mx-auto px-6 py-12 reveal">
  <h2 class="text-3xl md:text-4xl font-bold text-green-800 mb-8">
    Featured Collections
  </h2>

  <div id="featuredSlider" class="flex gap-6 overflow-x-auto scroll-smooth snap-x snap-mandatory scrollbar-hide">
    <?php foreach($featuredData as $row){ ?>
      <div class="snap-start min-w-[280px] md:min-w-[320px] bg-white rounded-2xl shadow-lg overflow-hidden transition-transform duration-500 hover:scale-105 hover:shadow-2xl">
        <img src="uploads/featured/<?php echo $row['image']; ?>" 
             class="h-48 w-full object-cover rounded-t-2xl">
        <div class="p-4">
          <p class="font-semibold text-lg md:text-xl text-gray-800">
            <?php echo $row['title']; ?>
          </p>
        </div>
      </div>
    <?php } ?>
  </div>
</section>

<style>

  .scrollbar-hide::-webkit-scrollbar {
  display: none;
}
.scrollbar-hide {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
</style>

<!-- EVENTS -->
<section class="max-w-6xl mx-auto px-6 py-12 reveal">
  <h2 class="text-3xl md:text-4xl font-bold text-green-800 mb-10 text-left">
    Announcement
  </h2>

  <!-- Horizontal scroll container -->
  <div class="flex gap-8 overflow-x-auto scroll-smooth snap-x snap-mandatory pb-4 no-scrollbar">

    <?php foreach($eventData as $index => $row){ ?>
      
      <div class="min-w-[300px] max-w-[300px] bg-white p-6 rounded-3xl shadow-lg hover:shadow-2xl transition-transform duration-500 transform hover:-translate-y-2 cursor-pointer snap-start"
           onclick="toggleContent(<?php echo $index; ?>)">

        <h3 class="font-bold text-xl md:text-2xl text-gray-900 mb-2">
          <?php echo $row['title']; ?>
        </h3>

        <!-- Short preview -->
        <p class="text-gray-700 leading-relaxed" id="short-<?php echo $index; ?>">
          <?php echo substr($row['description'], 0, 120) . '...'; ?>
        </p>

        <!-- Full content -->
        <p class="text-gray-700 leading-relaxed hidden mt-2" id="full-<?php echo $index; ?>">
          <?php echo $row['description']; ?>
        </p>

        <span class="text-green-600 text-sm mt-3 inline-block" id="toggle-text-<?php echo $index; ?>">
          Read more
        </span>

      </div>

    <?php } ?>

  </div>
</section>

<script>
function toggleContent(index) {
  const shortText = document.getElementById('short-' + index);
  const fullText = document.getElementById('full-' + index);
  const toggleText = document.getElementById('toggle-text-' + index);

  if (fullText.classList.contains('hidden')) {
    shortText.classList.add('hidden');
    fullText.classList.remove('hidden');
    toggleText.textContent = 'Show less';
  } else {
    shortText.classList.remove('hidden');
    fullText.classList.add('hidden');
    toggleText.textContent = 'Read more';
  }
}
</script>

<style>
/* Hide scrollbar for Chrome, Safari and Edge */
.no-scrollbar::-webkit-scrollbar {
  display: none;
}

/* Hide scrollbar for IE, Edge */
.no-scrollbar {
  -ms-overflow-style: none;
  scrollbar-width: none; /* Firefox */
}
</style>

<!-- ABOUT -->
<section class="relative py-20 px-6 reveal bg-gradient-to-br from-green-50 via-white to-green-100">

  <!-- Decorative background blur circles -->
  <div class="absolute top-10 left-10 w-72 h-72 bg-green-300/30 rounded-full blur-3xl"></div>
  <div class="absolute bottom-10 right-10 w-72 h-72 bg-green-500/20 rounded-full blur-3xl"></div>

  <!-- Wider container -->
  <div class="max-w-7xl mx-auto relative z-10">

    <div class="bg-white/70 backdrop-blur-xl border border-white/40 rounded-3xl shadow-2xl p-8 md:p-12">

      <!-- Header -->
      <div class="mb-6">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">
          About
        </h2>
        <div class="w-14 h-1 bg-green-500 rounded-full"></div>
      </div>

      <!-- Content -->
      <p class="text-gray-600 leading-relaxed text-sm md:text-base whitespace-pre-line">
        <?php echo $aboutRow['content'] ?? ''; ?>
      </p>

    </div>

  </div>
</section>


<!-- Porac Municipal Hall Location -->
<!-- Contact Us Section (No Form) -->
<!-- Contact Us Section with Scroll Animation -->
<section id="contact-us" style="padding:80px 20px; background-color:#f8f9fa; font-family:Arial, sans-serif;">
  
  
  <div class="contact-container" style="display:flex; flex-wrap:wrap; gap:40px; justify-content:center; align-items:flex-start;">

    <!-- Contact Details -->
    <div class="fade-in-left" style="flex:1; min-width:300px; max-width:500px; background:white; padding:30px; border-radius:10px; box-shadow:0 4px 15px rgba(0,0,0,0.1);">
      <p style="font-size:20px; font-weight:bold; color:#007BFF;">Contact Us</p>
      <p><strong>Address:</strong> Porac Municipal Hall, Brgy. Poblacion, Porac, Pampanga, Philippines</p>
      <p><strong>Phone:</strong> (+63) 45 123 4567</p>
      <p><strong>Email:</strong> info@porac.gov.ph</p>
      <p><strong>Office Hours:</strong> Monday – Friday, 8:00 AM – 5:00 PM</p>
    </div>

    <!-- Google Map -->
    <div class="fade-in-right" style="flex:1; min-width:300px; max-width:600px; height:400px; border-radius:10px; overflow:hidden; box-shadow:0 4px 15px rgba(0,0,0,0.1);">
      <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3850.1468494149326!2d120.54898631538828!3d15.07447587644914!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3396f4b9c835a37d%3A0x31f5e6ff1a63bf79!2sPorac%20Municipal%20Hall!5e0!3m2!1sen!2sph!4v1701012345678!5m2!1sen!2sph"
        width="100%"
        height="100%"
        style="border:0;"
        allowfullscreen=""
        loading="lazy"
        referrerpolicy="no-referrer-when-downgrade">
      </iframe>
    </div>
  </div>
</section>

<style>
/* Scroll Fade-In Animations */
.fade-in-left, .fade-in-right {
  opacity: 0;
  transform: translateY(40px);
  transition: all 1s ease-out;
}

.fade-in-left.visible {
  opacity: 1;
  transform: translateY(0) translateX(0);
}

.fade-in-right.visible {
  opacity: 1;
  transform: translateY(0) translateX(0);
}

/* Optional: staggered horizontal movement */
.fade-in-left.visible { transform: translateY(0) translateX(-10px); }
.fade-in-right.visible { transform: translateY(0) translateX(10px); }
</style>

<script>
// Scroll animation
function revealOnScroll() {
  const elements = document.querySelectorAll('.fade-in-left, .fade-in-right');
  const windowHeight = window.innerHeight;
  
  elements.forEach(el => {
    const elementTop = el.getBoundingClientRect().top;
    const revealPoint = 150; // when to trigger
    
    if(elementTop < windowHeight - revealPoint){
      el.classList.add('visible');
    }
  });
}

window.addEventListener('scroll', revealOnScroll);
window.addEventListener('load', revealOnScroll);
</script>



<!-- FOOTER -->
<footer class="bg-green-900 text-white text-center py-6">
<p>&copy; <?php echo date('Y');?> PSU IT Interns</p>
</footer>

<!-- SCRIPTS -->
<script>
const slides = document.querySelectorAll(".slide");
const captions = <?php echo json_encode($slideCaptions); ?>;
let i = 0;

setInterval(()=>{
  slides[i].classList.remove("opacity-100","scale-100");
  slides[i].classList.add("opacity-0","scale-110");

  i = (i+1)%slides.length;

  slides[i].classList.remove("opacity-0","scale-110");
  slides[i].classList.add("opacity-100","scale-100");

  document.getElementById("caption").textContent = captions[i] || "";
},4000);

/* PROFILE AUTO CHANGE */
const profiles = <?php echo json_encode($profileData); ?>;
let current = 0;

setInterval(() => {
  current = (current + 1) % profiles.length;

  const text = document.getElementById("profileText");
  const img = document.getElementById("profileImage");

  // MOVE WHOLE TEXT UP + FADE OUT
  text.classList.add("opacity-0","-translate-y-6");
  img.classList.add("opacity-0","scale-95");

  setTimeout(() => {
    // CHANGE CONTENT
    document.getElementById("name").textContent = profiles[current].name;
    document.getElementById("description").textContent = profiles[current].role;
    img.src = "uploads/profiles/" + profiles[current].image;

    // RESET POSITION (slightly below for smooth entry)
    text.classList.remove("-translate-y-6");
    text.classList.add("translate-y-6");

    // FORCE REPAINT (important for smooth animation)
    text.offsetHeight;

    // SLIDE UP INTO PLACE
    text.classList.remove("opacity-0","translate-y-6");
    img.classList.remove("opacity-0","scale-95");

  }, 300);

}, 4000);

/* FEATURED */
const slider = document.getElementById("featuredSlider");
function scrollFeatured(direction){
  slider.scrollBy({left: direction*320, behavior:"smooth"});
}

setInterval(()=>{
  const maxScroll = slider.scrollWidth - slider.clientWidth;
  if(slider.scrollLeft >= maxScroll){
    slider.scrollTo({left:0, behavior:"smooth"});
  }else{
    slider.scrollBy({left:320, behavior:"smooth"});
  }
},4000);

/* REVEAL */
const observer = new IntersectionObserver(entries=>{
  entries.forEach(entry=>{
    if(entry.isIntersecting){
      entry.target.classList.add("show");
    }
  });
});
document.querySelectorAll(".reveal").forEach(el=>observer.observe(el));
</script>

</body>
</html>