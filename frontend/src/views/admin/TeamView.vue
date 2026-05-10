<template>
  <div>
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
      <div>
        <h1 class="text-2xl font-sans font-bold dark:text-white">Team Members</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage your team roster and member profiles</p>
      </div>
      <button v-if="authStore.canDo('team_members', 'create')" @click="openAddForm" class="btn-primary flex items-center gap-2 text-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Member
      </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
      <div class="glass-card !p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-brand-violet/10 dark:bg-brand-violet/20 flex items-center justify-center">
          <svg class="w-5 h-5 text-brand-violet" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        </div>
        <div>
          <p class="text-xs text-gray-500 dark:text-gray-400">Total</p>
          <p class="text-lg font-bold dark:text-white">{{ members.length }}</p>
        </div>
      </div>
      <div class="glass-card !p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-amber-500/10 flex items-center justify-center">
          <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
        </div>
        <div>
          <p class="text-xs text-gray-500 dark:text-gray-400">Founders</p>
          <p class="text-lg font-bold text-amber-500">{{ founderCount }}</p>
        </div>
      </div>
      <div class="glass-card !p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-green-500/10 flex items-center justify-center">
          <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
        </div>
        <div>
          <p class="text-xs text-gray-500 dark:text-gray-400">Visible</p>
          <p class="text-lg font-bold text-green-500">{{ visibleCount }}</p>
        </div>
      </div>
      <div class="glass-card !p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-gray-500/10 flex items-center justify-center">
          <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878l4.242 4.242L21 21"/></svg>
        </div>
        <div>
          <p class="text-xs text-gray-500 dark:text-gray-400">Hidden</p>
          <p class="text-lg font-bold text-gray-500">{{ hiddenCount }}</p>
        </div>
      </div>
    </div>

    <!-- Search & View Toggle -->
    <div class="glass-card !p-4 mb-6">
      <div class="flex flex-col md:flex-row items-start md:items-center gap-3">
        <div class="relative flex-1 w-full">
          <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
          <input v-model="search" type="text" placeholder="Search members by name, title, or email..." class="input-field !pl-10 w-full" />
        </div>
        <div class="flex items-center border border-gray-200 dark:border-dark-600 rounded-lg overflow-hidden">
          <button @click="viewMode = 'grid'" class="p-2 transition-colors" :class="viewMode === 'grid' ? 'bg-brand-violet/10 text-brand-violet dark:bg-brand-cyan/10 dark:text-brand-cyan' : 'text-gray-400 hover:text-gray-600'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
          </button>
          <button @click="viewMode = 'table'" class="p-2 transition-colors" :class="viewMode === 'table' ? 'bg-brand-violet/10 text-brand-violet dark:bg-brand-cyan/10 dark:text-brand-cyan' : 'text-gray-400 hover:text-gray-600'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="flex justify-center py-20">
      <div class="w-10 h-10 border-4 border-brand-violet/30 border-t-brand-violet rounded-full animate-spin" />
    </div>

    <!-- Grid View -->
    <div v-else-if="viewMode === 'grid' && filtered.length" class="grid md:grid-cols-2 xl:grid-cols-3 gap-5">
      <div v-for="member in filtered" :key="member.id"
        class="glass-card relative group transition-all hover:border-brand-violet/30 dark:hover:border-brand-cyan/30 hover:shadow-lg overflow-hidden"
        :class="{ 'opacity-50': member.is_visible === false }">
        <!-- Founder Badge -->
        <div v-if="member.is_founder" class="absolute top-3 right-3 z-10">
          <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
            Founder
          </span>
        </div>
        <!-- Visibility Badge -->
        <div v-if="member.is_visible === false" class="absolute top-3 left-3 z-10">
          <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-medium bg-gray-100 text-gray-500 dark:bg-dark-600 dark:text-gray-400">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878l4.242 4.242L21 21"/></svg>
            Hidden
          </span>
        </div>

        <!-- Avatar Section -->
        <div class="flex flex-col items-center pt-6 pb-4">
          <div class="w-20 h-20 rounded-full overflow-hidden ring-4 ring-white dark:ring-dark-700 shadow-lg mb-4">
            <img v-if="member.avatar" :src="member.avatar" :alt="member.name" class="w-full h-full object-cover" />
            <div v-else class="w-full h-full flex items-center justify-center text-white font-bold text-2xl" :style="{ background: avatarGradient(member.name) }">
              {{ member.name?.charAt(0)?.toUpperCase() }}
            </div>
          </div>
          <h3 class="text-base font-bold text-gray-900 dark:text-white text-center">{{ member.name }}</h3>
          <p class="text-sm text-brand-violet dark:text-brand-cyan font-medium">{{ member.title }}</p>
          <span v-if="member.display_order != null" class="mt-1 text-[10px] text-gray-400 dark:text-gray-500 font-mono">#{{ member.display_order }}</span>
        </div>

        <!-- Bio -->
        <p v-if="member.bio" class="text-xs text-gray-500 dark:text-gray-400 text-center px-4 pb-3 line-clamp-2 leading-relaxed">{{ member.bio }}</p>

        <!-- Social Links -->
        <div class="flex items-center justify-center gap-2 px-4 pb-4">
          <a v-if="member.github_url" :href="member.github_url" target="_blank" rel="noopener" class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-dark-600 flex items-center justify-center text-gray-500 hover:text-gray-900 dark:hover:text-white hover:bg-gray-200 dark:hover:bg-dark-500 transition-colors" title="GitHub">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
          </a>
          <a v-if="member.linkedin_url" :href="member.linkedin_url" target="_blank" rel="noopener" class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-dark-600 flex items-center justify-center text-gray-500 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors" title="LinkedIn">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
          </a>
          <a v-if="member.telegram_url" :href="member.telegram_url" target="_blank" rel="noopener" class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-dark-600 flex items-center justify-center text-gray-500 hover:text-sky-500 hover:bg-sky-50 dark:hover:bg-sky-900/20 transition-colors" title="Telegram">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.479.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/></svg>
          </a>
          <a v-if="member.email" :href="'mailto:' + member.email" class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-dark-600 flex items-center justify-center text-gray-500 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors" title="Email">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
          </a>
        </div>

        <!-- Card Actions -->
        <div class="border-t border-gray-100 dark:border-dark-700 px-4 py-3 flex items-center justify-between">
          <button v-if="authStore.canDo('team_members', 'update')" @click="editMember(member)" class="text-xs font-medium text-brand-violet dark:text-brand-cyan hover:underline flex items-center gap-1">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Edit
          </button>
          <button v-if="authStore.canDo('team_members', 'delete')" @click="deleteTarget = member" class="text-xs font-medium text-red-500 hover:underline flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            Delete
          </button>
        </div>
      </div>
    </div>

    <!-- Table View -->
    <div v-else-if="viewMode === 'table' && filtered.length" class="glass-card overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="border-b border-gray-200 dark:border-dark-600">
              <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">#</th>
              <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Member</th>
              <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Title</th>
              <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Links</th>
              <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Founder</th>
              <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Visible</th>
              <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 dark:divide-dark-700">
            <tr v-for="member in filtered" :key="member.id" class="hover:bg-gray-50/50 dark:hover:bg-dark-700/30 transition-colors" :class="{ 'opacity-50': member.is_visible === false }">
              <td class="px-5 py-3">
                <span class="text-xs font-mono text-gray-400">{{ member.display_order }}</span>
              </td>
              <td class="px-5 py-3">
                <div class="flex items-center gap-3">
                  <div class="w-9 h-9 rounded-full overflow-hidden flex-shrink-0">
                    <img v-if="member.avatar" :src="member.avatar" :alt="member.name" class="w-full h-full object-cover" />
                    <div v-else class="w-full h-full flex items-center justify-center text-white font-bold text-sm" :style="{ background: avatarGradient(member.name) }">
                      {{ member.name?.charAt(0)?.toUpperCase() }}
                    </div>
                  </div>
                  <div class="min-w-0">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ member.name }}</p>
                    <p v-if="member.email" class="text-xs text-gray-400 truncate">{{ member.email }}</p>
                  </div>
                </div>
              </td>
              <td class="px-5 py-3">
                <p class="text-sm text-gray-700 dark:text-gray-300">{{ member.title }}</p>
              </td>
              <td class="px-5 py-3">
                <div class="flex items-center gap-1.5">
                  <a v-if="member.github_url" :href="member.github_url" target="_blank" rel="noopener" class="w-7 h-7 rounded-md bg-gray-100 dark:bg-dark-600 flex items-center justify-center text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                  </a>
                  <a v-if="member.linkedin_url" :href="member.linkedin_url" target="_blank" rel="noopener" class="w-7 h-7 rounded-md bg-gray-100 dark:bg-dark-600 flex items-center justify-center text-gray-400 hover:text-blue-600 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                  </a>
                  <a v-if="member.telegram_url" :href="member.telegram_url" target="_blank" rel="noopener" class="w-7 h-7 rounded-md bg-gray-100 dark:bg-dark-600 flex items-center justify-center text-gray-400 hover:text-sky-500 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.479.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/></svg>
                  </a>
                  <a v-if="member.email" :href="'mailto:' + member.email" class="w-7 h-7 rounded-md bg-gray-100 dark:bg-dark-600 flex items-center justify-center text-gray-400 hover:text-red-500 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                  </a>
                  <span v-if="!member.github_url && !member.linkedin_url && !member.telegram_url && !member.email" class="text-xs text-gray-400 italic">None</span>
                </div>
              </td>
              <td class="px-5 py-3 text-center">
                <span v-if="member.is_founder" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                  <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                  Yes
                </span>
                <span v-else class="text-xs text-gray-400">—</span>
              </td>
              <td class="px-5 py-3 text-center">
                <span :class="member.is_visible !== false ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-500 dark:bg-dark-600 dark:text-gray-400'" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-medium">
                  {{ member.is_visible !== false ? 'Visible' : 'Hidden' }}
                </span>
              </td>
              <td class="px-5 py-3 text-right">
                <div class="flex items-center justify-end gap-1">
                  <button v-if="authStore.canDo('team_members', 'update')" @click="editMember(member)" class="p-1.5 rounded-lg text-gray-400 hover:text-brand-violet dark:hover:text-brand-cyan hover:bg-brand-violet/10 dark:hover:bg-brand-cyan/10 transition-colors" title="Edit">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                  </button>
                  <button v-if="authStore.canDo('team_members', 'delete')" @click="deleteTarget = member" class="p-1.5 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-500/10 transition-colors" title="Delete">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Empty State -->
    <div v-else-if="!loading" class="glass-card flex flex-col items-center justify-center py-16">
      <div class="w-16 h-16 rounded-2xl bg-gray-100 dark:bg-dark-700 flex items-center justify-center mb-4">
        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
      </div>
      <h3 class="font-semibold text-gray-900 dark:text-white mb-1">No team members</h3>
      <p class="text-sm text-gray-500 dark:text-gray-400">{{ search ? 'No members match your search.' : 'Add your first team member to get started.' }}</p>
      <button v-if="search" @click="search = ''" class="mt-4 text-sm text-brand-violet dark:text-brand-cyan hover:underline">Clear search</button>
    </div>

    <!-- Add/Edit Modal -->
    <Teleport to="body">
      <Transition enter-active-class="transition-opacity duration-200" leave-active-class="transition-opacity duration-200" enter-from-class="opacity-0" leave-to-class="opacity-0">
        <div v-if="showForm" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" @click.self="showForm = false">
          <Transition enter-active-class="transition-all duration-200" leave-active-class="transition-all duration-200" enter-from-class="opacity-0 scale-95" leave-to-class="opacity-0 scale-95">
            <div v-if="showForm" class="bg-white dark:bg-dark-800 rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto border border-gray-200 dark:border-dark-600">
              <!-- Modal Header -->
              <div class="sticky top-0 bg-white dark:bg-dark-800 border-b border-gray-200 dark:border-dark-600 px-6 py-4 flex items-center justify-between rounded-t-2xl z-10">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ editingId ? 'Edit Member' : 'Add New Member' }}</h3>
                <button @click="showForm = false" class="p-2 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:text-gray-300 dark:hover:bg-dark-700 transition-colors">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
              </div>

              <!-- Form -->
              <form @submit.prevent="handleSubmit" class="p-6 space-y-5">
                <!-- Avatar Upload -->
                <div class="flex items-center gap-5">
                  <div class="w-20 h-20 rounded-full overflow-hidden flex-shrink-0 ring-4 ring-gray-100 dark:ring-dark-600">
                    <img v-if="avatarPreview" :src="avatarPreview" class="w-full h-full object-cover" />
                    <div v-else class="w-full h-full flex items-center justify-center text-white font-bold text-2xl" :style="{ background: avatarGradient(form.name || 'A') }">
                      {{ (form.name || 'A').charAt(0).toUpperCase() }}
                    </div>
                  </div>
                  <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Avatar</label>
                    <input type="file" accept="image/*" @change="onFile" class="text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-brand-violet/10 file:text-brand-violet dark:file:bg-brand-cyan/10 dark:file:text-brand-cyan hover:file:opacity-80 cursor-pointer" />
                    <p class="text-xs text-gray-400 mt-1">PNG, JPG up to 2MB</p>
                  </div>
                </div>

                <!-- Name & Title Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Full Name <span class="text-red-500">*</span></label>
                    <input v-model="form.name" required class="input-field" placeholder="e.g. John Doe" />
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Title <span class="text-red-500">*</span></label>
                    <input v-model="form.title" required class="input-field" placeholder="e.g. Full-Stack Developer" />
                  </div>
                </div>

                <!-- Bio -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Bio</label>
                  <textarea v-model="form.bio" rows="3" class="input-field resize-none" placeholder="Brief description about this team member..." />
                </div>

                <!-- Email -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Email</label>
                  <input v-model="form.email" type="email" class="input-field" placeholder="member@example.com" />
                </div>

                <!-- Social Links -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Social Links</label>
                  <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div class="relative">
                      <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                      <input v-model="form.github_url" type="url" class="input-field !pl-10" placeholder="GitHub URL" />
                    </div>
                    <div class="relative">
                      <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                      <input v-model="form.linkedin_url" type="url" class="input-field !pl-10" placeholder="LinkedIn URL" />
                    </div>
                    <div class="relative">
                      <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.479.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/></svg>
                      <input v-model="form.telegram_url" type="url" class="input-field !pl-10" placeholder="Telegram URL" />
                    </div>
                  </div>
                </div>

                <!-- Options Row -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Display Order</label>
                    <input v-model.number="form.display_order" type="number" min="0" class="input-field" />
                  </div>
                  <div class="flex items-center gap-3 md:pt-7">
                    <label class="relative inline-flex items-center cursor-pointer">
                      <input v-model="form.is_founder" type="checkbox" class="sr-only peer">
                      <div class="w-9 h-5 bg-gray-200 rounded-full peer dark:bg-dark-600 peer-checked:bg-amber-500 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-full" />
                    </label>
                    <span class="text-sm text-gray-700 dark:text-gray-300">Founder</span>
                  </div>
                  <div class="flex items-center gap-3 md:pt-7">
                    <label class="relative inline-flex items-center cursor-pointer">
                      <input v-model="form.is_visible" type="checkbox" class="sr-only peer">
                      <div class="w-9 h-5 bg-gray-200 rounded-full peer dark:bg-dark-600 peer-checked:bg-green-500 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-full" />
                    </label>
                    <span class="text-sm text-gray-700 dark:text-gray-300">Visible</span>
                  </div>
                </div>

                <!-- Error Message -->
                <div v-if="formError" class="p-3 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800">
                  <p class="text-sm text-red-600 dark:text-red-400">{{ formError }}</p>
                </div>

                <!-- Submit -->
                <div class="flex items-center gap-3 pt-2 border-t border-gray-200 dark:border-dark-600">
                  <button type="submit" :disabled="saving" class="btn-primary flex items-center gap-2">
                    <svg v-if="saving" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                    {{ saving ? 'Saving...' : editingId ? 'Update Member' : 'Add Member' }}
                  </button>
                  <button type="button" @click="showForm = false" class="px-4 py-2 text-sm font-medium rounded-lg bg-gray-100 dark:bg-dark-600 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-dark-500 transition-colors">Cancel</button>
                </div>
              </form>
            </div>
          </Transition>
        </div>
      </Transition>
    </Teleport>

    <!-- Delete Confirm Modal -->
    <Teleport to="body">
      <Transition enter-active-class="transition-opacity duration-200" leave-active-class="transition-opacity duration-200" enter-from-class="opacity-0" leave-to-class="opacity-0">
        <div v-if="deleteTarget" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" @click.self="deleteTarget = null">
          <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-2xl max-w-sm w-full p-6 border border-gray-200 dark:border-dark-600">
            <div class="flex items-center gap-3 mb-4">
              <div class="w-10 h-10 rounded-xl bg-red-500/10 flex items-center justify-center">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
              </div>
              <div>
                <h3 class="font-bold text-gray-900 dark:text-white">Remove Member</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">This action cannot be undone.</p>
              </div>
            </div>
            <div class="flex items-center gap-3 mb-5 p-3 rounded-lg bg-gray-50 dark:bg-dark-700">
              <div class="w-10 h-10 rounded-full overflow-hidden flex-shrink-0">
                <img v-if="deleteTarget.avatar" :src="deleteTarget.avatar" class="w-full h-full object-cover" />
                <div v-else class="w-full h-full flex items-center justify-center text-white font-bold" :style="{ background: avatarGradient(deleteTarget.name) }">
                  {{ deleteTarget.name?.charAt(0)?.toUpperCase() }}
                </div>
              </div>
              <div>
                <p class="text-sm font-semibold dark:text-white">{{ deleteTarget.name }}</p>
                <p class="text-xs text-gray-400">{{ deleteTarget.title }}</p>
              </div>
            </div>
            <div class="flex items-center gap-3 justify-end">
              <button @click="deleteTarget = null" class="px-4 py-2 text-sm font-medium rounded-lg bg-gray-100 dark:bg-dark-600 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-dark-500 transition-colors">Cancel</button>
              <button @click="confirmDelete" class="px-4 py-2 text-sm font-medium rounded-lg bg-red-600 text-white hover:bg-red-700 transition-colors">Remove</button>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { adminApi } from '@/services/api'
