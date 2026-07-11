<template>
  <div>
    <TheNavbar />
    <HeroSection />
    <SocialProof />
    <ComparisonTable />
    <JourneySelector />
    <BenefitsSlider />
    <BlogSection />
    <TestimonialsSection />
    <FaqSection />
    <TheFooter />
  </div>
</template>

<script setup>
import TheNavbar          from '@/components/landing/TheNavbar.vue';
import HeroSection        from '@/components/landing/HeroSection.vue';
import SocialProof        from '@/components/landing/SocialProof.vue';
import ComparisonTable    from '@/components/landing/ComparisonTable.vue';
import JourneySelector    from '@/components/landing/JourneySelector.vue';
import BenefitsSlider     from '@/components/landing/BenefitsSlider.vue';
import BlogSection        from '@/components/landing/BlogSection.vue';
import TestimonialsSection from '@/components/landing/TestimonialsSection.vue';
import FaqSection         from '@/components/landing/FaqSection.vue';
import TheFooter          from '@/components/landing/TheFooter.vue';

// Fade-in observer para elementos [data-fade]
import { onMounted } from 'vue';
onMounted(() => {
  const observer = new IntersectionObserver(
    (entries) => entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); }),
    { threshold: 0.1 }
  );
  document.querySelectorAll('[data-fade]').forEach(el => observer.observe(el));
});
</script>
