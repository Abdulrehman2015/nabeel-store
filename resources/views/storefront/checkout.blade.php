@extends('layouts.storefront')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{
    // Map & Geolocation state
    latitude: {{ $savedAddresses->first()?->latitude ?? $storeLat }},
    longitude: {{ $savedAddresses->first()?->longitude ?? $storeLng }},
    map: null,
    marker: null,
    nominatimQuery: '',
    nominatimSearching: false,

    // Form inputs
    customerName: '{{ auth()->user()?->name ?? old('customer_name') }}',
    customerPhone: '{{ auth()->user()?->phone ?? old('customer_phone') }}',
    customerEmail: '{{ auth()->user()?->email ?? old('customer_email') }}',
    deliveryAddress: '{{ $savedAddresses->first()?->address_line ?? old('delivery_address') }}',
    deliveryCity: '{{ $savedAddresses->first()?->area_city ?? old('delivery_city', 'Puchong, Selangor') }}',
    floor: {{ old('floor', $savedAddresses->first()?->floor ?? 0) }},
    landmark: '{{ old('landmark') }}',
    orderNotes: '{{ old('order_notes') }}',
    paymentReference: '{{ old('payment_reference') }}',
    termsAccepted: false,

    // Live calculation feedback from server
    eligible: true,
    zone: 'free',
    distanceKm: 0.0,
    deliveryCharge: 0.0,
    deliveryChargeFormatted: 'FREE',
    floorCharge: 0.0,
    floorChargeFormatted: 'FREE',
    subtotal: {{ $cartSummary['subtotal'] }},
    subtotalFormatted: '{{ $cartSummary['subtotal_formatted'] }}',
    grandTotal: {{ $cartSummary['subtotal'] }},
    grandTotalFormatted: '{{ $cartSummary['subtotal_formatted'] }}',
    advancePercentage: {{ $advancePercentage }},
    advanceAmount: {{ round(($cartSummary['subtotal'] * $advancePercentage) / 100, 2) }},
    advanceAmountFormatted: '{{ format_currency(round(($cartSummary['subtotal'] * $advancePercentage) / 100, 2)) }}',
    balanceAmount: {{ round($cartSummary['subtotal'] - round(($cartSummary['subtotal'] * $advancePercentage) / 100, 2), 2) }},
    balanceAmountFormatted: '{{ format_currency(round($cartSummary['subtotal'] - round(($cartSummary['subtotal'] * $advancePercentage) / 100, 2), 2)) }}',
    evalMessage: 'Evaluating delivery zone...',
    isEvaluating: false,

    // Initialize Leaflet map
    initMap() {
        this.$nextTick(() => {
            const mapContainer = document.getElementById('checkout-map');
            if (!mapContainer) return;

            this.map = L.map('checkout-map').setView([this.latitude, this.longitude], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(this.map);

            // Warehouse pin for visual reference
            const warehouseIcon = L.divIcon({
                className: 'custom-warehouse-pin',
                html: '<div style=\'background:#0f172a;color:#fff;padding:4px 8px;border-radius:8px;font-size:11px;font-weight:bold;white-space:nowrap;box-shadow:0 2px 4px rgba(0,0,0,0.3);\'>🏬 Store Hub</div>',
                iconSize: [80, 24],
                iconAnchor: [40, 12]
            });
            L.marker([{{ $storeLat }}, {{ $storeLng }}], { icon: warehouseIcon }).addTo(this.map);

            // Store delivery radius circles
            L.circle([{{ $storeLat }}, {{ $storeLng }}], {
                radius: {{ $freeRadius * 1000 }},
                color: '#10b981',
                fillColor: '#10b981',
                fillOpacity: 0.1
            }).addTo(this.map);

            L.circle([{{ $storeLat }}, {{ $storeLng }}], {
                radius: {{ ($freeRadius + $paidRadius) * 1000 }},
                color: '#0284c7',
                fillColor: '#0284c7',
                fillOpacity: 0.05
            }).addTo(this.map);

            // Draggable Customer marker
            this.marker = L.marker([this.latitude, this.longitude], { draggable: true }).addTo(this.map);

            this.marker.on('dragend', (e) => {
                const pos = e.target.getLatLng();
                this.updateCoordinates(pos.lat, pos.lng);
            });

            this.map.on('click', (e) => {
                this.marker.setLatLng(e.latlng);
                this.updateCoordinates(e.latlng.lat, e.latlng.lng);
            });

            this.evaluateZoneLive();
        });
    },

    updateCoordinates(lat, lng) {
        this.latitude = parseFloat(lat.toFixed(6));
        this.longitude = parseFloat(lng.toFixed(6));
        this.evaluateZoneLive();
    },

    useCurrentLocation() {
        if (!navigator.geolocation) {
            alert('Geolocation is not supported by your browser.');
            return;
        }
        navigator.geolocation.getCurrentPosition((pos) => {
            const lat = pos.coords.latitude;
            const lng = pos.coords.longitude;
            this.latitude = parseFloat(lat.toFixed(6));
            this.longitude = parseFloat(lng.toFixed(6));
            if (this.map && this.marker) {
                this.marker.setLatLng([lat, lng]);
                this.map.setView([lat, lng], 15);
            }
            this.evaluateZoneLive();
        }, (err) => {
            alert('Could not retrieve your location. Please drop a pin on the map.');
        });
    },

    async searchAddress() {
        if (this.nominatimQuery.length < 3) return;
        this.nominatimSearching = true;
        try {
            const res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=\${encodeURIComponent(this.nominatimQuery)}&countrycodes=my`, {
                headers: { 'Accept': 'application/json', 'User-Agent': '99BulkSalesApp/1.0' }
            });
            const data = await res.json();
            if (data && data.length > 0) {
                const first = data[0];
                const lat = parseFloat(first.lat);
                const lng = parseFloat(first.lon);
                this.latitude = lat;
                this.longitude = lng;
                if (!this.deliveryAddress) {
                    this.deliveryAddress = first.display_name;
                }
                if (this.map && this.marker) {
                    this.marker.setLatLng([lat, lng]);
                    this.map.setView([lat, lng], 15);
                }
                this.evaluateZoneLive();
            } else {
                alert('No matching address found. Please pin directly on the map.');
            }
        } catch(e) {
            console.error(e);
        } finally {
            this.nominatimSearching = false;
        }
    },

    async evaluateZoneLive() {
        this.isEvaluating = true;
        try {
            const res = await fetch('{{ route("checkout.evaluate-zone") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    latitude: this.latitude,
                    longitude: this.longitude,
                    floor: parseInt(this.floor) || 0
                })
            });

            const data = await res.json();
            this.eligible = data.eligible;
            this.zone = data.zone;
            this.distanceKm = data.distance_km;
            this.deliveryCharge = data.delivery_charge;
            this.deliveryChargeFormatted = data.delivery_charge_formatted;
            this.floorCharge = data.floor_charge;
            this.floorChargeFormatted = data.floor_charge_formatted;
            this.grandTotal = data.grand_total;
            this.grandTotalFormatted = data.grand_total_formatted;
            this.advanceAmount = data.advance_amount;
            this.advanceAmountFormatted = data.advance_amount_formatted;
            this.balanceAmount = data.balance_amount;
            this.balanceAmountFormatted = data.balance_amount_formatted;
            this.evalMessage = data.message;
        } catch(e) {
            console.error(e);
        } finally {
            this.isEvaluating = false;
        }
    }
}" x-init="initMap()">

    {{-- Breadcrumb --}}
    <nav class="flex text-xs text-slate-500 font-medium mb-6">
        <ol class="flex items-center gap-2">
            <li><a href="{{ url('/') }}" class="hover:text-sky-600">Home</a></li>
            <li>/</li>
            <li><a href="{{ route('cart.index') }}" class="hover:text-sky-600">Cart</a></li>
            <li>/</li>
            <li class="text-slate-900 font-bold" aria-current="page">Checkout</li>
        </ol>
    </nav>

    <div class="mb-8">
        <span class="text-xs font-bold text-sky-600 uppercase tracking-wider">Direct Wholesale Delivery</span>
        <h1 class="text-2xl sm:text-4xl font-black text-slate-900 tracking-tight">Checkout & Advance Payment</h1>
    </div>

    {{-- Validation Errors Banner --}}
    @if ($errors->any())
        <div class="mb-6 p-4 bg-rose-50 border border-rose-200 rounded-2xl text-xs text-rose-800 space-y-1">
            <h4 class="font-bold text-sm text-rose-900">Please correct the following:</h4>
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('checkout.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="latitude" :value="latitude">
        <input type="hidden" name="longitude" :value="longitude">
        <input type="hidden" name="idempotency_token" value="{{ $idempotencyToken }}">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            {{-- Left Side: Customer & Delivery Details + Map (7 Cols) --}}
            <div class="lg:col-span-7 space-y-8">
                
                {{-- 1. Customer Contact Details --}}
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-5">
                    <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
                        <span class="w-7 h-7 rounded-lg bg-sky-100 text-sky-700 font-black text-xs flex items-center justify-center">1</span>
                        <h2 class="text-lg font-bold text-slate-900">Customer Details</h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 mb-1">Full Name / Business Name *</label>
                            <input 
                                type="text" 
                                name="customer_name" 
                                x-model="customerName" 
                                required 
                                placeholder="e.g. Nabeel Ahmad / Kedai Makanan Sinaran" 
                                class="w-full text-sm px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-sky-500 focus:border-sky-500"
                            >
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Mobile Phone (with WhatsApp) *</label>
                            <input 
                                type="text" 
                                name="customer_phone" 
                                x-model="customerPhone" 
                                required 
                                placeholder="e.g. +60128899900" 
                                class="w-full text-sm px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-sky-500 focus:border-sky-500"
                            >
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Email Address *</label>
                            <input 
                                type="email" 
                                name="customer_email" 
                                x-model="customerEmail" 
                                required 
                                placeholder="name@example.com" 
                                class="w-full text-sm px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-sky-500 focus:border-sky-500"
                            >
                        </div>
                    </div>
                </div>

                {{-- 2. Interactive Delivery Map & Zone Locator (Phase 3.3) --}}
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-5">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <span class="w-7 h-7 rounded-lg bg-sky-100 text-sky-700 font-black text-xs flex items-center justify-center">2</span>
                            <div>
                                <h2 class="text-lg font-bold text-slate-900">Delivery Location Pin</h2>
                                <p class="text-xs text-slate-500">Pick your delivery destination on the map for instant zone calculation</p>
                            </div>
                        </div>
                        <button 
                            type="button" 
                            @click="useCurrentLocation()" 
                            class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold flex items-center gap-1.5 transition"
                        >
                            <span>🎯</span>
                            <span class="hidden sm:inline">Use My Location</span>
                        </button>
                    </div>

                    {{-- Address Search Box (Nominatim) --}}
                    <div class="flex gap-2">
                        <input 
                            type="text" 
                            x-model="nominatimQuery" 
                            @keydown.enter.prevent="searchAddress()" 
                            placeholder="Type street name or area (e.g. Bandar Kinrara, Puchong)..." 
                            class="flex-1 text-xs px-3.5 py-2 border border-slate-300 rounded-xl focus:ring-sky-500"
                        >
                        <button 
                            type="button" 
                            @click="searchAddress()" 
                            class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl transition"
                        >
                            <span x-show="!nominatimSearching">Locate</span>
                            <span x-show="nominatimSearching">Searching...</span>
                        </button>
                    </div>

                    {{-- Map Container --}}
                    <div class="relative rounded-2xl overflow-hidden border border-slate-300 shadow-inner">
                        <div id="checkout-map" class="h-64 sm:h-80 w-full z-10"></div>
                        <div class="absolute bottom-2 left-2 z-20 bg-white/90 backdrop-blur px-3 py-1 rounded-lg text-[11px] font-mono shadow text-slate-700">
                            Lat: <span x-text="latitude"></span>, Lng: <span x-text="longitude"></span>
                        </div>
                    </div>

                    {{-- Live Zone Evaluation Status Box (Phase 3.3) --}}
                    <div 
                        class="p-4 rounded-2xl border text-xs flex items-start gap-3 transition-colors"
                        :class="eligible ? (zone === 'free' ? 'bg-emerald-50 border-emerald-200 text-emerald-900' : 'bg-sky-50 border-sky-200 text-sky-900') : 'bg-rose-50 border-rose-300 text-rose-900'"
                    >
                        <div class="text-xl flex-shrink-0">
                            <span x-show="zone === 'free'">🎉</span>
                            <span x-show="zone === 'paid'">🚚</span>
                            <span x-show="!eligible">🚫</span>
                        </div>
                        <div class="flex-1">
                            <div class="font-bold text-sm" x-text="evalMessage"></div>
                            <div class="text-[11px] opacity-80 mt-0.5">
                                Straight-line distance from warehouse: <strong x-text="distanceKm + ' km'"></strong>
                                (Green circle: {{ $freeRadius }}km Free Zone &bull; Blue circle: {{ $freeRadius + $paidRadius }}km Paid Zone)
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 3. Address & Floor Level Input (Phase 3.4) --}}
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-5">
                    <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
                        <span class="w-7 h-7 rounded-lg bg-sky-100 text-sky-700 font-black text-xs flex items-center justify-center">3</span>
                        <h2 class="text-lg font-bold text-slate-900">Address & Floor Specifications</h2>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Full Delivery Address *</label>
                            <textarea 
                                name="delivery_address" 
                                x-model="deliveryAddress" 
                                rows="2" 
                                required 
                                placeholder="Unit / Lot No, Building name, Street address..." 
                                class="w-full text-sm px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-sky-500 focus:border-sky-500"
                            ></textarea>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Area / City *</label>
                                <input 
                                    type="text" 
                                    name="delivery_city" 
                                    x-model="deliveryCity" 
                                    required 
                                    placeholder="e.g. Puchong / Subang Jaya" 
                                    class="w-full text-sm px-3.5 py-2.5 border border-slate-300 rounded-xl"
                                >
                            </div>

                            {{-- REQUIRED Floor Field with Dynamic Helper Text (Phase 3.4) --}}
                            <div class="p-4 bg-amber-50/70 border border-amber-200 rounded-2xl">
                                <label class="block text-xs font-extrabold text-slate-900 mb-1 flex items-center justify-between">
                                    <span>Floor Level * (REQUIRED)</span>
                                    <span class="text-[10px] text-amber-800 font-semibold bg-amber-200/60 px-2 py-0.5 rounded">0 = Ground Floor</span>
                                </label>
                                <input 
                                    type="number" 
                                    name="floor" 
                                    x-model.number="floor" 
                                    @input.debounce.300ms="evaluateZoneLive()" 
                                    min="0" 
                                    max="{{ setting('max_floor_allowed', 20) }}" 
                                    required 
                                    class="w-full text-sm font-black px-3.5 py-2 border border-amber-300 rounded-xl focus:ring-amber-500 bg-white"
                                >
                                {{-- Dynamic Helper Text built from Admin Settings --}}
                                <p class="text-[11px] text-amber-900 font-medium mt-1.5 leading-snug">
                                    Up to floor {{ $freeFloorLimit }}: <strong class="text-emerald-700">Free delivery</strong>. 
                                    Above floor {{ $freeFloorLimit }}: <strong class="text-amber-800">{{ format_currency($extraFloorCharge) }} per extra floor</strong>.
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Landmark (Optional)</label>
                                <input 
                                    type="text" 
                                    name="landmark" 
                                    x-model="landmark" 
                                    placeholder="e.g. Opposite Shell petrol station" 
                                    class="w-full text-sm px-3.5 py-2.5 border border-slate-300 rounded-xl"
                                >
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Order Notes (Optional)</label>
                                <input 
                                    type="text" 
                                    name="order_notes" 
                                    x-model="orderNotes" 
                                    placeholder="e.g. Call before arrival, leave at security" 
                                    class="w-full text-sm px-3.5 py-2.5 border border-slate-300 rounded-xl"
                                >
                            </div>
                        </div>

                        @auth
                            <div class="pt-2">
                                <label class="flex items-center gap-2 cursor-pointer text-xs text-slate-700">
                                    <input type="checkbox" name="save_address" value="1" class="rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                                    <span>Save this delivery address to my account profile</span>
                                </label>
                            </div>
                        @endauth
                    </div>
                </div>

                {{-- 4. Advance Payment (DuitNow QR) & Proof Upload (Phase 3.6) --}}
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">
                    <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
                        <span class="w-7 h-7 rounded-lg bg-sky-100 text-sky-700 font-black text-xs flex items-center justify-center">4</span>
                        <div>
                            <h2 class="text-lg font-bold text-slate-900">Advance Deposit via DuitNow QR</h2>
                            <p class="text-xs text-slate-500">To reserve carton inventory, submit proof of your {{ $advancePercentage }}% advance payment</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center bg-slate-50 p-5 rounded-2xl border border-slate-200">
                        {{-- QR Display --}}
                        <div class="text-center space-y-2">
                            <div class="w-48 h-48 bg-white p-2 rounded-2xl border border-slate-200 shadow-sm mx-auto flex items-center justify-center">
                                @if(setting('qr_code_image'))
                                    <img src="{{ asset(setting('qr_code_image')) }}" alt="DuitNow QR Code" class="max-h-full max-w-full object-contain">
                                @else
                                    <img src="{{ asset('images/demo-qr.png') }}" alt="DuitNow QR Code" class="max-h-full max-w-full object-contain">
                                @endif
                            </div>
                            <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Scan with any Malaysian Banking App</span>
                        </div>

                        {{-- Bank Instructions --}}
                        <div class="space-y-3 text-xs text-slate-700">
                            <div class="p-3 bg-white rounded-xl border border-slate-200">
                                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Exact Advance Due</span>
                                <div class="text-2xl font-black text-sky-600" x-text="advanceAmountFormatted"></div>
                                <span class="text-[11px] text-slate-500 font-medium">({{ $advancePercentage }}% of <span x-text="grandTotalFormatted"></span>)</span>
                            </div>

                            <div class="whitespace-pre-line text-xs text-slate-600 font-medium">
                                {{ setting('bank_account_details') }}
                            </div>

                            <div class="text-[11px] text-slate-500 whitespace-pre-line border-t border-slate-200 pt-2">
                                {{ setting('payment_instructions') }}
                            </div>
                        </div>
                    </div>

                    {{-- Upload Proof Inputs --}}
                    <div class="space-y-4 pt-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-900 mb-1">
                                Bank Transaction / Reference ID * (REQUIRED)
                            </label>
                            <input 
                                type="text" 
                                name="payment_reference" 
                                x-model="paymentReference" 
                                required 
                                placeholder="e.g. MYB202611019988 / MBB-891283" 
                                class="w-full text-sm font-mono px-3.5 py-2.5 border border-slate-300 rounded-xl focus:ring-sky-500"
                            >
                            <p class="text-[11px] text-slate-400 mt-1">Found on your bank receipt confirmation screen.</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-900 mb-1">
                                Upload Payment Screenshot Receipt * (REQUIRED)
                            </label>
                            <input 
                                type="file" 
                                name="payment_proof" 
                                accept="image/jpeg,image/png,image/webp,application/pdf" 
                                required 
                                class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100 border border-slate-300 rounded-xl p-1.5 bg-slate-50"
                            >
                            <p class="text-[11px] text-slate-400 mt-1">Supported formats: JPG, PNG, WEBP, PDF (Max 5MB). Stored securely on private storage.</p>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Right Side: Order Summary (Always Visible - Phase 3.5) --}}
            <div class="lg:col-span-5">
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-xl space-y-6 sticky top-28">
                    <h2 class="text-lg font-black text-slate-900 pb-3 border-b border-slate-100">
                        Order Summary
                    </h2>

                    {{-- Cart Items List (Phase 3.5 Item 1) --}}
                    <div class="max-h-60 overflow-y-auto divide-y divide-slate-100 pr-1">
                        @foreach($cartSummary['items'] as $item)
                            <div class="py-3 flex items-center justify-between gap-3 text-xs">
                                <div class="flex items-center gap-3 min-w-0">
                                    <img src="{{ $item['image'] }}" class="w-10 h-10 object-contain rounded-lg border bg-slate-50 p-0.5 flex-shrink-0">
                                    <div class="truncate">
                                        <div class="font-bold text-slate-900 truncate">{{ $item['name'] }}</div>
                                        <div class="text-slate-400">{{ $item['quantity'] }} &times; {{ format_currency($item['unit_price']) }}</div>
                                    </div>
                                </div>
                                <div class="font-bold text-slate-900 flex-shrink-0">
                                    {{ format_currency($item['line_total']) }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Exact Price Line Items (Phase 3.5 Items 2-7) --}}
                    <div class="space-y-3 pt-3 border-t border-slate-100 text-xs">
                        {{-- 2. Subtotal --}}
                        <div class="flex justify-between text-slate-600">
                            <span>Cart Subtotal:</span>
                            <span class="font-bold text-slate-900" x-text="subtotalFormatted"></span>
                        </div>

                        {{-- 3. Floor Charges --}}
                        <div class="flex justify-between text-slate-600">
                            <span>Floor Charges (Floor <span x-text="floor"></span>):</span>
                            <span class="font-bold" :class="floorCharge > 0 ? 'text-slate-900' : 'text-emerald-600'" x-text="floorChargeFormatted"></span>
                        </div>

                        {{-- 4. Delivery Charges --}}
                        <div class="flex justify-between text-slate-600">
                            <span>Delivery Charges (<span x-text="distanceKm + ' km'"></span>):</span>
                            <span class="font-bold" :class="deliveryCharge > 0 ? 'text-slate-900' : 'text-emerald-600'" x-text="deliveryChargeFormatted"></span>
                        </div>

                        {{-- 5. Grand Total --}}
                        <div class="flex justify-between text-base font-black text-slate-900 pt-3 border-t border-slate-200">
                            <span>Grand Total:</span>
                            <span class="text-xl text-sky-600" x-text="grandTotalFormatted"></span>
                        </div>

                        {{-- 6. Advance Deposit --}}
                        <div class="flex justify-between p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 font-bold">
                            <span>Advance Deposit Due ({{ $advancePercentage }}%):</span>
                            <span x-text="advanceAmountFormatted"></span>
                        </div>

                        {{-- 7. Remaining Balance on Delivery --}}
                        <div class="flex justify-between p-3 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 font-bold">
                            <span>Balance on Delivery:</span>
                            <span x-text="balanceAmountFormatted"></span>
                        </div>
                    </div>

                    {{-- Terms Agreement Checkbox (Phase 8 Compliance) --}}
                    <div class="pt-2">
                        <label class="flex items-start gap-2.5 cursor-pointer text-xs text-slate-600">
                            <input 
                                type="checkbox" 
                                name="terms_accepted" 
                                value="1" 
                                x-model="termsAccepted" 
                                required 
                                class="mt-0.5 rounded border-slate-300 text-sky-600 focus:ring-sky-500"
                            >
                            <span>
                                I agree to the 
                                <a href="{{ route('page.show', 'terms-and-conditions') }}" target="_blank" class="text-sky-600 underline font-semibold">Terms & Conditions</a>, 
                                <a href="{{ route('page.show', 'privacy-policy') }}" target="_blank" class="text-sky-600 underline font-semibold">Privacy Policy</a>, and 
                                <a href="{{ route('page.show', 'refund-and-return-policy') }}" target="_blank" class="text-sky-600 underline font-semibold">Refund Policy</a>.
                            </span>
                        </label>
                    </div>

                    {{-- Submit Button (Disabled if zone unavailable or terms unchecked) --}}
                    <div class="pt-2">
                        <template x-if="eligible">
                            <button 
                                type="submit" 
                                :disabled="!termsAccepted || isEvaluating"
                                class="w-full py-4 bg-sky-600 hover:bg-sky-700 disabled:bg-slate-300 disabled:cursor-not-allowed text-white font-black text-sm rounded-2xl shadow-xl transition flex items-center justify-center gap-2"
                            >
                                <span x-show="!isEvaluating">Confirm Order & Submit Proof</span>
                                <span x-show="isEvaluating">Checking Delivery Zone...</span>
                            </button>
                        </template>

                        <template x-if="!eligible">
                            <div>
                                <button type="button" disabled class="w-full py-4 bg-rose-500 text-white font-bold text-xs rounded-2xl cursor-not-allowed">
                                    Delivery Not Available in Your Area
                                </button>
                                <p class="text-[11px] text-rose-600 text-center mt-2 font-medium" x-text="evalMessage"></p>
                            </div>
                        </template>
                    </div>

                    <p class="text-[11px] text-slate-400 text-center">
                        🔒 Safe & Verified Wholesale Transaction. Order is confirmed upon admin verification of your payment receipt.
                    </p>
                </div>
            </div>

        </div>
    </form>

</div>
@endsection