import { useUiStore } from '@/stores/ui'
import { useAuthStore } from '@/stores/auth'

const uiStore = useUiStore()
const authStore = useAuthStore()
const members = ref([])
const loading = ref(true)
const showForm = ref(false)
const editingId = ref(null)
const avatar = ref(null)
const avatarPreview = ref(null)
const saving = ref(false)
const formError = ref('')
const search = ref('')
const viewMode = ref('grid')
const deleteTarget = ref(null)

const defaultForm = { name: '', title: '', bio: '', email: '', github_url: '', linkedin_url: '', telegram_url: '', display_order: 0, is_founder: false, is_visible: true }
const form = ref({ ...defaultForm })

const founderCount = computed(() => members.value.filter(m => m.is_founder).length)
const visibleCount = computed(() => members.value.filter(m => m.is_visible !== false).length)
const hiddenCount = computed(() => members.value.filter(m => m.is_visible === false).length)

const filtered = computed(() => {
  if (!search.value) return members.value
  const q = search.value.toLowerCase()
  return members.value.filter(m =>
    (m.name || '').toLowerCase().includes(q) ||
    (m.title || '').toLowerCase().includes(q) ||
    (m.email || '').toLowerCase().includes(q)
  )
})

function avatarGradient(name) {
  const colors = [
    'linear-gradient(135deg, #6c5ce7, #a29bfe)',
    'linear-gradient(135deg, #00cec9, #81ecec)',
    'linear-gradient(135deg, #e17055, #fab1a0)',
    'linear-gradient(135deg, #0984e3, #74b9ff)',
    'linear-gradient(135deg, #6c5ce7, #fd79a8)',
    'linear-gradient(135deg, #00b894, #55efc4)',
    'linear-gradient(135deg, #fdcb6e, #e17055)',
  ]
  return colors[(name || '').charCodeAt(0) % colors.length]
}

