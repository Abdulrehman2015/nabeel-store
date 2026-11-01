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
        <button type="submit" form="deliveryForm" class="px-5 py-2 bg-primary hover:bg-primary-hover text-white rounded-lg text-sm font-semibold shadow">Save Delivery Settings</button>
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
                    <p class="text-xs text-gray-500">Search an address or drag the pin on the map to set your central distribution hub.</p>
                </div>
                <span class="px-2.5 py-1 bg-primary/10 text-primary text-xs font-semibold rounded-full">OpenStreetMap + Leaflet</span>
            </div>

            <div class="flex gap-2">
                <input type="text" id="storeSearchInput" placeholder="Search landmark, street, city in Malaysia..." class="flex-1 text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                <button type="button" id="storeSearchBtn" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium">Find on Map</button>
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

    marker.on('dragend', function(e) {
        var pos = marker.getLatLng();
        latInput.value = pos.lat.toFixed(8);
        lngInput.value = pos.lng.toFixed(8);
    });

    map.on('click', function(e) {
        marker.setLatLng(e.latlng);
        latInput.value = e.latlng.lat.toFixed(8);
        lngInput.value = e.latlng.lng.toFixed(8);
    });

    document.getElementById('storeSearchBtn').addEventListener('click', function() {
        var query = document.getElementById('storeSearchInput').value.trim();
        if(!query) return;

        fetch('https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(query))
            .then(res => res.json())
            .then(data => {
                if(data && data.length > 0) {
                    var lat = parseFloat(data[0].lat);
                    var lon = parseFloat(data[0].lon);
                    map.setView([lat, lon], 14);
                    marker.setLatLng([lat, lon]);
                    latInput.value = lat.toFixed(8);
                    lngInput.value = lon.toFixed(8);
                } else {
                    alert('Location not found. Please try another address.');
                }
            })
            .catch(err => {
                alert('Geocoding search failed. You can click anywhere directly on the map.');
            });
    });
});
</script>
@endsection
