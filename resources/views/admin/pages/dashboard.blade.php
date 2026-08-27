@extends('admin.layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex justify-between items-end animate-fade-in-up">
            <div>
                <h1 class="text-xl font-bold tracking-tight text-zinc-900 dark:text-white tracking-tight">Dashboard Overview</h1>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Here's what's happening with your platform today.</p>
            </div>
            <div class="hidden sm:flex space-x-2">
                <button class="px-4 py-2 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors shadow-sm flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Export Report
                </button>
                <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors shadow-sm shadow-blue-500/30 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    New Campaign
                </button>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 animate-fade-in-up delay-100">
            <!-- Stat Card 1 -->
            <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-100 dark:border-zinc-700 p-6 transform transition-all duration-300 hover:-translate-y-1 hover:shadow-md group cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Total Users</p>
                        <p class="mt-2 text-xl font-bold tracking-tight tracking-tight text-zinc-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">2,543</p>
                    </div>
                    <div class="p-3 bg-blue-50 dark:bg-blue-900/40 rounded-xl group-hover:bg-blue-100 dark:group-hover:bg-blue-900/60 transition-colors">
                        <svg class="w-7 h-7 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span class="text-green-600 dark:text-green-400 font-semibold flex items-center bg-green-50 dark:bg-green-900/30 px-2 py-0.5 rounded-full">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                        12.5%
                    </span>
                    <span class="text-zinc-500 dark:text-zinc-400 ml-2">vs last month</span>
                </div>
            </div>

            <!-- Stat Card 2 -->
            <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-100 dark:border-zinc-700 p-6 transform transition-all duration-300 hover:-translate-y-1 hover:shadow-md group cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Total Revenue</p>
                        <p class="mt-2 text-xl font-bold tracking-tight tracking-tight text-zinc-900 dark:text-white group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors">$45,231</p>
                    </div>
                    <div class="p-3 bg-green-50 dark:bg-green-900/40 rounded-xl group-hover:bg-green-100 dark:group-hover:bg-green-900/60 transition-colors">
                        <svg class="w-7 h-7 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span class="text-green-600 dark:text-green-400 font-semibold flex items-center bg-green-50 dark:bg-green-900/30 px-2 py-0.5 rounded-full">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                        8.2%
                    </span>
                    <span class="text-zinc-500 dark:text-zinc-400 ml-2">vs last month</span>
                </div>
            </div>

            <!-- Stat Card 3 -->
            <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-100 dark:border-zinc-700 p-6 transform transition-all duration-300 hover:-translate-y-1 hover:shadow-md group cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Active Projects</p>
                        <p class="mt-2 text-xl font-bold tracking-tight tracking-tight text-zinc-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">124</p>
                    </div>
                    <div class="p-3 bg-purple-50 dark:bg-purple-900/40 rounded-xl group-hover:bg-purple-100 dark:group-hover:bg-purple-900/60 transition-colors">
                        <svg class="w-7 h-7 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span class="text-green-600 dark:text-green-400 font-semibold flex items-center bg-green-50 dark:bg-green-900/30 px-2 py-0.5 rounded-full">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                        4.3%
                    </span>
                    <span class="text-zinc-500 dark:text-zinc-400 ml-2">vs last month</span>
                </div>
            </div>

            <!-- Stat Card 4 -->
            <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-100 dark:border-zinc-700 p-6 transform transition-all duration-300 hover:-translate-y-1 hover:shadow-md group cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Bounce Rate</p>
                        <p class="mt-2 text-xl font-bold tracking-tight tracking-tight text-zinc-900 dark:text-white group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors">24.5%</p>
                    </div>
                    <div class="p-3 bg-orange-50 dark:bg-orange-900/40 rounded-xl group-hover:bg-orange-100 dark:group-hover:bg-orange-900/60 transition-colors">
                        <svg class="w-7 h-7 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span class="text-red-600 dark:text-red-400 font-semibold flex items-center bg-red-50 dark:bg-red-900/30 px-2 py-0.5 rounded-full">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path></svg>
                        2.4%
                    </span>
                    <span class="text-zinc-500 dark:text-zinc-400 ml-2">vs last month</span>
                </div>
            </div>
        </div>

        <!-- Charts Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-fade-in-up delay-200">
            <!-- Revenue Area Chart -->
            <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-100 dark:border-zinc-700 lg:col-span-2 group">
                <div class="p-6 border-b border-zinc-100 dark:border-zinc-700 flex justify-between items-center">
                    <h2 class="text-base font-semibold tracking-tight text-zinc-900 dark:text-white">Revenue Growth</h2>
                    <div class="flex space-x-2">
                        <button class="px-3 py-1 bg-zinc-100 dark:bg-zinc-700 text-xs font-medium text-zinc-700 dark:text-zinc-300 rounded-md hover:bg-zinc-200 dark:hover:bg-zinc-600 transition-colors">7d</button>
                        <button class="px-3 py-1 bg-transparent text-xs font-medium text-zinc-500 dark:text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-300 rounded-md transition-colors">30d</button>
                        <button class="px-3 py-1 bg-transparent text-xs font-medium text-zinc-500 dark:text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-300 rounded-md transition-colors">YTD</button>
                    </div>
                </div>
                <div class="p-6">
                    <div id="revenueChart" class="w-full h-72 transition-opacity duration-300"></div>
                </div>
            </div>

            <!-- Device Usage Donut Chart -->
            <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-100 dark:border-zinc-700 lg:col-span-1 group">
                <div class="p-6 border-b border-zinc-100 dark:border-zinc-700">
                    <h2 class="text-base font-semibold tracking-tight text-zinc-900 dark:text-white">Traffic Sources</h2>
                </div>
                <div class="p-6 flex flex-col justify-center items-center h-full pb-10">
                    <div id="trafficChart" class="w-full h-64 flex justify-center"></div>
                </div>
            </div>
        </div>

        <!-- Tables Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-fade-in-up delay-300">
            <!-- Recent Users -->
            <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-100 dark:border-zinc-700 lg:col-span-2">
                <div class="p-6 border-b border-zinc-100 dark:border-zinc-700 flex justify-between items-center">
                    <h2 class="text-base font-semibold tracking-tight text-zinc-900 dark:text-white">Recent Users</h2>
                    <a href="#" class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline">View All</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-zinc-50 dark:bg-zinc-700">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                                    User</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                                    Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <img class="h-10 w-10 rounded-full"
                                            src="https://ui-avatars.com/api/?name=John+Doe" alt="">
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-zinc-900 dark:text-white">John Doe</div>
                                            <div class="text-sm text-zinc-500 dark:text-zinc-400">john@example.com</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">Active</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">2 hours
                                    ago</td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <img class="h-10 w-10 rounded-full"
                                            src="https://ui-avatars.com/api/?name=Jane+Smith" alt="">
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-zinc-900 dark:text-white">Jane Smith</div>
                                            <div class="text-sm text-zinc-500 dark:text-zinc-400">jane@example.com</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">Active</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">5 hours
                                    ago</td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <img class="h-10 w-10 rounded-full"
                                            src="https://ui-avatars.com/api/?name=Bob+Johnson" alt="">
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-zinc-900 dark:text-white">Bob Johnson
                                            </div>
                                            <div class="text-sm text-zinc-500 dark:text-zinc-400">bob@example.com</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">Pending</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">1 day ago
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-100 dark:border-zinc-700 lg:col-span-1">
                <div class="p-6 border-b border-zinc-100 dark:border-zinc-700">
                    <h2 class="text-base font-semibold tracking-tight text-zinc-900 dark:text-white">Quick Actions</h2>
                </div>
                <div class="p-4 space-y-3">
                    <a href="#" class="flex items-center p-3 text-base font-medium text-zinc-900 rounded-lg bg-zinc-50 hover:bg-zinc-100 hover:shadow-sm group hover:-translate-y-0.5 transition-all dark:bg-zinc-700/50 dark:hover:bg-zinc-700 dark:text-white">
                        <span class="flex-shrink-0 w-8 h-8 flex items-center justify-center bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 rounded-md">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        </span>
                        <span class="flex-1 ml-3 whitespace-nowrap group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">Add New User</span>
                    </a>
                    <a href="#" class="flex items-center p-3 text-base font-medium text-zinc-900 rounded-lg bg-zinc-50 hover:bg-zinc-100 hover:shadow-sm group hover:-translate-y-0.5 transition-all dark:bg-zinc-700/50 dark:hover:bg-zinc-700 dark:text-white">
                        <span class="flex-shrink-0 w-8 h-8 flex items-center justify-center bg-green-100 dark:bg-green-900/40 text-green-600 dark:text-green-400 rounded-md">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </span>
                        <span class="flex-1 ml-3 whitespace-nowrap group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors">Generate Report</span>
                    </a>
                    <a href="#" class="flex items-center p-3 text-base font-medium text-zinc-900 rounded-lg bg-zinc-50 hover:bg-zinc-100 hover:shadow-sm group hover:-translate-y-0.5 transition-all dark:bg-zinc-700/50 dark:hover:bg-zinc-700 dark:text-white">
                        <span class="flex-shrink-0 w-8 h-8 flex items-center justify-center bg-purple-100 dark:bg-purple-900/40 text-purple-600 dark:text-purple-400 rounded-md">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </span>
                        <span class="flex-1 ml-3 whitespace-nowrap group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">System Settings</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Fourth Row (Products & Server) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-fade-in-up delay-300" style="animation-delay: 400ms;">
            <!-- Top Products Table -->
            <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-100 dark:border-zinc-700 lg:col-span-2">
                <div class="p-6 border-b border-zinc-100 dark:border-zinc-700 flex justify-between items-center">
                    <h2 class="text-base font-semibold tracking-tight text-zinc-900 dark:text-white">Top Selling Products</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-zinc-500 dark:text-zinc-400">
                        <thead class="text-xs text-zinc-700 uppercase bg-zinc-50 dark:bg-zinc-700 dark:text-zinc-400">
                            <tr>
                                <th scope="col" class="px-6 py-4">Product Name</th>
                                <th scope="col" class="px-6 py-4">Price</th>
                                <th scope="col" class="px-6 py-4">Sold</th>
                                <th scope="col" class="px-6 py-4">Sales Target</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="bg-white border-b dark:bg-zinc-800 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-700/50 transition-colors">
                                <td class="px-6 py-4 flex items-center">
                                    <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/50 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <span class="font-medium text-zinc-900 dark:text-white">Smartphone X Pro</span>
                                </td>
                                <td class="px-6 py-4 font-semibold text-zinc-900 dark:text-white">$899</td>
                                <td class="px-6 py-4 text-green-600 dark:text-green-400 font-medium">1,245</td>
                                <td class="px-6 py-4">
                                    <div class="w-full bg-zinc-200 rounded-full h-2.5 dark:bg-zinc-700">
                                        <div class="bg-indigo-600 h-2.5 rounded-full" style="width: 85%"></div>
                                    </div>
                                </td>
                            </tr>
                            <tr class="bg-white border-b dark:bg-zinc-800 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-700/50 transition-colors">
                                <td class="px-6 py-4 flex items-center">
                                    <div class="w-10 h-10 bg-pink-100 dark:bg-pink-900/50 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-6 h-6 text-pink-600 dark:text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path></svg>
                                    </div>
                                    <span class="font-medium text-zinc-900 dark:text-white">Wireless Earbuds V2</span>
                                </td>
                                <td class="px-6 py-4 font-semibold text-zinc-900 dark:text-white">$149</td>
                                <td class="px-6 py-4 text-green-600 dark:text-green-400 font-medium">3,120</td>
                                <td class="px-6 py-4">
                                    <div class="w-full bg-zinc-200 rounded-full h-2.5 dark:bg-zinc-700">
                                        <div class="bg-pink-600 h-2.5 rounded-full" style="width: 92%"></div>
                                    </div>
                                </td>
                            </tr>
                            <tr class="bg-white border-b dark:bg-zinc-800 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-700/50 transition-colors">
                                <td class="px-6 py-4 flex items-center">
                                    <div class="w-10 h-10 bg-teal-100 dark:bg-teal-900/50 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-6 h-6 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <span class="font-medium text-zinc-900 dark:text-white">Smart Watch Series 5</span>
                                </td>
                                <td class="px-6 py-4 font-semibold text-zinc-900 dark:text-white">$299</td>
                                <td class="px-6 py-4 text-green-600 dark:text-green-400 font-medium">854</td>
                                <td class="px-6 py-4">
                                    <div class="w-full bg-zinc-200 rounded-full h-2.5 dark:bg-zinc-700">
                                        <div class="bg-teal-600 h-2.5 rounded-full" style="width: 65%"></div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Server Status -->
            <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-100 dark:border-zinc-700 lg:col-span-1">
                <div class="p-6 border-b border-zinc-100 dark:border-zinc-700 flex justify-between items-center">
                    <h2 class="text-base font-semibold tracking-tight text-zinc-900 dark:text-white">Server Resources</h2>
                    <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded-full dark:bg-green-900 dark:text-green-300">Healthy</span>
                </div>
                <div class="p-6 space-y-6">
                    <!-- CPU -->
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">CPU Usage</span>
                            <span class="text-sm font-medium text-zinc-900 dark:text-white">45%</span>
                        </div>
                        <div class="w-full bg-zinc-200 rounded-full h-2 dark:bg-zinc-700">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: 45%"></div>
                        </div>
                    </div>
                    <!-- RAM -->
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Memory (RAM)</span>
                            <span class="text-sm font-medium text-zinc-900 dark:text-white">72%</span>
                        </div>
                        <div class="w-full bg-zinc-200 rounded-full h-2 dark:bg-zinc-700">
                            <div class="bg-yellow-400 h-2 rounded-full" style="width: 72%"></div>
                        </div>
                        <p class="text-xs text-zinc-500 mt-1">11.5 GB / 16 GB used</p>
                    </div>
                    <!-- Disk -->
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">SSD Storage</span>
                            <span class="text-sm font-medium text-zinc-900 dark:text-white">88%</span>
                        </div>
                        <div class="w-full bg-zinc-200 rounded-full h-2 dark:bg-zinc-700">
                            <div class="bg-red-500 h-2 rounded-full" style="width: 88%"></div>
                        </div>
                        <p class="text-xs text-zinc-500 mt-1">440 GB / 500 GB used</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fifth Row (Bar Chart & Activity Timeline) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-fade-in-up delay-300" style="animation-delay: 500ms;">
            <!-- Bar Chart -->
            <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-100 dark:border-zinc-700 lg:col-span-2 group">
                <div class="p-6 border-b border-zinc-100 dark:border-zinc-700">
                    <h2 class="text-base font-semibold tracking-tight text-zinc-900 dark:text-white">Sales by Category</h2>
                </div>
                <div class="p-6">
                    <div id="barChart" class="w-full h-72 transition-opacity duration-300"></div>
                </div>
            </div>

            <!-- Activity Timeline -->
            <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-100 dark:border-zinc-700 lg:col-span-1">
                <div class="p-6 border-b border-zinc-100 dark:border-zinc-700">
                    <h2 class="text-base font-semibold tracking-tight text-zinc-900 dark:text-white">Activity Timeline</h2>
                </div>
                <div class="p-6">
                    <ol class="relative border-l border-zinc-200 dark:border-zinc-700 ml-3">
                        <li class="mb-6 ml-6">
                            <span class="absolute flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full -left-3 ring-8 ring-white dark:ring-zinc-800 dark:bg-blue-900">
                                <svg class="w-3 h-3 text-blue-800 dark:text-blue-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                            </span>
                            <h3 class="flex items-center mb-1 text-sm font-semibold text-zinc-900 dark:text-white">System Updated <span class="bg-blue-100 text-blue-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300 ml-3">Latest</span></h3>
                            <time class="block mb-2 text-xs font-normal leading-none text-zinc-400 dark:text-zinc-500">Just now</time>
                            <p class="mb-4 text-xs font-normal text-zinc-500 dark:text-zinc-400">Admin deployed version 2.4.1 to production servers.</p>
                        </li>
                        <li class="mb-6 ml-6">
                            <span class="absolute flex items-center justify-center w-6 h-6 bg-green-100 rounded-full -left-3 ring-8 ring-white dark:ring-zinc-800 dark:bg-green-900">
                                <svg class="w-3 h-3 text-green-800 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </span>
                            <h3 class="mb-1 text-sm font-semibold text-zinc-900 dark:text-white">3 New Users Registered</h3>
                            <time class="block mb-2 text-xs font-normal leading-none text-zinc-400 dark:text-zinc-500">2 hours ago</time>
                            <p class="text-xs font-normal text-zinc-500 dark:text-zinc-400">Sarah, John, and Mike joined the platform.</p>
                        </li>
                        <li class="ml-6">
                            <span class="absolute flex items-center justify-center w-6 h-6 bg-orange-100 rounded-full -left-3 ring-8 ring-white dark:ring-zinc-800 dark:bg-orange-900">
                                <svg class="w-3 h-3 text-orange-800 dark:text-orange-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </span>
                            <h3 class="mb-1 text-sm font-semibold text-zinc-900 dark:text-white">Big Order Received</h3>
                            <time class="block mb-2 text-xs font-normal leading-none text-zinc-400 dark:text-zinc-500">5 hours ago</time>
                            <p class="text-xs font-normal text-zinc-500 dark:text-zinc-400">Order #29381 processed for $2,490.00.</p>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Function to get current theme colors
        const isDark = document.documentElement.classList.contains('dark');
        const textColor = isDark ? '#9ca3af' : '#6b7280'; // gray-400 vs gray-500
        const gridColor = isDark ? '#374151' : '#e5e7eb'; // gray-700 vs gray-200

        const options = {
            series: [{
                name: 'Revenue',
                data: [31, 40, 28, 51, 42, 109, 100]
            }],
            chart: {
                height: 300,
                type: 'area',
                fontFamily: 'Inter, sans-serif',
                toolbar: {
                    show: false
                },
                background: 'transparent'
            },
            theme: {
                mode: isDark ? 'dark' : 'light',
            },
            colors: ['#3b82f6'], // blue-500
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 2
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.1,
                    stops: [0, 90, 100]
                }
            },
            xaxis: {
                categories: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                },
                labels: {
                    style: {
                        colors: textColor
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: textColor
                    },
                    formatter: function (value) {
                        return "$" + value;
                    }
                }
            },
            grid: {
                borderColor: gridColor,
                strokeDashArray: 4,
                yaxis: {
                    lines: {
                        show: true
                    }
                }
            }
        };

        const chart = new ApexCharts(document.querySelector("#revenueChart"), options);
        chart.render();

        // Traffic Sources Donut Chart
        const trafficOptions = {
            series: [44, 55, 13],
            labels: ['Organic', 'Direct', 'Referral'],
            chart: {
                type: 'donut',
                height: 280,
                fontFamily: 'Inter, sans-serif',
                background: 'transparent'
            },
            theme: {
                mode: isDark ? 'dark' : 'light',
            },
            colors: ['#3b82f6', '#8b5cf6', '#10b981'], // blue, purple, green
            plotOptions: {
                pie: {
                    donut: {
                        size: '70%',
                        labels: {
                            show: true,
                            name: {
                                color: textColor
                            },
                            value: {
                                color: isDark ? '#fff' : '#111827',
                                fontSize: '24px',
                                fontWeight: 700
                            },
                            total: {
                                show: true,
                                color: textColor,
                                label: 'Total Visits'
                            }
                        }
                    }
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: false
            },
            legend: {
                position: 'bottom',
                labels: {
                    colors: textColor
                }
            }
        };

        const trafficChart = new ApexCharts(document.querySelector("#trafficChart"), trafficOptions);
        trafficChart.render();

        // Bar Chart (Sales by Category)
        const barOptions = {
            series: [{
                name: 'Electronics',
                data: [44, 55, 41, 67, 22, 43, 21]
            }, {
                name: 'Apparel',
                data: [13, 23, 20, 8, 13, 27, 33]
            }, {
                name: 'Home',
                data: [11, 17, 15, 15, 21, 14, 15]
            }],
            chart: {
                type: 'bar',
                height: 300,
                stacked: true,
                fontFamily: 'Inter, sans-serif',
                toolbar: { show: false },
                background: 'transparent'
            },
            theme: {
                mode: isDark ? 'dark' : 'light',
            },
            colors: ['#3b82f6', '#10b981', '#f59e0b'],
            plotOptions: {
                bar: {
                    horizontal: false,
                    borderRadius: 4,
                    columnWidth: '40%',
                },
            },
            dataLabels: {
                enabled: false
            },
            xaxis: {
                categories: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: {
                    style: { colors: textColor }
                }
            },
            yaxis: {
                labels: {
                    style: { colors: textColor }
                }
            },
            grid: {
                borderColor: gridColor,
                strokeDashArray: 4,
            },
            legend: {
                position: 'top',
                horizontalAlign: 'right',
                labels: {
                    colors: textColor
                }
            }
        };

        const barChart = new ApexCharts(document.querySelector("#barChart"), barOptions);
        barChart.render();

        // Listen for theme toggle to update chart dynamically
        const themeToggle = document.getElementById('themeToggle');
        if (themeToggle) {
            themeToggle.addEventListener('click', function() {
                // Short delay to allow HTML class to update first
                setTimeout(() => {
                    const currentlyDark = document.documentElement.classList.contains('dark');
                    const newTextColor = currentlyDark ? '#9ca3af' : '#6b7280';
                    const newGridColor = currentlyDark ? '#374151' : '#e5e7eb';
                    
                    chart.updateOptions({
                        theme: { mode: currentlyDark ? 'dark' : 'light' },
                        xaxis: { labels: { style: { colors: newTextColor } } },
                        yaxis: { labels: { style: { colors: newTextColor } } },
                        grid: { borderColor: newGridColor }
                    });

                    trafficChart.updateOptions({
                        theme: { mode: currentlyDark ? 'dark' : 'light' },
                        legend: { labels: { colors: newTextColor } },
                        plotOptions: {
                            pie: {
                                donut: {
                                    labels: {
                                        name: { color: newTextColor },
                                        value: { color: currentlyDark ? '#fff' : '#111827' },
                                        total: { color: newTextColor }
                                    }
                                }
                            }
                        }
                    });

                    barChart.updateOptions({
                        theme: { mode: currentlyDark ? 'dark' : 'light' },
                        xaxis: { labels: { style: { colors: newTextColor } } },
                        yaxis: { labels: { style: { colors: newTextColor } } },
                        grid: { borderColor: newGridColor },
                        legend: { labels: { colors: newTextColor } }
                    });
                }, 50);
            });
        }
    });
</script>
@endpush
