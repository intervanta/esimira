<section class="w-full py-16 sm:py-20 lg:py-24 bg-[linear-gradient(180deg,#ffffff_0%,#e3e3e3_50%,#ffffff_100%)] w-full bg-[url('../assets/images/six_banner.png')] bg-cover bg-center">
  <div class="w-full max-w-[1140px] mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-12">
      <div class="flex items-center justify-center gap-2 mb-4">
        <img src="{{ asset('assets/images/img_group_5911.svg') }}" alt="testimonials icon" class="w-[14px] h-[16px]">
        <span class="text-[12px] font-medium text-[#000000] font-['Satoshi'] leading-[17px] uppercase">Testimonials</span>
      </div>
      <h2 class="text-[28px] sm:text-[32px] lg:text-[35px] font-normal text-[#101010] font-['Satoshi'] leading-[36px] sm:leading-[40px] lg:leading-[43px] capitalize text-center">
        <span>Our Happy </span><span class="font-bold text-[#f4633a]">Clients</span>
      </h2>
    </div>

    <div id="testimonialsContainer" class="flex sm:grid sm:grid-cols-3 gap-6 overflow-x-auto snap-x snap-mandatory no-scrollbar px-6 sm:px-0 scroll-smooth">
      <!-- Testimonial cards -->
      <div class="bg-[#f4f4f6] rounded-[12px] p-6 testimonial-card" id="1:857">
        <p class="text-[14px] sm:text-[16px] font-normal text-[#020203] font-['Satoshi'] leading-[24px] sm:leading-[24px] mb-4" id="1:859">"Bloomr always asked good questions for<br>our projects and helped us execute them at<br>a high level, top work!."</p>
        <div class="flex items-center gap-3">
          <img src="{{ asset('assets/images/img_670e85fe14e5634.png') }}" alt="Robert profile" class="w-[36px] h-[36px] rounded-[18px]" id="1:861">
          <div><h4 class="text-[12px] font-bold text-[#020203] font-['Satoshi'] leading-[17px]" id="1:866">Robert</h4><p class="text-[12px] font-light text-[#020203] font-['Satoshi'] leading-[17px]" id="1:868">HR Manager at etc.venues</p></div>
        </div>
      </div>

      <div class="bg-[#f1f1f1] border border-[#f4633a] rounded-[20px] p-1 testimonial-card" id="1_38_529_3995_382_214">
        <div class="bg-[#f4f4f6] rounded-[16px] p-8 shadow-[0px_0px_10px_#00000019]">
          <p class="text-[14px] sm:text-[16px] font-normal text-[#020203] font-['Satoshi'] leading-[24px] sm:leading-[24px] mb-4" id="1:871">"Bloomr always asked good questions for<br>our projects and helped us execute them at a high level, top work!."</p>
          <div class="flex items-center gap-4"><img src="{{ asset('assets/images/img_670e85fe14e5634_48x48.png') }}" alt="Alice profile" class="w-[48px] h-[48px] rounded-[24px]" id="1:873"><div><h4 class="text-[16px] font-bold text-[#020203] font-['Satoshi'] leading-[22px]" id="1:876">Alice</h4><p class="text-[16px] font-normal text-[#020203] font-['Satoshi'] leading-[22px]" id="1:878">HR Manager at etc.venues</p></div></div>
        </div>
      </div>

      <div class="bg-[#f4f4f6] rounded-[12px] p-6 testimonial-card" id="1:879">
        <p class="text-[14px] sm:text-[16px] font-normal text-[#020203] font-['Satoshi'] leading-[24px] sm:leading-[24px] mb-4" id="1:881">"Bloomr always asked good questions for<br>our projects and helped us execute them at<br>a high level, top work!."</p>
        <div class="flex items-center gap-3"><img src="{{ asset('assets/images/img_670e85fe14e5634.png') }}" alt="Alan profile" class="w-[36px] h-[36px] rounded-[18px]" id="1:883"><div><h4 class="text-[12px] font-bold text-[#020203] font-['Satoshi'] leading-[17px]" id="1:887">Alan</h4><p class="text-[12px] font-light text-[#020203] font-['Satoshi'] leading-[17px]" id="1:889">HR Manager at etc.venues</p></div></div>
      </div>
    </div>

    <div class="flex justify-center items-center gap-1 relative mt-6">
      <img src="{{ asset('assets/images/img_tab_raghav_pavaman.png') }}" alt="client profile" class="w-[32px] sm:w-[38px] h-[32px] sm:h-[38px] rounded-full avatar-selector" data-index="0" id="1:892" data-testimonial="0">
      <img src="{{ asset('assets/images/Anurag Singh.png') }}" alt="client profile" class="w-[30px] sm:w-[36px] h-[30px] sm:h-[36px] rounded-[18px] avatar-selector active" data-index="1" id="1:896" data-testimonial="1">
      <img src="{{ asset('assets/images/img_kain_kyel_seo.png') }}" alt="client profile" class="w-[32px] sm:w-[38px] h-[32px] sm:h-[38px] rounded-full avatar-selector" data-index="2" id="1:898" data-testimonial="2">
      <img src="{{ asset('assets/images/Helena Turpin.png') }}" alt="client profile" class="w-[32px] sm:w-[38px] h-[32px] sm:h-[38px] rounded-full avatar-selector" data-index="3" id="1:902" data-testimonial="3">
      <img src="{{ asset('assets/images/img_670e85fe14e5634_48x48.png') }}" alt="client profile" class="w-[32px] sm:w-[38px] h-[32px] sm:h-[38px] rounded-full avatar-selector" data-index="4" id="1:906" data-testimonial="4">

      <div class="testimonial-popup" id="testimonialPopup">
        <p class="text-sm font-medium text-[#020203] mb-2">John Doe</p>
        <p class="text-xs text-[#666] mb-3">CEO, Tech Company</p>
        <p class="text-xs text-[#020203]">"Amazing service! Highly recommended for travelers."</p>
      </div>
    </div>
  </div>
</section>