<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('license-verifier::license-verifier.activation') }}</title>
    <style>
        body { font-family: system-ui, sans-serif; background: #0f172a; color: #e2e8f0; display: grid; place-items: center; min-height: 100vh; margin: 0; }
        .lv-card { background: #1e293b; padding: 2rem; border-radius: .75rem; width: min(440px, 92vw); box-shadow: 0 10px 30px rgba(0,0,0,.4); }
        .lv-card h1 { font-size: 1.25rem; margin: 0 0 1rem; }
        .lv-field { margin-bottom: 1rem; display: flex; flex-direction: column; gap: .35rem; }
        .lv-field input[type=text], .lv-field input[type=password] { padding: .6rem .75rem; border-radius: .5rem; border: 1px solid #334155; background: #0f172a; color: #e2e8f0; }
        .lv-btn { padding: .65rem 1rem; border: 0; border-radius: .5rem; cursor: pointer; font-weight: 600; }
        .lv-btn-primary { background: #6366f1; color: #fff; width: 100%; }
        .lv-message { min-height: 1.25rem; font-size: .9rem; margin-top: .75rem; }
        .lv-message.is-error { color: #f87171; } .lv-message.is-success { color: #34d399; }
    </style>
</head>
<body>
    <div class="lv-card">
        <h1>{{ __('license-verifier::license-verifier.activation') }}</h1>
        @include('license-verifier-blade::license-form')
    </div>
    <script src="{{ asset('vendor/license-verifier-blade/license-verifier.js') }}"></script>
</body>
</html>
