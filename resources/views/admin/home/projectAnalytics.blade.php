<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - Legatura</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
  <link rel="stylesheet" href="{{ asset('css/admin/home/mainComponents.css') }}">
  <link rel="stylesheet" href="{{ asset('css/admin/home/projectAnalytics.css') }}">
  
  <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/3.0.0/uicons-solid-straight/css/uicons-solid-straight.css'>
  <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/3.0.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
  <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/3.0.0/uicons-bold-rounded/css/uicons-bold-rounded.css'>
  <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/3.0.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
  

  <script src="{{ asset('js/admin/home/mainComponents.js') }}" defer></script>

  
</head>

<body class="bg-gray-50 text-gray-800 font-sans">
  <div class="flex min-h-screen">

    <aside class="bg-white shadow-xl flex flex-col">

      <div class="flex justify-center items-center">
        <img src="{{ asset('img/logo.svg') }}" alt="Legatura Logo" class="logo-img">
      </div>



        <nav class="flex-1 px-3 py-4 space-y-1">
            <div class="nav-group">
                <button class="nav-btn">
                  <div class="flex items-center gap-3">
                  <i class="fi fi-ss-home" style="font-size: 20px;"></i>
                    <span>Home</span>
                  </div>
                  <span class="arrow">▼</span>
                </button>
                <div class="nav-submenu">
                  <a href="{{ route('admin.dashboard') }}" class="submenu-link">Dashboard</a>
                  <div class="submenu-nested">
                    <button class="submenu-link submenu-nested-btn">
                      <span>Analytics</span>
                      <span class="arrow-small">▼</span>
                    </button>
                    <div class="submenu-nested-content">
                      <a href="{{ route('admin.analytics') }}" class="submenu-nested-link active">Project Analytics</a>
                      <a href="{{ route('admin.analytics.subscription') }}" class="submenu-nested-link">Subscription Analytics</a>
                    </div>
                  </div>
                </div>
              </div>


        <div class="nav-group">
          <button class="nav-btn">
            <div class="flex items-center gap-3">
              <i class="fi fi-ss-users-alt" style="font-size: 20px;"></i>
              <span>User Management</span>
            </div>
            <span class="arrow">▼</span>
          </button>

          <div class="nav-submenu">
            <a href="{{ route('admin.userManagement.propertyOwner') }}" class="submenu-link">Property Owner</a>
            <a href="{{ route('admin.userManagement.contractor') }}" class="submenu-link">Contractor</a>
            <a href="{{ route('admin.userManagement.verificationRequest') }}" class="submenu-link">Verification Request</a>
            <a href="{{ route('admin.userManagement.suspendedAccounts') }}" class="submenu-link">Suspended Accounts</a>
          </div>
        </div>


        <div class="nav-group">
          <button class="nav-btn">
            <div class="flex items-center gap-3">
            <i class="fi fi-ss-globe" style="font-size: 20px;"></i>

              <span>Global Management</span>
            </div>
            <span class="arrow">▼</span>
          </button>
          <div class="nav-submenu">
            <a href="{{ route('admin.globalManagement.bidManagement') }}" class="submenu-link">Bid Management</a>
            <a href="{{ route('admin.globalManagement.proofOfpayments') }}" class="submenu-link">Proof of Payments</a>
            <a href="{{ route('admin.globalManagement.aiManagement') }}" class="submenu-link">AI Management</a>
          </div>
        </div>

        <div class="nav-group">
          <button class="nav-btn">
            <div class="flex items-center gap-3">
              <i class="fi fi-sr-master-plan" style="font-size: 20px;"></i>
              <span>Project Management</span>
            </div>
            <span class="arrow">▼</span>
          </button>
          <div class="nav-submenu">
            <a href="#" class="submenu-link">Disputes/Reports</a>
            <a href="#" class="submenu-link">Messages</a>
            <a href="#" class="submenu-link">Subscriptions & Boosts</a>
          </div>
        </div>

        <div class="nav-group">
          <button class="nav-btn">
            <div class="flex items-center gap-3">
            <i class="fi fi-br-settings-sliders" style="font-size: 20px;"></i>
              <span>Settings</span>
            </div>
            <span class="arrow">▼</span>
          </button>
          <div class="nav-submenu">
            <a href="#" class="submenu-link">Notifications</a>
            <a href="#" class="submenu-link">Security</a>
          </div>
        </div>
      </nav>

      <div class="mt-auto p-4">
          <div class="user-card flex items-center gap-3 p-3 rounded-lg shadow-md text-white">
              <div class="w-10 h-10 rounded-full bg-white text-indigo-900 flex items-center justify-center font-bold shadow flex-shrink-0">
                  ES
              </div>
              <div class="flex-1 min-w-0">
                  <div class="font-semibold text-sm truncate">Emmanuelle Santos</div>
                  <div class="text-xs opacity-80 truncate">santos@Legatura.com</div>
              </div>
              <button class="text-white opacity-80 hover:opacity-100 transition text-2xl">⋮</button>
          </div>
      </div>

    </aside>

    <main class="flex-1">
      <header class="bg-white shadow-sm border-b border-gray-200 flex items-center justify-between px-8 py-4 sticky top-0 z-30">
        <h1 class="text-2xl font-semibold text-gray-800">Project Analytics</h1>

        <div class="flex items-center gap-6">
          <div class="relative w-64" style="width: 600px;">
            <input 
              type="text" 
              placeholder="Search..." 
              class="border border-gray-300 rounded-lg px-4 py-2 pr-10 focus:ring-2 focus:ring-indigo-400 focus:outline-none w-full"
            >
            <i class="fi fi-rr-search absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
          </div>


          <div class="relative cursor-pointer">
          <i class="fi fi-ss-bell-notification-social-media" style="font-size: 20px;"></i>
            <span class="absolute -top-1 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">3</span>
          </div>
        </div>
      </header>

      <div class="analytics-container">
        <!-- Projects Analytics Card -->
        <div class="analytics-card">
          <h2 class="analytics-card-title">Projects</h2>
          
          <div class="projects-analytics-content">
            <!-- Donut Chart -->
            <div class="projects-chart-wrapper">
              <canvas id="projectsDonutChart" 
                data-labels='{{ json_encode(array_column($projectsAnalytics["data"], "label")) }}'
                data-values='{{ json_encode(array_column($projectsAnalytics["data"], "count")) }}'>
              </canvas>
            </div>

            <!-- Legend -->
            <div class="projects-legend">
              @foreach($projectsAnalytics['data'] as $index => $item)
              <div class="legend-item" data-index="{{ $index }}">
                <div class="legend-dot" data-color="{{ $index }}"></div>
                <div class="legend-info">
                  <span class="legend-label">{{ $item['label'] }}</span>
                  <span class="legend-count">{{ $item['count'] }}</span>
                </div>
              </div>
              @endforeach
            </div>
          </div>
        </div>

        <!-- Project Success Rate Card -->
        <div class="analytics-card">
          <h2 class="analytics-card-title">Project Success Rate</h2>
          <p class="analytics-card-subtitle">Completion rate by project type</p>
          
          <div class="success-rate-content">
            <!-- Pie Chart -->
            <div class="success-rate-chart-wrapper">
              <canvas id="projectSuccessRateChart" 
                data-labels='{{ json_encode(array_column($projectSuccessRate["data"], "label")) }}'
                data-values='{{ json_encode(array_column($projectSuccessRate["data"], "count")) }}'
                data-colors='{{ json_encode(array_column($projectSuccessRate["data"], "color")) }}'>
              </canvas>
            </div>

            <!-- Legend with Lines -->
            <div class="success-rate-legend">
              @foreach($projectSuccessRate['data'] as $index => $item)
              <div class="success-rate-legend-item" data-index="{{ $index }}">
                <div class="success-rate-legend-line" style="background: {{ $item['color'] }};"></div>
                <div class="success-rate-legend-info">
                  <span class="success-rate-legend-label">{{ $item['label'] }}</span>
                </div>
              </div>
              @endforeach
            </div>
          </div>
        </div>

        <!-- Projects Timeline Chart -->
        <div class="analytics-card projects-timeline-card">
          <div class="timeline-header">
            <h2 class="analytics-card-title">Projects</h2>
            <div class="timeline-date-picker-wrapper">
              <div class="timeline-date-picker">
                <span class="timeline-date-range">{{ $projectsTimeline['dateRange'] }}</span>
                <button class="timeline-dropdown-btn" id="timelineDropdownBtn">
                  <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                    <path d="M2.5 4.5L6 8L9.5 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                </button>
              </div>
              <div class="timeline-dropdown-menu" id="timelineDropdownMenu">
                <button class="timeline-dropdown-item" data-range="last3months">Last 3 Months</button>
                <button class="timeline-dropdown-item active" data-range="last6months">Last 6 Months</button>
                <button class="timeline-dropdown-item" data-range="thisyear">This Year</button>
                <button class="timeline-dropdown-item" data-range="lastyear">Last Year</button>
              </div>
            </div>
          </div>

          <!-- Chart Legend -->
          <div class="timeline-legend">
            <div class="timeline-legend-item" data-dataset="0">
              <span class="timeline-legend-dot" style="background: #fb923c;"></span>
              <span class="timeline-legend-label">New Projects</span>
            </div>
            <div class="timeline-legend-item" data-dataset="1">
              <span class="timeline-legend-dot" style="background: #818cf8;"></span>
              <span class="timeline-legend-label">Completed Projects</span>
            </div>
          </div>

          <!-- Chart Container -->
          <div class="timeline-chart-container">
            <canvas id="projectsTimelineChart"
              data-months='{{ json_encode($projectsTimeline["months"]) }}'
              data-new='{{ json_encode($projectsTimeline["newProjects"]) }}'
              data-completed='{{ json_encode($projectsTimeline["completedProjects"]) }}'>
            </canvas>
          </div>
        </div>
      </div>

      </main>
  </div>

  <script src="{{ asset('js/admin/home/analytics.js') }}" defer></script>

</body>

</html>