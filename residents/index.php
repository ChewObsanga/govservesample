<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in and is resident
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'resident') {
    header('Location: ../login.php');
    exit();
}

try {
    $pdo = getDBConnection();
    
    // Get resident user info
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? AND status = 'active'");
    $stmt->execute([$_SESSION['user_id']]);
    $resident = $stmt->fetch();
    
    if (!$resident) {
        session_destroy();
        header('Location: ../login.php');
        exit();
    }
    
} catch (Exception $e) {
    error_log('Resident home page error: ' . $e->getMessage());
    $error = 'System error. Please try again later.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Caloocan City Social Services</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=EB+Garamond:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
                        'serif': ['EB Garamond', 'serif'],
                    }
                }
            }
        }
    </script>
    <style>
        /* Apply Bold EB Garamond to all headings */
        h1, h2, h3, h4, h5, h6 {
            font-family: 'EB Garamond', serif !important;
            font-weight: 700 !important;
        }
        
        /* Increase heading font sizes */
        h1 {
            font-size: 2.5rem !important; /* 40px */
        }
        
        h2 {
            font-size: 2rem !important; /* 32px */
        }
        
        h3 {
            font-size: 1.75rem !important; /* 28px */
        }
        
        h4 {
            font-size: 1.5rem !important; /* 24px */
        }
        
        h5 {
            font-size: 1.25rem !important; /* 20px */
        }
        
        h6 {
            font-size: 1.125rem !important; /* 18px */
        }
        
        /* Make Caloocan City header text smaller */
        nav h1 {
            font-size: 1rem !important; /* 16px */
        }
        
        /* Responsive font sizes for Caloocan City header */
        @media (min-width: 640px) {
            nav h1 {
                font-size: 1.125rem !important; /* 18px */
            }
        }
        
        @media (min-width: 768px) {
            nav h1 {
                font-size: 1.25rem !important; /* 20px */
            }
        }
        
        @media (min-width: 1024px) {
            nav h1 {
                font-size: 1.375rem !important; /* 22px */
            }
        }
        
        /* Make Contact Us section font size smaller */
        footer .text-center h3 {
            font-size: 1.125rem !important; /* 18px */
        }
        
        footer .text-center div {
            font-size: 0.875rem !important; /* 14px */
        }
        
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
        
        /* Smooth scrolling behavior */
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans">
    <!-- Navigation Bar -->
    <nav class="bg-primary shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo and City Name -->
                <div class="flex items-center space-x-2 sm:space-x-3">
                    <img src="../caloocan-seal.png" alt="Caloocan City Seal" class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 object-contain">
                    <div>
                        <h1 class="text-xs sm:text-sm md:text-base lg:text-lg font-bold text-white">Caloocan City</h1>
                        <p class="text-xs text-white opacity-90">Social Services</p>
                    </div>
                </div>

                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn" class="md:hidden text-white p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>

                <!-- Right Side: Navigation Links, User Menu, and Time/Date -->
                <div class="hidden md:flex items-center space-x-4 lg:space-x-6">
                    <!-- Navigation Links -->
                    <div class="flex items-center space-x-4 lg:space-x-6">
                        <a href="index.php" class="text-white hover:text-gray-200 font-medium transition-colors duration-200">Home</a>
                        <a href="#services" class="text-white hover:text-gray-200 font-medium transition-colors duration-200">Our Services</a>
                    </div>

                    <!-- User Menu -->
                    <div class="relative">
                        <button id="user-menu-btn" class="flex items-center space-x-2 text-white hover:text-gray-200">
                            <div class="w-8 h-8 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                                <span class="text-white font-medium text-sm">
                                    <?php echo strtoupper(substr($resident['first_name'], 0, 1) . substr($resident['last_name'], 0, 1)); ?>
                                </span>
                            </div>
                            <span class="hidden lg:block"><?php echo htmlspecialchars($resident['first_name']); ?></span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <!-- User Dropdown Menu -->
                        <div id="user-menu" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden">
                            <a href="dashboard.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dashboard</a>
                            <a href="profile.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile Settings</a>
                            <a href="applications.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">My Applications</a>
                            <hr class="my-1">
                            <a href="../logout.php" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Logout</a>
                        </div>
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
                    <a href="index.php" class="text-white hover:text-gray-200 font-medium transition-colors duration-200">Home</a>
                    <a href="#services" class="text-white hover:text-gray-200 font-medium transition-colors duration-200">Mga Serbisyo</a>
                    <hr class="border-white border-opacity-20">
                     <a href="applications.php" class="text-white hover:text-red-200 font-medium transition-colors duration-200">My Applications</a>
                    <a href="../logout.php" class="text-red-300 hover:text-red-200 font-medium transition-colors duration-200">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative bg-gradient-to-r from-orange-300 to-orange-400 overflow-hidden">
        <div class="max-w-7xl mx-auto">
            <div class="relative z-10 pb-4 sm:pb-8 md:pb-12 lg:max-w-2xl lg:w-full lg:pb-16 xl:pb-20">
                <main class="mt-6 mx-auto max-w-7xl px-4 sm:mt-8 sm:px-6 md:mt-10 lg:mt-12 lg:px-8 xl:mt-16">
                    <div class="sm:text-center lg:text-left">
                        <h1 class="text-2xl tracking-tight font-extrabold text-white sm:text-3xl md:text-4xl">
                            <span class="block">City Social Welfare and</span>
                            <span class="block text-white">Development Department</span>
                        </h1>
                        <p class="mt-2 text-sm text-white sm:mt-3 sm:text-base sm:max-w-xl sm:mx-auto md:mt-4 md:text-lg lg:mx-0">
                            Comprehensive social welfare programs and community development initiatives para sa lahat ng Caloocan City residents.
                        </p>
 
                    </div>
                </main>
            </div>
        </div>
        


    </div>

    <!-- Services Section -->
    <div id="services" class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">


            <!-- Partner Logos -->
            <div class="flex justify-center items-center space-x-8 mb-8 mt-8">
                <div class="w-20 h-20 sm:w-24 sm:h-24 md:w-28 md:h-28 rounded-full overflow-hidden border-4 border-primary shadow-lg">
                    <img src="../image/AM.png" alt="AM Logo" class="w-full h-full object-cover">
                </div>
                <div class="w-20 h-20 sm:w-24 sm:h-24 md:w-28 md:h-28 rounded-full overflow-hidden border-4 border-primary shadow-lg">
                    <img src="../image/ccswdd.jpg" alt="AICS Logo" class="w-full h-full object-cover">
                </div>
                <div class="w-20 h-20 sm:w-24 sm:h-24 md:w-28 md:h-28 rounded-full overflow-hidden border-4 border-primary shadow-lg">
                    <img src="../image/osca.jpg" alt="OSCA Logo" class="w-full h-full object-cover">
                </div>
                <div class="w-20 h-20 sm:w-24 sm:h-24 md:w-28 md:h-28 rounded-full overflow-hidden border-4 border-primary shadow-lg">
                    <img src="../image/pdao.jpg" alt="PDAO Logo" class="w-full h-full object-cover">
                </div>
            </div>

            <!-- Our Services Section -->
            <div class="lg:text-center mb-8">
             
                <h2 class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    Mga Serbisyo
                </h2>
                <p class="mt-4 max-w-2xl text-lg text-gray-500 lg:mx-auto">
                    Browse and apply for available social services in Caloocan City.
                </p>
            </div>

            <div class="mt-10">
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
        </div>
    </div>

    <!-- Service Modals -->
    <!-- AICS Modal -->
    <div id="aicsModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-6xl w-full max-h-[90vh] overflow-y-auto">
                <!-- Modal Header -->
                <div class="bg-primary p-6 border-b sticky top-0 z-10">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center overflow-hidden">
                                <img src="../image/ccswdd.jpg" alt="AICS Logo" class="w-12 h-12 object-cover rounded-full">
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
                        <a href="services.php" class="bg-primary hover:bg-secondary text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200 text-center">
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
                                <img src="../image/pdao.jpg" alt="PDAO Logo" class="w-12 h-12 object-cover rounded-full">
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
                                             <p class="text-gray-700 mb-4">Philippine Registry Form for PWD (PRPD Form) - You can download the form or register online.</p>
                                             <div class="flex flex-col sm:flex-row gap-3">
                                                 <a href="../forms/PWD-APPLICATION-FORM.pdf" download class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 text-center text-sm">
                                                     📄 Download PDF Form
                                                 </a>
                                                 <a href="pwd-registration.php" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 text-center text-sm">
                                                     🌐 Online Registration
                                                 </a>
                                             </div>
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
                        <a href="services.php" class="bg-primary hover:bg-secondary text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200 text-center">
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
                                <img src="../image/osca.jpg" alt="OSCA Logo" class="w-12 h-12 object-cover rounded-full">
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
                        <a href="services.php" class="bg-primary hover:bg-secondary text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200 text-center">
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

    <!-- Solo Parent Modal -->
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
                        <a href="services.php" class="bg-primary hover:bg-secondary text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200 text-center">
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

    <!-- Livelihood Modal -->
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
                                            <p class="text-gray-700">Comprehensive business planning and strategy development support.</p>
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
                                            <p class="text-gray-700">Training in financial management, budgeting, and business accounting.</p>
                                        </div>
                                    </div>
                                    
                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleLivelihoodAccordion('entrepreneur-3')">
                                            <span class="font-semibold text-gray-800">Marketing and sales support</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="entrepreneur-3-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="entrepreneur-3-content">
                                            <p class="text-gray-700">Support in developing marketing strategies and sales techniques.</p>
                                        </div>
                                    </div>
                                    
                                    <div class="accordion-item border border-gray-200 rounded-lg">
                                        <button class="accordion-header w-full p-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex justify-between items-center" onclick="toggleLivelihoodAccordion('entrepreneur-4')">
                                            <span class="font-semibold text-gray-800">Networking and mentorship</span>
                                            <svg class="w-5 h-5 text-gray-600 transform transition-transform duration-200" id="entrepreneur-4-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="accordion-content hidden p-3 bg-white" id="entrepreneur-4-content">
                                            <p class="text-gray-700">Access to business networks and mentorship programs for entrepreneurs.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center mt-8 pt-6 border-t border-gray-200">
                        <a href="services.php" class="bg-primary hover:bg-secondary text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200 text-center">
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

    <!-- Financial Aid Modal -->
    <div id="financialAidModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-6xl w-full max-h-[90vh] overflow-y-auto">
                <!-- Modal Header -->
                <div class="bg-primary p-6 border-b sticky top-0 z-10">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center overflow-hidden">
                                <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center">
                                    <span class="text-3xl font-bold text-primary">₱</span>
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
                    <!-- Under Development Message -->
                    <div class="text-center py-12">
                        <div class="mb-6">
                            <svg class="w-24 h-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-700 mb-4">This Page is Under Development</h3>
                        <p class="text-gray-600 mb-8 max-w-md mx-auto">
                            We're currently working on improving the Financial Aid Disbursement section. 
                            Please check back soon for updated information and features.
                        </p>
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 max-w-md mx-auto">
                            <p class="text-blue-800 text-sm">
                                <strong>Coming Soon:</strong> Enhanced application process, real-time tracking, 
                                and improved user experience for financial aid services.
                            </p>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center mt-8 pt-6 border-t border-gray-200">
                        <button onclick="closeFinancialAidModal()" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200">
                            Iba pang Serbisyo
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });

        // User menu toggle
        document.getElementById('user-menu-btn').addEventListener('click', function() {
            document.getElementById('user-menu').classList.toggle('hidden');
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('#user-menu-btn')) {
                document.getElementById('user-menu').classList.add('hidden');
            }
        });

        // Live time and date - removed

        // Modal functions
        function openAICSModal() {
            document.getElementById('aicsModal').classList.remove('hidden');
        }

        function closeAICSModal() {
            document.getElementById('aicsModal').classList.add('hidden');
        }

        function openPDAOModal() {
            document.getElementById('pdaoModal').classList.remove('hidden');
        }

        function closePDAOModal() {
            document.getElementById('pdaoModal').classList.add('hidden');
        }

        function openOSCAModal() {
            document.getElementById('oscaModal').classList.remove('hidden');
        }

        function closeOSCAModal() {
            document.getElementById('oscaModal').classList.add('hidden');
        }

        function openSoloParentModal() {
            document.getElementById('soloParentModal').classList.remove('hidden');
        }

        function closeSoloParentModal() {
            document.getElementById('soloParentModal').classList.add('hidden');
        }

        function openLivelihoodModal() {
            document.getElementById('livelihoodModal').classList.remove('hidden');
        }

        function closeLivelihoodModal() {
            document.getElementById('livelihoodModal').classList.add('hidden');
        }

        function openFinancialAidModal() {
            document.getElementById('financialAidModal').classList.remove('hidden');
        }

        function closeFinancialAidModal() {
            document.getElementById('financialAidModal').classList.add('hidden');
        }

        // Close modals when clicking outside
        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('fixed')) {
                event.target.classList.add('hidden');
            }
        });

        // Accordion functions
        function togglePDAOAccordion(id) {
            const content = document.getElementById(`${id}-content`);
            const icon = document.getElementById(`${id}-icon`);
            const isHidden = content.classList.contains('hidden');

            // Close all other accordions
            document.querySelectorAll('.accordion-content').forEach(item => {
                if (item.id !== `${id}-content`) {
                    item.classList.add('hidden');
                }
            });
            document.querySelectorAll('.accordion-header svg').forEach(item => {
                if (item.id !== `${id}-icon`) {
                    item.classList.remove('rotate-180');
                }
            });

            // Toggle current accordion
            content.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
        }

        // Language content objects
        const content = {
            en: {
                // Navigation
                'nav-home': 'Home',
                'nav-services': 'Services',
                
                // Hero section
                'hero-title-1': 'City Social Welfare and',
                'hero-title-2': 'Development Department',
                'hero-subtitle': 'Comprehensive social welfare programs and community development initiatives para sa lahat ng Caloocan City residents.',
                
                // Services section
                'services-title': 'Our Services',
                'services-subtitle': 'Our Services',
                'services-description': 'Browse and apply for available social services in Caloocan City.',
                
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
                'footer-language': 'Language:',
                
                // Copyright
                'copyright-text': 'Caloocan City Social Services Management System. All rights reserved.',
                
                // Modal buttons
                'modal-close': 'Other Services',
                'modal-apply': 'Apply Now'
            },
            fil: {
                // Navigation
                'nav-home': 'Home',
                'nav-services': 'Mga Serbisyo',
                
                // Hero section
                'hero-title-1': 'Tanggapan ng Social Welfare at',
                'hero-title-2': 'Development Department',
                'hero-subtitle': 'Komprehensibong social welfare programs at community development initiatives para sa lahat ng Caloocan City residents.',
                
                // Services section
                'services-title': 'Mga Serbisyo',
                'services-subtitle': 'Mga Serbisyo',
                'services-description': 'Tingnan at mag-apply para sa mga available na social services sa Caloocan City.',
                
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
                'footer-language': 'Wika:',
                
                // Copyright
                'copyright-text': 'Caloocan City Social Services Management System. Lahat ng karapatan ay nakalaan.',
                
                // Modal buttons
                'modal-close': 'Iba pang Serbisyo',
                'modal-apply': 'Mag-apply'
            }
        };

        // Function to switch language
        function switchLanguage(lang) {
            const langEnBtn = document.getElementById('lang-en');
            const langFilBtn = document.getElementById('lang-fil');
            
            if (!langEnBtn || !langFilBtn) return;
            
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
            const navHome = document.querySelector('a[href="index.php"]');
            const navServices = document.querySelector('a[href="#services"]');
            if (navHome) navHome.textContent = currentContent['nav-home'];
            if (navServices) navServices.textContent = currentContent['nav-services'];
            
            // Update hero section
            const heroTitle1 = document.querySelector('.text-2xl.tracking-tight.font-extrabold.text-white.sm\\:text-3xl.md\\:text-4xl span:first-child');
            const heroTitle2 = document.querySelector('.text-2xl.tracking-tight.font-extrabold.text-white.sm\\:text-3xl.md\\:text-4xl span:last-child');
            const heroSubtitle = document.querySelector('.mt-2.text-sm.text-white.sm\\:mt-3.sm\\:text-base.sm\\:max-w-xl.sm\\:mx-auto.md\\:mt-4.md\\:text-lg.lg\\:mx-0');
            if (heroTitle1) heroTitle1.textContent = currentContent['hero-title-1'];
            if (heroTitle2) heroTitle2.textContent = currentContent['hero-title-2'];
            if (heroSubtitle) heroSubtitle.textContent = currentContent['hero-subtitle'];
            
            // Update services section
            const servicesTitle = document.querySelector('.lg\\:text-center.mb-8 h2');
            const servicesSubtitle = document.querySelector('.lg\\:text-center.mb-8 p:first-of-type');
            const servicesDescription = document.querySelector('.lg\\:text-center.mb-8 p:last-of-type');
            if (servicesTitle) servicesTitle.textContent = currentContent['services-title'];
            if (servicesSubtitle) servicesSubtitle.textContent = currentContent['services-subtitle'];
            if (servicesDescription) servicesDescription.textContent = currentContent['services-description'];
            
            // Update service cards
            updateServiceCards(currentContent);
            
            // Update footer
            const footerContact = document.querySelector('footer h3');
            const footerLanguage = document.querySelector('footer .text-white.text-xs.font-medium');
            const footerCopyright = document.querySelector('footer .text-gray-500.text-sm');
            if (footerContact) footerContact.textContent = currentContent['footer-contact'];
            if (footerLanguage) footerLanguage.textContent = currentContent['footer-language'];
            if (footerCopyright) footerCopyright.textContent = currentContent['copyright-text'];
            
            // Update modal buttons
            updateModalContent(currentContent);
        }

        // Function to update modal content
        function updateModalContent(currentContent) {
            // Update modal close buttons
            const modalCloseButtons = document.querySelectorAll('button[onclick*="close"]');
            modalCloseButtons.forEach(button => {
                if (button.textContent.includes('Iba pang Serbisyo') || button.textContent.includes('Other Services')) {
                    button.textContent = currentContent['modal-close'];
                }
            });
            
            // Update modal apply buttons
            const modalApplyButtons = document.querySelectorAll('a[href="services.php"]');
            modalApplyButtons.forEach(button => {
                if (button.textContent.includes('Mag-apply') || button.textContent.includes('Apply Now')) {
                    button.textContent = currentContent['modal-apply'];
                }
            });
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

        // Initialize language on page load
        document.addEventListener('DOMContentLoaded', () => {
            // Get language toggle buttons after DOM is loaded
            const langEnBtn = document.getElementById('lang-en');
            const langFilBtn = document.getElementById('lang-fil');
            
            console.log('Language buttons found:', { langEnBtn, langFilBtn });
            
            if (langEnBtn && langFilBtn) {
                // Event listeners for language buttons
                langEnBtn.addEventListener('click', () => {
                    console.log('English button clicked');
                    switchLanguage('en');
                });
                langFilBtn.addEventListener('click', () => {
                    console.log('Filipino button clicked');
                    switchLanguage('fil');
                });
                
                // Set default language to English
                switchLanguage('en');
                console.log('Language system initialized successfully');
            } else {
                console.error('Language buttons not found!');
            }
        });
    </script>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 py-8 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h3 class="text-base sm:text-lg font-bold text-white mb-3 sm:mb-4">Contact Us</h3>
                <div class="flex flex-col sm:flex-row items-center justify-center space-y-2 sm:space-y-0 sm:space-x-4 lg:space-x-8 mb-4 sm:mb-6">
                    <div class="flex items-center text-xs sm:text-sm"><span class="text-gray-400 mr-2">📍</span>Caloocan City Hall, 123 Main St.</div>
                    <div class="flex items-center text-xs sm:text-sm"><span class="text-gray-400 mr-2">📞</span>Tel: (02) 533-65705</div>
                    <div class="flex items-center text-xs sm:text-sm"><span class="text-gray-400 mr-2">📧</span>Email: info@caloocancity.gov.ph</div>
                </div>

                <!-- Language Toggle -->
                <div class="flex justify-center items-center space-x-2 sm:space-x-3 mb-4 sm:mb-6">
                    <span class="text-white text-xs font-medium">Language:</span>
                    <button id="lang-en" class="px-2 sm:px-3 py-1 sm:py-1.5 bg-primary hover:bg-orange-600 text-white rounded-md text-xs sm:text-sm font-medium transition-colors duration-200 active">English</button>
                    <button id="lang-fil" class="px-2 sm:px-3 py-1 sm:py-1.5 bg-gray-600 hover:bg-gray-700 text-white rounded-md text-xs sm:text-sm font-medium transition-colors duration-200">Filipino</button>
                </div>
            </div>
            <div class="text-center text-gray-500 text-xs sm:text-sm">
                &copy; <?php echo date('Y'); ?> Caloocan City Social Services Management System. All rights reserved.
            </div>
        </div>
    </footer>
</body>
</html>
