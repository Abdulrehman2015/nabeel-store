@extends('layouts.admin')

@section('title', 'Delivery & Zone Settings')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <span>Settings</span>
                <span>/</span>
                <span class="text-gray-900 font-medium">Delivery Engine</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mt-1">Delivery Zones & Floor Surcharge Engine</h1>
        </div>
        <button type="submit" form="deliveryForm" class="px-5 py-2.5 bg-sky-600 hover:bg-sky-700 active:bg-sky-800 text-white rounded-xl text-sm font-semibold shadow-sm transition-colors cursor-pointer">Save Delivery Settings</button>
    </div>

    @if($errors->any())
        <div class="p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg">
            <ul class="text-xs text-red-700 list-disc list-inside">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="deliveryForm" action="{{ route('admin.settings.delivery.save') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Store Warehouse Location Map Picker -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <div>
                    <h3 class="text-base font-semibold text-gray-900">Store Warehouse Coordinates</h3>
                    <p class="text-xs text-gray-500">Add a Google Maps link, search an address, or drag the pin on the map to set your central distribution hub.</p>
                </div>
                <span class="px-2.5 py-1 bg-sky-50 text-sky-700 text-xs font-semibold rounded-full border border-sky-200">OpenStreetMap + Leaflet</span>
            </div>

            <!-- Mode Switcher Tabs -->
            <div class="inline-flex p-1 bg-slate-100 rounded-xl border border-slate-200">
                <button type="button" id="tabLinkBtn" class="flex items-center gap-2 px-3.5 py-2 text-xs font-semibold rounded-lg transition shadow-xs bg-white text-slate-900 border border-slate-200 cursor-pointer">
                    <svg class="w-3.5 h-3.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                    <span>Google Maps Link</span>
                </button>
                <button type="button" id="tabAddressBtn" class="flex items-center gap-2 px-3.5 py-2 text-xs font-medium rounded-lg transition text-slate-600 hover:text-slate-900 border border-transparent cursor-pointer">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <span>Search Address</span>
                </button>
            </div>

            <!-- Tab 1: Google Maps Link Input -->
            <div id="panelLink" class="space-y-2">
                <div class="flex gap-2.5 items-stretch">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <input type="text" id="mapLinkInput" placeholder="Paste Google Maps link (e.g. https://maps.app.goo.gl/... or coordinates)..." class="w-full h-11 pl-10 pr-4 text-sm border-slate-300 rounded-xl focus:ring-2 focus:ring-sky-500 focus:border-sky-500 shadow-xs">
                    </div>
                    <button type="button" id="fetchLinkBtn" class="h-11 px-5 bg-sky-600 hover:bg-sky-700 active:bg-sky-800 text-white rounded-xl text-sm font-semibold flex items-center justify-center gap-2 shadow-sm transition-colors whitespace-nowrap cursor-pointer disabled:opacity-60 disabled:cursor-not-allowed">
                        <svg class="w-4 h-4" id="fetchLinkIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        <span id="fetchLinkText">Fetch Location</span>
                    </button>
                </div>
                <div class="flex items-center justify-between text-xs text-slate-500 pt-0.5">
                    <span>Supports <code>maps.app.goo.gl</code> short links, desktop Google Maps links, or coordinates (<code>lat, lng</code>).</span>
                    <button type="button" id="pasteClipboardBtn" class="text-sky-600 hover:text-sky-700 font-semibold inline-flex items-center gap-1 cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        Paste from clipboard
                    </button>
                </div>
            </div>

            <!-- Tab 2: Search Address Input -->
            <div id="panelAddress" class="space-y-2 hidden">
                <div class="flex gap-2.5 items-stretch">
                    <input type="text" id="storeSearchInput" placeholder="Search landmark, street, city..." class="flex-1 h-11 px-4 text-sm border-slate-300 rounded-xl focus:ring-2 focus:ring-sky-500 focus:border-sky-500 shadow-xs">
                    <button type="button" id="storeSearchBtn" class="h-11 px-5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-sm font-semibold flex items-center justify-center gap-2 shadow-sm transition-colors whitespace-nowrap cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <span>Find on Map</span>
                    </button>
                </div>
                <p class="text-xs text-slate-500 pt-0.5">Geocoded using OpenStreetMap Nominatim.</p>
            </div>

            <!-- Status message container -->
            <div id="fetchStatusBox" class="hidden p-3.5 rounded-xl text-xs flex items-center justify-between transition-all">
                <div id="fetchStatusMsg" class="flex items-center gap-2 font-medium"></div>
                <button type="button" id="closeFetchStatus" class="text-slate-400 hover:text-slate-600 text-base font-bold ml-2 cursor-pointer leading-none">&times;</button>
            </div>

            <!-- Leaflet Container -->
            <div id="storePickerMap" class="w-full h-80 rounded-lg border border-gray-200 z-10"></div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Store Latitude *</label>
                    <input type="number" step="0.00000001" id="store_latitude" name="store_latitude" required value="{{ old('store_latitude', setting('store_latitude', 3.139003)) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Store Longitude *</label>
                    <input type="number" step="0.00000001" id="store_longitude" name="store_longitude" required value="{{ old('store_longitude', setting('store_longitude', 101.686855)) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                </div>
            </div>
        </div>

        <!-- Delivery Radii & Zones -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
            <h3 class="text-base font-semibold text-gray-900 border-b border-gray-100 pb-3">Delivery Zones (Haversine Formula)</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="p-4 bg-emerald-50/50 border border-emerald-200 rounded-xl space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                        <h4 class="text-sm font-bold text-emerald-900">Zone 1: Free Delivery</h4>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Free Delivery Radius (KM) *</label>
                        <input type="number" step="0.1" name="free_delivery_radius" required value="{{ old('free_delivery_radius', setting('free_delivery_radius', 5)) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                        <p class="text-[11px] text-gray-500 mt-1">Orders within this radius receive free shipping (0.00 delivery fee).</p>
                    </div>
                </div>

                <div class="p-4 bg-amber-50/50 border border-amber-200 rounded-xl space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                        <h4 class="text-sm font-bold text-amber-900">Zone 2: Paid Delivery Extension</h4>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Paid Delivery Additional Radius (KM) *</label>
                        <input type="number" step="0.1" name="paid_delivery_radius" required value="{{ old('paid_delivery_radius', setting('paid_delivery_radius', 2)) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                        <p class="text-[11px] text-gray-500 mt-1">E.g. if 2km, covers distance between 5.01km up to 7.00km.</p>
                    </div>

                    <div class="grid grid-cols-2 gap-3 pt-1">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Charge Mode *</label>
                            <select name="paid_delivery_charge_mode" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                                <option value="fixed" {{ old('paid_delivery_charge_mode', setting('paid_delivery_charge_mode', 'fixed')) === 'fixed' ? 'selected' : '' }}>Flat Fee</option>
                                <option value="per_km" {{ old('paid_delivery_charge_mode', setting('paid_delivery_charge_mode', 'fixed')) === 'per_km' ? 'selected' : '' }}>Per KM</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Fee Amount *</label>
                            <input type="number" step="0.01" name="paid_delivery_charge" required value="{{ old('paid_delivery_charge', setting('paid_delivery_charge', 15)) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-3 bg-gray-50 rounded-lg text-xs text-gray-600 flex items-center gap-2">
                <svg class="w-4 h-4 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <span><strong>Zone 3:</strong> Any pin beyond {{ (float)setting('free_delivery_radius', 5) + (float)setting('paid_delivery_radius', 2) }} km is marked out-of-coverage; customer cannot submit checkout.</span>
            </div>
        </div>

        <!-- Heavy Bulk Floor Surcharge & Min Order -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
            <h3 class="text-base font-semibold text-gray-900 border-b border-gray-100 pb-3">Floor Handling Surcharge (Carton Carry-Up) & Minimum Order</h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Free Floor Limit *</label>
                    <input type="number" name="free_floor_limit" required value="{{ old('free_floor_limit', setting('free_floor_limit', 1)) }}" min="0" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                    <p class="text-[11px] text-gray-500 mt-1">Ground floor / 1st floor with lift = 0 surcharge.</p>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Extra Fee Per Floor *</label>
                    <input type="number" step="0.01" name="extra_floor_charge" required value="{{ old('extra_floor_charge', setting('extra_floor_charge', 10)) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                    <p class="text-[11px] text-gray-500 mt-1">Charged for each floor above free limit (e.g. 4th floor = 3 x fee).</p>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Max Floor Allowed *</label>
                    <input type="number" name="max_floor_allowed" required value="{{ old('max_floor_allowed', setting('max_floor_allowed', 20)) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2 border-t border-gray-100">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Minimum Order Value *</label>
                    <input type="number" step="0.01" name="min_order_value" required value="{{ old('min_order_value', setting('min_order_value', 150)) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                    <p class="text-[11px] text-gray-500 mt-1">Checkout blocked if cart subtotal is less than this value.</p>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Currency Symbol *</label>
                    <input type="text" name="currency_symbol" required value="{{ old('currency_symbol', setting('currency_symbol', 'RM')) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Currency Decimals</label>
                    <select name="currency_decimals" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                        <option value="2" {{ old('currency_decimals', setting('currency_decimals', 2)) == 2 ? 'selected' : '' }}>2 (e.g. RM 120.50)</option>
                        <option value="0" {{ old('currency_decimals', setting('currency_decimals', 2)) == 0 ? 'selected' : '' }}>0 (e.g. RM 121)</option>
                    </select>
                </div>
            </div>

            <div class="pt-2">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="delivery_enabled" value="1" {{ old('delivery_enabled', setting('delivery_enabled', true)) ? 'checked' : '' }} class="rounded text-primary focus:ring-primary">
                    <span class="text-sm font-semibold text-gray-800">Enable Delivery System (Uncheck to temporarily pause all store deliveries)</span>
                </label>
            </div>
        </div>
    </form>
