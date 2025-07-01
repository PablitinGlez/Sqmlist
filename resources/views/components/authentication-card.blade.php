
<div class="min-h-screen flex">
  
    <div class="hidden lg:flex lg:w-1/2 relative">
    
        <img 
    src="{{ asset(request()->routeIs('login') ? 'images/login-auth.webp' : 'images/register-auth.webp') }}" 
    alt="Background" 
    class="w-full h-full object-cover"
   
/>

       
        <div class="absolute inset-0 bg-black/20"></div>
        
        
       
    </div>
    
   
   
       <div class="flex-1 flex items-center justify-center p-6 bg-gray-50">
        <div class="w-full max-w-md">
          
            <div class="flex justify-center text-center mb-8">
                {{ $logo ?? '' }}
            </div>
            
       
            <div class="bg-white px-8 py-10 shadow-lg rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>