    <!-- Top Bar -->
    <div class="bg-tosca-900 text-slate-200 text-xs py-2 border-b border-tosca-800/50 hidden md:block whitespace-nowrap">
        <div class="max-w-[1440px] mx-auto px-6 lg:px-8 2xl:px-10 flex justify-between items-center gap-10">
            <div class="flex items-center gap-6">
                @if (setting('contact_phone'))
                    <a href="{{ tel_link(setting('contact_phone')) }}" class="flex items-center hover:text-gold-400 transition"><i class="fa-solid fa-phone text-gold-400 mr-2"></i> {{ setting('contact_phone') }}</a>
                @endif
                @if (setting('contact_whatsapp'))
                    <a href="{{ wa_link(setting('contact_whatsapp')) }}" target="_blank" rel="noopener noreferrer" class="flex items-center hover:text-gold-400 transition"><i class="fa-brands fa-whatsapp text-gold-400 mr-2 text-sm"></i> {{ setting('contact_whatsapp') }}</a>
                @endif
                @if (setting('contact_email'))
                    <a href="mailto:{{ setting('contact_email') }}" class="flex items-center hover:text-gold-400 transition"><i class="fa-solid fa-envelope text-gold-400 mr-2"></i> {{ setting('contact_email') }}</a>
                @endif
                @if (setting('contact_address'))
                    <span class="hidden xl:flex items-center"><i class="fa-solid fa-location-dot text-gold-400 mr-2"></i> {{ setting('contact_address') }}</span>
                @endif
            </div>
            <div class="flex items-center gap-5">
                <div class="flex space-x-3 text-sm">
                    @include('partials.social-links')
                </div>
            </div>
        </div>
    </div>
