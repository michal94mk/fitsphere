<button 
    x-data="{ show: false }" 
    x-show="show" 
    x-transition 
    @scroll.window="show = window.scrollY > 200" 
    @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
    class="fixed bottom-5 right-5 bg-orange-500 text-white w-16 h-16 rounded-full shadow-lg flex items-center justify-center hover:bg-orange-600 transition-opacity duration-300 opacity-0 pointer-events-none"
    :class="{ 'opacity-100 pointer-events-auto': show }">
    ▲
</button>