</div>

<!-- Leaflet Map Setup Script -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var latInput = document.getElementById('store_latitude');
    var lngInput = document.getElementById('store_longitude');

    var currentLat = parseFloat(latInput.value) || 3.139003;
    var currentLng = parseFloat(lngInput.value) || 101.686855;

    var map = L.map('storePickerMap').setView([currentLat, currentLng], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    var marker = L.marker([currentLat, currentLng], { draggable: true }).addTo(map);
    marker.bindPopup('<b>Store Central Hub</b><br>Drag to reposition').openPopup();

    function updateCoordinates(lat, lng) {
        latInput.value = parseFloat(lat).toFixed(8);
        lngInput.value = parseFloat(lng).toFixed(8);
    }

    marker.on('dragend', function(e) {
        var pos = marker.getLatLng();
        updateCoordinates(pos.lat, pos.lng);
        showStatus('info', 'Pin moved to: ' + pos.lat.toFixed(6) + ', ' + pos.lng.toFixed(6));
    });

    map.on('click', function(e) {
        marker.setLatLng(e.latlng);
        updateCoordinates(e.latlng.lat, e.latlng.lng);
        showStatus('info', 'Pin moved to: ' + e.latlng.lat.toFixed(6) + ', ' + e.latlng.lng.toFixed(6));
    });

    // Tab switcher
    var tabLinkBtn = document.getElementById('tabLinkBtn');
    var tabAddressBtn = document.getElementById('tabAddressBtn');
    var panelLink = document.getElementById('panelLink');
    var panelAddress = document.getElementById('panelAddress');

    function switchTab(mode) {
        if(mode === 'link') {
            tabLinkBtn.className = 'flex items-center gap-2 px-3.5 py-2 text-xs font-semibold rounded-lg transition shadow-xs bg-white text-slate-900 border border-slate-200 cursor-pointer';
            tabAddressBtn.className = 'flex items-center gap-2 px-3.5 py-2 text-xs font-medium rounded-lg transition text-slate-600 hover:text-slate-900 border border-transparent cursor-pointer';
            panelLink.classList.remove('hidden');
            panelAddress.classList.add('hidden');
            document.getElementById('mapLinkInput').focus();
        } else {
            tabAddressBtn.className = 'flex items-center gap-2 px-3.5 py-2 text-xs font-semibold rounded-lg transition shadow-xs bg-white text-slate-900 border border-slate-200 cursor-pointer';
            tabLinkBtn.className = 'flex items-center gap-2 px-3.5 py-2 text-xs font-medium rounded-lg transition text-slate-600 hover:text-slate-900 border border-transparent cursor-pointer';
            panelAddress.classList.remove('hidden');
            panelLink.classList.add('hidden');
            document.getElementById('storeSearchInput').focus();
        }
    }

    tabLinkBtn.addEventListener('click', function() { switchTab('link'); });
    tabAddressBtn.addEventListener('click', function() { switchTab('address'); });

    // Status banner helpers
    var statusBox = document.getElementById('fetchStatusBox');
    var statusMsg = document.getElementById('fetchStatusMsg');
    var closeStatusBtn = document.getElementById('closeFetchStatus');

    if(closeStatusBtn) {
        closeStatusBtn.addEventListener('click', function() {
            statusBox.classList.add('hidden');
        });
    }

    function showStatus(type, messageHtml) {
        statusBox.classList.remove('hidden', 'bg-emerald-50', 'border-emerald-200', 'text-emerald-800', 'bg-red-50', 'border-red-200', 'text-red-800', 'bg-sky-50', 'border-sky-200', 'text-sky-800');
        
        var icon = '';
        if(type === 'success') {
            statusBox.classList.add('bg-emerald-50', 'border', 'border-emerald-200', 'text-emerald-800');
            icon = '<svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>';
        } else if(type === 'error') {
            statusBox.classList.add('bg-red-50', 'border', 'border-red-200', 'text-red-800');
            icon = '<svg class="w-4 h-4 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>';
        } else {
            statusBox.classList.add('bg-sky-50', 'border', 'border-sky-200', 'text-sky-800');
            icon = '<svg class="w-4 h-4 text-sky-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
        }

        statusMsg.innerHTML = icon + '<span>' + messageHtml + '</span>';
    }

    function escapeHtml(str) {
        var div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    // Google Maps Link Resolver
    var fetchLinkBtn = document.getElementById('fetchLinkBtn');
    var fetchLinkIcon = document.getElementById('fetchLinkIcon');
    var fetchLinkText = document.getElementById('fetchLinkText');
    var mapLinkInput = document.getElementById('mapLinkInput');

    function resolveLocation(link) {
        if(!link) {
            showStatus('error', 'Please enter or paste a Google Maps link or coordinates.');
            return;
        }

        // Set Loading State
        fetchLinkBtn.disabled = true;
        fetchLinkText.textContent = 'Resolving...';
        fetchLinkIcon.innerHTML = '<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>';
        fetchLinkIcon.classList.add('animate-spin');

        var csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '{{ csrf_token() }}';

        fetch('{{ route("admin.settings.delivery.resolve-link") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ url: link })
        })
        .then(function(res) {
            return res.json().then(function(data) {
                return { status: res.status, ok: res.ok, data: data };
            });
        })
        .then(function(result) {
            if(result.ok && result.data.success) {
                var lat = parseFloat(result.data.lat);
                var lng = parseFloat(result.data.lng);
                var placeName = result.data.name || 'Store Central Hub';

                updateCoordinates(lat, lng);
                map.setView([lat, lng], 16);
                marker.setLatLng([lat, lng]);

                marker.bindPopup(
                    '<b>' + escapeHtml(placeName) + '</b><br>' +
                    '<span style="font-size:11px;color:#16a34a;font-weight:600;">✓ Fetched from Google Maps</span><br>' +
                    '<span style="font-size:11px;color:#6b7280;">' + lat.toFixed(6) + ', ' + lng.toFixed(6) + '</span>'
                ).openPopup();

                showStatus('success', 'Location successfully fetched: <strong>' + escapeHtml(placeName) + '</strong> (' + lat.toFixed(6) + ', ' + lng.toFixed(6) + ')');
            } else {
                var errMsg = (result.data && result.data.message) ? result.data.message : 'Could not resolve location from this link.';
                showStatus('error', escapeHtml(errMsg));
            }
        })
        .catch(function(err) {
            showStatus('error', 'Error connecting to map resolver. Please check your internet connection.');
        })
        .finally(function() {
            fetchLinkBtn.disabled = false;
            fetchLinkText.textContent = 'Fetch Location';
            fetchLinkIcon.classList.remove('animate-spin');
            fetchLinkIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>';
        });
    }

    fetchLinkBtn.addEventListener('click', function() {
        resolveLocation(mapLinkInput.value.trim());
    });

    mapLinkInput.addEventListener('keydown', function(e) {
        if(e.key === 'Enter') {
            e.preventDefault();
            resolveLocation(mapLinkInput.value.trim());
        }
    });

    // Paste from clipboard helper
    var pasteClipboardBtn = document.getElementById('pasteClipboardBtn');
    if(pasteClipboardBtn) {
        pasteClipboardBtn.addEventListener('click', function() {
            if(navigator.clipboard && navigator.clipboard.readText) {
                navigator.clipboard.readText().then(function(clipText) {
                    if(clipText) {
                        mapLinkInput.value = clipText.trim();
                        resolveLocation(clipText.trim());
                    }
                }).catch(function() {
                    alert('Clipboard access denied. Please paste directly using Ctrl+V or right-click.');
                });
            } else {
                alert('Clipboard API not supported in this browser. Please paste directly using Ctrl+V.');
            }
        });
    }

    // Address search with smart URL auto-detection
    var storeSearchBtn = document.getElementById('storeSearchBtn');
    var storeSearchInput = document.getElementById('storeSearchInput');

    function searchAddress() {
        var query = storeSearchInput.value.trim();
        if(!query) return;

        // If user accidentally or deliberately pasted a map link into the address box
        if(query.match(/^https?:\/\//i) || query.includes('maps.app.goo.gl') || query.includes('goo.gl') || query.includes('google.com/maps')) {
            switchTab('link');
            mapLinkInput.value = query;
            resolveLocation(query);
            return;
        }

        storeSearchBtn.disabled = true;
        storeSearchBtn.classList.add('opacity-75');

        fetch('https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(query))
            .then(res => res.json())
            .then(data => {
                if(data && data.length > 0) {
                    var lat = parseFloat(data[0].lat);
                    var lon = parseFloat(data[0].lon);
                    var displayName = data[0].display_name || query;

                    updateCoordinates(lat, lon);
                    map.setView([lat, lon], 14);
                    marker.setLatLng([lat, lon]);

                    marker.bindPopup(
                        '<b>' + escapeHtml(displayName.split(',')[0]) + '</b><br>' +
                        '<span style="font-size:11px;color:#6b7280;">' + lat.toFixed(6) + ', ' + lon.toFixed(6) + '</span>'
                    ).openPopup();

                    showStatus('success', 'Found: <strong>' + escapeHtml(displayName.split(',')[0]) + '</strong> (' + lat.toFixed(6) + ', ' + lon.toFixed(6) + ')');
                } else {
                    showStatus('error', 'Location not found. Please try another address or use Google Maps Link.');
                }
            })
            .catch(err => {
                showStatus('error', 'Geocoding search failed. You can click anywhere directly on the map.');
            })
            .finally(function() {
                storeSearchBtn.disabled = false;
                storeSearchBtn.classList.remove('opacity-75');
            });
    }

    storeSearchBtn.addEventListener('click', searchAddress);
    storeSearchInput.addEventListener('keydown', function(e) {
        if(e.key === 'Enter') {
            e.preventDefault();
            searchAddress();
        }
    });
});
</script>
@endsection

