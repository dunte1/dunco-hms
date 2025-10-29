<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <i class="fa fa-envelope text-blue-600 mr-3"></i>
                    Messaging Center
                </h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Send emails and SMS to patients</p>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <a href="{{ route('hms.messaging.bulk') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6">
                        <div class="flex items-center justify-between text-white">
                            <div>
                                <h3 class="text-lg font-bold">Bulk Messaging</h3>
                                <p class="text-sm opacity-90 mt-1">Send to multiple</p>
                            </div>
                            <div class="p-3 bg-white bg-opacity-30 rounded-lg">
                                <i class="fa fa-users text-2xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 bg-gray-50 dark:bg-gray-700">
                        <p class="text-sm text-gray-600 dark:text-gray-400">Send messages to multiple recipients at once</p>
                    </div>
                </a>

                <a href="{{ route('hms.messaging.templates') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition">
                    <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-6">
                        <div class="flex items-center justify-between text-white">
                            <div>
                                <h3 class="text-lg font-bold">Templates</h3>
                                <p class="text-sm opacity-90 mt-1">Manage templates</p>
                            </div>
                            <div class="p-3 bg-white bg-opacity-30 rounded-lg">
                                <i class="fa fa-file-alt text-2xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 bg-gray-50 dark:bg-gray-700">
                        <p class="text-sm text-gray-600 dark:text-gray-400">Create and manage message templates</p>
                    </div>
                </a>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-green-500 to-green-600 p-6">
                        <div class="flex items-center justify-between text-white">
                            <div>
                                <h3 class="text-lg font-bold">Messages Sent</h3>
                                <p class="text-sm opacity-90 mt-1">This month</p>
                            </div>
                            <div class="p-3 bg-white bg-opacity-30 rounded-lg">
                                <i class="fa fa-paper-plane text-2xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 bg-gray-50 dark:bg-gray-700">
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">0</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Total messages sent</p>
                    </div>
                </div>
            </div>

            <!-- Send Single Message -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-4">
                    <h3 class="text-lg font-bold text-white">Send Single Message</h3>
                </div>
                <form method="POST" action="{{ route('hms.messaging.send') }}" class="p-6 space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Message Type <span class="text-red-500">*</span>
                            </label>
                            <select name="message_type" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Type</option>
                                <option value="email">Email</option>
                                <option value="sms">SMS</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Recipient <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="recipient" required placeholder="Enter email or phone number"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Message <span class="text-red-500">*</span>
                        </label>
                        <textarea name="message" rows="6" required
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Type your message here..."></textarea>
                    </div>

                    <div class="flex gap-4">
                        <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition">
                            <i class="fa fa-paper-plane mr-2"></i> Send Message
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

