  <!-- SIDEBAR -->
  <aside id="sidebar" class="lg:w-1/4 w-72 lg:h-[650px] fixed lg:static inset-y-0 left-0 z-30 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out lg:flex lg:flex-col">
    <div class="bg-white/95 backdrop-blur-lg rounded-3xl lg:rounded-[3rem] shadow-2xl p-3 lg:p-8 border border-gray-200/50 flex flex-col h-screen lg:h-[650px] items-center relative overflow-hidden">
      
      <!-- Close Button (Mobile) -->
      <button onclick="toggleSidebar()" class="lg:hidden absolute top-2 right-2 text-gray-500 text-lg">
        <i class="fas fa-times"></i>
      </button>

      <!-- CONTENT -->
      <div class="flex flex-col w-full h-full pt-4">

        <!-- LOGO -->
        <div class="mb-6 flex justify-center">
          <img src="img/adminLogo.png"
               class="w-32 h-32 object-cover rounded-3xl shadow-xl border-4 border-white">
        </div>

        <!-- ADMIN NAME -->
        <div class="text-center mb-6">
          <p class="font-bold text-lg text-gray-800">
            <?php echo ucwords(htmlentities($_SESSION['admin_name'])); ?>
          </p>

          <a href="#" onclick="confirmLogout(this)" 
             class="mt-2 inline-block bg-green-500 text-white px-4 py-1 rounded-lg text-sm hover:bg-green-600">
            Log out
          </a>
        </div>

        <!-- MENU -->
        <nav class="flex flex-col gap-2 w-full">

          <a href="folder_management.php" class="sidebar-link">
            <i class="fas fa-home"></i> Records Management
          </a>

          <a href="communication_letters.php" class="sidebar-link">
            <i class="fas fa-building"></i> Communication Letters
          </a>

        </nav>
      </div>
    </div>
  </aside>