@extends('layouts.about')

@section('title', 'About Us | Omega Decoration')

@section('content')
<header class="about-hero">
    <div class="hero-content">
        <h1 class="hero-title">About Us at Omega Decoration</h1>
        <p class="hero-subtitle">Bringing walls to life with creativity, quality, and passion.</p>
        <a href="#our-story" class="btn btn-primary btn-lg hero-btn"><i class="fas fa-arrow-down me-2"></i>Discover Our Story</a>
    </div>
</header>
<main>
    <div class="container mt-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">About Us</li>
            </ol>
        </nav>
    </div>

    <section id="our-story" class="section-padding about-content">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0 fade-in">
                    <h2 class="section-title">Our Story</h2>
                    <p>Omega Decoration was born in 2024 in Amman from a shared passion for interior design and a belief that walls are canvases waiting to be adorned. We noticed a gap in the market for unique, high-quality wallpapers and home decor items that were both beautiful and easy for anyone to install.</p>
                    <p>Driven by a desire to transform spaces and inspire creativity, we set out on a mission: to provide exceptional decor designs that blend timeless aesthetics with contemporary flair, crafted with care and sustainability in mind.</p>
                    <p>Today, Omega Decoration is more than just a decor company; it's a community of design lovers dedicated to helping you create spaces that truly reflect your personality and style.</p>
                </div>
                <div class="col-lg-6 fade-in">
                    <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80" 
                         alt="Omega Decoration Workspace Inspiration" class="img-fluid rounded shadow-lg">
                </div>
            </div>
        </div>
    </section>

    <section class="section-padding bg-light about-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mb-4 mb-lg-0 fade-in">
                    <h2 class="section-title">Our Mission & Values</h2>
                    <h5>Our Mission</h5>
                    <p>To empower individuals to express their unique style and transform their living spaces through exceptional, accessible, and inspiring decor designs.</p>
                    
                    <h5 class="mt-4">Our Core Values</h5>
                    <ul>
                        <li><p>Quality Craftsmanship: We are committed to using premium, eco-friendly materials and meticulous production processes.</p></li>
                        <li><p>Design Excellence: We constantly explore creativity, blending artistry with functionality in every pattern.</p></li>
                        <li><p>Customer Focus: Your satisfaction is our priority. We strive to provide outstanding service and support.</p></li>
                        <li><p>Sustainability: We make conscious choices to minimize our environmental impact, from sourcing materials to packaging.</p></li>
                        <li><p>Inspiration & Creativity: We believe in the power of design to inspire and aim to foster creativity within homes.</p></li>
                    </ul>
                </div>
                <div class="col-lg-6 d-flex align-items-center justify-content-center fade-in">
                    <img src="https://coveringscanada-media.s3.amazonaws.com/wp-content/uploads/2021/05/material-bank-product-samples-benefit-designers-manufacturers.jpg" 
                         alt="Quality Materials and Design Focus" class="img-fluid rounded shadow-lg">
                </div>
            </div>
        </div>
    </section>

    <section class="section-padding about-content">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0 fade-in">
                    <h2 class="section-title">Design Philosophy</h2>
                    <p>At Omega Decoration, design is the heartbeat of everything we do. We believe decor is more than just decoration; it's a form of self-expression, a way to set a mood, and a crucial element in creating a home that feels uniquely yours.</p>
                    <p>Our approach blends timeless design principles with contemporary trends. We draw inspiration from the rich textures of nature, the elegance of historical patterns, the boldness of modern art, and the diverse beauty found in global cultures. Each collection is thoughtfully curated to offer a range of styles, from subtle and sophisticated to vibrant and expressive.</p>
                    <p>We focus on creating designs that not only look beautiful but also feel right within a space, considering scale, color harmony, and the interplay of light. Our goal is to design products that tell a story and provide a stunning backdrop for life's moments.</p>
                </div>
                <div class="col-lg-6 order-lg-1 fade-in">
                    <img src="https://images.unsplash.com/photo-1517048676732-d65bc937f952?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80" 
                         alt="Omega Decoration Design Process Moodboard" class="img-fluid rounded shadow-lg">
                </div>
            </div>
        </div>
    </section>

    <section class="section-padding bg-white">
        <div class="container text-center">
            <h2 class="section-title d-inline-block mb-5">Meet the Team</h2>
            <p class="lead mb-5">Behind Omega Decoration is a passionate group of designers, artists, customer support specialists, and logistics experts dedicated to bringing you the best home decor experience.</p>
            
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="team-member-card fade-in">
                        <img src="https://media.licdn.com/dms/image/v2/D4D03AQGCWUAT5slEBQ/profile-displayphoto-shrink_400_400/B4DZUJCLObGcAg-/0/1739613321622?e=1750896000&v=beta&t=ikM5NyzgGWJs1XlZ1HuId6DwToylcNYTFYxnBIF7fQU" 
                             alt="Ibrahem Wael Alsaleh" class="team-member-img">
                        <h5>Ibrahem Wael Alsaleh</h5>
                        <span class="title">General Manager & Owner</span>
                        <p>With a strong entrepreneurial spirit and over 15 years of leadership experience, Ibrahim drives the company's success through strategic vision, innovation, and a deep commitment to quality and excellence.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="team-member-card fade-in">
                        <img src="https://media-hbe1-1.cdn.whatsapp.net/v/t61.24694-24/473402717_557896320564238_7543434695065504788_n.jpg?ccb=11-4&oh=01_Q5Aa1QGXF5-Iyce8QOT4-hXBlJWmdoTRnCy0spwHHK_guvMEXg&oe=68298DF8&_nc_sid=5e03e0&_nc_cat=103" 
                             alt="Ismail Al-Yamani" class="team-member-img">
                        <h5>Ismail Al-Yamani</h5>
                        <span class="title">Lead Designer</span>
                        <p>Leads with vision and expertise, shaping creative direction and ensuring exceptional design quality across all projects.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="team-member-card fade-in">
                        <img src="https://media-hbe1-1.cdn.whatsapp.net/v/t61.24694-24/491873900_661717190184494_7026306766397603006_n.jpg?ccb=11-4&oh=01_Q5Aa1gHCNMgOXZpbUixUpRbmlJMjzQkNs9iMxoV8x2mBtRVvJQ&oe=6837F3F1&_nc_sid=5e03e0&_nc_cat=110" 
                             alt="Salem Alhasanen" class="team-member-img">
                        <h5>Salem Alhasanen</h5>
                        <span class="title">Lead Designer</span>
                        <p>Leads with vision and expertise, shaping creative direction and ensuring exceptional design quality across all projects.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@push('scripts')
<script>
    // تفعيل تأثيرات الظهور عند التمرير
    document.addEventListener('DOMContentLoaded', function() {
        const fadeElements = document.querySelectorAll('.fade-in');
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = 1;
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, {
            threshold: 0.3
        });
        
        fadeElements.forEach(element => {
            element.style.opacity = 0;
            element.style.transform = 'translateY(20px)';
            element.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
            observer.observe(element);
        });
    });
</script>
@endpush