function openAddForm() {
  form.value = { ...defaultForm }
  editingId.value = null
  avatar.value = null
  avatarPreview.value = null
  formError.value = ''
  showForm.value = true
}

function editMember(m) {
  form.value = {
    name: m.name || '',
    title: m.title || '',
    bio: m.bio || '',
    email: m.email || '',
    github_url: m.github_url || '',
    linkedin_url: m.linkedin_url || '',
    telegram_url: m.telegram_url || '',
    display_order: m.display_order || 0,
    is_founder: !!m.is_founder,
    is_visible: m.is_visible !== false,
  }
  editingId.value = m.id
  avatar.value = null
  avatarPreview.value = m.avatar || null
  formError.value = ''
  showForm.value = true
}

function onFile(e) {
  const file = e.target.files[0]
  if (!file) return
  avatar.value = file
  const reader = new FileReader()
  reader.onload = (ev) => { avatarPreview.value = ev.target.result }
  reader.readAsDataURL(file)
}

async function handleSubmit() {
  saving.value = true
  formError.value = ''

  const fd = new FormData()
  fd.append('name', form.value.name)
  fd.append('title', form.value.title)
  fd.append('bio', form.value.bio || '')
  fd.append('email', form.value.email || '')
  fd.append('github_url', form.value.github_url || '')
  fd.append('linkedin_url', form.value.linkedin_url || '')
  fd.append('telegram_url', form.value.telegram_url || '')
  fd.append('display_order', form.value.display_order || 0)
  fd.append('is_founder', form.value.is_founder ? '1' : '0')
  fd.append('is_visible', form.value.is_visible ? '1' : '0')
  if (avatar.value) fd.append('avatar', avatar.value)

  try {
    if (editingId.value) {
      await adminApi.updateTeamMember(editingId.value, fd)
    } else {
      await adminApi.createTeamMember(fd)
    }
    uiStore.showToast(editingId.value ? 'Member updated!' : 'Member added!')
    showForm.value = false
    fetchMembers()
  } catch (err) {
    if (err.response?.data?.intercepted) {
      uiStore.showToast(err.response.data.message || 'Queued for approval', 'warning')
      showForm.value = false
    } else {
      const msg = err.response?.data?.message || 'Failed to save'
      formError.value = msg
      uiStore.showToast(msg, 'error')
    }
  } finally {
    saving.value = false
  }
}

async function confirmDelete() {
  if (!deleteTarget.value) return
  try {
    await adminApi.deleteTeamMember(deleteTarget.value.id)
    members.value = members.value.filter(x => x.id !== deleteTarget.value.id)
    uiStore.showToast('Member removed')
  } catch (e) {
    if (e.response?.data?.intercepted) {
      uiStore.showToast(e.response.data.message || 'Queued for approval', 'warning')
    } else {
      uiStore.showToast('Failed to remove', 'error')
    }
  }
  deleteTarget.value = null
}

async function fetchMembers() {
  loading.value = true
  try {
    const { data } = await adminApi.getTeamMembers()
    members.value = data.data || []
  } catch {
    members.value = []
  } finally {
    loading.value = false
  }
}

onMounted(() => fetchMembers())
</script>
