
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Caloocan City Social Services Management System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#ff6600',
                        secondary: '#ff8829',
                        accent: '#ffb366',
                        success: '#a5c90f',
                        dark: '#6f9c3d'
                    },
                    fontFamily: {
                        'sans': ['Poppins', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        /* Custom scrollbar for chat messages */
        #chatMessages::-webkit-scrollbar {
            width: 8px;
        }
        
        #chatMessages::-webkit-scrollbar-track {
            background: #f9fafb;
            border-radius: 4px;
        }
        
        #chatMessages::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 4px;
        }
        
        #chatMessages::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }
        
        /* Ensure messages are visible */
        #chatMessages .flex {
            display: flex !important;
        }
        
        #chatMessages .bg-white {
            background-color: white !important;
            border: 1px solid #e5e7eb !important;
        }
        
        /* Force initial message to stay visible */
        #initialMessage {
            display: flex !important;
            visibility: visible !important;
            opacity: 1 !important;
            margin-top: 0 !important;
            padding-top: 0 !important;
        }
        
        #initialMessage .bg-white {
            background-color: white !important;
            border: 1px solid #e5e7eb !important;
        }
        
        #initialMessage p {
            color: #374151 !important;
        }
        
        /* Ensure proper spacing between header and messages */
        #chatMessages {
            padding-top: 6rem !important;
        }
        
        /* Prevent header overlap */
        #chatWindow .bg-primary {
            position: sticky !important;
            top: 0 !important;
            z-index: 50 !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
        }
        
        /* Ensure messages are below header */
        #chatMessages {
            margin-top: 0 !important;
            position: relative !important;
            z-index: 10 !important;
        }
        
        /* Force initial message to be visible and properly positioned */
        #initialMessage {
            margin-top: 4rem !important;
            padding-top: 0 !important;
            position: relative !important;
            z-index: 10 !important;
        }
        
        /* Prevent scroll overlap */
        #chatMessages::-webkit-scrollbar {
            width: 8px;
        }
        
        #chatMessages::-webkit-scrollbar-track {
            background: #f9fafb;
            border-radius: 4px;
        }
        
        #chatMessages::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 4px;
        }
        
        #chatMessages::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }
        
        /* Ensure header stays above all content */
        #chatWindow .bg-primary {
            position: sticky !important;
            top: 0 !important;
            z-index: 100 !important;
            background-color: #ff6600 !important;
        }
        
        /* Ensure messages don't overlap header */
        #chatMessages {
            position: relative !important;
            z-index: 1 !important;
        }
    </style>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="bg-gray-50 font-sans">
    <!-- Navigation Bar -->
    <nav class="bg-primary shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo and City Name -->
                <div class="flex items-center space-x-2 sm:space-x-3">
                    <img src="caloocan-seal.png" alt="Caloocan City Seal" class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 object-contain">
                    <div>
                        <h1 class="text-sm sm:text-lg md:text-xl font-bold text-white">Caloocan City</h1>
                        <p class="text-xs sm:text-sm text-white opacity-90">Social Services</p>
                    </div>
                </div>

                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn" class="md:hidden text-white p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>

                <!-- Right Side: Navigation Links, Auth Buttons, and Time/Date -->
                <div class="hidden md:flex items-center space-x-4 lg:space-x-6">
                    <!-- Navigation Links -->
                    <div class="flex items-center space-x-4 lg:space-x-6">
                        <a href="#" class="text-white hover:text-gray-200 font-medium transition-colors duration-200">Home</a>
                        <a href="#services" class="text-white hover:text-gray-200 font-medium transition-colors duration-200">Services</a>
                    </div>

                    <!-- Authentication Buttons -->
                    <div class="flex items-center space-x-2 lg:space-x-4">
                        <!-- Always show Login and Register buttons -->
                            <a href="login.php" class="bg-white hover:bg-gray-100 text-primary px-3 lg:px-6 py-2 rounded-lg font-medium transition-colors duration-200 inline-block text-sm lg:text-base">
                                Login
                            </a>
                            <a href="register.php" class="border-2 border-white text-white hover:bg-white hover:text-primary px-3 lg:px-6 py-2 rounded-lg font-medium transition-colors duration-200 inline-block text-sm lg:text-base">
                                Register
                            </a>
                    </div>

                    <!-- Live Time and Date -->
                    <div class="hidden lg:block text-right">
                        <div id="current-time" class="text-lg font-semibold text-white"></div>
                        <div id="current-date" class="text-sm text-white opacity-90"></div>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="md:hidden hidden pb-4">
                <div class="flex flex-col space-y-4">
                    <a href="#" class="text-white hover:text-gray-200 font-medium transition-colors duration-200">Home</a>
                    <a href="#services" class="text-white hover:text-gray-200 font-medium transition-colors duration-200">Our Services</a>
                    <div class="flex flex-col space-y-2">
                        <a href="login.php" class="bg-white hover:bg-gray-100 text-primary px-4 py-2 rounded-lg font-medium transition-colors duration-200 text-center">
                            Login
                        </a>
                        <a href="register.php" class="border-2 border-white text-white hover:bg-white hover:text-primary px-4 py-2 rounded-lg font-medium transition-colors duration-200 text-center">
                            Register
                        </a>
                    </div>
                    <div class="text-center text-white">
                        <div id="mobile-time" class="text-lg font-semibold"></div>
                        <div id="mobile-date" class="text-sm opacity-90"></div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-section relative bg-cover bg-center bg-no-repeat min-h-[300px] sm:min-h-[400px] md:min-h-[500px] lg:min-h-[600px]" style="background-image: url('banner.jpg'); background-color: #ff6600;">
        <div class="absolute inset-0 bg-black bg-opacity-20"></div>
    </div>



    <!-- Services Section -->
    <section id="services" class="py-12 sm:py-16 md:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Partner Logos -->
            <div class="flex justify-center items-center space-x-8 mb-8">
                <div class="w-20 h-20 sm:w-24 sm:h-24 md:w-28 md:h-28 rounded-full overflow-hidden border-4 border-primary shadow-lg">
                    <img src="image/AM.png" alt="AM Logo" class="w-full h-full object-cover">
                </div>
                <div class="w-20 h-20 sm:w-24 sm:h-24 md:w-28 md:h-28 rounded-full overflow-hidden border-4 border-primary shadow-lg">
                    <img src="image/ccswdd.jpg" alt="AICS Logo" class="w-full h-full object-cover">
                </div>
                <div class="w-20 h-20 sm:w-24 sm:h-24 md:w-28 md:h-28 rounded-full overflow-hidden border-4 border-primary shadow-lg">
                    <img src="image/osca.jpg" alt="OSCA Logo" class="w-full h-full object-cover">
                </div>
                <div class="w-20 h-20 sm:w-24 sm:h-24 md:w-28 md:h-28 rounded-full overflow-hidden border-4 border-primary shadow-lg">
                    <img src="image/pdao.jpg" alt="PDAO Logo" class="w-full h-full object-cover">
                </div>
            </div>
            
            <h2 class="text-3xl font-bold text-gray-800 mb-6 sm:mb-8 text-center">
                Our Services
            </h2>
            <!-- Service Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <!-- AICS Card -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col h-full cursor-pointer hover:shadow-2xl hover:scale-105 hover:bg-gray-50 transition-all duration-300 ease-in-out transform" onclick="openAICSModal()">
                    <div class="bg-green-100 p-4 flex justify-center">
                    </div>
                    <div class="p-6 flex flex-col flex-grow">
                        <h3 class="text-2xl font-bold text-green-800 mb-2">AICS</h3>
                        <p class="text-lg font-semibold text-gray-700 mb-3">Assistance for Individuals In Crisis Situation</p>
                        <p class="text-sm text-gray-600 mb-4">Financial and material assistance program offered by the City Social Welfare Development Department (CSWDD) to provide support to people in crisis.</p>
                        <div class="mt-auto flex justify-end">
                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- PDAO Card -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col h-full cursor-pointer hover:shadow-2xl hover:scale-105 hover:bg-gray-50 transition-all duration-300 ease-in-out transform" onclick="openPDAOModal()">
                    <div class="bg-green-100 p-4 flex justify-center">
                    </div>
                    <div class="p-6 flex flex-col flex-grow">
                        <h3 class="text-2xl font-bold text-green-800 mb-2">PDAO</h3>
                        <p class="text-lg font-semibold text-gray-800 mb-3">Persons with Disability Affairs Office</p>
                        <p class="text-sm text-gray-600 mb-4">Specialized services and support for persons with disabilities in our community.</p>
    
                        <div class="mt-auto flex justify-end">
                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- OSCA Card -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col h-full cursor-pointer hover:shadow-2xl hover:scale-105 hover:bg-gray-50 transition-all duration-300 ease-in-out transform" onclick="openOSCAModal()">
                    <div class="bg-green-100 p-4 flex justify-center">
                    </div>
                    <div class="p-6 flex flex-col flex-grow">
                        <h3 class="text-2xl font-bold text-green-800 mb-2">OSCA</h3>
                        <p class="text-lg font-semibold text-gray-800 mb-3">Office for Senior Citizens Affairs</p>
                        <p class="text-sm text-gray-600 mb-4">Dedicated services and programs for senior citizens and elderly care.</p>
    
                        <div class="mt-auto flex justify-end">
                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Solo Parent and Child Welfare Support Card -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col h-full cursor-pointer hover:shadow-2xl hover:scale-105 hover:bg-gray-50 transition-all duration-300 ease-in-out transform" onclick="openSoloParentModal()">
                    <div class="bg-green-100 p-4 flex justify-center">
                    </div>
                    <div class="p-6 flex flex-col flex-grow">
                        <h3 class="text-2xl font-bold text-green-800 mb-2">Solo Parent and Child Welfare Support</h3>
                        <p class="text-lg font-semibold text-gray-700 mb-3">Comprehensive support for single parents and children</p>
                        <p class="text-sm text-gray-600 mb-4">Specialized assistance programs for solo parents and child welfare services.</p>
    
                        <div class="mt-auto flex justify-end">
                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Livelihood and Training Program Card -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col h-full cursor-pointer hover:shadow-2xl hover:scale-105 hover:bg-gray-50 transition-all duration-300 ease-in-out transform" onclick="openLivelihoodModal()">
                    <div class="bg-green-100 p-4 flex justify-center">
                    </div>
                    <div class="p-6 flex flex-col flex-grow">
                        <h3 class="text-2xl font-bold text-green-800 mb-2">Livelihood and Training Program</h3>
                        <p class="text-lg font-semibold text-gray-700 mb-3">Employment and skills development</p>
                        <p class="text-sm text-gray-600 mb-4">Professional development and skills training programs for sustainable employment.</p>
    
                        <div class="mt-auto flex justify-end">
                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Financial Aid Disbursement Card -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col h-full cursor-pointer hover:shadow-2xl hover:scale-105 hover:bg-gray-50 transition-all duration-300 ease-in-out transform" onclick="openFinancialAidModal()">
                    <div class="bg-green-100 p-4 flex justify-center">
                    </div>
                    <div class="p-6 flex flex-col flex-grow">
                        <h3 class="text-2xl font-bold text-green-800 mb-2">Financial Aid Disbursement</h3>
                        <p class="text-lg font-semibold text-gray-700 mb-3">Efficient financial assistance distribution</p>
                        <p class="text-sm text-gray-600 mb-4">Streamlined process for distributing financial aid to qualified beneficiaries.</p>
    
                        <div class="mt-auto flex justify-end">
                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- AICS Modal -->
    <div id="aicsModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-6xl w-full max-h-[90vh] overflow-y-auto">
                <!-- Modal Header -->
                <div class="bg-primary p-6 border-b sticky top-0 z-10">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center overflow-hidden">
                                <img src="image/ccswdd.jpg" alt="AICS Logo" class="w-12 h-12 object-cover rounded-full">
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-white">AICS</h2>
                                <p class="text-white opacity-90">Assistance for Individuals In Crisis Situation</p>
                            </div>
                        </div>
                        <button onclick="closeAICSModal()" class="text-white hover:text-gray-200 text-2xl font-bold">
                            &times;
                        </button>
                    </div>
                </div>
                
                <!-- Modal Content -->
                <div class="p-6">
                    <!-- Program Description -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                        <h3 class="text-xl font-bold text-blue-800 mb-3">Program Overview</h3>
                        <p class="text-gray-700 mb-4">
                            This is a program offered by the City Social Welfare Development Department (CSWDD) to provide financial and material assistance to people in crisis.
                        </p>
                        <h4 class="font-bold text-blue-800 mb-2">Who can avail?</h4>
                        <p class="text-gray-800 mb-3">All indigent resident in difficult and in crisis Situation</p>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                        <!-- Medical Assistance -->
                        <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
                            <div class="bg-green-100 p-4 border-b border-gray-200">
                                <h3 class="text-xl font-bold text-gray-800 text-center">Medical Assistance</h3>
                            </div>
                            <div class="p-6">
                                <h4 class="font-semibold text-gray-700 mb-3 text-lg">Requirements:</h4>
                                <p class="text-sm text-gray-600 mb-3">If you are applying for medical assistance, you will need to submit the original and a photocopy of these documents:</p>
                                <div class="space-y-2">
                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleAccordion('medical-1')">
                                            <span class="font-semibold text-gray-800">Certificate of Indigency of Solicitor</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="medical-1-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="medical-1-content">
                                            <p class="text-gray-700">You can get this from your local Barangay.</p>
                                </div>
                                    </div>
                                    
                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleAccordion('medical-2')">
                                            <span class="font-semibold text-gray-800">Medical Abstract/Certificate</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="medical-2-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="medical-2-content">
                                            <p class="text-gray-700">This should be an updated version from the Hospital/Clinic.</p>
                                </div>
                                    </div>
                                    
                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleAccordion('medical-3')">
                                            <span class="font-semibold text-gray-800">Medical Prescription</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="medical-3-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="medical-3-content">
                                            <p class="text-gray-700">This must be signed and include the Professional Tax Receipt (PTR) and license number of the attending physician. You can get this from the Hospital/Clinic.</p>
                            </div>
                        </div>

                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleAccordion('medical-4')">
                                            <span class="font-semibold text-gray-800">Laboratory Request</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="medical-4-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="medical-4-content">
                                            <p class="text-gray-700">This must also be signed and include the PTR and license number of the attending physician. You can get this from the Hospital/Clinic.</p>
                            </div>
                                </div>
                                    
                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleAccordion('medical-5')">
                                            <span class="font-semibold text-gray-800">Valid Government ID</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="medical-5-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="medical-5-content">
                                            <p class="text-gray-700">The solicitor and the patient must each have a valid government-issued ID. The ID of the patient should also include their Barangay ID.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Employment Assistance -->
                        <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
                            <div class="bg-green-100 p-4 border-b border-gray-200">
                                <h3 class="text-xl font-bold text-gray-800 text-center">Employment Assistance</h3>
                            </div>
                            <div class="p-6">
                                <h4 class="font-semibold text-gray-700 mb-3 text-lg">Requirements:</h4>
                                <p class="text-sm text-gray-600 mb-3">If you are applying for employment assistance, you will need to submit the following documents:</p>
                                <div class="space-y-2">
                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleAccordion('employment-1')">
                                            <span class="font-semibold text-gray-800">Certificate of Indigency of Solicitor</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="employment-1-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="employment-1-content">
                                            <p class="text-gray-700">You can get this from your local Barangay.</p>
                                </div>
                                    </div>
                                    
                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleAccordion('employment-2')">
                                            <span class="font-semibold text-gray-800">List of Employment Requirements</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="employment-2-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="employment-2-content">
                                            <p class="text-gray-700">This should include the corresponding amounts for documents such as your National Bureau of Investigation (NBI) clearance, Police Clearance, Drug Test, and PSA birth certificate.</p>
                                </div>
                                    </div>
                                    
                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleAccordion('employment-3')">
                                            <span class="font-semibold text-gray-800">Request Slip</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="employment-3-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="employment-3-content">
                                            <p class="text-gray-700">This is a request slip from the agency where you are applying for a job.</p>
                            </div>
                        </div>

                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleAccordion('employment-4')">
                                            <span class="font-semibold text-gray-800">Valid Government ID</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="employment-4-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="employment-4-content">
                                            <p class="text-gray-700">The solicitor must have a valid government-issued ID that also includes their Barangay ID.</p>
                            </div>
                                </div>
                                </div>
                            </div>
                        </div>

                        <!-- Balik Probinsya Program -->
                        <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
                            <div class="bg-green-100 p-4 border-b border-gray-200">
                                <h3 class="text-xl font-bold text-gray-800 text-center">Balik Probinsya Program</h3>
                            </div>
                            <div class="p-6">
                                <h4 class="font-semibold text-gray-700 mb-3 text-lg">Requirements:</h4>
                                <p class="text-sm text-gray-600 mb-3">If you are applying for assistance under the Balik Probinsya Program, you need to provide the following documents:</p>
                                <div class="space-y-2">
                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleAccordion('balik-1')">
                                            <span class="font-semibold text-gray-800">Certificate of Indigency of Solicitor</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="balik-1-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="balik-1-content">
                                            <p class="text-gray-700">Obtain this from your local Barangay.</p>
                                        </div>
                                    </div>
                                    
                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleAccordion('balik-2')">
                                            <span class="font-semibold text-gray-800">Certification from the Bureau of Fire Protection</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="balik-2-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="balik-2-content">
                                            <p class="text-gray-700">This is specifically for fire victims and can be secured from the Caloocan Bureau of Fire.</p>
                                        </div>
                                    </div>
                                    
                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleAccordion('balik-3')">
                                            <span class="font-semibold text-gray-800">Police Blotter</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="balik-3-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="balik-3-content">
                                            <p class="text-gray-700">For victims of pickpocketing, you can get this from the Caloocan PNP Station.</p>
                                        </div>
                                    </div>
                                    
                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleAccordion('balik-4')">
                                            <span class="font-semibold text-gray-800">Certification from the Bus Company</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="balik-4-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="balik-4-content">
                                            <p class="text-gray-700">This certification should state the transportation fare with a discount. You can get this directly from the Bus Company.</p>
                                        </div>
                                    </div>
                                    
                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleAccordion('balik-5')">
                                            <span class="font-semibold text-gray-800">Valid Government ID of the Solicitor</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="balik-5-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="balik-5-content">
                                            <p class="text-gray-700">This ID must be government-issued and include the Barangay ID.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Burial Assistance -->
                        <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
                            <div class="bg-green-100 p-4 border-b border-gray-200">
                                <h3 class="text-xl font-bold text-gray-800 text-center">Burial Assistance</h3>
                            </div>
                            <div class="p-6">
                                <h4 class="font-semibold text-gray-700 mb-3 text-lg">Requirements for Burial Assistance</h4>
                                <p class="text-sm text-gray-600 mb-3">To apply for burial assistance, you'll need the following documents:</p>
                                <div class="space-y-2">
                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleAccordion('burial-1')">
                                            <span class="font-semibold text-gray-800">Death Certificate with Registry Number</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="burial-1-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="burial-1-content">
                                            <p class="text-gray-700">You can get this from the Civil Registry Department.</p>
                                        </div>
                                    </div>
                                    
                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleAccordion('burial-2')">
                                            <span class="font-semibold text-gray-800">Barangay Indigency Certificate</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="burial-2-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="burial-2-content">
                                            <p class="text-gray-700">This is available at your local Barangay.</p>
                                        </div>
                                    </div>
                                    
                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleAccordion('burial-3')">
                                            <span class="font-semibold text-gray-800">Funeral Contract</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="burial-3-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="burial-3-content">
                                            <p class="text-gray-700">This document is provided by the Funeral Parlor.</p>
                                        </div>
                                    </div>
                                    
                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleAccordion('burial-4')">
                                            <span class="font-semibold text-gray-800">Valid Government-Issued ID</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="burial-4-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="burial-4-content">
                                            <p class="text-gray-700">You must have a valid ID that includes your Barangay ID. You can get this from any government agency that issues IDs.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center mt-8 pt-6 border-t border-gray-200">
                        <a href="login.php" class="bg-primary hover:bg-secondary text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200 text-center">
                            Mag-apply
                        </a>
                        <button onclick="closeAICSModal()" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200">
                            Iba pang Serbisyo
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PDAO Modal -->
    <div id="pdaoModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-6xl w-full max-h-[90vh] overflow-y-auto">
                <!-- Modal Header -->
                <div class="bg-primary p-6 border-b sticky top-0 z-10">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center overflow-hidden">
                                <img src="image/pdao.jpg" alt="PDAO Logo" class="w-12 h-12 object-cover rounded-full">
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-white">PDAO</h2>
                                <p class="text-white opacity-90">Persons with Disability Affairs Office</p>
                            </div>
                        </div>
                        <button onclick="closePDAOModal()" class="text-white hover:text-gray-200 text-2xl font-bold">
                            &times;
                        </button>
                    </div>
                </div>
                
                <!-- Modal Content -->
                <div class="p-6">
                    
                     
                     <!-- Program Overview -->
                     <div class="mb-8">
                         <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                             <h4 class="text-xl font-bold text-blue-800 mb-4">Program Overview</h4>
                             <p class="text-gray-700 mb-4">
                                 This is a program offered by the Persons with Disability Affairs Office (PDAO) to provide specialized services and support for persons with disabilities in our community.
                             </p>
                             <h4 class="font-bold text-blue-800 mb-2">Who can avail?</h4>
                        <p class="text-gray-800 mb-3">All persons with disabilities (PWDs) in Caloocan City.</p>
                         </div>
                     </div>
                     
                     <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                        <!-- Accessibility Assistance -->
                         <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
                             <div class="bg-green-100 p-4 border-b border-gray-200">
                                 <h3 class="text-xl font-bold text-gray-800 text-center">Accessibility Assistance</h3>
                            </div>
                             <div class="p-6">
                                 <h4 class="font-semibold text-gray-700 mb-3 text-lg">Services:</h4>
                                 <div class="space-y-2">
                                     <div class="accordion-item border border-gray-200 rounded-lg">
                                         <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="togglePDAOAccordion('accessibility-1')">
                                             <span class="font-semibold text-gray-800">Wheelchair and mobility aid provision</span>
                                             <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="accessibility-1-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                             </svg>
                                         </button>
                                         <div class="accordion-content hidden p-3 bg-white" id="accessibility-1-content">
                                             <p class="text-gray-700">Providing wheelchairs and other mobility assistance devices to PWDs.</p>
                                </div>
                                     </div>
                                     
                                     <div class="accordion-item border border-gray-200 rounded-lg">
                                         <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="togglePDAOAccordion('accessibility-2')">
                                             <span class="font-semibold text-gray-800">Home accessibility modifications</span>
                                             <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="accessibility-2-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                         </button>
                                         <div class="accordion-content hidden p-3 bg-white" id="accessibility-2-content">
                                             <p class="text-gray-700">Modifying homes to make them more accessible for PWDs.</p>
                                         </div>
                                     </div>
                                     
                                     <div class="accordion-item border border-gray-200 rounded-lg">
                                         <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="togglePDAOAccordion('accessibility-3')">
                                             <span class="font-semibold text-gray-800">Public facility accessibility support</span>
                                             <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="accessibility-3-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                             </svg>
                                         </button>
                                         <div class="accordion-content hidden p-3 bg-white" id="accessibility-3-content">
                                             <p class="text-gray-700">Ensuring public facilities are accessible to persons with disabilities.</p>
                                         </div>
                                     </div>
                                     
                                     <div class="accordion-item border border-gray-200 rounded-lg">
                                         <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="togglePDAOAccordion('accessibility-4')">
                                             <span class="font-semibold text-gray-800">Transportation assistance</span>
                                             <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="accessibility-4-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                             </svg>
                                         </button>
                                         <div class="accordion-content hidden p-3 bg-white" id="accessibility-4-content">
                                             <p class="text-gray-700">Providing accessible transportation options for PWDs.</p>
                                         </div>
                                     </div>
                                </div>
                            </div>
                        </div>

                        <!-- Educational Support -->
                         <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
                             <div class="bg-green-100 p-4 border-b border-gray-200">
                                 <h3 class="text-xl font-bold text-gray-800 text-center">Educational Support</h3>
                            </div>
                             <div class="p-6">
                                 <h4 class="font-semibold text-gray-700 mb-3 text-lg">Services:</h4>
                                 <div class="space-y-2">
                                     <div class="accordion-item border border-gray-200 rounded-lg">
                                         <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="togglePDAOAccordion('education-1')">
                                             <span class="font-semibold text-gray-800">Special education programs</span>
                                             <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="education-1-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                             </svg>
                                         </button>
                                         <div class="accordion-content hidden p-3 bg-white" id="education-1-content">
                                             <p class="text-gray-700">Specialized educational programs designed for persons with disabilities.</p>
                                </div>
                                     </div>
                                     
                                     <div class="accordion-item border border-gray-200 rounded-lg">
                                         <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="togglePDAOAccordion('education-2')">
                                             <span class="font-semibold text-gray-800">Learning material assistance</span>
                                             <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="education-2-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                         </button>
                                         <div class="accordion-content hidden p-3 bg-white" id="education-2-content">
                                             <p class="text-gray-700">Providing learning materials and resources adapted for PWDs.</p>
                                         </div>
                                     </div>
                                     
                                     <div class="accordion-item border border-gray-200 rounded-lg">
                                         <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="togglePDAOAccordion('education-3')">
                                             <span class="font-semibold text-gray-800">Skills training programs</span>
                                             <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="education-3-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                             </svg>
                                         </button>
                                         <div class="accordion-content hidden p-3 bg-white" id="education-3-content">
                                             <p class="text-gray-700">Vocational and skills training programs for PWDs.</p>
                                         </div>
                                     </div>
                                     
                                     <div class="accordion-item border border-gray-200 rounded-lg">
                                         <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="togglePDAOAccordion('education-4')">
                                             <span class="font-semibold text-gray-800">Career development support</span>
                                             <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="education-4-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                             </svg>
                                         </button>
                                         <div class="accordion-content hidden p-3 bg-white" id="education-4-content">
                                             <p class="text-gray-700">Supporting career development and employment opportunities for PWDs.</p>
                                         </div>
                                     </div>
                                </div>
                            </div>
                        </div>

                        <!-- Healthcare Support -->
                         <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
                             <div class="bg-green-100 p-4 border-b border-gray-200">
                                 <h3 class="text-xl font-bold text-gray-800 text-center">Healthcare Support</h3>
                            </div>
                             <div class="p-6">
                                 <h4 class="font-semibold text-gray-700 mb-3 text-lg">Services:</h4>
                                 <div class="space-y-2">
                                     <div class="accordion-item border border-gray-200 rounded-lg">
                                         <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="togglePDAOAccordion('healthcare-1')">
                                             <span class="font-semibold text-gray-800">Medical consultation assistance</span>
                                             <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="healthcare-1-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                             </svg>
                                         </button>
                                         <div class="accordion-content hidden p-3 bg-white" id="healthcare-1-content">
                                             <p class="text-gray-700">Assisting PWDs with medical consultations and healthcare access.</p>
                                </div>
                                     </div>
                                     
                                     <div class="accordion-item border border-gray-200 rounded-lg">
                                         <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="togglePDAOAccordion('healthcare-2')">
                                             <span class="font-semibold text-gray-800">Therapy and rehabilitation support</span>
                                             <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="healthcare-2-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                         </button>
                                         <div class="accordion-content hidden p-3 bg-white" id="healthcare-2-content">
                                             <p class="text-gray-700">Providing therapy and rehabilitation services for PWDs.</p>
                                </div>
                                     </div>
                                     
                                     <div class="accordion-item border border-gray-200 rounded-lg">
                                         <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="togglePDAOAccordion('healthcare-3')">
                                             <span class="font-semibold text-gray-800">Medical equipment provision</span>
                                             <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="healthcare-3-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                             </svg>
                                         </button>
                                         <div class="accordion-content hidden p-3 bg-white" id="healthcare-3-content">
                                             <p class="text-gray-700">Providing necessary medical equipment and devices for PWDs.</p>
                            </div>
                        </div>

                                     <div class="accordion-item border border-gray-200 rounded-lg">
                                         <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="togglePDAOAccordion('healthcare-4')">
                                             <span class="font-semibold text-gray-800">Health monitoring programs</span>
                                             <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="healthcare-4-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                             </svg>
                                         </button>
                                         <div class="accordion-content hidden p-3 bg-white" id="healthcare-4-content">
                                             <p class="text-gray-700">Ongoing health monitoring and support programs for PWDs.</p>
                            </div>
                                </div>
                                 </div>
                             </div>
                         </div>

                                              <!-- PWD ID Card Application -->
                         <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
                             <div class="bg-green-100 p-4 border-b border-gray-200">
                                 <h3 class="text-xl font-bold text-gray-800 text-center">PWD ID Card Application</h3>
                             </div>
                             <div class="p-6">
                                 <h4 class="font-semibold text-gray-700 mb-3 text-lg">Service:</h4>
                                 <p class="text-gray-700 mb-4">ID application for qualified PWD residents to claim benefits and privileges.</p>
                                 <h4 class="font-semibold text-gray-700 mb-3 text-lg">Requirements:</h4>
                                 <div class="space-y-2">
                                     <div class="accordion-item border border-gray-200 rounded-lg">
                                         <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="togglePDAOAccordion('pwd-1')">
                                             <span class="font-semibold text-gray-800">PRPD Form</span>
                                             <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="pwd-1-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                         </button>
                                         <div class="accordion-content hidden p-3 bg-white" id="pwd-1-content">
                                             <p class="text-gray-700">Philippine Registry Form for PWD (PRPD Form) - You can get this from the PDAO Office or your Barangay.</p>
                                         </div>
                                     </div>
                                     
                                     <div class="accordion-item border border-gray-200 rounded-lg">
                                         <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="togglePDAOAccordion('pwd-2')">
                                             <span class="font-semibold text-gray-800">Medical Certificate</span>
                                             <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="pwd-2-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                             </svg>
                                         </button>
                                         <div class="accordion-content hidden p-3 bg-white" id="pwd-2-content">
                                             <p class="text-gray-700">Original and Photocopy of Medical Certificate of Disability (Medical Assessment) from your Attending/Specialized Doctor.</p>
                                         </div>
                                     </div>
                                     
                                     <div class="accordion-item border border-gray-200 rounded-lg">
                                         <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="togglePDAOAccordion('pwd-3')">
                                             <span class="font-semibold text-gray-800">Barangay Certificate</span>
                                             <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="pwd-3-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                             </svg>
                                         </button>
                                         <div class="accordion-content hidden p-3 bg-white" id="pwd-3-content">
                                             <p class="text-gray-700">Barangay Certificate of Residency or Certificate of Indigency from your local Barangay.</p>
                                         </div>
                                     </div>
                                     
                                     <div class="accordion-item border border-gray-200 rounded-lg">
                                         <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="togglePDAOAccordion('pwd-4')">
                                             <span class="font-semibold text-gray-800">2x2 Photos</span>
                                             <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="pwd-4-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                             </svg>
                                         </button>
                                         <div class="accordion-content hidden p-3 bg-white" id="pwd-4-content">
                                             <p class="text-gray-700">Two (2) pieces latest 2x2 photo - Recent passport-sized photographs.</p>
                                         </div>
                                     </div>
                                     
                                     <div class="accordion-item border border-gray-200 rounded-lg">
                                         <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="togglePDAOAccordion('pwd-5')">
                                             <span class="font-semibold text-gray-800">Valid Government ID</span>
                                             <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="pwd-5-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                             </svg>
                                         </button>
                                         <div class="accordion-content hidden p-3 bg-white" id="pwd-5-content">
                                             <p class="text-gray-700">Valid Identification Card or PSA Birth Certificate - Government-issued ID or birth certificate from PSA.</p>
                                         </div>
                                     </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center mt-8 pt-6 border-t border-gray-200">
                        <a href="login.php" class="bg-primary hover:bg-secondary text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200 text-center">
                            Mag-apply
                        </a>
                        <button onclick="closePDAOModal()" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200">
                            Iba pang Serbisyo
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- OSCA Modal -->
    <div id="oscaModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-6xl w-full max-h-[90vh] overflow-y-auto">
                <!-- Modal Header -->
                <div class="bg-primary p-6 border-b sticky top-0 z-10">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center overflow-hidden">
                                <img src="image/osca.jpg" alt="OSCA Logo" class="w-12 h-12 object-cover rounded-full">
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-white">OSCA</h2>
                                <p class="text-white opacity-90">Office for Senior Citizens Affairs</p>
                            </div>
                        </div>
                        <button onclick="closeOSCAModal()" class="text-white hover:text-gray-200 text-2xl font-bold">
                            &times;
                        </button>
                    </div>
                </div>
                
                <!-- Modal Content -->
                <div class="p-6">
                    <!-- Program Overview -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                        <h3 class="text-xl font-bold text-blue-800 mb-4">Program Overview</h3>
                        <p class="text-gray-700 mb-3">The Office for Senior Citizens Affairs (OSCA) provides comprehensive support and services for senior citizens in Caloocan City, ensuring their well-being, dignity, and active participation in community life.</p>
                        <h4 class="font-bold text-blue-800 mb-2">Who can avail?</h4>
                        <p class="text-gray-700">All senior citizens aged 60 years and above who are residents of Caloocan City.</p>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                        <!-- Healthcare Assistance -->
                        <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
                            <div class="bg-green-100 p-4 border-b border-gray-200">
                                <h3 class="text-xl font-bold text-gray-800 text-center">Healthcare Assistance</h3>
                            </div>
                            <div class="p-6">
                                <h4 class="font-semibold text-gray-700 mb-3 text-lg">Services:</h4>
                                <div class="space-y-2">
                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleOSCAAccordion('healthcare-1')">
                                            <span class="font-semibold text-gray-800">Medical consultation and check-ups</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="healthcare-1-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="healthcare-1-content">
                                            <p class="text-gray-700">Regular medical consultations and health check-ups for senior citizens.</p>
                                </div>
                                    </div>
                                    
                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleOSCAAccordion('healthcare-2')">
                                            <span class="font-semibold text-gray-800">Medicine assistance</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="healthcare-2-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="healthcare-2-content">
                                            <p class="text-gray-700">Assistance in providing necessary medicines and medical supplies.</p>
                                </div>
                                    </div>
                                    
                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleOSCAAccordion('healthcare-3')">
                                            <span class="font-semibold text-gray-800">Hospitalization support</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="healthcare-3-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="healthcare-3-content">
                                            <p class="text-gray-700">Support and assistance during hospitalization and medical procedures.</p>
                            </div>
                        </div>

                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleOSCAAccordion('healthcare-4')">
                                            <span class="font-semibold text-gray-800">Health monitoring programs</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="healthcare-4-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="healthcare-4-content">
                                            <p class="text-gray-700">Regular health monitoring and preventive care programs.</p>
                            </div>
                                </div>
                                </div>
                            </div>
                        </div>

                        <!-- Elderly Care Support -->
                        <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
                            <div class="bg-green-100 p-4 border-b border-gray-200">
                                <h3 class="text-xl font-bold text-gray-800 text-center">Elderly Care Support</h3>
                            </div>
                            <div class="p-6">
                                <h4 class="font-semibold text-gray-700 mb-3 text-lg">Services:</h4>
                                <div class="space-y-2">
                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleOSCAAccordion('elderly-1')">
                                            <span class="font-semibold text-gray-800">Home care assistance</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="elderly-1-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="elderly-1-content">
                                            <p class="text-gray-700">In-home care and assistance services for senior citizens.</p>
                                        </div>
                                    </div>
                                    
                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleOSCAAccordion('elderly-2')">
                                            <span class="font-semibold text-gray-800">Caregiver support programs</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="elderly-2-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="elderly-2-content">
                                            <p class="text-gray-700">Support and training programs for caregivers of senior citizens.</p>
                                        </div>
                                    </div>
                                    
                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleOSCAAccordion('elderly-3')">
                                            <span class="font-semibold text-gray-800">Safety and security services</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="elderly-3-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="elderly-3-content">
                                            <p class="text-gray-700">Safety and security services for senior citizens' homes.</p>
                                        </div>
                                    </div>
                                    
                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleOSCAAccordion('elderly-4')">
                                            <span class="font-semibold text-gray-800">Emergency response support</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="elderly-4-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="elderly-4-content">
                                            <p class="text-gray-700">Emergency response and support services for senior citizens.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Financial Support -->
                        <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
                            <div class="bg-green-100 p-4 border-b border-gray-200">
                                <h3 class="text-xl font-bold text-gray-800 text-center">Financial Support</h3>
                            </div>
                            <div class="p-6">
                                <h4 class="font-semibold text-gray-700 mb-3 text-lg">Services:</h4>
                                <div class="space-y-2">
                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleOSCAAccordion('financial-1')">
                                            <span class="font-semibold text-gray-800">Pension assistance</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="financial-1-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="financial-1-content">
                                            <p class="text-gray-700">Assistance with pension applications and processing.</p>
                                </div>
                                    </div>
                                    
                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleOSCAAccordion('financial-2')">
                                            <span class="font-semibold text-gray-800">Emergency financial aid</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="financial-2-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="financial-2-content">
                                            <p class="text-gray-700">Financial assistance during emergency situations.</p>
                                </div>
                                    </div>
                                    
                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleOSCAAccordion('financial-3')">
                                            <span class="font-semibold text-gray-800">Subsidy programs</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="financial-3-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="financial-3-content">
                                            <p class="text-gray-700">Various subsidy programs for senior citizens.</p>
                            </div>
                        </div>

                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleOSCAAccordion('financial-4')">
                                            <span class="font-semibold text-gray-800">Economic support initiatives</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="financial-4-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="financial-4-content">
                                            <p class="text-gray-700">Economic support and financial literacy programs.</p>
                            </div>
                                </div>
                                </div>
                            </div>
                        </div>

                        <!-- Senior Citizen ID Card Application -->
                        <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
                            <div class="bg-green-100 p-4 border-b border-gray-200">
                                <h3 class="text-xl font-bold text-gray-800 text-center">Senior Citizen ID Card Application</h3>
                            </div>
                            <div class="p-6">
                                <h4 class="font-semibold text-gray-700 mb-3 text-lg">Service:</h4>
                                <p class="text-gray-700 mb-4">ID application for qualified senior citizens to claim benefits and privileges.</p>
                                <h4 class="font-semibold text-gray-700 mb-3 text-lg">Requirements:</h4>
                                <div class="space-y-2">
                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleOSCAAccordion('senior-1')">
                                            <span class="font-semibold text-gray-800">Age requirement</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="senior-1-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="senior-1-content">
                                            <p class="text-gray-700">Must be 60 years old and above to qualify for Senior Citizen ID.</p>
                                        </div>
                                    </div>
                                    
                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleOSCAAccordion('senior-2')">
                                            <span class="font-semibold text-gray-800">Barangay Certificate</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="senior-2-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="senior-2-content">
                                            <p class="text-gray-700">Barangay Certificate of Residency from your local Barangay.</p>
                                        </div>
                                    </div>
                                    
                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleOSCAAccordion('senior-3')">
                                            <span class="font-semibold text-gray-800">Birth Certificate</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="senior-3-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="senior-3-content">
                                            <p class="text-gray-700">PSA Birth Certificate as proof of age and identity.</p>
                                        </div>
                                    </div>
                                    
                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleOSCAAccordion('senior-4')">
                                            <span class="font-semibold text-gray-800">2x2 Photos</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="senior-4-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="senior-4-content">
                                            <p class="text-gray-700">Two (2) pieces latest 2x2 photo - Recent passport-sized photographs.</p>
                                        </div>
                                    </div>
                                    
                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleOSCAAccordion('senior-5')">
                                            <span class="font-semibold text-gray-800">Valid Government ID</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="senior-5-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="senior-5-content">
                                            <p class="text-gray-700">Any valid government-issued ID for verification purposes.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center mt-8 pt-6 border-t border-gray-200">
                        <a href="login.php" class="bg-primary hover:bg-secondary text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200 text-center">
                            Mag-apply
                        </a>
                        <button onclick="closeOSCAModal()" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200">
                            Iba pang Serbisyo
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Solo Parent and Child Welfare Support Modal -->
    <div id="soloParentModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-6xl w-full max-h-[90vh] overflow-y-auto">
                <!-- Modal Header -->
                <div class="bg-primary p-6 border-b sticky top-0 z-10">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center overflow-hidden">
                                <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-white">Solo Parent and Child Welfare Support</h2>
                                <p class="text-white opacity-90">Comprehensive support for single parents and children</p>
                            </div>
                        </div>
                        <button onclick="closeSoloParentModal()" class="text-white hover:text-gray-200 text-2xl font-bold">
                            &times;
                        </button>
                    </div>
                </div>
                
                <!-- Modal Content -->
                <div class="p-6">
                    <!-- Program Overview -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                        <h3 class="text-xl font-bold text-blue-800 mb-4">Program Overview</h3>
                        <p class="text-gray-700 mb-3">The City Social Welfare Development Department (CSWDD) provides comprehensive support and services for solo parents and child welfare, ensuring the well-being and protection of vulnerable individuals and families.</p>
                        <h4 class="font-bold text-blue-800 mb-2">Who can avail?</h4>
                        <p class="text-gray-700">Solo parents, children in need of special protection, victims of abuse, and families requiring social welfare assistance.</p>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                        <!-- Handling Special Cases -->
                        <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
                            <div class="bg-green-100 p-4 border-b border-gray-200">
                                <h3 class="text-xl font-bold text-gray-800 text-center">Handling Special Cases</h3>
                            </div>
                            <div class="p-6">
                                <h4 class="font-semibold text-gray-700 mb-3 text-lg">Case Management Process:</h4>
                                <p class="text-gray-700 mb-4">The City Social Welfare Development Department (CSWDD) handles case management for special cases including VAWC, CNSP, CICL, Drug Dependents, and abandoned elderly individuals.</p>

                                <h4 class="font-semibold text-gray-700 mb-3 text-lg">Requirements:</h4>
                                <div class="space-y-2">
                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleSoloParentAccordion('special-1')">
                                            <span class="font-semibold text-gray-800">Report</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="special-1-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="special-1-content">
                                            <p class="text-gray-700">This can be a report made via telephone, or a referral letter from your barangay, a concerned citizen, or other agencies.</p>
                                </div>
                                    </div>
                                    
                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleSoloParentAccordion('special-2')">
                                            <span class="font-semibold text-gray-800">Police Report</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="special-2-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="special-2-content">
                                            <p class="text-gray-700">Obtain this from the Caloocan PNP.</p>
                                </div>
                                    </div>
                                    
                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleSoloParentAccordion('special-3')">
                                            <span class="font-semibold text-gray-800">Barangay Blotter</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="special-3-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="special-3-content">
                                            <p class="text-gray-700">This can be secured from your local Barangay.</p>
                            </div>
                        </div>

                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleSoloParentAccordion('special-4')">
                                            <span class="font-semibold text-gray-800">Birth Certificate and other documents</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="special-4-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="special-4-content">
                                            <p class="text-gray-700">These are documents required by the institution and can be obtained from the Philippine Statistics Authority.</p>
                            </div>
                                </div>
                                    
                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleSoloParentAccordion('special-5')">
                                            <span class="font-semibold text-gray-800">Medical Certificate</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="special-5-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="special-5-content">
                                            <p class="text-gray-700">If available, this can be obtained from the Hospital.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Street Children Program -->
                        <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
                            <div class="bg-green-100 p-4 border-b border-gray-200">
                                <h3 class="text-xl font-bold text-gray-800 text-center">Street Children Program</h3>
                            </div>
                            <div class="p-6">
                                <h4 class="font-semibold text-gray-700 mb-3 text-lg">Assistance for Street Children and Families:</h4>
                                <p class="text-gray-700 mb-4">This program, run by the City Social Welfare Development Department (CSWDD), provides interventions for street children and families to help them transition off the streets.</p>
                                <h4 class="font-semibold text-gray-700 mb-3 text-lg">Requirements:</h4>
                                <div class="space-y-2">
                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleSoloParentAccordion('street-1')">
                                            <span class="font-semibold text-gray-800">Referral Letter or Phone-in Report</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="street-1-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="street-1-content">
                                            <p class="text-gray-700">This can come from your local Barangay, PNP, other referring agencies, or a concerned citizen.</p>
                                </div>
                                    </div>
                                    
                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleSoloParentAccordion('street-2')">
                                            <span class="font-semibold text-gray-800">Medical Certificate</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="street-2-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="street-2-content">
                                            <p class="text-gray-700">Obtain this from a Hospital.</p>
                                </div>
                                    </div>
                                    
                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleSoloParentAccordion('street-3')">
                                            <span class="font-semibold text-gray-800">Barangay/Police Blotter</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="street-3-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="street-3-content">
                                            <p class="text-gray-700">You can get this from your Barangay or the PNP.</p>
                            </div>
                        </div>

                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleSoloParentAccordion('street-4')">
                                            <span class="font-semibold text-gray-800">Intake Sheet/Social Case Study Report</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="street-4-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="street-4-content">
                                            <p class="text-gray-700">This document is prepared by the CSWDD, a referring agency, or the Local Government Unit (LGU).</p>
                            </div>
                                </div>
                                </div>
                            </div>
                        </div>

                        <!-- ECCD Program -->
                        <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
                            <div class="bg-green-100 p-4 border-b border-gray-200">
                                <h3 class="text-xl font-bold text-gray-800 text-center">ECCD Program</h3>
                            </div>
                            <div class="p-6">
                                <h4 class="font-semibold text-gray-700 mb-3 text-lg">Early Childhood Care and Development Program for Indigent Children:</h4>
                                <p class="text-gray-700 mb-4">The City Social Welfare Development Department (CSWDD) offers a daycare service program for children aged 3 to 4.7 years old from economically disadvantaged families.</p>
                                <div class="accordion-item border border-gray-200 rounded-lg mb-4">
                                    <button class="accordion-header w-full p-3 text-left bg-blue-50 hover:bg-blue-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleSoloParentAccordion('eccd-who-can-apply')">
                                        <h4 class="font-bold text-blue-800 text-lg">Who Can Apply?</h4>
                                        <svg class="w-5 h-5 text-blue-600 transform transition-transform duration-200" id="eccd-who-can-apply-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                    </button>
                                    <div class="accordion-content hidden p-4 bg-white" id="eccd-who-can-apply-content">
                                        <div class="space-y-3">
                                            <p class="text-gray-700">• Children from large families</p>
                                            <p class="text-gray-700">• Families where both parents are working</p>
                                            <p class="text-gray-700">• Children who are at nutritional risk</p>
                                            <p class="text-gray-700">• Families who cannot afford private daycare services</p>
                                        </div>
                                    </div>
                                </div>
                                <h4 class="font-semibold text-gray-700 mb-3 text-lg">Requirements:</h4>
                                <div class="space-y-2">
                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleSoloParentAccordion('eccd-1')">
                                            <span class="font-semibold text-gray-800">Birth Certificate / Baptismal Certificate</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="eccd-1-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="eccd-1-content">
                                            <p class="text-gray-700">You can get this from the Philippine Statistics Authority (PSA).</p>
                                        </div>
                                    </div>
                                    
                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleSoloParentAccordion('eccd-2')">
                                            <span class="font-semibold text-gray-800">Immunization Record</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="eccd-2-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="eccd-2-content">
                                            <p class="text-gray-700">Obtain this from your local Health Center.</p>
                                        </div>
                                    </div>
                                    
                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleSoloParentAccordion('eccd-3')">
                                            <span class="font-semibold text-gray-800">Barangay Indigency Certificate</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="eccd-3-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="eccd-3-content">
                                            <p class="text-gray-700">This is available at your Barangay.</p>
                                        </div>
                                    </div>
                                    
                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleSoloParentAccordion('eccd-4')">
                                            <span class="font-semibold text-gray-800">Initial ECCD Assessment</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="eccd-4-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="eccd-4-content">
                                            <p class="text-gray-700">This will be conducted by a Child Development Worker or at the Child Development Center.</p>
                                        </div>
                                    </div>
                                    
                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleSoloParentAccordion('eccd-5')">
                                            <span class="font-semibold text-gray-800">Accomplished Child's Profile Forms</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="eccd-5-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="eccd-5-content">
                                            <p class="text-gray-700">You can get these from the Child Development Worker.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Solo Parent ID Card Application -->
                        <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
                            <div class="bg-green-100 p-4 border-b border-gray-200">
                                <h3 class="text-xl font-bold text-gray-800 text-center">Solo Parent ID Card Application</h3>
                            </div>
                            <div class="p-6">
                                <h4 class="font-semibold text-gray-700 mb-3 text-lg">Assistance for Solo Parents and Their Children:</h4>
                                <p class="text-gray-700 mb-4">The City Social Welfare Development Department (CSWDD) offers assistance and issues Solo Parent Identification Cards to qualified individuals.</p>
                                <div class="accordion-item border border-gray-200 rounded-lg mb-4">
                                    <button class="accordion-header w-full p-3 text-left bg-blue-50 hover:bg-blue-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleSoloParentAccordion('who-can-apply')">
                                        <h4 class="font-bold text-blue-800 text-lg">Who Can Apply?</h4>
                                        <svg class="w-5 h-5 text-blue-600 transform transition-transform duration-200" id="who-can-apply-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>
                                    <div class="accordion-content hidden p-4 bg-white" id="who-can-apply-content">
                                        <div class="space-y-3">
                                            <p class="text-gray-700">• Solo parents with minor children</p>
                                            <p class="text-gray-700">• Dependents aged 18 to 22 who are still studying</p>
                                            <p class="text-gray-700">• Dependents over 22 who are unable to fully care for themselves due to a physical or mental disability</p>
                                        </div>
                                    </div>
                                </div>
                                <h4 class="font-semibold text-gray-700 mb-3 text-lg">Requirements:</h4>
                                <div class="space-y-2">
                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleSoloParentAccordion('solo-1')">
                                            <span class="font-semibold text-gray-800">Sworn Statement</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="solo-1-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="solo-1-content">
                                            <p class="text-gray-700">This can be secured from the CSWDD or your Barangay.</p>
                                        </div>
                                    </div>
                                    
                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleSoloParentAccordion('solo-2')">
                                            <span class="font-semibold text-gray-800">One (1) 1x1 or 2x2 picture</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="solo-2-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="solo-2-content">
                                            <p class="text-gray-700">Recent passport-sized photograph.</p>
                                        </div>
                                    </div>
                                    
                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleSoloParentAccordion('solo-3')">
                                            <span class="font-semibold text-gray-800">Income Tax Return (ITR) or Certificate of Employment</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="solo-3-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="solo-3-content">
                                            <p class="text-gray-700">You can get these from the Bureau of Internal Revenue (BIR) if you are employed.</p>
                                        </div>
                                    </div>
                                    
                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleSoloParentAccordion('solo-4')">
                                            <span class="font-semibold text-gray-800">Proof of Evidence of Being a Solo Parent</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="solo-4-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="solo-4-content">
                                            <p class="text-gray-700">If you are a widow, you must also provide a photocopy of the Death Certificate from the Civil Registry Department.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center mt-8 pt-6 border-t border-gray-200">
                        <a href="login.php" class="bg-primary hover:bg-secondary text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200 text-center">
                            Mag-apply
                        </a>
                        <button onclick="closeSoloParentModal()" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200">
                            Iba pang Serbisyo
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Livelihood and Training Program Modal -->
    <div id="livelihoodModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-6xl w-full max-h-[90vh] overflow-y-auto">
                <!-- Modal Header -->
                <div class="bg-primary p-6 border-b sticky top-0 z-10">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center overflow-hidden">
                                <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2z"/>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-white">Livelihood and Training Program</h2>
                                <p class="text-white opacity-90">Employment and skills development</p>
                            </div>
                        </div>
                        <button onclick="closeLivelihoodModal()" class="text-white hover:text-gray-200 text-2xl font-bold">
                            &times;
                        </button>
                    </div>
                </div>
                
                <!-- Modal Content -->
                <div class="p-6">
                    <!-- Program Overview -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                        <h3 class="text-xl font-bold text-blue-800 mb-4">Program Overview</h3>
                        <p class="text-gray-700 mb-3">The Livelihood and Training Program provides comprehensive support for employment and skills development, offering professional training, entrepreneurship support, and continuing education opportunities for sustainable employment.</p>
                        <h4 class="font-bold text-blue-800 mb-2">Who can avail?</h4>
                        <p class="text-gray-700">All residents of Caloocan City who are seeking employment opportunities, skills development, or entrepreneurship support.</p>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                        <!-- Skills Training -->
                        <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
                            <div class="bg-green-100 p-4 border-b border-gray-200">
                                <h3 class="text-xl font-bold text-gray-800 text-center">Skills Training</h3>
                            </div>
                            <div class="p-6">
                                <h4 class="font-semibold text-gray-700 mb-3 text-lg">Programs:</h4>
                                <div class="space-y-2">
                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleLivelihoodAccordion('skills-1')">
                                            <span class="font-semibold text-gray-800">Technical skills development</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="skills-1-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="skills-1-content">
                                            <p class="text-gray-700">Comprehensive training in technical skills for various industries and professions.</p>
                                </div>
                                    </div>
                                    
                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleLivelihoodAccordion('skills-2')">
                                            <span class="font-semibold text-gray-800">Computer literacy training</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="skills-2-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="skills-2-content">
                                            <p class="text-gray-700">Basic to advanced computer skills training for digital literacy and workplace readiness.</p>
                                </div>
                                    </div>
                                    
                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleLivelihoodAccordion('skills-3')">
                                            <span class="font-semibold text-gray-800">Language proficiency courses</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="skills-3-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="skills-3-content">
                                            <p class="text-gray-700">Language training programs to improve communication skills and career opportunities.</p>
                            </div>
                        </div>

                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleLivelihoodAccordion('skills-4')">
                                            <span class="font-semibold text-gray-800">Industry-specific training</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="skills-4-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="skills-4-content">
                                            <p class="text-gray-700">Specialized training programs tailored to specific industry requirements and standards.</p>
                            </div>
                                </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sustainable Livelihood Program -->
                        <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
                            <div class="bg-green-100 p-4 border-b border-gray-200">
                                <h3 class="text-xl font-bold text-gray-800 text-center">Sustainable Livelihood Program</h3>
                            </div>
                            <div class="p-6">
                                <p class="text-gray-700 mb-4">The City Social Welfare Development Department (CSWDD) provides capital assistance to help indigent individuals and families expand their existing businesses.</p>
                                
                                <div class="accordion-item border border-gray-200 rounded-lg mb-4">
                                    <button class="accordion-header w-full p-3 text-left bg-blue-50 hover:bg-blue-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleLivelihoodAccordion('who-can-apply')">
                                        <h4 class="font-bold text-blue-800 text-lg">Who Can Apply?</h4>
                                        <svg class="w-5 h-5 text-blue-600 transform transition-transform duration-200" id="who-can-apply-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                    </button>
                                    <div class="accordion-content hidden p-4 bg-white" id="who-can-apply-content">
                                        <div class="space-y-3">
                                            <p class="text-gray-700">• Qualified PWDs</p>
                                            <p class="text-gray-700">• Solo Parents</p>
                                            <p class="text-gray-700">• Indigent individuals</p>
                                            <p class="text-gray-700">• Individuals referred by other government agencies</p>
                                </div>
                            </div>
                        </div>

                                <h4 class="font-semibold text-gray-700 mb-3 text-lg">Requirements:</h4>
                                <div class="space-y-2">
                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleLivelihoodAccordion('req-1')">
                                            <span class="font-semibold text-gray-800">Barangay Indigency Certificate for Livelihood</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="req-1-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="req-1-content">
                                            <p class="text-gray-700">Get this from your local Barangay.</p>
                            </div>
                                </div>
                                    
                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleLivelihoodAccordion('req-2')">
                                            <span class="font-semibold text-gray-800">Voter's ID or Certification from COMELEC</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="req-2-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="req-2-content">
                                            <p class="text-gray-700">This can be a Voter's Registration Record.</p>
                                </div>
                                    </div>
                                    
                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleLivelihoodAccordion('req-3')">
                                            <span class="font-semibold text-gray-800">Valid Government-Issued ID</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="req-3-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="req-3-content">
                                            <p class="text-gray-700">This ID must include your Barangay ID.</p>
                            </div>
                        </div>

                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleLivelihoodAccordion('req-4')">
                                            <span class="font-semibold text-gray-800">Project Proposal of your small business</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="req-4-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="req-4-content">
                                            <p class="text-gray-700">Detailed proposal outlining your business plan and how the capital assistance will be used.</p>
                            </div>
                                </div>
                                </div>
                            </div>
                        </div>

                        <!-- Entrepreneurship Support -->
                        <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
                            <div class="bg-green-100 p-4 border-b border-gray-200">
                                <h3 class="text-xl font-bold text-gray-800 text-center">Entrepreneurship Support</h3>
                            </div>
                            <div class="p-6">
                                <h4 class="font-semibold text-gray-700 mb-3 text-lg">Services:</h4>
                                <div class="space-y-2">
                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleLivelihoodAccordion('entrepreneur-1')">
                                            <span class="font-semibold text-gray-800">Business planning assistance</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="entrepreneur-1-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="entrepreneur-1-content">
                                            <p class="text-gray-700">Comprehensive support in developing business plans and strategies for startup success.</p>
                                </div>
                            </div>
                                    
                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleLivelihoodAccordion('entrepreneur-2')">
                                            <span class="font-semibold text-gray-800">Financial management training</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="entrepreneur-2-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="entrepreneur-2-content">
                                            <p class="text-gray-700">Training programs focused on financial planning, budgeting, and business financial management.</p>
                        </div>
                                    </div>
                                    
                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleLivelihoodAccordion('entrepreneur-3')">
                                            <span class="font-semibold text-gray-800">Marketing strategies</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="entrepreneur-3-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="entrepreneur-3-content">
                                            <p class="text-gray-700">Strategic marketing guidance and training for business growth and customer acquisition.</p>
                                        </div>
                                    </div>
                                    
                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleLivelihoodAccordion('entrepreneur-4')">
                                            <span class="font-semibold text-gray-800">Startup funding support</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="entrepreneur-4-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="entrepreneur-4-content">
                                            <p class="text-gray-700">Assistance in securing funding and financial resources for business startup and expansion.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center mt-8 pt-6 border-t border-gray-200">
                        <a href="login.php" class="bg-primary hover:bg-secondary text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200 text-center">
                            Mag-apply
                        </a>
                        <button onclick="closeLivelihoodModal()" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200">
                            Iba pang Serbisyo
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chatbot -->
    <div id="chatbot" class="fixed bottom-6 right-6 z-50">
        <!-- Chat Button -->
        <button id="chatButton" onclick="toggleChat()" class="bg-primary hover:bg-secondary text-white rounded-full p-5 shadow-2xl transition-all duration-300 hover:scale-110 hover:shadow-3xl border-4 border-white">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
            </svg>
        </button>

        <!-- Chat Window -->
        <div id="chatWindow" class="hidden bg-white rounded-3xl shadow-2xl border border-gray-200 w-96 h-[500px] mb-4 overflow-hidden">
            <!-- Chat Header -->
            <div class="bg-primary p-4 border-b border-gray-200 sticky top-0 z-10">
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center overflow-hidden border border-white border-opacity-30">
                            <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center shadow-lg">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-white drop-shadow-lg">Serbisyo-Kbot</h2>
                            <p class="text-white text-opacity-90 text-xs font-medium">Professional assistance for social welfare services</p>
                        </div>
                    </div>
                    <button onclick="toggleChat()" class="text-white hover:text-white hover:opacity-80 text-2xl font-bold transition-colors duration-200 hover:scale-110">
                        &times;
                    </button>
                </div>
            </div>

            <!-- Chat Messages -->
            <div id="chatMessages" class="pt-24 pb-6 px-6 h-96 overflow-y-auto bg-gray-50" style="scrollbar-width: thin; scrollbar-color: #d1d5db #f9fafb;">
                <div id="initialMessage" class="flex items-start space-x-3 mb-6" style="display: flex !important; visibility: visible !important; opacity: 1 !important; position: relative !important; z-index: 10 !important; margin-top: 4rem !important;">
                    <div class="w-8 h-8 bg-primary rounded-full flex items-center justify-center flex-shrink-0 shadow-lg" style="display: flex !important; align-items: center !important; justify-content: center !important; visibility: visible !important;">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: block !important; margin: auto !important;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <div class="bg-white rounded-2xl p-4 shadow-lg max-w-sm border border-gray-100" style="background-color: white !important; border: 1px solid #e5e7eb !important; display: block !important; visibility: visible !important; opacity: 1 !important;">
                        <p class="text-sm text-gray-700 leading-relaxed font-medium" style="color: #374151 !important; display: block !important; visibility: visible !important;">Hello, Batang Kankaloo! I'm here to help you with CSWDD services. How can I assist you today?</p>
                    </div>
                </div>
            </div>

            <!-- Language Toggle -->
            <div class="px-4 py-3 bg-gray-100 border-t border-gray-200">
                <div class="flex space-x-2">
                    <button id="langEN" onclick="setLanguage('en')" class="px-3 py-1.5 text-xs rounded-lg bg-primary text-white font-medium shadow-md hover:shadow-lg transition-all duration-200 hover:scale-105">English</button>
                    <button id="langTL" onclick="setLanguage('tl')" class="px-3 py-1.5 text-xs rounded-lg bg-gray-200 text-gray-700 font-medium shadow-sm hover:shadow-md transition-all duration-200 hover:scale-105 hover:bg-gray-300">Tagalog</button>
                </div>
            </div>

            <!-- Chat Input -->
            <div class="p-6 border-t-2 border-gray-300 bg-gray-50">
                <div class="flex space-x-3 items-center">
                    <input type="text" id="chatInput" placeholder="Type your message here..." class="flex-1 px-4 py-3 border-2 border-gray-400 rounded-2xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200 text-sm bg-white shadow-inner" style="min-height: 48px;">
                    <button onclick="sendMessage()" class="bg-primary hover:bg-secondary text-white px-5 py-3 rounded-2xl transition-all duration-200 shadow-lg hover:shadow-xl hover:scale-105 min-w-[60px]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Financial Aid Disbursement Modal -->
    <div id="financialAidModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-6xl w-full max-h-[90vh] overflow-y-auto">
                <!-- Modal Header -->
                <div class="bg-primary p-6 border-b sticky top-0 z-10">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center overflow-hidden">
                                <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-white">Financial Aid Disbursement</h2>
                                <p class="text-white opacity-90">Efficient financial assistance distribution</p>
                            </div>
                        </div>
                        <button onclick="closeFinancialAidModal()" class="text-white hover:text-gray-200 text-2xl font-bold">
                            &times;
                        </button>
                    </div>
                </div>
                
                <!-- Modal Content -->
                <div class="p-6">
                    <p class="text-center text-gray-600 mb-8 max-w-4xl mx-auto">
                        Streamlined process for distributing financial aid to qualified beneficiaries, ensuring quick processing, secure disbursement, and transparent tracking.
                    </p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <!-- Quick Processing -->
                        <div class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col h-full">
                            <div class="bg-green-100 p-4 flex justify-center">
                            </div>
                            <div class="p-6 flex flex-col flex-grow">
                                <h3 class="text-xl font-bold text-gray-800 mb-2">Quick Processing</h3>
                                <div class="flex-grow">
                                    <h4 class="font-semibold text-gray-700 mb-2 text-sm">Features:</h4>
                                    <ul class="text-xs text-gray-600 space-y-1">
                                        <li>• Expedited application review</li>
                                        <li>• Automated verification systems</li>
                                        <li>• Priority processing for urgent cases</li>
                                        <li>• Real-time status updates</li>
                                    </ul>
                                </div>
                                <div class="mt-auto flex justify-end">
                                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Secure Disbursement -->
                        <div class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col h-full">
                            <div class="bg-green-100 p-4 flex justify-center">
                            </div>
                            <div class="p-6 flex flex-col flex-grow">
                                <h3 class="text-lg font-bold text-gray-800 mb-2">Secure Disbursement</h3>
                                <div class="flex-grow">
                                    <h4 class="font-semibold text-gray-700 mb-2 text-sm">Security Measures:</h4>
                                    <ul class="text-xs text-gray-600 space-y-1">
                                        <li>• Multi-factor authentication</li>
                                        <li>• Encrypted transactions</li>
                                        <li>• Secure payment methods</li>
                                        <li>• Fraud prevention systems</li>
                                    </ul>
                                </div>
                                <div class="mt-auto flex justify-end">
                                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Transparent Tracking -->
                        <div class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col h-full">
                            <div class="bg-green-100 p-4 flex justify-center">
                            </div>
                            <div class="p-6 flex flex-col flex-grow">
                                <h3 class="text-lg font-bold text-gray-800 mb-2">Transparent Tracking</h3>
                                <div class="flex-grow">
                                    <h4 class="font-semibold text-gray-700 mb-2 text-sm">Tracking Features:</h4>
                                    <ul class="text-xs text-gray-600 space-y-1">
                                        <li>• Application status tracking</li>
                                        <li>• Payment confirmation notifications</li>
                                        <li>• Transaction history access</li>
                                        <li>• Audit trail maintenance</li>
                                    </ul>
                                </div>
                                <div class="mt-auto flex justify-end">
                                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Support Services -->
                        <div class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col h-full">
                            <div class="bg-green-100 p-4 flex justify-center">
                            </div>
                            <div class="p-6 flex flex-col flex-grow">
                                <h3 class="text-lg font-bold text-gray-800 mb-2">Support Services</h3>
                                <div class="flex-grow">
                                    <h4 class="font-semibold text-gray-700 mb-2 text-sm">Services:</h4>
                                    <ul class="text-xs text-gray-600 space-y-1">
                                        <li>• 24/7 helpline support</li>
                                        <li>• Online assistance portal</li>
                                        <li>• In-person consultation</li>
                                        <li>• Documentation assistance</li>
                                    </ul>
                                </div>
                                <div class="mt-auto flex justify-end">
                                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center mt-8 pt-6 border-t border-gray-200">
                        <a href="login.php" class="bg-primary hover:bg-secondary text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200 text-center">
                            Mag-apply
                        </a>
                        <button onclick="closeFinancialAidModal()" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200">
                            Iba pang Serbisyo
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">


            <div class="text-center">
                <h3 class="text-lg font-bold text-white mb-4">Contact Us</h3>
                <div class="flex items-center justify-center space-x-8 mb-6">
                    <div class="flex items-center"><span class="text-gray-400 mr-2">📍</span>Caloocan City Hall, 123 Main St.</div>
                    <div class="flex items-center"><span class="text-gray-400 mr-2">📞</span>Tel: (02) 123-4567</div>
                    <li class="flex items-center justify-center"><span class="text-gray-400 mr-3">��</span>Email: info@caloocancity.gov.ph</li>
            </div>
            </div>

            <!-- Language Toggle -->
            <div class="flex justify-center items-center space-x-3 mb-6">
                <span class="text-white text-xs font-medium">Language:</span>
                <button id="lang-en" class="px-3 py-1.5 bg-primary hover:bg-orange-600 text-white rounded-md text-sm font-medium transition-colors duration-200 active">English</button>
                <button id="lang-fil" class="px-3 py-1.5 bg-gray-600 hover:bg-gray-700 text-white rounded-md text-sm font-medium transition-colors duration-200">Filipino</button>
            </div>
        </div>
                <div class="text-center text-gray-500 text-sm">
            &copy; <?php echo date('Y'); ?> Caloocan City Social Services Management System. All rights reserved.
        </div>
    </footer>

    <script>
        // JavaScript for mobile menu
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        const navLinks = document.querySelectorAll('.hidden.md\\:flex.items-center.space-x-4.lg\\:space-x-6 a');

        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        // Close mobile menu when a link is clicked
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.add('hidden');
            });
        });

        // Update current time and date
        function updateDateTime() {
            const now = new Date();
            const timeElement = document.getElementById('current-time');
            const dateElement = document.getElementById('current-date');

            const options = {
                hour: 'numeric',
                minute: 'numeric',
                hour12: true
            };
            timeElement.textContent = now.toLocaleTimeString('en-US', options);

            const dateOptions = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            dateElement.textContent = now.toLocaleDateString('en-US', dateOptions);
        }

        // Modal functions
        function openAICSModal() {
            document.getElementById('aicsModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeAICSModal() {
            document.getElementById('aicsModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function openPDAOModal() {
            document.getElementById('pdaoModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closePDAOModal() {
            document.getElementById('pdaoModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function openOSCAModal() {
            document.getElementById('oscaModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeOSCAModal() {
            document.getElementById('oscaModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function openSoloParentModal() {
            document.getElementById('soloParentModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeSoloParentModal() {
            document.getElementById('soloParentModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function openLivelihoodModal() {
            document.getElementById('livelihoodModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeLivelihoodModal() {
            document.getElementById('livelihoodModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Language toggle functionality
        const langEnBtn = document.getElementById('lang-en');
        const langFilBtn = document.getElementById('lang-fil');
        
        // Language content objects
        const content = {
            en: {
                // Navigation
                'nav-home': 'Home',
                'nav-services': 'Services',
                'nav-login': 'Login',
                'nav-register': 'Register',
                
                // Hero section
                'hero-title': 'Caloocan City Social Services Management System',
                'hero-subtitle': 'Providing essential social services to the residents of Caloocan City',
                
                // Services section
                'services-title': 'Our Services',
                'services-subtitle': 'Browse and apply for available social services in Caloocan City',
                
                // Service names
                'aics-title': 'AICS',
                'aics-subtitle': 'Assistance for Individuals In Crisis Situation',
                'aics-description': 'Financial and material assistance program offered by the City Social Welfare Development Department (CSWDD) to provide support to people in crisis.',
                'pdao-title': 'PDAO',
                'pdao-subtitle': 'Persons with Disability Affairs Office',
                'pdao-description': 'Specialized services and support for persons with disabilities in our community.',
                'osca-title': 'OSCA',
                'osca-subtitle': 'Office for Senior Citizens Affairs',
                'osca-description': 'Dedicated services and programs for senior citizens and elderly care.',
                'solo-parent-title': 'Solo Parent and Child Welfare Support',
                'solo-parent-subtitle': 'Comprehensive support for single parents and children',
                'solo-parent-description': 'Specialized assistance programs for solo parents and child welfare services.',
                'livelihood-title': 'Livelihood and Training Program',
                'livelihood-subtitle': 'Employment and skills development',
                'livelihood-description': 'Professional development and skills training programs for sustainable employment.',
                'financial-aid-title': 'Financial Aid Disbursement',
                'financial-aid-subtitle': 'Efficient financial assistance distribution',
                'financial-aid-description': 'Streamlined process for distributing financial aid to qualified beneficiaries.',
                
                // Footer
                'footer-contact': 'Contact Us',
                'footer-copyright': 'Caloocan City Social Services Management System. All rights reserved.',
                'footer-language': 'English'
            },
            fil: {
                // Navigation
                'nav-home': 'Home',
                'nav-services': 'Mga Serbisyo',
                'nav-login': 'Mag-login',
                'nav-register': 'Magparehistro',
                
                // Hero section
                'hero-title': 'Caloocan City Social Services Management System',
                'hero-subtitle': 'Nagbibigay ng mahalagang serbisyong panlipunan sa mga residente ng Caloocan City',
                
                // Services section
                'services-title': 'Mga Serbisyo',
                'services-subtitle': 'Tingnan at mag-apply para sa mga available na social services sa Caloocan City',
                
                // Service names
                'aics-title': 'AICS',
                'aics-subtitle': 'Tulong para sa mga Indibidwal sa Krisis na Sitwasyon',
                'aics-description': 'Programa ng financial at material assistance na inaalok ng City Social Welfare Development Department (CSWDD) para magbigay ng suporta sa mga taong nasa krisis.',
                'pdao-title': 'PDAO',
                'pdao-subtitle': 'Tanggapan para sa mga May Kapansanan',
                'pdao-description': 'Espesyal na serbisyo at suporta para sa mga may kapansanan sa aming komunidad.',
                'osca-title': 'OSCA',
                'osca-subtitle': 'Tanggapan para sa mga Senior Citizen',
                'osca-description': 'Dedikadong serbisyo at programa para sa mga senior citizen at elderly care.',
                'solo-parent-title': 'Suporta para sa Solo Parent at Child Welfare',
                'solo-parent-subtitle': 'Komprehensibong suporta para sa mga solo parent at kanilang mga anak',
                'solo-parent-description': 'Espesyal na programa ng tulong para sa mga solo parent at child welfare services.',
                'livelihood-title': 'Programa sa Livelihood at Training',
                'livelihood-subtitle': 'Pag-unlad ng trabaho at kasanayan',
                'livelihood-description': 'Programa ng professional development at skills training para sa sustainable employment.',
                'financial-aid-title': 'Pamamahagi ng Financial Aid',
                'financial-aid-subtitle': 'Mahusay na pamamahagi ng financial assistance',
                'financial-aid-description': 'Streamlined na proseso para sa pamamahagi ng financial aid sa mga qualified na beneficiary.',
                
                // Footer
                'footer-contact': 'Makipag-ugnayan sa Amin',
                'footer-copyright': 'Caloocan City Social Services Management System. Lahat ng karapatan ay nakalaan.',
                'footer-language': 'English'
            }
        };

        // Function to switch language
        function switchLanguage(lang) {
            // Update button states
            if (lang === 'en') {
                langEnBtn.classList.add('active', 'bg-primary');
                langEnBtn.classList.remove('bg-gray-600');
                langFilBtn.classList.remove('active', 'bg-primary');
                langFilBtn.classList.add('bg-gray-600');
            } else {
                langFilBtn.classList.add('active', 'bg-primary');
                langFilBtn.classList.remove('bg-gray-600');
                langEnBtn.classList.remove('active', 'bg-primary');
                langEnBtn.classList.add('bg-gray-600');
            }

            // Update content
            updateContent(lang);
        }

        // Function to update content
        function updateContent(lang) {
            const currentContent = content[lang];
            
            // Update navigation
            const navHome = document.querySelector('a[href="#"]');
            const navServices = document.querySelector('a[href="#services"]');
            if (navHome) navHome.textContent = currentContent['nav-home'];
            if (navServices) navServices.textContent = currentContent['nav-services'];
            
            // Update services section
            const servicesTitle = document.querySelector('#services h2');
            const servicesSubtitle = document.querySelector('#services p:last-of-type');
            if (servicesTitle) servicesTitle.textContent = currentContent['services-title'];
            if (servicesSubtitle) servicesSubtitle.textContent = currentContent['services-subtitle'];
            
            // Update service cards
            updateServiceCards(currentContent);
            
            // Update footer
            const footerContact = document.querySelector('footer h3');
            const footerLanguage = document.querySelector('footer .text-white.text-sm.font-medium');
            if (footerContact) footerContact.textContent = currentContent['footer-contact'];
            if (footerLanguage) footerLanguage.textContent = currentContent['footer-language'];
        }

        // Function to update service cards
        function updateServiceCards(currentContent) {
            // Update all service card descriptions
            const serviceCards = document.querySelectorAll('.bg-white.rounded-lg.shadow-md.overflow-hidden.flex.flex-col.h-full.cursor-pointer.hover\\:shadow-2xl.hover\\:scale-105.hover\\:bg-gray-50.transition-all.duration-300.ease-in-out.transform');
            
            serviceCards.forEach(card => {
                const title = card.querySelector('h3');
                const subtitle = card.querySelector('p.text-lg');
                const description = card.querySelector('p.text-sm.text-gray-600');
                
                if (title && subtitle && description) {
                    // Update based on service type
                    if (title.textContent.includes('AICS')) {
                        title.textContent = currentContent['aics-title'];
                        subtitle.textContent = currentContent['aics-subtitle'];
                        description.textContent = currentContent['aics-description'];
                    } else if (title.textContent.includes('PDAO')) {
                        title.textContent = currentContent['pdao-title'];
                        subtitle.textContent = currentContent['pdao-subtitle'];
                        description.textContent = currentContent['pdao-description'];
                    } else if (title.textContent.includes('OSCA')) {
                        title.textContent = currentContent['osca-title'];
                        subtitle.textContent = currentContent['osca-subtitle'];
                        description.textContent = currentContent['osca-description'];
                    } else if (title.textContent.includes('Solo Parent')) {
                        title.textContent = currentContent['solo-parent-title'];
                        subtitle.textContent = currentContent['solo-parent-subtitle'];
                        description.textContent = currentContent['solo-parent-description'];
                    } else if (title.textContent.includes('Livelihood')) {
                        title.textContent = currentContent['livelihood-title'];
                        subtitle.textContent = currentContent['livelihood-subtitle'];
                        description.textContent = currentContent['livelihood-description'];
                    } else if (title.textContent.includes('Financial Aid')) {
                        title.textContent = currentContent['financial-aid-title'];
                        subtitle.textContent = currentContent['financial-aid-subtitle'];
                        description.textContent = currentContent['financial-aid-description'];
                    }
                }
            });
        }

        // Event listeners for language buttons
        langEnBtn.addEventListener('click', () => switchLanguage('en'));
        langFilBtn.addEventListener('click', () => switchLanguage('fil'));

        // Initialize language on page load
        document.addEventListener('DOMContentLoaded', () => {
            switchLanguage('en'); // Set default language to English
        });

        function openFinancialAidModal() {
            document.getElementById('financialAidModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeFinancialAidModal() {
            document.getElementById('financialAidModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Accordion function for AICS modal
        function toggleAccordion(id) {
            const content = document.getElementById(id + '-content');
            const icon = document.getElementById(id + '-icon');
            
            if (content.classList.contains('hidden')) {
                // Show content
                content.classList.remove('hidden');
                icon.style.transform = 'rotate(180deg)';
            } else {
                // Hide content
                content.classList.add('hidden');
                icon.style.transform = 'rotate(0deg)';
            }
        }

        // Accordion function for PDAO modal
        function togglePDAOAccordion(id) {
            const content = document.getElementById(id + '-content');
            const icon = document.getElementById(id + '-icon');
            
            if (content.classList.contains('hidden')) {
                // Show content
                content.classList.remove('hidden');
                icon.style.transform = 'rotate(180deg)';
            } else {
                // Hide content
                content.classList.add('hidden');
                icon.style.transform = 'rotate(0deg)';
            }
        }

        // Accordion function for OSCA modal
        function toggleOSCAAccordion(id) {
            const content = document.getElementById(id + '-content');
            const icon = document.getElementById(id + '-icon');
            
            if (content.classList.contains('hidden')) {
                // Show content
                content.classList.remove('hidden');
                icon.style.transform = 'rotate(180deg)';
            } else {
                // Hide content
                content.classList.add('hidden');
                icon.style.transform = 'rotate(0deg)';
            }
        }

        // Accordion function for Solo Parent modal
        function toggleSoloParentAccordion(id) {
            const content = document.getElementById(id + '-content');
            const icon = document.getElementById(id + '-icon');
            
            if (content.classList.contains('hidden')) {
                // Show content
                content.classList.remove('hidden');
                icon.style.transform = 'rotate(180deg)';
            } else {
                // Hide content
                content.classList.add('hidden');
                icon.style.transform = 'rotate(0deg)';
            }
        }

        // Accordion function for Livelihood modal
        function toggleLivelihoodAccordion(id) {
            const content = document.getElementById(id + '-content');
            const icon = document.getElementById(id + '-icon');
            
            if (content.classList.contains('hidden')) {
                // Show content
                content.classList.remove('hidden');
                icon.style.transform = 'rotate(180deg)';
            } else {
                // Hide content
                content.classList.add('hidden');
                icon.style.transform = 'rotate(0deg)';
            }
        }

        // Close modals when clicking outside
        document.addEventListener('click', function(event) {
            const aicsModal = document.getElementById('aicsModal');
            const pdaoModal = document.getElementById('pdaoModal');
            const oscaModal = document.getElementById('oscaModal');
            const soloParentModal = document.getElementById('soloParentModal');
            const livelihoodModal = document.getElementById('livelihoodModal');
            const financialAidModal = document.getElementById('financialAidModal');
            
            if (event.target === aicsModal) {
                closeAICSModal();
            }
            if (event.target === pdaoModal) {
                closePDAOModal();
            }
            if (event.target === oscaModal) {
                closeOSCAModal();
            }
            if (event.target === soloParentModal) {
                closeSoloParentModal();
            }
            if (event.target === livelihoodModal) {
                closeLivelihoodModal();
            }
            if (event.target === financialAidModal) {
                closeFinancialAidModal();
            }
        });

        // Close modals with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeAICSModal();
                closePDAOModal();
                closeOSCAModal();
                closeSoloParentModal();
                closeLivelihoodModal();
                closeFinancialAidModal();
            }
        });

        updateDateTime(); // Initial call
        setInterval(updateDateTime, 1000); // Update every second

        // Update mobile time and date
        function updateMobileDateTime() {
            const now = new Date();
            const mobileTimeElement = document.getElementById('mobile-time');
            const mobileDateElement = document.getElementById('mobile-date');

            const options = {
                hour: 'numeric',
                minute: 'numeric',
                hour12: true
            };
            mobileTimeElement.textContent = now.toLocaleTimeString('en-US', options);

            const dateOptions = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            mobileDateElement.textContent = now.toLocaleDateString('en-US', dateOptions);
        }

        updateMobileDateTime(); // Initial call
        setInterval(updateMobileDateTime, 1000); // Update every second

        // Chatbot functionality
        let currentLanguage = 'en';
        let chatHistory = [];

        function toggleChat() {
            const chatWindow = document.getElementById('chatWindow');
            const chatButton = document.getElementById('chatButton');
            const chatInput = document.getElementById('chatInput');
            const chatMessages = document.getElementById('chatMessages');
            const initialMessage = document.getElementById('initialMessage');
            
            if (chatWindow.classList.contains('hidden')) {
                chatWindow.classList.remove('hidden');
                chatButton.classList.add('hidden');
                
                // Force initial message to be visible immediately
                if (initialMessage) {
                    initialMessage.style.cssText = 'display: flex !important; visibility: visible !important; opacity: 1 !important; position: relative !important; z-index: 10 !important;';
                    initialMessage.querySelector('.bg-white').style.cssText = 'background-color: white !important; border: 1px solid #e5e7eb !important; display: block !important; visibility: visible !important; opacity: 1 !important;';
                    initialMessage.querySelector('p').style.cssText = 'color: #374151 !important; display: block !important; visibility: visible !important;';
                    console.log('Initial message forced visible');
                }
                
                // Ensure initial message is visible and stays visible
                setTimeout(() => {
                    if (initialMessage) {
                        initialMessage.style.cssText = 'display: flex !important; visibility: visible !important; opacity: 1 !important; position: relative !important; z-index: 10 !important;';
                        initialMessage.querySelector('.bg-white').style.cssText = 'background-color: white !important; border: 1px solid #e5e7eb !important; display: block !important; visibility: visible !important; opacity: 1 !important;';
                        initialMessage.querySelector('p').style.cssText = 'color: #374151 !important; display: block !important; visibility: visible !important;';
                        console.log('Initial message should be visible');
                    }
                    if (chatMessages) {
                        chatMessages.scrollTop = 0;
                    }
                    if (chatInput) {
                        chatInput.focus();
                        chatInput.style.display = 'block';
                        chatInput.style.visibility = 'visible';
                    }
                }, 100);
                
                // Double-check visibility after a longer delay
                setTimeout(() => {
                    if (initialMessage) {
                        initialMessage.style.cssText = 'display: flex !important; visibility: visible !important; opacity: 1 !important; position: relative !important; z-index: 10 !important;';
                        initialMessage.querySelector('.bg-white').style.cssText = 'background-color: white !important; border: 1px solid #e5e7eb !important; display: block !important; visibility: visible !important; opacity: 1 !important;';
                        initialMessage.querySelector('p').style.cssText = 'color: #374151 !important; display: block !important; visibility: visible !important;';
                        console.log('Double-checking initial message visibility');
                    }
                }, 500);
            } else {
                chatWindow.classList.add('hidden');
                chatButton.classList.remove('hidden');
            }
        }

        function setLanguage(lang) {
            currentLanguage = lang;
            
            // Update button styles - keep the smaller styling
            if (lang === 'en') {
                document.getElementById('langEN').className = 'px-3 py-1.5 text-xs rounded-lg bg-primary text-white font-medium shadow-md hover:shadow-lg transition-all duration-200 hover:scale-105';
                document.getElementById('langTL').className = 'px-3 py-1.5 text-xs rounded-lg bg-gray-200 text-gray-700 font-medium shadow-sm hover:shadow-md transition-all duration-200 hover:scale-105 hover:bg-gray-300';
            } else {
                document.getElementById('langEN').className = 'px-3 py-1.5 text-xs rounded-lg bg-gray-200 text-gray-700 font-medium shadow-sm hover:shadow-md transition-all duration-200 hover:scale-105 hover:bg-gray-300';
                document.getElementById('langTL').className = 'px-3 py-1.5 text-xs rounded-lg bg-primary text-white font-medium shadow-md hover:shadow-lg transition-all duration-200 hover:scale-105';
            }
            
            // Update placeholder text
            document.getElementById('chatInput').placeholder = lang === 'en' ? 
                'Type your message here...' : 'I-type ang iyong mensahe...';
        }

        function sendMessage() {
            const input = document.getElementById('chatInput');
            const message = input.value.trim();
            
            if (message === '') return;
            
            // Add user message
            addMessage(message, 'user');
            input.value = '';
            
            // Generate bot response
            setTimeout(() => {
                const response = generateResponse(message, currentLanguage);
                addMessage(response, 'bot');
            }, 500);
        }

        function addMessage(message, sender) {
            const chatMessages = document.getElementById('chatMessages');
            const initialMessage = document.getElementById('initialMessage');
            const messageDiv = document.createElement('div');
            messageDiv.className = 'flex items-start space-x-3 mb-4';
            
            if (sender === 'user') {
                messageDiv.className += ' justify-end';
                messageDiv.innerHTML = `
                    <div class="bg-primary text-white rounded-2xl p-4 shadow-lg max-w-xs border-0">
                        <p class="text-sm text-white leading-relaxed">${message}</p>
                    </div>
                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0 shadow-lg">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                `;
            } else {
                messageDiv.innerHTML = `
                    <div class="w-8 h-8 bg-primary rounded-full flex items-center justify-center flex-shrink-0 shadow-lg">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <div class="bg-white rounded-2xl p-4 shadow-lg max-w-xs border border-gray-100">
                        <p class="text-sm text-gray-700 leading-relaxed">${message}</p>
                    </div>
                `;
            }
            
            // Always append new messages at the bottom
            chatMessages.appendChild(messageDiv);
            
            // Ensure initial message stays visible
            if (initialMessage) {
                initialMessage.style.cssText = 'display: flex !important; visibility: visible !important; opacity: 1 !important; position: relative !important; z-index: 10 !important;';
            }
            
            // Scroll to the bottom to show the new message
            chatMessages.scrollTop = chatMessages.scrollHeight;
            
            // Store in history
            chatHistory.push({ sender, message, timestamp: new Date() });
        }

        function generateResponse(message, language) {
            const lowerMessage = message.toLowerCase();
            
            // English responses
            if (language === 'en') {
                if (lowerMessage.includes('hello') || lowerMessage.includes('hi') || lowerMessage.includes('kumusta')) {
                    return "Hello! 👋 I'm your CSWDD Assistant. I'm here to help you with social welfare services. How can I assist you today?";
                } else if (lowerMessage.includes('address') || lowerMessage.includes('location') || lowerMessage.includes('where') || lowerMessage.includes('office')) {
                    return "📍 **CSWDD Office Location:**\nCity Hall Compound, A. Bonifacio Avenue, Caloocan City\n\nYou can find us at the main City Hall building, 2nd floor, Social Welfare Department.";
                } else if (lowerMessage.includes('contact') || lowerMessage.includes('phone') || lowerMessage.includes('number') || lowerMessage.includes('call')) {
                    return "📞 **Contact Information:**\n• Main Office: (02) 8-XXX-XXXX\n• Hotline: 0917-XXX-XXXX\n• Email: cswdd@caloocan.gov.ph\n\nOffice Hours: Monday-Friday, 8:00 AM - 5:00 PM";
                } else if (lowerMessage.includes('aics') || lowerMessage.includes('assistance')) {
                    return "🤝 **AICS (Assistance for Individuals in Crisis Situation)** provides financial and material assistance to people in crisis. Would you like to know more about the requirements or application process?";
                } else if (lowerMessage.includes('pdao') || lowerMessage.includes('pwd') || lowerMessage.includes('disability')) {
                    return "♿ **PDAO (Persons with Disability Affairs Office)** provides specialized services for PWDs including ID applications, accessibility assistance, and support programs. What specific information do you need?";
                } else if (lowerMessage.includes('osca') || lowerMessage.includes('senior') || lowerMessage.includes('elderly')) {
                    return "👴👵 **OSCA (Office for Senior Citizens Affairs)** offers healthcare assistance, social activities, and financial support for senior citizens aged 60 and above. How can I help you?";
                } else if (lowerMessage.includes('solo parent') || lowerMessage.includes('single parent')) {
                    return "👨‍👩‍👧‍👦 **Solo Parent and Child Welfare Support** provides assistance for single parents including ID cards, case management, and child care programs. What would you like to know?";
                } else if (lowerMessage.includes('livelihood') || lowerMessage.includes('training') || lowerMessage.includes('employment')) {
                    return "💼 **Livelihood and Training Program** offers skills training, entrepreneurship support, and sustainable livelihood assistance. Which area interests you?";
                } else if (lowerMessage.includes('financial aid') || lowerMessage.includes('money') || lowerMessage.includes('funds')) {
                    return "💰 **Financial Aid Disbursement** provides quick processing and secure distribution of financial assistance to qualified beneficiaries. Do you need help with the application process?";
                } else if (lowerMessage.includes('requirements') || lowerMessage.includes('documents') || lowerMessage.includes('papers')) {
                    return "📋 **Requirements** vary by service. For specific requirements, please tell me which service you're interested in (AICS, PDAO, OSCA, Solo Parent, Livelihood, or Financial Aid).";
                } else if (lowerMessage.includes('apply') || lowerMessage.includes('application') || lowerMessage.includes('process')) {
                    return "📝 **To apply** for CSWDD services, you can visit our office or use the 'Mag-apply' button in our service modals. Which service would you like to apply for?";
                } else if (lowerMessage.includes('thank') || lowerMessage.includes('salamat')) {
                    return "You're welcome! Is there anything else I can help you with? 🤖✨";
                } else {
                    return "I'm here to help with CSWDD services! 🤖 You can ask me about AICS, PDAO, OSCA, Solo Parent support, Livelihood programs, Financial Aid, our office location, or contact information. What would you like to know?";
                }
            } 
            // Tagalog responses
            else {
                if (lowerMessage.includes('kumusta') || lowerMessage.includes('hello') || lowerMessage.includes('hi')) {
                    return "Kumusta! 👋 Ako ang iyong CSWDD Assistant. Nandito ako para tumulong sa mga serbisyo ng social welfare. Paano kita matutulungan ngayon?";
                } else if (lowerMessage.includes('address') || lowerMessage.includes('location') || lowerMessage.includes('saan') || lowerMessage.includes('office') || lowerMessage.includes('tanggapan')) {
                    return "📍 **Lokasyon ng CSWDD Office:**\nCity Hall Compound, A. Bonifacio Avenue, Caloocan City\n\nMakikita mo kami sa main City Hall building, 2nd floor, Social Welfare Department.";
                } else if (lowerMessage.includes('contact') || lowerMessage.includes('phone') || lowerMessage.includes('number') || lowerMessage.includes('tawag') || lowerMessage.includes('telepono')) {
                    return "📞 **Contact Information:**\n• Main Office: (02) 8-XXX-XXXX\n• Hotline: 0917-XXX-XXXX\n• Email: cswdd@caloocan.gov.ph\n\nOras ng Opisina: Lunes-Biyernes, 8:00 AM - 5:00 PM";
                } else if (lowerMessage.includes('aics') || lowerMessage.includes('tulong') || lowerMessage.includes('assistance')) {
                    return "🤝 **AICS (Assistance for Individuals in Crisis Situation)** ay nagbibigay ng financial at material assistance sa mga taong nasa krisis. Gusto mo bang malaman ang requirements o application process?";
                } else if (lowerMessage.includes('pdao') || lowerMessage.includes('pwd') || lowerMessage.includes('may kapansanan')) {
                    return "♿ **PDAO (Persons with Disability Affairs Office)** ay nagbibigay ng specialized services para sa mga PWD kabilang ang ID applications, accessibility assistance, at support programs. Anong specific information ang kailangan mo?";
                } else if (lowerMessage.includes('osca') || lowerMessage.includes('senior') || lowerMessage.includes('matanda')) {
                    return "👴👵 **OSCA (Office for Senior Citizens Affairs)** ay nagbibigay ng healthcare assistance, social activities, at financial support para sa mga senior citizen na 60 taong gulang pataas. Paano kita matutulungan?";
                } else if (lowerMessage.includes('solo parent') || lowerMessage.includes('single parent') || lowerMessage.includes('mag-isa')) {
                    return "👨‍👩‍👧‍👦 **Solo Parent at Child Welfare Support** ay nagbibigay ng assistance para sa mga solo parent kabilang ang ID cards, case management, at child care programs. Anong gusto mong malaman?";
                } else if (lowerMessage.includes('livelihood') || lowerMessage.includes('training') || lowerMessage.includes('trabaho')) {
                    return "💼 **Livelihood at Training Program** ay nagbibigay ng skills training, entrepreneurship support, at sustainable livelihood assistance. Anong area ang interesado ka?";
                } else if (lowerMessage.includes('financial aid') || lowerMessage.includes('pera') || lowerMessage.includes('tulong pinansyal')) {
                    return "💰 **Financial Aid Disbursement** ay nagbibigay ng quick processing at secure distribution ng financial assistance sa qualified beneficiaries. Kailangan mo ba ng tulong sa application process?";
                } else if (lowerMessage.includes('requirements') || lowerMessage.includes('documents') || lowerMessage.includes('papeles')) {
                    return "📋 **Requirements** ay nag-iiba depende sa serbisyo. Para sa specific requirements, sabihin mo kung anong serbisyo ang interesado ka (AICS, PDAO, OSCA, Solo Parent, Livelihood, o Financial Aid).";
                } else if (lowerMessage.includes('mag-apply') || lowerMessage.includes('application') || lowerMessage.includes('process')) {
                    return "📝 **Para mag-apply** sa CSWDD services, pwede kang bumisita sa aming office o gamitin ang 'Mag-apply' button sa aming service modals. Anong serbisyo ang gusto mong apply-an?";
                } else if (lowerMessage.includes('salamat') || lowerMessage.includes('thank')) {
                    return "Walang anuman! May iba pa bang matutulungan kita? 🤖✨";
                } else {
                    return "Nandito ako para tumulungan sa mga serbisyo ng CSWDD! 🤖 Pwede mo akong tanungin tungkol sa AICS, PDAO, OSCA, Solo Parent support, Livelihood programs, Financial Aid, lokasyon ng aming office, o contact information. Anong gusto mong malaman?";
                }
            }
        }

        // Enter key support for chat input
        document.addEventListener('DOMContentLoaded', function() {
            const chatInput = document.getElementById('chatInput');
            if (chatInput) {
                chatInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        sendMessage();
                    }
                });
            }
            
            // Monitor initial message visibility
            const initialMessage = document.getElementById('initialMessage');
            if (initialMessage) {
                // Check every 100ms if the message is still visible
                setInterval(() => {
                    if (initialMessage.style.display === 'none' || 
                        initialMessage.style.visibility === 'hidden' || 
                        initialMessage.style.opacity === '0') {
                        console.log('Initial message was hidden, restoring...');
                        initialMessage.style.cssText = 'display: flex !important; visibility: visible !important; opacity: 1 !important; position: relative !important; z-index: 10 !important;';
                    }
                    
                    // Also check if the message content is visible
                    const messageBubble = initialMessage.querySelector('.bg-white');
                    const messageText = initialMessage.querySelector('p');
                    
                    if (messageBubble) {
                        messageBubble.style.cssText = 'background-color: white !important; border: 1px solid #e5e7eb !important; display: block !important; visibility: visible !important; opacity: 1 !important;';
                    }
                    
                    if (messageText) {
                        messageText.style.cssText = 'color: #374151 !important; display: block !important; visibility: visible !important;';
                    }
                }, 100);
                
                // Prevent the initial message from being removed
                const observer = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        if (mutation.type === 'childList') {
                            mutation.removedNodes.forEach(function(node) {
                                if (node === initialMessage) {
                                    console.log('Initial message was removed! Restoring...');
                                    chatMessages.appendChild(initialMessage);
                                    initialMessage.style.cssText = 'display: flex !important; visibility: visible !important; opacity: 1 !important; position: relative !important; z-index: 10 !important;';
                                }
                            });
                        }
                    });
                });
                
                observer.observe(chatMessages, { childList: true, subtree: true });
            }
        });
    </script>
</body>
</html>
