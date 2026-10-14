@extends('layouts.app')

@section('title', 'Profil & Identitas Sekolah')

@section('content')
    <div class="py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            @include('partials.section-title', ['eyebrow' => 'Identitas Resmi', 'title' => 'Profil & Identitas '.setting('school_name', 'MTs. Darul Ulum Waru')])

            <div class="bg-white rounded-3xl shadow-md border border-slate-100 overflow-hidden">
                <div class="bg-tosca-700 text-white p-6">
                    <h2 class="text-xl font-bold flex items-center space-x-2">
                        <i class="fa-solid fa-id-card text-gold-400"></i>
                        <span>Data Identitas Madrasah</span>
                    </h2>
                </div>
                <div class="p-6 sm:p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div class="border-b border-slate-100 pb-2">
                                <span class="text-xs font-bold text-slate-400 uppercase block">Nama Madrasah</span>
                                <span class="font-semibold text-slate-800 text-base">{{ setting('school_name', '-') }}</span>
                            </div>
                            <div class="border-b border-slate-100 pb-2">
                                <span class="text-xs font-bold text-slate-400 uppercase block">NPSN / NSM</span>
                                <span class="font-semibold text-slate-800 text-base">{{ setting('npsn_nsm', '-') }}</span>
                            </div>
                            <div class="border-b border-slate-100 pb-2">
                                <span class="text-xs font-bold text-slate-400 uppercase block">Status Akreditasi</span>
                                <span class="inline-block bg-emerald-100 text-emerald-800 text-xs font-bold px-3 py-1 rounded-full">{{ setting('accreditation', '-') }}</span>
                            </div>
                            <div class="border-b border-slate-100 pb-2">
                                <span class="text-xs font-bold text-slate-400 uppercase block">Status Kepemilikan</span>
                                <span class="font-semibold text-slate-800 text-base">{{ setting('ownership', '-') }}</span>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="border-b border-slate-100 pb-2">
                                <span class="text-xs font-bold text-slate-400 uppercase block">Alamat Lengkap</span>
                                <span class="font-semibold text-slate-800 text-base">{{ setting('address_full', '-') }}</span>
                            </div>
                            <div class="border-b border-slate-100 pb-2">
                                <span class="text-xs font-bold text-slate-400 uppercase block">Kode Pos / Telepon</span>
                                <span class="font-semibold text-slate-800 text-base">{{ setting('postal_code', '-') }} / {{ setting('contact_phone', '-') }}</span>
                            </div>
                            <div class="border-b border-slate-100 pb-2">
                                <span class="text-xs font-bold text-slate-400 uppercase block">Email & Website</span>
                                <span class="font-semibold text-slate-800 text-base">
                                    @if (setting('contact_email'))<a href="mailto:{{ setting('contact_email') }}" class="hover:text-tosca-700 transition">{{ setting('contact_email') }}</a>@else - @endif
                                    / {{ setting('website', '-') }}
                                </span>
                            </div>
                            <div class="border-b border-slate-100 pb-2">
                                <span class="text-xs font-bold text-slate-400 uppercase block">SK Izin Operasional</span>
                                <span class="font-semibold text-slate-800 text-base">{{ setting('sk_operational', '-') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
