<x-blog-layout>
    <section class="bg-gray-100 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold text-center text-gray-900 mb-8">Contact Us</h1>
            <p class="text-lg text-gray-700 leading-relaxed text-center mb-6">
                We’d love to hear from you! If you have any questions, feedback, or suggestions, feel free to reach out to us. We’re always here to help you on your health and fitness journey.
            </p>
            
            <div class="flex justify-center">
                <div class="w-full max-w-md">
                    <form action="#" method="POST" class="bg-white shadow-lg rounded-lg p-8 space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Your Name</label>
                            <input type="text" name="name" id="name" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500" required>
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Your Email</label>
                            <input type="email" name="email" id="email" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500" required>
                        </div>
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700">Your Message</label>
                            <textarea name="message" id="message" rows="6" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500" required></textarea>
                        </div>
                        <div>
                            <button type="submit" class="w-full px-4 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-500">
                                Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</x-blog-layout>
