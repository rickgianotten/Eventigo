
<div>
    <label for="terms_accepted" class="text-light-grey flex  gap-2">
        <input type="checkbox" name="terms_accepted" id="terms_accepted" class="peer hidden">

        <div class=" w-5 h-5 border-2 border-gray-400 rounded-4xl flex items-center justify-center peer-checked:bg-orange peer-checked:border-orange transition">
            <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="100" height="100" viewBox="0 0 26 26" class="">
                <path d="M 22.566406 4.730469 L 20.773438 3.511719 C 20.277344 3.175781 19.597656 3.304688 19.265625 3.796875 L 10.476563 16.757813 L 6.4375 12.71875 C 6.015625 12.296875 5.328125 12.296875 4.90625 12.71875 L 3.371094 14.253906 C 2.949219 14.675781 2.949219 15.363281 3.371094 15.789063 L 9.582031 22 C 9.929688 22.347656 10.476563 22.613281 10.96875 22.613281 C 11.460938 22.613281 11.957031 22.304688 12.277344 21.839844 L 22.855469 6.234375 C 23.191406 5.742188 23.0625 5.066406 22.566406 4.730469 Z"></path>
            </svg>
        </div>
        <span class="text-sm">I agree to the <a href="/terms-and-conditions"class="text-orange hover:underline">terms and conditions</a> annd <a href="/privacy-policy"class="text-orange hover:underline">privacy policy</a></span>
    </label>
    <x-form.error name="terms_accepted"/>
</div>