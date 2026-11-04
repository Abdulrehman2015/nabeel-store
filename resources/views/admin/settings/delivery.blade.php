@extends('layouts.admin')

@section('title', 'Delivery & Zone Settings')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-2 border-b border-slate-200">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-1">
                <span>Settings</span>
                <span class="text-slate-300">/</span>
                <span class="text-slate-700">Delivery Engine</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Delivery Zones & Logistics Engine</h1>
            <p class="text-xs text-slate-500 mt-0.5">Configure N&J Mini Mart dispatch coordinates, radius coverage zones, staircase surcharges, and order limits</p>
        </div>
        <div class="flex items-center gap-2.5">
            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-white hover:bg-slate-50 border border-slate-300 rounded-xl text-xs font-bold text-slate-700 shadow-2xs hover:border-slate-400 transition">
                Dashboard
            </a>
            <button type="submit" form="deliveryForm" class="px-5 py-2 bg-sky-600 hover:bg-sky-500 text-white rounded-xl text-xs font-bold shadow-md shadow-sky-600/25 hover:shadow-sky-600/40 transition flex items-center gap-1.5 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>Save Delivery Settings</span>
            </button>
        </div>
    </div>

    @if($errors->any())
        <div class="p-4 bg-rose-50 border-l-4 border-rose-500 rounded-xl shadow-xs">
            <div class="flex items-center gap-2 text-rose-800 font-bold text-sm mb-1">
                <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Please correct the errors below:</span>
            </div>
            <ul class="text-xs text-rose-700 list-disc list-inside space-y-1 font-medium">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="deliveryForm" action="{{ route('admin.settings.delivery.save') }}" method="POST" class="space-y-6">
        @csrf

        <!-- 1. Store Location & Interactive Radius Map -->
        <div class="bg-white rounded-2xl shadow-xs border border-slate-200/90 p-5 sm:p-6 space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </span>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Store Location & Dispatch Hub</h3>
                        <p class="text-[11px] text-slate-400">Add a Google Maps link, search an address, or drag the pin to set store coordinates</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-1 bg-sky-50 text-sky-700 text-xs font-bold rounded-lg border border-sky-200/80 flex items-center gap-1.5 shadow-2xs">
                        <span class="w-1.5 h-1.5 rounded-full bg-sky-500 animate-pulse"></span>
                        <span>OpenStreetMap &bull; Live Radii Overlay</span>
                    </span>
                </div>
            </div>

            <!-- Mode Switcher Tabs -->
            <div class="inline-flex p-1 bg-slate-100/90 rounded-xl border border-slate-200/80 shadow-2xs">
                <button type="button" id="tabLinkBtn" class="flex items-center gap-2 px-3.5 py-1.5 text-xs font-bold rounded-lg transition shadow-2xs bg-white text-slate-900 border border-slate-200 cursor-pointer">
                    <svg class="w-3.5 h-3.5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                    <span>Google Maps Link</span>
                </button>
                <button type="button" id="tabAddressBtn" class="flex items-center gap-2 px-3.5 py-1.5 text-xs font-semibold rounded-lg transition text-slate-600 hover:text-slate-900 border border-transparent cursor-pointer">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <span>Search Address</span>
                </button>
            </div>

            <!-- Tab 1: Google Maps Link Input -->
            <div id="panelLink" class="space-y-2">
                <div class="flex gap-2.5 items-stretch">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <input type="text" id="mapLinkInput" placeholder="Paste Google Maps link (e.g. https://maps.app.goo.gl/... or coordinates)..." class="w-full h-11 pl-10 pr-4 text-xs font-mono font-medium border-slate-300 rounded-xl focus:ring-4 focus:ring-sky-500/10 focus:border-sky-500 shadow-2xs bg-slate-50/50 hover:bg-white focus:bg-white transition">
                    </div>
                    <button type="button" id="fetchLinkBtn" class="h-11 px-5 bg-sky-600 hover:bg-sky-500 text-white rounded-xl text-xs font-bold flex items-center justify-center gap-2 shadow-sm transition whitespace-nowrap cursor-pointer disabled:opacity-60 disabled:cursor-not-allowed">
                        <svg class="w-4 h-4" id="fetchLinkIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        <span id="fetchLinkText">Fetch Location</span>
                    </button>
                </div>
                <div class="flex items-center justify-between text-[11px] text-slate-400 pt-0.5">
                    <span>Supports <code>maps.app.goo.gl</code> short URLs, desktop Google Maps links, or coordinates (<code>lat, lng</code>).</span>
                    <button type="button" id="pasteClipboardBtn" class="text-sky-600 hover:text-sky-800 font-bold inline-flex items-center gap-1 cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        <span>Paste from clipboard</span>
                    </button>
                </div>
            </div>

            <!-- Tab 2: Search Address Input -->
            <div id="panelAddress" class="space-y-2 hidden">
                <div class="flex gap-2.5 items-stretch">
                    <input type="text" id="storeSearchInput" placeholder="Search landmark, street, city (e.g. Taman Universiti, Skudai, Johor)..." class="flex-1 h-11 px-4 text-xs font-medium border-slate-300 rounded-xl focus:ring-4 focus:ring-sky-500/10 focus:border-sky-500 shadow-2xs bg-slate-50/50 hover:bg-white focus:bg-white transition">
                    <button type="button" id="storeSearchBtn" class="h-11 px-5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold flex items-center justify-center gap-2 shadow-2xs transition whitespace-nowrap cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <span>Find on Map</span>
                    </button>
                </div>
                <p class="text-[11px] text-slate-400 pt-0.5">Geocoded via OpenStreetMap Nominatim.</p>
            </div>

            <!-- Status message container -->
            <div id="fetchStatusBox" class="hidden p-3.5 rounded-xl text-xs flex items-center justify-between transition-all shadow-2xs">
                <div id="fetchStatusMsg" class="flex items-center gap-2 font-medium"></div>
                <button type="button" id="closeFetchStatus" class="text-slate-400 hover:text-slate-600 text-base font-bold ml-2 cursor-pointer leading-none">&times;</button>
            </div>

            <!-- Leaflet Container with Map Legend Header -->
            <div class="relative rounded-2xl overflow-hidden border border-slate-300 shadow-2xs">
                <div id="storePickerMap" class="w-full h-96 z-10"></div>

                {{-- Live Map Overlay Legend --}}
                <div class="absolute bottom-4 left-4 z-20 bg-white/95 backdrop-blur-md rounded-xl p-3 border border-slate-200/90 shadow-md text-xs space-y-1.5 pointer-events-auto">
                    <div class="text-[10px] font-extrabold uppercase tracking-wider text-slate-500 border-b border-slate-100 pb-1">
                        Coverage Radii Legend
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-emerald-500 shrink-0"></span>
                        <span class="text-slate-700 font-semibold text-[11px]">Zone 1: Free Delivery</span>
                        <span class="text-[10px] text-emerald-700 font-bold ml-auto" id="legendFreeRadius">0 - 5.0 km</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-amber-500 shrink-0"></span>
                        <span class="text-slate-700 font-semibold text-[11px]">Zone 2: Paid Extension</span>
                        <span class="text-[10px] text-amber-700 font-bold ml-auto" id="legendPaidRadius">5.0 - 7.0 km</span>
                    </div>
                    <div class="flex items-center gap-2 pt-1 border-t border-slate-100 text-[10px] text-slate-400">
                        <span>Beyond Zone 2 = Out of Coverage</span>
                    </div>
                </div>
            </div>

            <!-- Coordinates Inputs Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-1">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Store Latitude *</label>
                    <div class="relative rounded-xl shadow-2xs">
                        <input type="number" step="0.00000001" id="store_latitude" name="store_latitude" required value="{{ old('store_latitude', setting('store_latitude', 3.139003)) }}" class="w-full text-xs font-mono font-bold border-slate-300 rounded-xl focus:ring-4 focus:ring-sky-500/10 focus:border-sky-500 py-2.5 px-3 bg-slate-50/50 hover:bg-white focus:bg-white transition">
                    </div>
                    <p class="text-[10px] text-slate-400 mt-1">Updates automatically when you drag the store marker on the map.</p>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Store Longitude *</label>
                    <div class="relative rounded-xl shadow-2xs">
                        <input type="number" step="0.00000001" id="store_longitude" name="store_longitude" required value="{{ old('store_longitude', setting('store_longitude', 101.686855)) }}" class="w-full text-xs font-mono font-bold border-slate-300 rounded-xl focus:ring-4 focus:ring-sky-500/10 focus:border-sky-500 py-2.5 px-3 bg-slate-50/50 hover:bg-white focus:bg-white transition">
                    </div>
                    <p class="text-[10px] text-slate-400 mt-1">Exact dispatch longitude coordinates for distance calculations.</p>
                </div>
            </div>
        </div>

        <!-- 2. Delivery Zones (Haversine Formula) -->
        <div class="bg-white rounded-2xl shadow-xs border border-slate-200/90 p-5 sm:p-6 space-y-5">
            <div class="flex items-center gap-2.5 border-b border-slate-100 pb-3">
                <span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                </span>
                <div>
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Delivery Zones & Distance Radii</h3>
                    <p class="text-[11px] text-slate-400">Calculated in real-time from store pin using geodesic Haversine formula</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Zone 1: Free Delivery --}}
                <div class="p-5 bg-gradient-to-br from-emerald-50/80 via-white to-emerald-50/30 border border-emerald-200 rounded-2xl shadow-2xs space-y-4">
                    <div class="flex items-center justify-between border-b border-emerald-100 pb-3">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></span>
                            <h4 class="text-sm font-black text-emerald-950">Zone 1: Free Delivery</h4>
                        </div>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800">RM 0.00 FEE</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Free Delivery Radius (KM) *</label>
                        <div class="flex rounded-xl shadow-2xs border border-slate-300 overflow-hidden focus-within:border-emerald-500 focus-within:ring-4 focus-within:ring-emerald-500/10 bg-slate-50 transition">
                            <input type="number" step="0.1" id="freeRadiusInput" name="free_delivery_radius" required value="{{ old('free_delivery_radius', setting('free_delivery_radius', 5)) }}" class="flex-1 min-w-0 block w-full px-3 py-2 text-base font-black text-slate-900 bg-white border-0 focus:ring-0 focus:outline-none">
                            <span class="inline-flex items-center px-3 text-xs font-bold text-slate-500 bg-slate-100 border-l border-slate-200 select-none">
                                KM
                            </span>
                        </div>
                        <p class="text-[11px] text-slate-500 mt-1.5">All customer orders placed within this radius receive free shipping (RM 0.00 delivery fee).</p>
                    </div>
                </div>

                {{-- Zone 2: Paid Delivery Extension --}}
                <div class="p-5 bg-gradient-to-br from-amber-50/80 via-white to-amber-50/30 border border-amber-200 rounded-2xl shadow-2xs space-y-4">
                    <div class="flex items-center justify-between border-b border-amber-100 pb-3">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                            <h4 class="text-sm font-black text-amber-950">Zone 2: Paid Delivery Extension</h4>
                        </div>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-amber-100 text-amber-800">EXTENDED AREA</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Additional Radius Beyond Free Zone (KM) *</label>
                        <div class="flex rounded-xl shadow-2xs border border-slate-300 overflow-hidden focus-within:border-amber-500 focus-within:ring-4 focus-within:ring-amber-500/10 bg-slate-50 transition">
                            <input type="number" step="0.1" id="paidRadiusInput" name="paid_delivery_radius" required value="{{ old('paid_delivery_radius', setting('paid_delivery_radius', 2)) }}" class="flex-1 min-w-0 block w-full px-3 py-2 text-base font-black text-slate-900 bg-white border-0 focus:ring-0 focus:outline-none">
                            <span class="inline-flex items-center px-3 text-xs font-bold text-slate-500 bg-slate-100 border-l border-slate-200 select-none">
                                KM
                            </span>
                        </div>
                        <p class="text-[11px] text-slate-500 mt-1.5" id="zone2CoverageBlurb">
                            Covers distance between <strong class="text-slate-800">{{ (float)setting('free_delivery_radius', 5) }} km</strong> up to <strong class="text-slate-800">{{ (float)setting('free_delivery_radius', 5) + (float)setting('paid_delivery_radius', 2) }} km</strong>.
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-3 pt-2 border-t border-amber-100/80">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Charge Mode *</label>
                            <select name="paid_delivery_charge_mode" class="w-full text-xs font-bold py-2.5 px-3 border-slate-300 rounded-xl focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 bg-white">
                                <option value="fixed" {{ old('paid_delivery_charge_mode', setting('paid_delivery_charge_mode', 'fixed')) === 'fixed' ? 'selected' : '' }}>Flat Fee</option>
                                <option value="per_km" {{ old('paid_delivery_charge_mode', setting('paid_delivery_charge_mode', 'fixed')) === 'per_km' ? 'selected' : '' }}>Per KM Rate</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Fee Amount *</label>
                            <div class="flex rounded-xl shadow-2xs border border-slate-300 overflow-hidden focus-within:border-amber-500 focus-within:ring-4 focus-within:ring-amber-500/10 bg-slate-50 transition">
                                <span class="inline-flex items-center px-3 text-xs font-bold text-slate-600 bg-slate-100 border-r border-slate-200 select-none">
                                    {{ setting('currency_symbol', 'RM') }}
                                </span>
                                <input type="number" step="0.01" name="paid_delivery_charge" required value="{{ old('paid_delivery_charge', setting('paid_delivery_charge', 15)) }}" class="flex-1 min-w-0 block w-full px-3 py-2 text-sm font-black text-slate-900 bg-white border-0 focus:ring-0 focus:outline-none">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Out of Coverage Callout --}}
            <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-600 flex items-center gap-3 shadow-2xs">
                <span class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </span>
                <div>
                    <span class="font-bold text-slate-900 block">Zone 3: Out of Coverage Boundary</span>
                    <span class="text-slate-500">
                        Any delivery address pin located beyond the total combined radius (<span id="outCoverageKm" class="font-bold text-slate-800">{{ (float)setting('free_delivery_radius', 5) + (float)setting('paid_delivery_radius', 2) }} km</span>) is flagged as out-of-coverage; the checkout button is safely blocked.
                    </span>
                </div>
            </div>
        </div>

        <!-- 3. Floor Delivery Surcharges & Order Thresholds -->
        <div class="bg-white rounded-2xl shadow-xs border border-slate-200/90 p-5 sm:p-6 space-y-5">
            <div class="flex items-center gap-2.5 border-b border-slate-100 pb-3">
                <span class="w-8 h-8 rounded-lg bg-violet-50 text-violet-600 flex items-center justify-center font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </span>
                <div>
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Floor Delivery Surcharges & Order Thresholds</h3>
                    <p class="text-[11px] text-slate-400">Rules for staircase walk-ups (when no elevator/lift is available) and checkout rules</p>
                </div>
            </div>

            <!-- Floor Surcharges -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Free Floor Limit *</label>
                    <div class="flex rounded-xl shadow-2xs border border-slate-300 overflow-hidden focus-within:border-sky-500 focus-within:ring-4 focus-within:ring-sky-500/10 bg-slate-50 transition">
                        <input type="number" name="free_floor_limit" required value="{{ old('free_floor_limit', setting('free_floor_limit', 1)) }}" min="0" class="flex-1 min-w-0 block w-full px-3 py-2 text-sm font-black text-slate-900 bg-white border-0 focus:ring-0 focus:outline-none">
                        <span class="inline-flex items-center px-3 text-xs font-bold text-slate-500 bg-slate-100 border-l border-slate-200 select-none">
                            Floor
                        </span>
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1">Ground floor / 1st floor with lift = RM 0.00 surcharge.</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Extra Fee Per Staircase Floor *</label>
                    <div class="flex rounded-xl shadow-2xs border border-slate-300 overflow-hidden focus-within:border-sky-500 focus-within:ring-4 focus-within:ring-sky-500/10 bg-slate-50 transition">
                        <span class="inline-flex items-center px-3 text-xs font-bold text-slate-600 bg-slate-100 border-r border-slate-200 select-none">
                            {{ setting('currency_symbol', 'RM') }}
                        </span>
                        <input type="number" step="0.01" name="extra_floor_charge" required value="{{ old('extra_floor_charge', setting('extra_floor_charge', 10)) }}" class="flex-1 min-w-0 block w-full px-3 py-2 text-sm font-black text-slate-900 bg-white border-0 focus:ring-0 focus:outline-none">
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1">Charged for each floor above free limit (e.g. 3rd floor = 2x fee).</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Max Walk-Up Floor Allowed *</label>
                    <div class="flex rounded-xl shadow-2xs border border-slate-300 overflow-hidden focus-within:border-sky-500 focus-within:ring-4 focus-within:ring-sky-500/10 bg-slate-50 transition">
                        <input type="number" name="max_floor_allowed" required value="{{ old('max_floor_allowed', setting('max_floor_allowed', 20)) }}" class="flex-1 min-w-0 block w-full px-3 py-2 text-sm font-black text-slate-900 bg-white border-0 focus:ring-0 focus:outline-none">
                        <span class="inline-flex items-center px-3 text-xs font-bold text-slate-500 bg-slate-100 border-l border-slate-200 select-none">
                            Floors
                        </span>
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1">Maximum floor delivery personnel will service without lift.</p>
                </div>
            </div>

            <!-- Order Limits & Currency -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-4 border-t border-slate-100">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Minimum Order Value *</label>
                    <div class="flex rounded-xl shadow-2xs border border-slate-300 overflow-hidden focus-within:border-sky-500 focus-within:ring-4 focus-within:ring-sky-500/10 bg-slate-50 transition">
                        <span class="inline-flex items-center px-3 text-xs font-bold text-slate-600 bg-slate-100 border-r border-slate-200 select-none">
                            {{ setting('currency_symbol', 'RM') }}
                        </span>
                        <input type="number" step="0.01" name="min_order_value" required value="{{ old('min_order_value', setting('min_order_value', 150)) }}" class="flex-1 min-w-0 block w-full px-3 py-2 text-sm font-black text-slate-900 bg-white border-0 focus:ring-0 focus:outline-none">
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1">Checkout blocked if cart subtotal is below this amount.</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Store Currency Symbol *</label>
                    <input type="text" name="currency_symbol" required value="{{ old('currency_symbol', setting('currency_symbol', 'RM')) }}" class="w-full text-xs font-bold border-slate-300 rounded-xl focus:ring-4 focus:ring-sky-500/10 focus:border-sky-500 py-2.5 px-3 bg-slate-50/50 hover:bg-white focus:bg-white transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Currency Decimal Display</label>
                    <select name="currency_decimals" class="w-full text-xs font-bold py-2.5 px-3 border-slate-300 rounded-xl focus:ring-4 focus:ring-sky-500/10 focus:border-sky-500 bg-slate-50/50 hover:bg-white focus:bg-white transition">
                        <option value="2" {{ old('currency_decimals', setting('currency_decimals', 2)) == 2 ? 'selected' : '' }}>2 Decimals (e.g. RM 120.50)</option>
                        <option value="0" {{ old('currency_decimals', setting('currency_decimals', 2)) == 0 ? 'selected' : '' }}>0 Decimals (e.g. RM 121)</option>
                    </select>
                </div>
            </div>

            <!-- Enable / Pause Delivery Switch -->
            <div class="pt-4 border-t border-slate-100" x-data="{ deliveryEnabled: {{ old('delivery_enabled', setting('delivery_enabled', true)) ? 'true' : 'false' }} }">
                <div class="p-4 rounded-xl border border-slate-200/80 bg-slate-50/50 hover:bg-slate-50 transition flex items-center justify-between cursor-pointer" @click="deliveryEnabled = !deliveryEnabled">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center font-bold shrink-0 transition"
                            :class="deliveryEnabled ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-500'">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-slate-900 block" x-text="deliveryEnabled ? 'Delivery System Active' : 'Delivery Service Temporarily Paused'"></span>
                            <span class="text-[11px] text-slate-500 block">When paused, storefront will inform shoppers that local delivery is temporarily unavailable.</span>
                        </div>
                    </div>
                    <input type="checkbox" name="delivery_enabled" value="1" class="hidden" :checked="deliveryEnabled">
                    <button type="button" class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none" :class="deliveryEnabled ? 'bg-emerald-600' : 'bg-slate-300'">
                        <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out" :class="deliveryEnabled ? 'translate-x-5' : 'translate-x-0'"></span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Leaflet Map Setup Script with Live Radii Overlay -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var latInput = document.getElementById('store_latitude');
    var lngInput = document.getElementById('store_longitude');
    var freeRadiusInput = document.getElementById('freeRadiusInput');
    var paidRadiusInput = document.getElementById('paidRadiusInput');

    var currentLat = parseFloat(latInput.value) || 3.139003;
    var currentLng = parseFloat(lngInput.value) || 101.686855;

    var map = L.map('storePickerMap', {
        zoomControl: true,
        scrollWheelZoom: true
    }).setView([currentLat, currentLng], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    var marker = L.marker([currentLat, currentLng], { draggable: true }).addTo(map);
    marker.bindPopup('<b class="text-slate-900">N&J Mini Mart Store</b><br><span style="font-size:11px;color:#0284c7;">Drag pin to reposition hub</span>').openPopup();

    var freeCircle = null;
    var paidCircle = null;

    function updateCoordinates(lat, lng) {
        latInput.value = parseFloat(lat).toFixed(8);
        lngInput.value = parseFloat(lng).toFixed(8);
        updateRadiiCircles();
    }

    function updateRadiiCircles() {
        var lat = parseFloat(latInput.value) || 3.139003;
        var lng = parseFloat(lngInput.value) || 101.686855;
        var freeKm = Math.max(0.1, parseFloat(freeRadiusInput.value) || 5);
        var paidKm = Math.max(0, parseFloat(paidRadiusInput.value) || 2);
        var totalKm = freeKm + paidKm;

        // Update DOM labels
        var legendFree = document.getElementById('legendFreeRadius');
        var legendPaid = document.getElementById('legendPaidRadius');
        var outCoverage = document.getElementById('outCoverageKm');
        var blurb = document.getElementById('zone2CoverageBlurb');

        if (legendFree) legendFree.textContent = '0 - ' + freeKm.toFixed(1) + ' km';
        if (legendPaid) legendPaid.textContent = freeKm.toFixed(1) + ' - ' + totalKm.toFixed(1) + ' km';
        if (outCoverage) outCoverage.textContent = totalKm.toFixed(1) + ' km';
        if (blurb) {
            blurb.innerHTML = 'Covers distance between <strong class="text-slate-800">' + freeKm.toFixed(1) + ' km</strong> up to <strong class="text-slate-800">' + totalKm.toFixed(1) + ' km</strong>.';
        }

        // Remove existing layers
        if (paidCircle) map.removeLayer(paidCircle);
        if (freeCircle) map.removeLayer(freeCircle);

        // Draw Zone 2 (Paid extension) outer circle
        if (totalKm > 0) {
            paidCircle = L.circle([lat, lng], {
                radius: totalKm * 1000,
                color: '#f59e0b',
                fillColor: '#fef3c7',
                fillOpacity: 0.22,
                weight: 2,
                dashArray: '5, 5'
            }).addTo(map);
            paidCircle.bindTooltip('Zone 2: Paid Delivery (' + totalKm.toFixed(1) + ' km total radius)', { sticky: true });
        }

        // Draw Zone 1 (Free delivery) inner circle
        if (freeKm > 0) {
            freeCircle = L.circle([lat, lng], {
                radius: freeKm * 1000,
                color: '#10b981',
                fillColor: '#d1fae5',
                fillOpacity: 0.32,
                weight: 2.5
            }).addTo(map);
            freeCircle.bindTooltip('Zone 1: Free Delivery (Up to ' + freeKm.toFixed(1) + ' km radius)', { sticky: true });
        }
    }

    // Initial circles rendering
    updateRadiiCircles();

    // Live update when radius inputs change
    if (freeRadiusInput) freeRadiusInput.addEventListener('input', updateRadiiCircles);
    if (paidRadiusInput) paidRadiusInput.addEventListener('input', updateRadiiCircles);

    marker.on('dragend', function(e) {
        var pos = marker.getLatLng();
        updateCoordinates(pos.lat, pos.lng);
        showStatus('info', 'Pin relocated to: ' + pos.lat.toFixed(6) + ', ' + pos.lng.toFixed(6));
    });

    map.on('click', function(e) {
        marker.setLatLng(e.latlng);
        updateCoordinates(e.latlng.lat, e.latlng.lng);
        showStatus('info', 'Pin relocated to: ' + e.latlng.lat.toFixed(6) + ', ' + e.latlng.lng.toFixed(6));
    });

    // Tab switcher
    var tabLinkBtn = document.getElementById('tabLinkBtn');
    var tabAddressBtn = document.getElementById('tabAddressBtn');
    var panelLink = document.getElementById('panelLink');
    var panelAddress = document.getElementById('panelAddress');

    function switchTab(mode) {
        if(mode === 'link') {
            tabLinkBtn.className = 'flex items-center gap-2 px-3.5 py-1.5 text-xs font-bold rounded-lg transition shadow-2xs bg-white text-slate-900 border border-slate-200 cursor-pointer';
            tabAddressBtn.className = 'flex items-center gap-2 px-3.5 py-1.5 text-xs font-semibold rounded-lg transition text-slate-600 hover:text-slate-900 border border-transparent cursor-pointer';
            panelLink.classList.remove('hidden');
            panelAddress.classList.add('hidden');
            document.getElementById('mapLinkInput').focus();
        } else {
            tabAddressBtn.className = 'flex items-center gap-2 px-3.5 py-1.5 text-xs font-bold rounded-lg transition shadow-2xs bg-white text-slate-900 border border-slate-200 cursor-pointer';
            tabLinkBtn.className = 'flex items-center gap-2 px-3.5 py-1.5 text-xs font-semibold rounded-lg transition text-slate-600 hover:text-slate-900 border border-transparent cursor-pointer';
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
                var placeName = result.data.name || 'N&J Mini Mart Store';

                updateCoordinates(lat, lng);
                map.setView([lat, lng], 15);
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
