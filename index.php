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
.reveal {
  opacity: 0;
  transform: translateY(40px);
  transition: all 0.8s ease;
}
.reveal.show {
  opacity: 1;
  transform: translateY(0);
}
.slide {
  transition: opacity 1.2s ease-in-out, transform 1.2s ease-in-out;
}
</style>

</head>

<body class="bg-gradient-to-br from-green-50 via-white to-green-100 font-sans">

<nav id="navbar" class="fixed top-0 w-full bg-green-700/80 backdrop-blur-md z-50 py-5 transition-all duration-300">
  <div class="max-w-7xl mx-auto px-6 flex justify-between items-center">

    <div class="flex items-center gap-3">
      <img src="js/img/municipalLogo.png" id="logo" class="w-12 h-12 transition-all duration-300">
      <h1 id="title" class="text-white font-semibold text-xl md:text-2xl transition-all duration-300">
        Bayung Porac Archive
      </h1>
    </div>

    <a href="records-administrator/index.php"
       class="bg-white text-green-800 px-4 py-2 rounded-full flex items-center gap-2 shadow-sm hover:shadow-md hover:scale-105 transition-all duration-300">
      <i class="fas fa-user"></i> Login
    </a>

  </div>
</nav>

<script>
const navbar = document.getElementById("navbar");
const logo = document.getElementById("logo");
const title = document.getElementById("title");

window.addEventListener("scroll", () => {
  if (window.scrollY > 40) {
    navbar.classList.add("py-3", "shadow-md");
    navbar.classList.remove("py-5");

    logo.classList.replace("w-12","w-10");
    logo.classList.replace("h-12","h-10");

    title.classList.replace("text-2xl","text-lg");
  } else {
    navbar.classList.remove("py-3", "shadow-md");
    navbar.classList.add("py-5");

    logo.classList.replace("w-10","w-12");
    logo.classList.replace("h-10","h-12");

    title.classList.replace("text-lg","text-2xl");
  }
});
</script>

<section class="pt-28 pb-10 px-4 reveal">
<div class="max-w-6xl mx-auto">
<div class="relative rounded-2xl overflow-hidden shadow-2xl">
<div class="relative h-[500px]">

<?php foreach($slideImages as $index => $img){ ?>
<img src="<?php echo $img; ?>"
     class="slide absolute inset-0 w-full h-full object-cover <?php echo $index == 0 ? 'opacity-100 scale-100' : 'opacity-0 scale-105'; ?> transition duration-1000">
<?php } ?>

<div class="absolute inset-0 bg-black/60"></div>

<div class="absolute bottom-0 p-8 text-white">
<h2 id="caption" class="text-3xl font-bold transition-all duration-500">
<?php echo $slideCaptions[0] ?? ''; ?>
</h2>

</div>

</div>
</div>
</div>
</section>

<section class="max-w-6xl mx-auto px-6 py-10 mt-20 reveal">
<div class="grid md:grid-cols-2 gap-8 items-start">

<div>
<p class="text-lg font-semibold uppercase">Officials of the Municipality of Porac, Pampanga</p>
<h2 id="name" class="text-5xl font-semibold text-green-700 mb-4 transition-all duration-500">
<?php echo $profileData[0]['name'] ?? ''; ?>
</h2>
<p id="description" class="text-lg uppercase">
<?php echo $profileData[0]['role'] ?? ''; ?>
</p>
</div>

<div class="flex justify-end">
  <div class="w-full max-w-2xl h-[600px] flex items-center justify-center">
    
    <img id="profileImage"
         src="<?php echo isset($profileData[0]) ? 'uploads/profiles/'.$profileData[0]['image'] : 'js/img/pic6.jfif'; ?>"
         class="rounded-lg max-w-full max-h-full shadow-lg transition-all duration-700 hover:scale-105">

  </div>
</div>

</div>
</section>

<section class="max-w-6xl mx-auto px-6 py-10 reveal">
<h2 class="text-2xl font-bold text-green-800 mb-6">Featured Collections</h2>

