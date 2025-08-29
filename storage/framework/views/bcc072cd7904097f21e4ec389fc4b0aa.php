<button 
    x-data="{ show: false }" 
    x-show="show" 
    x-cloak
    x-transition 
    @scroll.window="show = window.scrollY > 200" 
    @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
    class="fixed bottom-6 right-6 bg-gradient-to-r from-blue-500 to-blue-600 text-white w-14 h-14 rounded-full shadow-2xl flex items-center justify-center hover:from-blue-600 hover:to-blue-700 transition-all duration-300 opacity-0 pointer-events-none transform hover:scale-110 z-50"
    :class="{ 'opacity-100 pointer-events-auto': show }">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
    </svg>
</button><?php /**PATH C:\Laravel\fitsphere\resources\views/partials/scroll-to-top.blade.php ENDPATH**/ ?>