@extends('layouts.admin')

@section('title', 'Notification Channels & Alerts')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <span>Settings</span>
                <span>/</span>
                <span class="text-gray-900 font-medium">Notification Channels</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mt-1">Multi-Channel Instant Notifications</h1>
        </div>
        <button type="submit" form="notifyForm" class="px-5 py-2 bg-primary hover:bg-primary-hover text-white rounded-lg text-sm font-semibold shadow">Save Settings</button>
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

    <form id="notifyForm" action="{{ route('admin.settings.notifications.save') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Email Configuration -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <h3 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                    <span>✉️ SMTP Email Alerts</span>
                </h3>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="notify_email_enabled" value="1" {{ old('notify_email_enabled', setting('notify_email_enabled', true)) ? 'checked' : '' }} class="rounded text-primary focus:ring-primary">
                    <span class="text-xs font-semibold text-gray-700">Enable Email</span>
                </label>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Admin Notification Email *</label>
                    <input type="email" name="admin_email" required value="{{ old('admin_email', setting('admin_email', 'admin@99bulksales.my')) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                    <p class="text-[11px] text-gray-500 mt-1">Receives instant alerts whenever a new bulk order is placed.</p>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Admin WhatsApp Contact Phone *</label>
                    <input type="text" name="admin_phone" required value="{{ old('admin_phone', setting('admin_phone', '+60123456789')) }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                    <p class="text-[11px] text-gray-500 mt-1">Used for customer 1-click WhatsApp order inquiries & tracking support.</p>
                </div>
            </div>
        </div>

        <!-- Telegram Bot Free Notifications -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <h3 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                    <span>✈️ Free Telegram Bot Alerts</span>
                </h3>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="notify_telegram_enabled" value="1" {{ old('notify_telegram_enabled', setting('notify_telegram_enabled', false)) ? 'checked' : '' }} class="rounded text-primary focus:ring-primary">
                    <span class="text-xs font-semibold text-gray-700">Enable Telegram</span>
                </label>
            </div>

            <div class="p-3.5 bg-blue-50/60 rounded-lg text-xs text-blue-900 space-y-1">
                <p class="font-bold">How to setup your 100% free Telegram Bot:</p>
                <ol class="list-decimal list-inside space-y-0.5 text-blue-800">
                    <li>Message <strong>@BotFather</strong> on Telegram and send <code>/newbot</code>. Copy your API token.</li>
                    <li>Start a chat with your bot, then message <strong>@userinfobot</strong> to get your numerical Chat ID.</li>
                    <li>Paste both values below. Instant alerts will ping your phone on every order!</li>
                </ol>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Telegram Bot Token</label>
                    <input type="text" name="telegram_bot_token" value="{{ old('telegram_bot_token', setting('telegram_bot_token', '')) }}" placeholder="e.g. 123456789:ABCdefGhIJKlmNoPQRsTUVwxyZ" class="w-full text-sm font-mono border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Telegram Chat ID</label>
                    <input type="text" name="telegram_chat_id" value="{{ old('telegram_chat_id', setting('telegram_chat_id', '')) }}" placeholder="e.g. 987654321" class="w-full text-sm font-mono border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                </div>
            </div>
        </div>

        <!-- WhatsApp via CallMeBot (Free) -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <h3 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                    <span>💬 Free WhatsApp via CallMeBot</span>
                </h3>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="notify_callmebot_enabled" value="1" {{ old('notify_callmebot_enabled', setting('notify_callmebot_enabled', false)) ? 'checked' : '' }} class="rounded text-primary focus:ring-primary">
                    <span class="text-xs font-semibold text-gray-700">Enable WhatsApp</span>
                </label>
            </div>

            <div class="p-3.5 bg-emerald-50/60 rounded-lg text-xs text-emerald-900 space-y-1">
                <p class="font-bold">How to setup CallMeBot WhatsApp Gateway (Free):</p>
                <ol class="list-decimal list-inside space-y-0.5 text-emerald-800">
                    <li>Add phone number <strong>+34 644 44 24 99</strong> to your WhatsApp contacts.</li>
                    <li>Send the message: <code>I allow callmebot to send me messages</code></li>
                    <li>Wait for CallMeBot's reply with your personal API Key.</li>
                </ol>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">WhatsApp Phone (International format)</label>
                    <input type="text" name="callmebot_phone" value="{{ old('callmebot_phone', setting('callmebot_phone', '')) }}" placeholder="e.g. +60123456789" class="w-full text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">CallMeBot API Key</label>
                    <input type="text" name="callmebot_api_key" value="{{ old('callmebot_api_key', setting('callmebot_api_key', '')) }}" placeholder="e.g. 123456" class="w-full text-sm font-mono border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