<div class="relative">

  <!-- Controls -->
  <button onclick="scrollFeatured(-1)"
    class="absolute left-0 top-1/2 -translate-y-1/2 bg-white shadow-md p-3 rounded-full z-10 hover:scale-110 transition">
    <i class="fas fa-chevron-left"></i>
  </button>

  <button onclick="scrollFeatured(1)"
    class="absolute right-0 top-1/2 -translate-y-1/2 bg-white shadow-md p-3 rounded-full z-10 hover:scale-110 transition">
    <i class="fas fa-chevron-right"></i>
  </button>

  <!-- Slider -->
  <div id="featuredSlider" class="flex overflow-x-hidden scroll-smooth gap-6">

    <?php foreach($featuredData as $row){ ?>
    <div class="min-w-[300px] bg-white rounded-2xl shadow p-4 transition transform hover:-translate-y-2 hover:shadow-xl duration-500">
      <img src="uploads/featured/<?php echo $row['image']; ?>" class="h-40 w-full object-cover rounded">
      <p class="mt-2 font-semibold"><?php echo $row['title']; ?></p>
    </div>
    <?php } ?>

  </div>
</div>
</section>

<script>
const slider = document.getElementById("featuredSlider");

function scrollFeatured(direction) {
  const cardWidth = 320; // width + gap approximation
  slider.scrollBy({
    left: direction * cardWidth,
    behavior: "smooth"
  });
}

/* Auto slide */
setInterval(() => {
  const maxScroll = slider.scrollWidth - slider.clientWidth;

  if (slider.scrollLeft >= maxScroll) {
    slider.scrollTo({ left: 0, behavior: "smooth" });
  } else {
    slider.scrollBy({ left: 320, behavior: "smooth" });
  }
}, 4000);
</script>

<section class="max-w-6xl mx-auto px-6 py-10 reveal">
<h2 class="text-2xl font-bold text-green-800 mb-6">Events</h2>
<div class="grid md:grid-cols-3 gap-6">
<?php foreach($eventData as $row){ ?>
<div class="bg-white p-5 rounded-2xl shadow transition hover:scale-105 duration-500">
<h3 class="font-bold"><?php echo $row['title']; ?></h3>
<p class="text-sm text-gray-600 mt-2"><?php echo $row['description']; ?></p>
</div>
<?php } ?>
</div>
</section>

<section class="max-w-5xl mx-auto px-6 py-12 reveal">
<div class="bg-green-700 text-white p-10 rounded-3xl">
<h2 class="text-3xl font-bold mb-4">About</h2>
<p><?php echo nl2br($aboutRow['content'] ?? 'No content available'); ?></p>
</div>
</section>

<footer class="bg-green-900 text-white text-center py-6">
<p>All right Reserved &copy; <?php echo date('Y');?> Created By: PSU IT Interns</p>
</footer>

<script>
const slides = document.querySelectorAll(".slide");
const captions = <?php echo json_encode($slideCaptions); ?>;
let i = 0;

setInterval(()=>{
  slides[i].classList.replace("opacity-100","opacity-0");
  slides[i].classList.replace("scale-100","scale-105");

  i = (i+1)%slides.length;

  slides[i].classList.replace("opacity-0","opacity-100");
  slides[i].classList.replace("scale-105","scale-100");

  document.getElementById("caption").textContent = captions[i] || "";
},3000);

const profiles = <?php echo json_encode($profileData); ?>;
let current = 0;

setInterval(() => {
  current = (current + 1) % profiles.length;

  document.getElementById("name").textContent = profiles[current].name;
  document.getElementById("description").textContent = profiles[current].role;
  document.getElementById("profileImage").src = "uploads/profiles/" + profiles[current].image;

}, 3000);

/* SCROLL REVEAL */
const observer = new IntersectionObserver(entries => {
  entries.forEach(entry => {
    if(entry.isIntersecting){
      entry.target.classList.add("show");
    }
  });
});

document.querySelectorAll(".reveal").forEach(el => observer.observe(el));
</script>

</body>
</html>
