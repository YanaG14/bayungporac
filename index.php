<?php
require_once("include/connection.php");

/* FETCH DATA */
$slides = mysqli_query($conn,"SELECT * FROM homepage_slides WHERE status='Active' ORDER BY slide_id DESC");
$profiles = mysqli_query($conn,"SELECT * FROM homepage_profiles WHERE status='Active' ORDER BY profile_id DESC");
$featured = mysqli_query($conn,"SELECT * FROM homepage_featured WHERE status='Active' ORDER BY featured_id DESC");
$events = mysqli_query($conn,"SELECT * FROM homepage_events WHERE status='Active' ORDER BY event_id DESC");

$about = mysqli_query($conn,"SELECT * FROM homepage_about LIMIT 1");
$aboutRow = mysqli_fetch_assoc($about);

/* Convert slides to JS arrays */
$slideImages = [];
$slideCaptions = [];

while($row = mysqli_fetch_assoc($slides)){
    $slideImages[] = "uploads/slides/".$row['image'];
    $slideCaptions[] = $row['caption'];
}

/* Profiles array */
$profileData = [];
while($row = mysqli_fetch_assoc($profiles)){
    $profileData[] = $row;
}

/* Featured */
$featuredData = [];
while($row = mysqli_fetch_assoc($featured)){
    $featuredData[] = $row;
}

/* Events */
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

</head>

<body class="bg-gradient-to-br from-green-50 via-white to-green-100 font-sans">

<!-- NAVBAR (UNCHANGED) -->
<nav id="navbar" class="fixed top-0 w-full bg-green-700/90 backdrop-blur z-50 py-6">
  <div class="max-w-7xl mx-auto px-6 flex justify-between items-center">

    <div class="flex items-center gap-3">
      <img src="js/img/municipalLogo.png" id="logo" class="w-12 h-12">
      <h1 id="title" class="text-white font-semibold text-xl md:text-2xl">Bayung Porac Archive</h1>
    </div>

    <div class="flex gap-4 items-center">
      <a href="Private_Dashboard/index.php"
         class="bg-white text-green-800 px-4 py-2 rounded-full flex items-center gap-2">
        <i class="fas fa-user"></i> Login
      </a>
    </div>

  </div>
</nav>

<!-- HERO -->
<section class="pt-28 pb-10 px-4">
<div class="max-w-6xl mx-auto">
<div class="relative rounded-2xl overflow-hidden shadow-2xl">

<div class="relative h-[500px]">

<?php foreach($slideImages as $index => $img){ ?>
<img src="<?php echo $img; ?>"
     class="slide absolute inset-0 w-full h-full object-cover <?php echo $index == 0 ? 'opacity-100' : 'opacity-0'; ?> transition">
<?php } ?>

<div class="absolute inset-0 bg-black/60"></div>

<div class="absolute bottom-0 p-8 text-white">
<h2 id="caption" class="text-3xl font-bold">
<?php echo $slideCaptions[0] ?? ''; ?>
</h2>
<p class="text-sm">Preserving history digitally</p>
</div>

</div>
</div>
</div>
</section>

<!-- PROFILE -->
<section class="max-w-6xl mx-auto px-6 py-10 mt-20">

<div class="grid md:grid-cols-2 gap-8 items-start">

<div id="profileText">

<p id="role" class="text-lg font-semibold uppercase">
Officials of the Municipality of Porac, Pampanga
</p>

<h2 id="name" class="text-5xl font-semibold text-green-700 mb-4">
<?php echo $profileData[0]['name'] ?? ''; ?>
</h2>

<p id="description" class="text-lg uppercase">
<?php echo $profileData[0]['role'] ?? ''; ?>
</p>

</div>

<div class="flex justify-end">
<img id="profileImage"
     src="<?php echo isset($profileData[0]) ? 'uploads/profiles/'.$profileData[0]['image'] : 'js/img/pic6.jfif'; ?>"
     class="rounded-lg w-full max-w-md shadow-lg">
</div>

</div>

</section>

<!-- FEATURED -->
<section class="max-w-6xl mx-auto px-6 py-10">
<h2 class="text-2xl font-bold text-green-800 mb-6">Featured Collections</h2>

<div class="grid md:grid-cols-3 gap-6">

<?php foreach($featuredData as $row){ ?>
<div class="bg-white rounded-2xl shadow p-4">
<img src="uploads/featured/<?php echo $row['image']; ?>" class="h-40 w-full object-cover rounded">
<p class="mt-2 font-semibold"><?php echo $row['title']; ?></p>
</div>
<?php } ?>

</div>
</section>

<!-- EVENTS -->
<section class="max-w-6xl mx-auto px-6 py-10">
<h2 class="text-2xl font-bold text-green-800 mb-6">Events</h2>

<div class="grid md:grid-cols-3 gap-6">

<?php foreach($eventData as $row){ ?>
<div class="bg-white p-5 rounded-2xl shadow">
<h3 class="font-bold"><?php echo $row['title']; ?></h3>
<p class="text-sm text-gray-600 mt-2"><?php echo $row['description']; ?></p>
</div>
<?php } ?>

</div>
</section>

<!-- ABOUT -->
<section class="max-w-5xl mx-auto px-6 py-12">
<div class="bg-green-700 text-white p-10 rounded-3xl">
<h2 class="text-3xl font-bold mb-4">About</h2>
<p>
<?php echo nl2br($aboutRow['content'] ?? 'No content available'); ?>
</p>
</div>
</section>

<!-- FOOTER (UNCHANGED) -->
<footer class="bg-green-900 text-white text-center py-6">
<p>All right Reserved &copy; <?php echo date('Y');?> Created By: PSU IT Interns</p>
</footer>

<script>
/* SLIDER */
const slides = document.querySelectorAll(".slide");
const captions = <?php echo json_encode($slideCaptions); ?>;

let i = 0;

setInterval(()=>{
slides[i].classList.replace("opacity-100","opacity-0");
i = (i+1)%slides.length;
slides[i].classList.replace("opacity-0","opacity-100");

document.getElementById("caption").textContent = captions[i] || "";

},3000);

/* PROFILE ROTATION */
const profiles = <?php echo json_encode($profileData); ?>;

let current = 0;

setInterval(() => {

current = (current + 1) % profiles.length;

document.getElementById("role").textContent = profiles[current].role;
document.getElementById("name").textContent = profiles[current].name;
document.getElementById("description").textContent = profiles[current].role;

document.getElementById("profileImage").src = "uploads/profiles/" + profiles[current].image;

}, 8000);
</script>

</body>
</html>