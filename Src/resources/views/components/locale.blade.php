<li class="nav-item user-panel-custom">
    <div class="tab">
        <a class="tab-locale vi @if (config('app.locale') === 'vi') selected @endif"
            href="{{ route('locale','vi') }}">VN</a>
        <a class="tab-locale en @if (config('app.locale') === 'en') selected @endif"
            href="{{ route('locale','en') }}">EN</a>
    </div>
</li>