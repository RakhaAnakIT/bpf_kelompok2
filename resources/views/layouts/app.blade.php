<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Bloom E-Commerce Template</title>
    <meta name="description"
        content="Discover a wide selection of trendy clothes, shoes and accessories on Bloom E-Commerce. Enjoy fast delivery and free returns. Shop now!" />
    <link rel="stylesheet" href="{{ asset('css/bloom.css') }}" />
</head>

<body class="inter_7b064e0d-module__MOT0tq__className antialiased flex flex-col min-h-screen">
    <header
        class="sticky top-0 z-50 transition-all duration-300 bg-white/80 backdrop-blur-md border-b border-gray-200 shadow-sm"
        id="site-header">
        <div class="container mx-auto px-4 sm:px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-8 lg:space-x-12">
                    <a class="text-2xl tracking-tight text-gray-900 hover:text-gray-700 transition-colors"
                        aria-label="BloomShop Home" href="index.html">BLOOM<span class="text-primary">SHOP</span></a>
                    <nav class="hidden md:flex items-center space-x-1" role="navigation" aria-label="Main navigation">
                        <a class="relative py-2 px-4 rounded-lg text-sm font-medium transition-all duration-200 text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                            href="contact.html">Contact</a>
                    </nav>
                </div>
                <div class="hidden lg:flex flex-1 max-w-md mx-8">
                    <form class="relative w-full">
                        <input type="search" placeholder="Search products..."
                            class="w-full pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all"
                            aria-label="Search products" value="" /><svg xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-search absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400"
                            aria-hidden="true">
                            <path d="m21 21-4.34-4.34"></path>
                            <circle cx="11" cy="11" r="8"></circle>
                        </svg>
                    </form>
                </div>
                <div class="flex items-center space-x-2 sm:space-x-4">
                    <button class="lg:hidden p-2 rounded-full hover:bg-gray-100 transition-colors" aria-label="Search"
                        id="btn-search">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-search h-5 w-5 text-gray-700" aria-hidden="true">
                            <path d="m21 21-4.34-4.34"></path>
                            <circle cx="11" cy="11" r="8"></circle>
                        </svg></button><button class="md:hidden p-2 rounded-full hover:bg-gray-100 transition-colors"
                        aria-label="Toggle navigation menu" aria-expanded="true" id="btn-menu">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-x h-6 w-6 text-gray-700" aria-hidden="true">
                            <path d="M18 6 6 18"></path>
                            <path d="m6 6 12 12"></path>
                        </svg></button><a
                        class="relative p-2 rounded-full hover:bg-gray-100 transition-all duration-200 group"
                        aria-label="Shopping cart with 0 items" href="cart.html"><svg xmlns="http://www.w3.org/2000/svg"
                            width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-shopping-cart h-6 w-6 text-gray-700 group-hover:text-gray-900 transition-colors"
                            aria-hidden="true">
                            <circle cx="8" cy="21" r="1"></circle>
                            <circle cx="19" cy="21" r="1"></circle>
                            <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12">
                            </path>
                        </svg></a>
                    <div class="hidden sm:flex items-center space-x-2">
                        <a href="index.html"><button
                                class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 hover:bg-accent hover:text-accent-foreground h-8 rounded-md px-3 text-sm">
                                Sign In
                            </button></a><a href="index.html"><button
                                class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-8 rounded-md px-3 text-sm">
                                Sign Up
                            </button></a>
                    </div>
                </div>
            </div>
            <div class="lg:hidden mt-4 animate-in slide-in-from-top duration-200 hidden" id="mobile-search">
                <form class="relative">
                    <input placeholder="Search products..."
                        class="w-full pl-10 pr-4 py-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent"
                        aria-label="Search products" type="search" value="" /><svg xmlns="http://www.w3.org/2000/svg"
                        width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-search absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400"
                        aria-hidden="true">
                        <path d="m21 21-4.34-4.34"></path>
                        <circle cx="11" cy="11" r="8"></circle>
                    </svg>
                </form>
            </div>
            <nav class="md:hidden mt-4 animate-in slide-in-from-top duration-200 hidden" role="navigation"
                aria-label="Mobile navigation" id="mobile-menu">
                <div class="flex flex-col space-y-3 pb-4 border-b border-gray-200">
                    <a class="text-sm font-medium py-2 px-3 rounded-lg transition-all text-gray-700 hover:text-gray-900 hover:bg-gray-50"
                        href="contact.html">Contact</a>
                </div>
                <div class="flex flex-col space-y-3 pt-4 sm:hidden">
                    <a class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2 w-full text-sm"
                        href="index.html">Sign In</a><a
                        class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2 w-full text-sm"
                        href="index.html">Sign Up</a>
                </div>
            </nav>
        </div>
    </header>
    <main class="flex-grow">
        @yield('content')
    </main>
    <footer class="bg-background border-t border-border">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-12 border-b border-border">
                <div class="max-w-2xl mx-auto text-center">
                    <h3 class="text-2xl font-bold text-foreground mb-4">Stay in the loop</h3>
                    <p class="text-muted-foreground mb-6">
                        Subscribe to our newsletter for exclusive offers, new arrivals, and style inspiration.
                    </p>
                    <form class="flex max-w-md mx-auto gap-2">
                        <input type="email"
                            class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm flex-1"
                            placeholder="Enter your email" required="" value="" /><button
                            class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-sm text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 shadow h-9 px-4 py-2 bg-primary text-primary-foreground hover:bg-primary/90"
                            type="submit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-arrow-right h-4 w-4" aria-hidden="true">
                                <path d="M5 12h14"></path>
                                <path d="m12 5 7 7-7 7"></path>
                            </svg><span class="sr-only">Subscribe</span>
                        </button>
                    </form>
                </div>
            </div>
            <div class="py-12">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-8">
                    <div class="lg:col-span-2">
                        <a class="text-2xl tracking-tight text-gray-900 hover:text-gray-700 transition-colors"
                            aria-label="BloomShop Home" href="index.html">BLOOM<span
                                class="text-primary">SHOP</span></a>
                        <p class="text-muted-foreground mb-6 max-w-sm">
                            Discover unique products that inspire your lifestyle. Quality craftsmanship meets modern
                            design.
                        </p>
                        <div class="space-y-3">
                            <div class="flex items-center gap-3 text-sm text-muted-foreground">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-map-pin h-4 w-4 text-primary"
                                    aria-hidden="true">
                                    <path
                                        d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0">
                                    </path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg><span>123 Fashion Street, Style City, SC 12345</span>
                            </div>
                            <div class="flex items-center gap-3 text-sm text-muted-foreground">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-phone h-4 w-4 text-primary"
                                    aria-hidden="true">
                                    <path
                                        d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384">
                                    </path>
                                </svg><span>+1 (555) 123-4567</span>
                            </div>
                            <div class="flex items-center gap-3 text-sm text-muted-foreground">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-mail h-4 w-4 text-primary"
                                    aria-hidden="true">
                                    <path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7"></path>
                                    <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                                </svg><span>hello@bloomshop.com</span>
                            </div>
                        </div>
                        <div class="flex gap-3 mt-6">
                            <a aria-label="Facebook"
                                class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 h-10 w-10 rounded-full bg-muted hover:bg-primary hover:text-primary-foreground transition-colors"
                                href="#"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-facebook h-4 w-4" aria-hidden="true">
                                    <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                                </svg></a><a aria-label="Twitter"
                                class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 h-10 w-10 rounded-full bg-muted hover:bg-primary hover:text-primary-foreground transition-colors"
                                href="#"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-twitter h-4 w-4"
                                    aria-hidden="true">
                                    <path
                                        d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z">
                                    </path>
                                </svg></a><a aria-label="Instagram"
                                class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 h-10 w-10 rounded-full bg-muted hover:bg-primary hover:text-primary-foreground transition-colors"
                                href="#"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-instagram h-4 w-4" aria-hidden="true">
                                    <rect width="20" height="20" x="2" y="2" rx="5" ry="5"></rect>
                                    <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                    <line x1="17.5" x2="17.51" y1="6.5" y2="6.5"></line>
                                </svg></a><a aria-label="GitHub"
                                class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 h-10 w-10 rounded-full bg-muted hover:bg-primary hover:text-primary-foreground transition-colors"
                                href="#"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-github h-4 w-4"
                                    aria-hidden="true">
                                    <path
                                        d="M15 22v-4a4.8 4.8 0 0 0-1-3.5c3 0 6-2 6-5.5.08-1.25-.27-2.48-1-3.5.28-1.15.28-2.35 0-3.5 0 0-1 0-3 1.5-2.64-.5-5.36-.5-8 0C6 2 5 2 5 2c-.3 1.15-.3 2.35 0 3.5A5.403 5.403 0 0 0 4 9c0 3.5 3 5.5 6 5.5-.39.49-.68 1.05-.85 1.65-.17.6-.22 1.23-.15 1.85v4">
                                    </path>
                                    <path d="M9 18c-4.51 2-5-2-7-2"></path>
                                </svg></a>
                        </div>
                    </div>
                    <div class="">
                        <h4 class="text-sm font-semibold text-foreground mb-4 uppercase tracking-wider">Shop</h4>
                        <ul class="space-y-3">
                            <li>
                                <a class="text-sm text-muted-foreground hover:text-foreground transition-colors inline-block"
                                    href="#">All Products</a>
                            </li>
                            <li>
                                <a class="text-sm text-muted-foreground hover:text-foreground transition-colors inline-block"
                                    href="#">New Arrivals</a>
                            </li>
                            <li>
                                <a class="text-sm text-muted-foreground hover:text-foreground transition-colors inline-block"
                                    href="#">Sale</a>
                            </li>
                            <li>
                                <a class="text-sm text-muted-foreground hover:text-foreground transition-colors inline-block"
                                    href="#">Featured</a>
                            </li>
                        </ul>
                    </div>
                    <div class="">
                        <h4 class="text-sm font-semibold text-foreground mb-4 uppercase tracking-wider">Customer Care
                        </h4>
                        <ul class="space-y-3">
                            <li>
                                <a class="text-sm text-muted-foreground hover:text-foreground transition-colors inline-block"
                                    href="contact.html">Contact Us</a>
                            </li>
                            <li>
                                <a class="text-sm text-muted-foreground hover:text-foreground transition-colors inline-block"
                                    href="index.html">Help Center</a>
                            </li>
                            <li>
                                <a class="text-sm text-muted-foreground hover:text-foreground transition-colors inline-block"
                                    href="index.html">Shipping Info</a>
                            </li>
                            <li>
                                <a class="text-sm text-muted-foreground hover:text-foreground transition-colors inline-block"
                                    href="index.html">Returns &amp; Exchanges</a>
                            </li>
                        </ul>
                    </div>
                    <div class="lg:col-span-1">
                        <h4 class="text-sm font-semibold text-foreground mb-4 uppercase tracking-wider">Company</h4>
                        <ul class="space-y-3">
                            <li>
                                <a class="text-sm text-muted-foreground hover:text-foreground transition-colors inline-block"
                                    href="#">About Us</a>
                            </li>
                            <li>
                                <a class="text-sm text-muted-foreground hover:text-foreground transition-colors inline-block"
                                    href="index.html">Careers</a>
                            </li>
                            <li>
                                <a class="text-sm text-muted-foreground hover:text-foreground transition-colors inline-block"
                                    href="index.html">Blog</a>
                            </li>
                            <li>
                                <a class="text-sm text-muted-foreground hover:text-foreground transition-colors inline-block"
                                    href="index.html">Press</a>
                            </li>
                        </ul>
                    </div>
                    <div class="lg:col-span-1">
                        <h4 class="text-sm font-semibold text-foreground mb-4 uppercase tracking-wider">Legal</h4>
                        <ul class="space-y-3">
                            <li>
                                <a class="text-sm text-muted-foreground hover:text-foreground transition-colors inline-block"
                                    href="index.html">Privacy Policy</a>
                            </li>
                            <li>
                                <a class="text-sm text-muted-foreground hover:text-foreground transition-colors inline-block"
                                    href="index.html">Terms &amp; Conditions</a>
                            </li>
                            <li>
                                <a class="text-sm text-muted-foreground hover:text-foreground transition-colors inline-block"
                                    href="index.html">Cookie Policy</a>
                            </li>
                            <li>
                                <a class="text-sm text-muted-foreground hover:text-foreground transition-colors inline-block"
                                    href="index.html">Accessibility</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div data-orientation="horizontal" role="none" class="shrink-0 bg-border h-[1px] w-full my-8"></div>
            <div class="py-6 flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <div class="flex items-center gap-2 text-sm text-muted-foreground">
                        <span>© 2025 BloomShop™. Made with</span><svg xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-heart h-4 w-4 text-red-500 fill-current" aria-hidden="true">
                            <path
                                d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5">
                            </path>
                        </svg><span>All Rights Reserved.</span><br />
                    </div>
                    <p class="text-sm text-muted-foreground">
                        Developed by
                        <a href="https://github.com/bloomtpl" target="_blank" rel="noopener noreferrer"
                            class="font-bold hover:text-primary transition-colors">Bloomtpl</a>
                        • Distributed by
                        <a href="https://themewagon.com" target="_blank" rel="noopener noreferrer"
                            class="font-bold hover:text-primary transition-colors">ThemeWagon</a>
                    </p>
                </div>
                <div class="flex items-center gap-6 text-sm">
                    <a class="text-muted-foreground hover:text-foreground transition-colors" href="#">Privacy</a><a
                        class="text-muted-foreground hover:text-foreground transition-colors" href="#">Terms</a><a
                        class="text-muted-foreground hover:text-foreground transition-colors" href="#">Cookies</a>
                </div>
            </div>
        </div>
    </footer>
    <script src="{{ asset('assets/js/bloom.js') }}"></script>
</body>

</html>