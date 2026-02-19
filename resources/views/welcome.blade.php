<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>AccountPilot - Browser Profile Management</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-50 dark:bg-gray-900">
        <div class="min-h-screen flex flex-col items-center justify-center px-6 py-12">
            <div class="max-w-4xl w-full text-center">
                <h1 class="text-5xl font-bold text-gray-900 dark:text-white mb-4">
                    AccountPilot
                </h1>
                <p class="text-xl text-gray-600 dark:text-gray-400 mb-8">
                    Browser Profile Management System
                </p>
                
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">
                        Professional Browser Identity Management
                    </h2>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">
                        A comprehensive CRM system designed for managing browser identities and cloud-based profiles using GoLogin Cloud Browser integration.
                    </p>
                    
                    <div class="grid md:grid-cols-3 gap-6 text-left">
                        <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Centralized Management</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Organize and control multiple browser environments in one place</p>
                        </div>
                        <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Team Collaboration</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Role-based access control for distributed team workflows</p>
                        </div>
                        <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Secure & Scalable</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Encrypted credential storage with comprehensive audit logs</p>
                        </div>
                    </div>
                </div>

                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-6 mb-8">
                    <h3 class="text-lg font-semibold text-yellow-900 dark:text-yellow-200 mb-2">
                        ⚠️ Educational Purpose Only
                    </h3>
                    <p class="text-sm text-yellow-800 dark:text-yellow-300">
                        This software is designed for educational and research purposes. Users must comply with all applicable laws and platform terms of service.
                    </p>
                </div>

                <div class="text-sm text-gray-500 dark:text-gray-500">
                    <p>© {{ date('Y') }} AccountPilot. Licensed under EPL-2.0</p>
                </div>
            </div>
        </div>
    </body>
</html>
