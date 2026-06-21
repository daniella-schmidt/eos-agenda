@php
    $agendaLinks = [
        [
            'label' => 'Eventos',
            'route' => 'events.index',
            'active' => 'events.*',
        ],
        [
            'label' => 'Calend&aacute;rios',
            'route' => 'calendars.index',
            'active' => 'calendars.*',
        ],
        [
            'label' => 'Contatos',
            'route' => 'contacts.index',
            'active' => 'contacts.*',
        ],
    ];

    $smartLinks = [
        [
            'label' => 'Smart Requests',
            'route' => 'smart-requests.index',
            'active' => 'smart-requests.*',
        ],
    ];

    $allLinks = array_merge($agendaLinks, $smartLinks);
    $isAgendaActive = collect($agendaLinks)->contains(fn ($link) => request()->routeIs($link['active']));
    $isSmartActive = collect($smartLinks)->contains(fn ($link) => request()->routeIs($link['active']));

    $dropdownTriggerClasses = fn ($active) => $active
        ? 'inline-flex h-16 items-center gap-1 border-b-2 border-[#008f91] px-1 pt-1 text-sm font-semibold leading-5 text-[#0d2b2b] transition duration-150 ease-in-out focus:outline-none'
        : 'inline-flex h-16 items-center gap-1 border-b-2 border-transparent px-1 pt-1 text-sm font-medium leading-5 text-gray-500 transition duration-150 ease-in-out hover:border-[#ff6bb3] hover:text-[#0d2b2b] focus:border-[#008f91] focus:text-[#0d2b2b] focus:outline-none';
@endphp

<nav x-data="{ open: false }" class="sticky top-0 z-50 border-b border-gray-200 bg-white shadow-sm">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 justify-between">
            <div class="flex min-w-0">
                <div class="flex shrink-0 items-center">
                    <a href="{{ route('dashboard') }}" class="group inline-flex items-center gap-2" aria-label="Ir para o dashboard">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl text-lg font-black text-[#008f91] transition group-hover:-translate-y-0.5">
                            EO<span class="text-[#ff6bb3]">S</span>
                        </span>
                        <span class="hidden text-sm font-bold text-[#0d2b2b] lg:inline">Agenda</span>
                    </a>
                </div>

                <div class="hidden sm:-my-px sm:ms-10 sm:flex sm:items-center sm:gap-6">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    Home
                    </x-nav-link>

                    <x-dropdown align="left" width="48">
                        <x-slot name="trigger">
                            <button type="button" class="{{ $dropdownTriggerClasses($isAgendaActive) }}">
                                Agenda
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            @foreach ($agendaLinks as $link)
                                <x-dropdown-link :href="route($link['route'])">
                                    {!! $link['label'] !!}
                                </x-dropdown-link>
                            @endforeach
                        </x-slot>
                    </x-dropdown>

                    <x-dropdown align="left" width="48">
                        <x-slot name="trigger">
                            <button type="button" class="{{ $dropdownTriggerClasses($isSmartActive) }}">
                                Intelig&ecirc;ncia
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            @foreach ($smartLinks as $link)
                                <x-dropdown-link :href="route($link['route'])">
                                    {!! $link['label'] !!}
                                </x-dropdown-link>
                            @endforeach
                        </x-slot>
                    </x-dropdown>

                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6" style="gap: 20px;">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button type="button" class="inline-flex items-center gap-2 rounded-full border border-gray-200 bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-600 transition duration-150 ease-in-out hover:border-[#008f91] hover:text-[#0d2b2b] focus:outline-none">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-[#ccfeff] text-xs font-bold text-[#008f91]">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </span>
                            <span class="max-w-32 truncate">{{ Auth::user()->name }}</span>
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            Perfil
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                Sair
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>

                <a href="{{ route('user-preferences.index') }}"
                   title="Preferências"
                   class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-500 transition duration-150 ease-in-out hover:border-[#008f91] hover:text-[#008f91] {{ request()->routeIs('user-preferences.*') ? 'border-[#008f91] text-[#008f91] bg-[#e5ffff]' : '' }}"
                   aria-label="Configurações">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </a>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" type="button" class="inline-flex items-center justify-center rounded-md p-2 text-gray-500 transition duration-150 ease-in-out hover:bg-[#ccfeff] hover:text-[#0d2b2b] focus:bg-[#ccfeff] focus:text-[#0d2b2b] focus:outline-none" aria-label="Abrir menu">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden border-t border-gray-100 bg-white sm:hidden">
        <div class="space-y-1 pb-3 pt-2">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                Home
            </x-responsive-nav-link>

            @foreach ($allLinks as $link)
                <x-responsive-nav-link :href="route($link['route'])" :active="request()->routeIs($link['active'])">
                    {!! $link['label'] !!}
                </x-responsive-nav-link>
            @endforeach
        </div>

        <div class="border-t border-gray-200 pb-1 pt-4">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('user-preferences.index')" :active="request()->routeIs('user-preferences.*')">
                    Preferências
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')">
                    Perfil
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        Sair
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
