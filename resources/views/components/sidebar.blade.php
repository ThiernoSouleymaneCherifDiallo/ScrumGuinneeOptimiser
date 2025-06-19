<!-- Sidebar -->
<aside class="fixed left-0 top-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-[#23272f] border-r border-[#31343b] sm:translate-x-0" aria-label="Sidebar">
    <div class="h-full px-3 pb-4 overflow-y-auto bg-[#23272f]">
        <ul class="space-y-2 font-medium">
            <li>
                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                    </svg>
                    <span>Tableau de bord</span>
                </x-nav-link>
            </li>
            <li>
                @if(isset($project))
                    <x-nav-link :href="route('tasks.index', $project)" :active="request()->routeIs('tasks.*')">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                        <span>Tâches</span>
                    </x-nav-link>
                @else
                    <div class="flex items-center p-2 text-slate-500 rounded-lg cursor-not-allowed">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                        <span>Tâches</span>
                    </div>
                @endif
            </li>
            <li>
                <a href="{{ route('projects.index') }}" class="flex items-center p-2 text-slate-200 rounded-lg hover:bg-[#232b36] group">
                    <svg class="flex-shrink-0 w-5 h-5 text-slate-400 transition duration-75 group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18">
                        <path d="M6.143 0H1.857A1.857 1.857 0 0 0 0 1.857v4.286C0 7.169.831 8 1.857 8h4.286A1.857 1.857 0 0 0 8 6.143V1.857A1.857 1.857 0 0 0 6.143 0Zm10 0h-4.286A1.857 1.857 0 0 0 10 1.857v4.286C10 7.169 10.831 8 11.857 8h4.286A1.857 1.857 0 0 0 18 6.143V1.857A1.857 1.857 0 0 0 16.143 0Zm-10 10H1.857A1.857 1.857 0 0 0 0 11.857v4.286C0 17.169.831 18 1.857 18h4.286A1.857 1.857 0 0 0 8 16.143v-4.286A1.857 1.857 0 0 0 6.143 10Zm10 0h-4.286A1.857 1.857 0 0 0 10 11.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 18 16.143v-4.286A1.857 1.857 0 0 0 16.143 10Z"/>
                    </svg>
                    <span class="flex-1 ms-3 whitespace-nowrap">Projets</span>
                </a>
            </li>
            <li>
                <a href="{{ isset($project) ? route('projects.sprints.index', $project) : '#' }}" class="flex items-center p-2 text-slate-200 rounded-lg hover:bg-[#232b36] group">
                    <svg class="flex-shrink-0 w-5 h-5 text-slate-400 transition duration-75 group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M5 5V.13a2.96 2.96 0 0 0-1.293.749L.879 3.707A2.96 2.96 0 0 0 .13 5H5Z"/>
                        <path d="M6.737 11.061a2.961 2.961 0 0 1 .81-1.515l6.117-6.116A4.839 4.839 0 0 1 16 2.141V2a1.97 1.97 0 0 0-1.933-2H7v5a2 2 0 0 1-2 2H0v11a1.969 1.969 0 0 0 1.933 2h12.134A1.97 1.97 0 0 0 16 18v-3.093l-1.546 1.546c-.413.413-.94.695-1.514.81l-3.165.707a.5.5 0 0 1-.647-.646l.708-3.166a4.8 4.8 0 0 1 .81-1.517l6.117-6.116Z"/>
                        <path d="M8.961 16a.93.93 0 0 0 .189-.019l3.4-.679a.961.961 0 0 0 .49-.263l6.118-6.117a2.884 2.884 0 0 0-4.079-4.078l-6.117 6.117a.96.96 0 0 0-.263.491l-.678 3.4A.961.961 0 0 0 8.961 16Zm7.477-9.8a.958.958 0 0 1 .68-.281.961.961 0 0 1 .682 1.644l-.315.315-1.36-1.36.313-.318Zm-5.911 5.911 4.236-4.236 1.359 1.36-4.236 4.237-1.7.339.34-1.7Z"/>
                    </svg>
                    <span class="flex-1 ms-3 whitespace-nowrap">Sprint</span>
                </a>
            </li>
            <li>
                <a href="{{ isset($project) ? route('projects.backlog.index', $project) : '#' }}" class="flex items-center p-2 text-slate-200 rounded-lg hover:bg-[#232b36] group">
                    <svg class="flex-shrink-0 w-5 h-5 text-slate-400 transition duration-75 group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                        <path d="M14 2a3.963 3.963 0 0 0-1.4.267 6.439 6.439 0 0 1-1.331 6.638A4 4 0 1 0 14 2Zm1 9h-1.264A6.957 6.957 0 0 1 15 15v2a2.97 2.97 0 0 1-.184 1H19a1 1 0 0 0 1-1v-1a5.006 5.006 0 0 0-5-5ZM6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Z"/>
                    </svg>
                    <span class="flex-1 ms-3 whitespace-nowrap">Backlog</span>
                </a>
            </li>
            <li>
                <a href="#" class="flex items-center p-2 text-slate-200 rounded-lg hover:bg-[#232b36] group">
                    <svg class="flex-shrink-0 w-5 h-5 text-slate-400 transition duration-75 group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                        <path d="M14 2a3.963 3.963 0 0 0-1.4.267 6.439 6.439 0 0 1-1.331 6.638A4 4 0 1 0 14 2Zm1 9h-1.264A6.957 6.957 0 0 1 15 15v2a2.97 2.97 0 0 1-.184 1H19a1 1 0 0 0 1-1v-1a5.006 5.006 0 0 0-5-5ZM6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Z"/>
                    </svg>
                    <span class="flex-1 ms-3 whitespace-nowrap">Équipe</span>
                </a>
            </li>
            <li>
                @if(isset($project) && isset($sprint))
                    <a href="{{ route('projects.sprints.summary', ['project' => $project, 'sprint' => $sprint]) }}" class="flex items-center p-2 text-slate-200 rounded-lg hover:bg-[#232b36] group">
                        <svg class="flex-shrink-0 w-5 h-5 text-slate-400 transition duration-75 group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"/>
                            <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"/>
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Résumé Sprint</span>
                    </a>
                @else
                    <div class="flex items-center p-2 text-slate-500 rounded-lg cursor-not-allowed">
                        <svg class="flex-shrink-0 w-5 h-5 text-slate-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"/>
                            <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"/>
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Résumé Sprint</span>
                    </div>
                @endif
            </li>
            <li>
                <a href="{{ route('projects.chat.detached', $project) }}" class="flex items-center p-2 text-slate-200 rounded-lg hover:bg-[#232b36] group">
                    <svg class="flex-shrink-0 w-5 h-5 text-slate-400 transition duration-75 group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M7.824 5.016a.5.5 0 0 1 .566 0 7.608 7.608 0 0 1 3.22 3.22.5.5 0 0 1 0 .566 7.608 7.608 0 0 1-3.22 3.22.5.5 0 0 1-.566 0 7.608 7.608 0 0 1-3.22-3.22.5.5 0 0 1 0-.566 7.608 7.608 0 0 1 3.22-3.22Z"/>
                        <path d="M10 0c4.3 0 8 3.033 8 7 0 1.887-.82 3.6-2.12 4.815a1 1 0 0 1-.283.35l-.66.51a1 1 0 0 1-1.2 0l-.66-.51a1 1 0 0 1-.283-.35A6.8 6.8 0 0 1 10 12c-4.3 0-8-3.033-8-7s3.7-7 8-7Z"/>
                    </svg>
                    <span class="flex-1 ms-3 whitespace-nowrap">ChatBot</span>
                </a>
            </li>
            <li>
                <a href="#" class="flex items-center p-2 text-slate-200 rounded-lg hover:bg-[#232b36] group">
                    <svg class="flex-shrink-0 w-5 h-5 text-slate-400 transition duration-75 group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                    </svg>
                    <span class="flex-1 ms-3 whitespace-nowrap">Paramètres</span>
                </a>
            </li>
        </ul>
    </div>
</aside>
