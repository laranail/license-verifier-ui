{{-- Settings panel: shows current license details or the activation form. --}}
@php($info = $info ?? app(\Simtabi\Laranail\Licence\Verifier\Drivers\DriverManager::class)->active()->getLicenseInfo())
<div class="lv-settings" data-lv-settings>
    @if ($info->status->isUsable())
        <dl class="lv-details">
            <dt>{{ __('license-verifier::license-verifier.status') }}</dt><dd>{{ $info->status->label() }}</dd>
            @if ($info->licensedTo)
                <dt>{{ __('license-verifier::license-verifier.licensed_to') }}</dt><dd>{{ $info->licensedTo }}</dd>
            @endif
            @if ($info->expiresAt)
                <dt>{{ __('license-verifier::license-verifier.expires_at') }}</dt><dd>{{ $info->expiresAt }}</dd>
            @endif
        </dl>
        <form method="POST" action="{{ route('license-verifier.deactivate') }}" data-lv-form data-lv-reload>
            @csrf
            <button type="submit" class="lv-btn">{{ __('license-verifier::license-verifier.deactivate') }}</button>
        </form>
    @else
        @include('license-verifier-blade::license-form', ['fields' => app(\Simtabi\Laranail\Licence\Verifier\Drivers\DriverManager::class)->active()->activationFields()])
    @endif
</div>
