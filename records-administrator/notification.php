 <!-- 🔔 NOTIFICATION ICON -->
  <div class="relative">
    <button onclick="toggleNotif()" 
      class="relative p-2 rounded-full hover:bg-green-600 transition">

      <i class="fas fa-bell text-white text-lg"></i>

      <!-- 🔴 BADGE -->
      <span id="notifCount"
        class="absolute -top-1 -right-1 bg-red-500 text-white text-xs px-1.5 rounded-full hidden">
        0
      </span>
    </button>

    <!-- NOTIFICATION DROPDOWN -->
    <div id="notifDropdown"
      class="hidden absolute right-0 mt-3 w-80 bg-white rounded-xl shadow-lg z-50 max-h-80 overflow-y-auto">

      <div class="p-3 border-b font-semibold text-gray-700">
        Notifications
      </div>

      <div id="notifList" class="divide-y text-sm text-gray-700">
        <div class="p-3 text-gray-400 text-center">No notifications</div>
      </div>

    </div>
  </div>



<script>
function toggleNotif() {
  const box = document.getElementById('notifDropdown');
  box.classList.toggle('hidden');
}

document.addEventListener('click', function (e) {
  const notifBox = document.getElementById('notifDropdown');
  const notifBtn = e.target.closest('button[onclick="toggleNotif()"]');

  // if click is NOT inside notification box AND NOT the bell button
  if (!notifBox.contains(e.target) && !notifBtn) {
    notifBox.classList.add('hidden');
  }
});



function loadNotifications(){
  $.get("fetch_notifications_admin.php", function(res){

    let data = JSON.parse(res);

    let html = "";

    if(data.length === 0){
      html = `<div class="p-3 text-gray-400 text-center">No notifications</div>`;
      $("#notifCount").addClass("hidden");
    } else {
      data.forEach(n => {
        html += `
  <a href="view_letter.php?id=${n.letter_id}&notif_id=${n.id}"
     class="block p-3 hover:bg-gray-100 transition">

            <div class="text-sm text-gray-800">
              ${n.text}
            </div>

            <div class="text-xs text-gray-500">
              ${n.time}
            </div> 

          </a>
        `;
      });

      $("#notifCount").removeClass("hidden").text(data.length);
    }

    $("#notifList").html(html);
  });
}

setInterval(loadNotifications, 1000);
loadNotifications();




</script>
