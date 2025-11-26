<?php 
include '../koneksi.php';

// Query untuk mengambil total peserta dari tabel participant
$queryPeserta = "SELECT COUNT(*) as total_peserta FROM participant";
$resultPeserta = mysqli_query($koneksi, $queryPeserta);
$dataPeserta = mysqli_fetch_assoc($resultPeserta);
$totalPeserta = $dataPeserta['total_peserta'];

// Query untuk mengambil total rapat dari tabel meetings
$queryRapat = "SELECT COUNT(*) as total_rapat FROM meetings";
$resultRapat = mysqli_query($koneksi, $queryRapat);
$dataRapat = mysqli_fetch_assoc($resultRapat);
$totalRapat = $dataRapat['total_rapat'];

// Query untuk mengambil data rapat dari tabel meetings untuk kalender
$queryMeetings = "SELECT id, title, descriptions, dates, start_time, end_time, locations, leader, created_by, created_at FROM meetings ORDER BY dates";
$resultMeetings = mysqli_query($koneksi, $queryMeetings);
$meetingsData = array();

while ($row = mysqli_fetch_assoc($resultMeetings)) {
    $meetingsData[] = $row;
}

// Convert meetings data to JSON for JavaScript
$meetingsJson = json_encode($meetingsData);
?>

  <!DOCTYPE html>
  <html lang="id">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/style_dashboard_admin.css">
    <title>Pengelolaan Rapat - Admin</title>
   
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  </head>
  <body>
    <!-- Sidebar -->
    <div class="sidebar">
      <h2>Pengelolaan Rapat</h2>
      <div class="menu">
        <a href="dashboard_admin.php" class="active"><i class="fas fa-home"></i> Home</a>
        <a href="jadwal_admin.php"><i class="fas fa-calendar-alt"></i> Jadwal</a>
        <a href="peserta_admin.php"><i class="fas fa-user-graduate"></i> Peserta</a>
        <a href="notulen_admin.php"><i class="fas fa-file-alt"></i> Notulen</a>
        <a href="undangan_admin.php"><i class="fas fa-file-alt"></i> Undangan</a>
    
      </div>
    </div>

  <!-- Main Content -->
  <div class="main">
    <div class="topbar">
      <h1>Admin</h1>
      <div class="right">
        <div class="search-box">
          <input type="text" placeholder="Search...">
          <i class="fas fa-search"></i>
        </div>
        <i class="fas fa-bell bell"></i>
      </div>
    </div>

    <!-- Calendar Section -->
    <div class="calendar-section">
      <div class="calendar-header">
        <h3 id="calendarMonth">Desember 2023</h3>
        <div class="calendar-nav">
          <button id="prevMonth"><i class="fas fa-chevron-left"></i></button>
          <button id="nextMonth"><i class="fas fa-chevron-right"></i></button>
        </div>
      </div>
      <div class="calendar-grid" id="calendarDates">
        <div class="calendar-day">Sen</div>
        <div class="calendar-day">Sel</div>
        <div class="calendar-day">Rab</div>
        <div class="calendar-day">Kam</div>
        <div class="calendar-day">Jum</div>
        <div class="calendar-day">Sab</div>
        <div class="calendar-day">Min</div>
        <!-- Calendar dates will be populated by JavaScript -->
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-cards">
      <div class="stat-card">
        <div class="stat-number" id="totalPeserta"><?php echo $totalPeserta; ?></div>
        <div class="stat-label">Total Peserta</div>
      </div>
      <div class="stat-card">
        <div class="stat-number" id="totalRapat"><?php echo $totalRapat; ?></div>
        <div class="stat-label">Total Rapat</div>
      </div>
    </div>
  </div>

  <!-- Meeting Tooltip -->
  <div id="meetingTooltip" class="meeting-tooltip"></div>

  <script>
    // Get meetings data from PHP
    const meetingsData = <?php echo $meetingsJson; ?>;
    
    // Convert meetings data to a more usable format
    const meetingsMap = {};
    meetingsData.forEach(meeting => {
      const date = new Date(meeting.dates);
      const dateString = date.toISOString().split('T')[0]; // Format: YYYY-MM-DD
      if (!meetingsMap[dateString]) {
        meetingsMap[dateString] = [];
      }
      meetingsMap[dateString].push(meeting);
    });

    // Calendar functionality
    let currentDate = new Date();
    
    function formatTime(timeString) {
      if (!timeString) return '';
      const time = new Date(`1970-01-01T${timeString}`);
      return time.toLocaleTimeString('id-ID', { 
        hour: '2-digit', 
        minute: '2-digit',
        hour12: false 
      });
    }

    function showMeetingTooltip(meetings, x, y) {
      const tooltip = document.getElementById('meetingTooltip');
      let html = '';
      
      meetings.forEach((meeting, index) => {
        html += `
          <div style="margin-bottom: ${index < meetings.length - 1 ? '15px' : '0'}">
            <h4>${meeting.title}</h4>
            <p class="time">⏰ ${formatTime(meeting.start_time)} - ${formatTime(meeting.end_time)}</p>
            <p class="location">📍 ${meeting.location || 'Tidak ada lokasi'}</p>
            <p class="leader">👤 ${meeting.leader || 'Tidak ada pemimpin'}</p>
            ${meeting.descriptions ? `<p style="margin-top: 8px; font-size: 11px; color: #666;">${meeting.descriptions}</p>` : ''}
          </div>
        `;
      });
      
      tooltip.innerHTML = html;
      tooltip.style.display = 'block';
      tooltip.style.left = (x + 10) + 'px';
      tooltip.style.top = (y + 10) + 'px';
    }

    function hideMeetingTooltip() {
      document.getElementById('meetingTooltip').style.display = 'none';
    }
    
    function updateCalendar() {
      const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni",
                         "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
      
      const year = currentDate.getFullYear();
      const month = currentDate.getMonth();
      
      // Update month display
      document.getElementById('calendarMonth').textContent = `${monthNames[month]} ${year}`;
      
      // Get first day of month and number of days
      const firstDay = new Date(year, month, 1);
      const lastDay = new Date(year, month + 1, 0);
      const daysInMonth = lastDay.getDate();
      const startingDay = firstDay.getDay();
      
      // Adjust starting day for Indonesian calendar (Monday first)
      const adjustedStartingDay = startingDay === 0 ? 6 : startingDay - 1;
      
      // Get previous month days
      const prevMonthLastDay = new Date(year, month, 0).getDate();
      
      // Clear calendar
      const calendarGrid = document.getElementById('calendarDates');
      // Keep the day headers
      while (calendarGrid.children.length > 7) {
        calendarGrid.removeChild(calendarGrid.lastChild);
      }
      
      // Add previous month days
      for (let i = adjustedStartingDay - 1; i >= 0; i--) {
        const dateElement = document.createElement('div');
        dateElement.className = 'calendar-date other-month';
        dateElement.textContent = prevMonthLastDay - i;
        calendarGrid.appendChild(dateElement);
      }
      
      // Add current month days
      const today = new Date();
      for (let i = 1; i <= daysInMonth; i++) {
        const dateElement = document.createElement('div');
        dateElement.className = 'calendar-date';
        dateElement.textContent = i;
        
        // Create date string for comparison
        const currentDateString = `${year}-${String(month + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;
        
        // Check if today
        if (today.getDate() === i && today.getMonth() === month && today.getFullYear() === year) {
          dateElement.classList.add('today');
        }
        
        // Check if date has meetings from database
        if (meetingsMap[currentDateString]) {
          const meetings = meetingsMap[currentDateString];
          dateElement.classList.add('has-meeting');
          
          // Add meeting dot indicator
          const meetingDot = document.createElement('div');
          meetingDot.className = 'meeting-dot';
          dateElement.appendChild(meetingDot);

          // Add meeting count badge if multiple meetings
          if (meetings.length > 1) {
            const meetingCount = document.createElement('div');
            meetingCount.className = 'meeting-info';
            meetingCount.textContent = meetings.length;
            dateElement.appendChild(meetingCount);
          }
          
          // Add event listeners for tooltip
          dateElement.addEventListener('mouseenter', function(e) {
            showMeetingTooltip(meetings, e.pageX, e.pageY);
          });
          
          dateElement.addEventListener('mousemove', function(e) {
            const tooltip = document.getElementById('meetingTooltip');
            tooltip.style.left = (e.pageX + 10) + 'px';
            tooltip.style.top = (e.pageY + 10) + 'px';
          });
          
          dateElement.addEventListener('mouseleave', hideMeetingTooltip);
        }
        
        calendarGrid.appendChild(dateElement);
      }
      
      // Add next month days to fill grid
      const totalCells = 42; // 6 rows * 7 days
      const remainingCells = totalCells - (adjustedStartingDay + daysInMonth);
      for (let i = 1; i <= remainingCells; i++) {
        const dateElement = document.createElement('div');
        dateElement.className = 'calendar-date other-month';
        dateElement.textContent = i;
        calendarGrid.appendChild(dateElement);
      }
    }
    
    // Navigation buttons
    document.getElementById('prevMonth').addEventListener('click', function() {
      currentDate.setMonth(currentDate.getMonth() - 1);
      updateCalendar();
    });
    
    document.getElementById('nextMonth').addEventListener('click', function() {
      currentDate.setMonth(currentDate.getMonth() + 1);
      updateCalendar();
    });
    
    // Initialize calendar
    updateCalendar();

    // Hide tooltip when clicking anywhere
    document.addEventListener('click', hideMeetingTooltip);
  </script>
</body>
</html>