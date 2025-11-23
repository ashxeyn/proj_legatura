<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - Legatura</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
  <link rel="stylesheet" href="{{ asset('css/admin/home/mainComponents.css') }}">
  <link rel="stylesheet" href="{{ asset('css/admin/home/subscriptionAnalytics.css') }}">
  
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
                      <a href="{{ route('admin.analytics') }}" class="submenu-nested-link">Project Analytics</a>
                      <a href="{{ route('admin.analytics.subscription') }}" class="submenu-nested-link active">Subscription Analytics</a>
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
        <h1 class="text-2xl font-semibold text-gray-800">Subscription Analytics</h1>

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

      <div class="p-8">
        <!-- Subscription Stats Cards -->
        <div class="subscription-stats-grid">
          <!-- Total Subscriptions Card -->
          <div class="stat-card stat-card-blue">
            <div class="stat-card-header">
              <div class="stat-icon stat-icon-blue">
                <i class="fi fi-sr-users-alt"></i>
              </div>
              <div class="stat-info">
                <h3 class="stat-label">Total Subscriptions</h3>
                <div class="stat-value-row">
                  <p class="stat-value">{{ $subscriptionMetrics['active'] }}</p>
                  <span class="stat-total">/{{ $subscriptionMetrics['total'] }}</span>
                </div>
              </div>
            </div>
            <div class="stat-progress-bar">
              <div class="stat-progress-fill stat-progress-blue" 
                style="width: {{ $subscriptionMetrics['total'] > 0 ? round(($subscriptionMetrics['active'] / $subscriptionMetrics['total']) * 100) : 0 }}%"></div>
            </div>
          </div>

          <!-- Total Revenue Card -->
          <div class="stat-card stat-card-green">
            <div class="stat-card-header">
              <div class="stat-icon stat-icon-green">
                <i class="fi fi-sr-peso-sign"></i>
              </div>
              <div class="stat-info">
                <h3 class="stat-label">Total Revenue</h3>
                <p class="stat-value">₱{{ number_format($subscriptionMetrics['revenue'], 2) }}</p>
              </div>
            </div>
            <p class="stat-description">From commission payments</p>
          </div>

          <!-- Expiring Soon Card -->
          <div class="stat-card stat-card-orange">
            <div class="stat-card-header">
              <div class="stat-icon stat-icon-orange">
                <i class="fi fi-sr-alarm-clock"></i>
              </div>
              <div class="stat-info">
                <h3 class="stat-label">Expiring Soon</h3>
                <p class="stat-value">{{ $subscriptionMetrics['expiring'] }}</p>
              </div>
            </div>
            <p class="stat-description">Next 7 days</p>
          </div>

          <!-- Expired Card -->
          <div class="stat-card stat-card-red">
            <div class="stat-card-header">
              <div class="stat-icon stat-icon-red">
                <i class="fi fi-sr-time-past"></i>
              </div>
              <div class="stat-info">
                <h3 class="stat-label">Expired</h3>
                <p class="stat-value">{{ $subscriptionMetrics['expired'] }}</p>
              </div>
            </div>
            <p class="stat-description">Past subscription period</p>
          </div>
        </div>

        <!-- Total Subscriptions Chart -->
        <div class="subscription-chart-card">
          <div class="chart-header">
            <div>
              <h2 class="chart-title">Total Subscriptions</h2>
              <p class="chart-subtitle">Distribution by contractor tier</p>
            </div>
            <div class="chart-legend">
              @foreach($subscriptionTiers['tiers'] as $tier)
              <div class="legend-item-inline">
                <span class="legend-dot-inline" style="background: {{ $tier['color'] }};"></span>
                <span class="legend-text">{{ $tier['label'] }}</span>
              </div>
              @endforeach
            </div>
          </div>

          <div class="chart-container">
            <div class="bar-chart">
              @foreach($subscriptionTiers['tiers'] as $index => $tier)
              <div class="bar-item" data-tier="{{ $index }}">
                <div class="bar-wrapper">
                  <div class="bar-fill" 
                    data-count="{{ $tier['count'] }}" 
                    data-max="{{ $subscriptionTiers['maxCount'] }}"
                    style="background: {{ $tier['gradient'] }}; height: 0%;">
                    <div class="bar-value">{{ number_format($tier['count']) }}K</div>
                  </div>
                </div>
                <div class="bar-label">{{ $tier['name'] }}</div>
              </div>
              @endforeach
            </div>

            <!-- Y-axis labels -->
            <div class="y-axis">
              <span class="y-label">30K</span>
              <span class="y-label">20K</span>
              <span class="y-label">10K</span>
              <span class="y-label">0</span>
            </div>
          </div>
        </div>

        <!-- Total Revenue Chart -->
        <div class="revenue-chart-card">
          <div class="revenue-header">
            <div class="revenue-titles">
              <h2 class="revenue-title">Total Revenue</h2>
              <p class="revenue-subtitle">Monthly revenue comparison (current vs previous year)</p>
            </div>
            <div class="revenue-controls">
              <label class="revenue-tier-label" for="revenueTierSelect">Tier:</label>
              <select id="revenueTierSelect" class="revenue-tier-select">
                <option value="all" selected>All Tiers</option>
                <option value="gold">Gold</option>
                <option value="silver">Silver</option>
                <option value="bronze">Bronze</option>
              </select>
              <span class="revenue-date-range">{{ $subscriptionRevenue['dateRange'] ?? '' }}</span>
            </div>
          </div>
          <div class="revenue-chart-wrapper">
            <canvas id="subscriptionRevenueChart" height="140"></canvas>
            <div id="revenueLoading" class="revenue-loading" hidden>Loading...</div>
          </div>
        </div>
      </div>

      </main>
  </div>
  <script id="initialRevenueData" type="application/json">{!! json_encode($subscriptionRevenue ?? []) !!}</script>
  <script src="{{ asset('js/admin/home/subscriptionAnalytics.js') }}" defer></script>

</body>

</html>