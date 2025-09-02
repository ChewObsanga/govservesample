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
    error_log('PWD registration error: ' . $e->getMessage());
    $error = 'System error. Please try again later.';
}

$success = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate required fields
        $required_fields = [
            'first_name', 'last_name', 'middle_name', 'birth_date', 'birth_place',
            'gender', 'civil_status', 'nationality', 'religion', 'educational_attainment',
            'occupation', 'monthly_income', 'disability_type', 'disability_cause',
            'disability_date', 'address', 'barangay', 'city', 'province', 'zip_code',
            'contact_number', 'emergency_contact_name', 'emergency_contact_relationship',
            'emergency_contact_number', 'emergency_contact_address'
        ];

        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("Please fill in all required fields.");
            }
        }

        // Insert PWD application
        $stmt = $pdo->prepare("
            INSERT INTO pwd_applications (
                resident_id, first_name, last_name, middle_name, birth_date, birth_place,
                gender, civil_status, nationality, religion, educational_attainment,
                occupation, monthly_income, disability_type, disability_cause,
                disability_date, address, barangay, city, province, zip_code,
                contact_number, emergency_contact_name, emergency_contact_relationship,
                emergency_contact_number, emergency_contact_address, status, created_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())
        ");

        $stmt->execute([
            $_SESSION['user_id'],
            $_POST['first_name'],
            $_POST['last_name'],
            $_POST['middle_name'],
            $_POST['birth_date'],
            $_POST['birth_place'],
            $_POST['gender'],
            $_POST['civil_status'],
            $_POST['nationality'],
            $_POST['religion'],
            $_POST['educational_attainment'],
            $_POST['occupation'],
            $_POST['monthly_income'],
            $_POST['disability_type'],
            $_POST['disability_cause'],
            $_POST['disability_date'],
            $_POST['address'],
            $_POST['barangay'],
            $_POST['city'],
            $_POST['province'],
            $_POST['zip_code'],
            $_POST['contact_number'],
            $_POST['emergency_contact_name'],
            $_POST['emergency_contact_relationship'],
            $_POST['emergency_contact_number'],
            $_POST['emergency_contact_address']
        ]);

        $success = "PWD application submitted successfully! Your application is now under review.";
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PWD Registration - Caloocan City Social Services</title>
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
        h1 { font-size: 2.5rem !important; }
        h2 { font-size: 2rem !important; }
        h3 { font-size: 1.75rem !important; }
        h4 { font-size: 1.5rem !important; }
        h5 { font-size: 1.25rem !important; }
        h6 { font-size: 1.125rem !important; }
        
        /* Make Caloocan City header text smaller */
        nav h1 {
            font-size: 1rem !important;
        }
        
        @media (min-width: 640px) {
            nav h1 { font-size: 1.125rem !important; }
        }
        
        @media (min-width: 768px) {
            nav h1 { font-size: 1.25rem !important; }
        }
        
        @media (min-width: 1024px) {
            nav h1 { font-size: 1.375rem !important; }
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

                <!-- Right Side: Navigation Links and User Menu -->
                <div class="hidden md:flex items-center space-x-4 lg:space-x-6">
                    <!-- Navigation Links -->
                    <div class="flex items-center space-x-4 lg:space-x-6">
                        <a href="index.php" class="text-white hover:text-gray-200 font-medium transition-colors duration-200">Home</a>
                        <a href="index.php#services" class="text-white hover:text-gray-200 font-medium transition-colors duration-200">Our Services</a>
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
                            <a href="index.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Home</a>
                            <a href="change-password.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Change Password</a>
                            <hr class="my-1">
                            <a href="../logout.php" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Logout</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="md:hidden hidden pb-4">
                <div class="flex flex-col space-y-4">
                    <a href="index.php" class="text-white hover:text-gray-200 font-medium transition-colors duration-200">Home</a>
                    <a href="index.php#services" class="text-white hover:text-gray-200 font-medium transition-colors duration-200">Our Services</a>
                    <hr class="border-white border-opacity-20">
                    <a href="change-password.php" class="text-white hover:text-gray-200 font-medium transition-colors duration-200">Change Password</a>
                    <a href="../logout.php" class="text-red-300 hover:text-red-200 font-medium transition-colors duration-200">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
                            <h1 class="text-3xl font-bold text-green-600 mb-2">PWD Registration Form</h1>
            <p class="text-gray-600">Complete the form below to apply for PWD ID Card registration.</p>
        </div>

        <!-- Success/Error Messages -->
        <?php if ($success): ?>
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <!-- Two Column Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
            <!-- Left Column: Registration Form -->
            <div class="lg:col-span-3 bg-white rounded-lg shadow-md p-6">
            <form method="POST" class="space-y-4" id="pwd-form">
                <!-- Application Type -->
                <div class="border-2 border-gray-300 rounded-lg p-4 bg-gray-50">
                    <h2 class="text-sm font-bold text-gray-800 mb-3" style="font-size: 18px !important;">Application Type</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <label class="flex items-center">
                            <input type="radio" name="application_type" value="new" class="mr-2" required>
                            <span class="text-xs font-medium text-gray-700">NEW APPLICANT</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="application_type" value="renewal" class="mr-2">
                            <span class="text-xs font-medium text-gray-700">RENEWAL</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="application_type" value="representative" class="mr-2">
                            <span class="text-xs font-medium text-gray-700">REPRESENTATIVE</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="application_type" value="lost_replacement" class="mr-2">
                            <span class="text-xs font-medium text-gray-700">LOST (FOR REPLACEMENT)</span>
                        </label>
                    </div>
                    <p class="text-xs text-gray-600 mt-2">PLEASE FILL OUT ALL FIELDS MARKED BY * (ASTERISK)</p>
                    
                    <!-- Date Applied -->
                    <div class="mt-4">
                        <label for="date_applied" class="block text-xs font-medium text-gray-700 mb-2">DATE APPLIED:</label>
                        <input type="date" id="date_applied" name="date_applied" value="<?php echo date('Y-m-d'); ?>" readonly
                            class="w-full px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-xs">
                    </div>
                </div>



                <!-- Personal Information -->
                <div class="border-2 border-gray-300 rounded-lg p-4 bg-gray-50">
                    <h2 class="text-sm font-bold text-gray-800 mb-3" style="font-size: 18px !important;">PERSONAL INFORMATION</h2>
                                         <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                             <label for="last_name" class="block text-xs font-medium text-gray-700 mb-2">* LAST NAME:</label>
                             <input type="text" id="last_name" name="last_name" required value="<?php echo htmlspecialchars($resident['last_name']); ?>"
                                 class="w-full px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-xs">
                        </div>
                        <div>
                             <label for="first_name" class="block text-xs font-medium text-gray-700 mb-2">* FIRST NAME:</label>
                             <input type="text" id="first_name" name="first_name" required value="<?php echo htmlspecialchars($resident['first_name']); ?>"
                                 class="w-full px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-xs">
                        </div>
                        <div>
                             <label for="middle_name" class="block text-xs font-medium text-gray-700 mb-2">* MIDDLE NAME:</label>
                            <input type="text" id="middle_name" name="middle_name" required
                                 class="w-full px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-xs">
                             <div class="mt-2">
                                 <label class="flex items-center">
                                     <input type="checkbox" id="no_middle_name" name="no_middle_name" class="mr-2">
                                     <span class="text-xs font-medium text-gray-700">No Middle Name</span>
                                 </label>
                        </div>
                    </div>
                        <div>
                             <label for="suffix" class="block text-xs font-medium text-gray-700 mb-2">SUFFIX:</label>
                             <input type="text" id="suffix" name="suffix" placeholder="Jr., Sr., III, etc."
                                 class="w-full px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-xs">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                        <div>
                             <label for="birth_date" class="block text-xs font-medium text-gray-700 mb-2">*DATE OF BIRTH (mm-dd-yyyy)</label>
                             <input type="date" id="birth_date" name="birth_date" required max="<?php echo date('Y-m-d'); ?>"
                                 class="w-full px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-xs">
                        </div>
                        <div>
                            <label for="age" class="block text-xs font-medium text-gray-700 mb-2">*AGE:</label>
                            <input type="number" id="age" name="age" required min="0" max="150"
                                class="w-full px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-xs">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">*SEX:</label>
                            <div class="flex space-x-4">
                                <label class="flex items-center">
                                    <input type="radio" name="sex" value="male" class="mr-2" required>
                                    <span class="text-xs font-medium text-gray-700">MALE</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="sex" value="female" class="mr-2">
                                    <span class="text-xs font-medium text-gray-700">FEMALE</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Blood Type -->
                    <div class="mt-4">
                        <label class="block text-xs font-medium text-gray-700 mb-2">* BLOOD TYPE:</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <label class="flex items-center">
                                <input type="radio" name="blood_type" value="A+" class="mr-2" required>
                                <span class="text-xs font-medium text-gray-700">A+</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="blood_type" value="A-" class="mr-2">
                                <span class="text-xs font-medium text-gray-700">A-</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="blood_type" value="B+" class="mr-2">
                                <span class="text-xs font-medium text-gray-700">B+</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="blood_type" value="B-" class="mr-2">
                                <span class="text-xs font-medium text-gray-700">B-</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="blood_type" value="O+" class="mr-2">
                                <span class="text-xs font-medium text-gray-700">O+</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="blood_type" value="O-" class="mr-2">
                                <span class="text-xs font-medium text-gray-700">O-</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="blood_type" value="AB+" class="mr-2">
                                <span class="text-xs font-medium text-gray-700">AB+</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="blood_type" value="AB-" class="mr-2">
                                <span class="text-xs font-medium text-gray-700">AB-</span>
                            </label>
                        </div>
                        </div>

                    <!-- Civil Status -->
                    <div class="mt-4">
                        <label class="block text-xs font-medium text-gray-700 mb-2">*CIVIL STATUS:</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                            <label class="flex items-center">
                                <input type="radio" name="civil_status" value="single" class="mr-2" required>
                                <span class="text-xs font-medium text-gray-700">Single</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="civil_status" value="separated" class="mr-2">
                                <span class="text-xs font-medium text-gray-700">Separated</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="civil_status" value="cohabitation" class="mr-2">
                                <span class="text-xs font-medium text-gray-700">Cohabitation (Live-In)</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="civil_status" value="married" class="mr-2">
                                <span class="text-xs font-medium text-gray-700">Married</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="civil_status" value="widower" class="mr-2">
                                <span class="text-xs font-medium text-gray-700">Widower</span>
                            </label>
                        </div>
                    </div>
                    
                                         <!-- Disability Type -->
                     <div class="mt-4">
                         <label class="block text-xs font-medium text-gray-700 mb-2">*TYPE OF DISABILITY:</label>
                         <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                             <label class="flex items-center">
                                 <input type="radio" name="disability_type" value="deaf_hard_hearing" class="mr-2" required>
                                 <span class="text-xs font-medium text-gray-700">Deaf or Hard of Hearing</span>
                             </label>
                             <label class="flex items-center">
                                 <input type="radio" name="disability_type" value="psychosocial" class="mr-2">
                                 <span class="text-xs font-medium text-gray-700">Psychosocial Disability</span>
                             </label>
                             <label class="flex items-center">
                                 <input type="radio" name="disability_type" value="intellectual" class="mr-2">
                                 <span class="text-xs font-medium text-gray-700">Intellectual Disability</span>
                             </label>
                             <label class="flex items-center">
                                 <input type="radio" name="disability_type" value="speech_language" class="mr-2">
                                 <span class="text-xs font-medium text-gray-700">Speech and Language Impairment</span>
                             </label>
                             <label class="flex items-center">
                                 <input type="radio" name="disability_type" value="learning" class="mr-2">
                                 <span class="text-xs font-medium text-gray-700">Learning Disability</span>
                             </label>
                             <label class="flex items-center">
                                 <input type="radio" name="disability_type" value="visual" class="mr-2">
                                 <span class="text-xs font-medium text-gray-700">Visual Disability</span>
                             </label>
                             <label class="flex items-center">
                                 <input type="radio" name="disability_type" value="mental" class="mr-2">
                                 <span class="text-xs font-medium text-gray-700">Mental Disability</span>
                             </label>
                             <label class="flex items-center">
                                 <input type="radio" name="disability_type" value="physical" class="mr-2">
                                 <span class="text-xs font-medium text-gray-700">Physical Disability</span>
                             </label>
                             <label class="flex items-center">
                                 <input type="radio" name="disability_type" value="cancer" class="mr-2">
                                 <span class="text-xs font-medium text-gray-700">Cancer (RA 11215)</span>
                             </label>
                             <label class="flex items-center">
                                 <input type="radio" name="disability_type" value="rare_disease" class="mr-2">
                                 <span class="text-xs font-medium text-gray-700">Rare Disease (RA 10747)</span>
                             </label>
                         </div>
                        </div>

                     <!-- Cause of Disability -->
                     <div class="mt-4">
                         <label for="disability_cause" class="block text-xs font-medium text-gray-700 mb-2">*CAUSE OF DISABILITY:</label>
                         <select id="disability_cause" name="disability_cause" required
                             class="w-full px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-xs"
                             onchange="console.log('Dropdown changed to:', this.value); if(this.value === 'congenital') { document.getElementById('congenital_options').style.display = 'block'; document.getElementById('acquired_options').style.display = 'none'; } else if(this.value === 'acquired') { document.getElementById('acquired_options').style.display = 'block'; document.getElementById('congenital_options').style.display = 'none'; } else { document.getElementById('congenital_options').style.display = 'none'; document.getElementById('acquired_options').style.display = 'none'; }">
                             <option value="">-- Select Cause --</option>
                             <option value="congenital">Congenital / Inborn</option>
                             <option value="acquired">Acquired</option>
                            </select>
                         
                         <!-- Congenital Options -->
                         <div id="congenital_options" class="mt-3 hidden">
                             <label class="block text-xs font-medium text-gray-700 mb-2">Select Specific Cause:</label>
                             <div class="space-y-2">
                                 <label class="flex items-center">
                                     <input type="radio" name="disability_cause_specific" value="adhd" class="mr-2">
                                     <span class="text-xs font-medium text-gray-700">ADHD</span>
                                 </label>
                                 <label class="flex items-center">
                                     <input type="radio" name="disability_cause_specific" value="cerebral_palsy" class="mr-2">
                                     <span class="text-xs font-medium text-gray-700">Cerebral Palsy</span>
                                 </label>
                                 <label class="flex items-center">
                                     <input type="radio" name="disability_cause_specific" value="down_syndrome" class="mr-2">
                                     <span class="text-xs font-medium text-gray-700">Down Syndrome</span>
                                 </label>
                                 <label class="flex items-center">
                                     <input type="radio" name="disability_cause_specific" value="other_congenital" class="mr-2" onchange="if(this.checked) { document.getElementById('other_congenital_div').style.display = 'block'; } else { document.getElementById('other_congenital_div').style.display = 'none'; }">
                                     <span class="text-xs font-medium text-gray-700">Other: Specify</span>
                                 </label>
                                 <div id="other_congenital_div" class="ml-6 hidden">
                                     <input type="text" name="other_congenital_specify" placeholder="Please specify" 
                                         class="w-full px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-xs">
                        </div>
                    </div>
                </div>

                         <!-- Acquired Options -->
                         <div id="acquired_options" class="mt-3 hidden">
                             <label class="block text-xs font-medium text-gray-700 mb-2">Select Specific Cause:</label>
                             <div class="space-y-2">
                                 <label class="flex items-center">
                                     <input type="radio" name="disability_cause_specific" value="chronic_illness" class="mr-2">
                                     <span class="text-xs font-medium text-gray-700">Chronic Illness</span>
                                 </label>
                                 <label class="flex items-center">
                                     <input type="radio" name="disability_cause_specific" value="cerebral_palsy_acquired" class="mr-2">
                                     <span class="text-xs font-medium text-gray-700">Cerebral Palsy</span>
                                 </label>
                                 <label class="flex items-center">
                                     <input type="radio" name="disability_cause_specific" value="injury" class="mr-2">
                                     <span class="text-xs font-medium text-gray-700">Injury</span>
                                 </label>
                                 <label class="flex items-center">
                                     <input type="radio" name="disability_cause_specific" value="other_acquired" class="mr-2" onchange="if(this.checked) { document.getElementById('other_acquired_div').style.display = 'block'; } else { document.getElementById('other_acquired_div').style.display = 'none'; }">
                                     <span class="text-xs font-medium text-gray-700">Other: Specify</span>
                                 </label>
                                 <div id="other_acquired_div" class="ml-6 hidden">
                                     <input type="text" name="other_acquired_specify" placeholder="Please specify" 
                                         class="w-full px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-xs">
                                 </div>
                        </div>
                        </div>
                    </div>
                    
                                         
                </div>

                <!-- Residence Address -->
                <div class="border-2 border-gray-300 rounded-lg p-4 bg-gray-50">
                    <h2 class="text-sm font-bold text-gray-800 mb-3" style="font-size: 18px !important;">RESIDENCE ADDRESS</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="house_street" class="block text-xs font-medium text-gray-700 mb-2">*HOUSE NO. AND STREET:</label>
                            <input type="text" id="house_street" name="house_street" required
                                class="w-full px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-xs">
                        </div>
                        <div>
                            <label for="barangay" class="block text-xs font-medium text-gray-700 mb-2">*BARANGAY:</label>
                            <input type="text" id="barangay" name="barangay" required
                                class="w-full px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-xs">
                        </div>
                        <div>
                            <label for="municipality" class="block text-xs font-medium text-gray-700 mb-2">MUNICIPALITY:</label>
                            <input type="text" id="municipality" name="municipality" value="CALOOCAN CITY" readonly
                                class="w-full px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-xs">
                        </div>
                        <div>
                            <label for="province" class="block text-xs font-medium text-gray-700 mb-2">PROVINCE:</label>
                            <input type="text" id="province" name="province" value="METRO MANILA, 3RD DISTRICT" readonly
                                class="w-full px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-xs">
                        </div>
                        <div>
                            <label for="region" class="block text-xs font-medium text-gray-700 mb-2">REGION:</label>
                            <input type="text" id="region" name="region" value="NATIONAL CAPITAL REGION" readonly
                                class="w-full px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-xs">
                        </div>
                        </div>
                    </div>
                    
                <!-- Contact Details -->
                <div class="border-2 border-gray-300 rounded-lg p-4 bg-gray-50">
                    <h2 class="text-sm font-bold text-gray-800 mb-3" style="font-size: 18px !important;">CONTACT DETAILS</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="landline" class="block text-xs font-medium text-gray-700 mb-2">LANDLINE NO.:</label>
                            <input type="tel" id="landline" name="landline"
                                class="w-full px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-xs">
                        </div>
                        <div>
                            <label for="mobile" class="block text-xs font-medium text-gray-700 mb-2">*MOBILE NO.:</label>
                            <input type="tel" id="mobile" name="mobile" required
                                class="w-full px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-xs">
                        </div>
                        <div>
                            <label for="email" class="block text-xs font-medium text-gray-700 mb-2">EMAIL ADDRESS:</label>
                            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($resident['email'] ?? ''); ?>" readonly
                                class="w-full px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-xs">
                        </div>
                    </div>
                </div>

                <!-- Educational Attainment -->
                <div class="border-2 border-gray-300 rounded-lg p-4 bg-gray-50">
                    <h2 class="text-sm font-bold text-gray-800 mb-3" style="font-size: 18px !important;">*EDUCATIONAL ATTAINMENT:</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <label class="flex items-center">
                            <input type="radio" name="educational_attainment" value="none" class="mr-2" required>
                            <span class="text-xs font-medium text-gray-700">None</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="educational_attainment" value="kindergarten" class="mr-2">
                            <span class="text-xs font-medium text-gray-700">Kindergarten</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="educational_attainment" value="elementary" class="mr-2">
                            <span class="text-xs font-medium text-gray-700">Elementary</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="educational_attainment" value="junior_high" class="mr-2">
                            <span class="text-xs font-medium text-gray-700">Junior High School</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="educational_attainment" value="senior_high" class="mr-2">
                            <span class="text-xs font-medium text-gray-700">Senior High School</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="educational_attainment" value="college" class="mr-2">
                            <span class="text-xs font-medium text-gray-700">College</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="educational_attainment" value="vocational" class="mr-2">
                            <span class="text-xs font-medium text-gray-700">Vocational</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="educational_attainment" value="post_graduate" class="mr-2">
                            <span class="text-xs font-medium text-gray-700">Post Graduate</span>
                        </label>
                        </div>
                    </div>
                    
                <!-- Status of Employment -->
                <div class="border-2 border-gray-300 rounded-lg p-4 bg-gray-50">
                    <h2 class="text-sm font-bold text-gray-800 mb-3" style="font-size: 18px !important;">*STATUS OF EMPLOYMENT:</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <label class="flex items-center">
                            <input type="radio" name="employment_status" value="employed" class="mr-2" required>
                            <span class="text-xs font-medium text-gray-700">Employed</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="employment_status" value="unemployed" class="mr-2">
                            <span class="text-xs font-medium text-gray-700">Unemployed</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="employment_status" value="self_employed" class="mr-2">
                            <span class="text-xs font-medium text-gray-700">Self-Employed</span>
                        </label>
                    </div>
                </div>

                <!-- Category of Employment -->
                <div class="border-2 border-gray-300 rounded-lg p-4 bg-gray-50">
                    <h2 class="text-sm font-bold text-gray-800 mb-3" style="font-size: 18px !important;">*CATEGORY OF EMPLOYMENT:</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <label class="flex items-center">
                            <input type="radio" name="employment_category" value="government" class="mr-2" required>
                            <span class="text-xs font-medium text-gray-700">Government</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="employment_category" value="private" class="mr-2">
                            <span class="text-xs font-medium text-gray-700">Private</span>
                        </label>
                    </div>
                </div>

                <!-- Types of Employment -->
                <div class="border-2 border-gray-300 rounded-lg p-4 bg-gray-50">
                    <h2 class="text-sm font-bold text-gray-800 mb-3" style="font-size: 18px !important;">*TYPES OF EMPLOYMENT:</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <label class="flex items-center">
                            <input type="radio" name="employment_type" value="permanent_regular" class="mr-2" required>
                            <span class="text-xs font-medium text-gray-700">Permanent / Regular</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="employment_type" value="seasonal" class="mr-2">
                            <span class="text-xs font-medium text-gray-700">Seasonal</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="employment_type" value="casual" class="mr-2">
                            <span class="text-xs font-medium text-gray-700">Casual</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="employment_type" value="emergency" class="mr-2">
                            <span class="text-xs font-medium text-gray-700">Emergency</span>
                        </label>
                    </div>
                        </div>

                <!-- Occupation -->
                <div class="border-2 border-gray-300 rounded-lg p-4 bg-gray-50">
                    <h2 class="text-sm font-bold text-gray-800 mb-3" style="font-size: 18px !important;">*OCCUPATION:</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <label class="flex items-center">
                            <input type="radio" name="occupation" value="managers" class="mr-2" required>
                            <span class="text-xs font-medium text-gray-700">Managers</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="occupation" value="professionals" class="mr-2">
                            <span class="text-xs font-medium text-gray-700">Professionals</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="occupation" value="technician_associate" class="mr-2">
                            <span class="text-xs font-medium text-gray-700">Technician and Associate</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="occupation" value="clerical_support" class="mr-2">
                            <span class="text-xs font-medium text-gray-700">Clerical Support Workers</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="occupation" value="service_sales" class="mr-2">
                            <span class="text-xs font-medium text-gray-700">Service and Sales Workers</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="occupation" value="skilled_agricultural" class="mr-2">
                            <span class="text-xs font-medium text-gray-700">Skilled Agricultural, Forestry and Fishery Workers</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="occupation" value="craft_related" class="mr-2">
                            <span class="text-xs font-medium text-gray-700">Craft and Related Trade Workers</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="occupation" value="plant_machine" class="mr-2">
                            <span class="text-xs font-medium text-gray-700">Plant and Machine Operators and Assemblers</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="occupation" value="elementary_occupations" class="mr-2">
                            <span class="text-xs font-medium text-gray-700">Elementary Occupations</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="occupation" value="armed_forces" class="mr-2">
                            <span class="text-xs font-medium text-gray-700">Armed Forces Occupation</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="occupation" value="others" class="mr-2">
                            <span class="text-xs font-medium text-gray-700">Others, Specify:</span>
                            <input type="text" name="occupation_others" class="ml-2 px-2 py-1 border border-gray-300 rounded text-xs" placeholder="_______________">
                        </label>
                        </div>
                    </div>
                    






                <!-- Emergency Contact -->
                <div class="border-2 border-gray-300 rounded-lg p-4 bg-gray-50">
                    <h2 class="text-sm font-bold text-gray-800 mb-3" style="font-size: 18px !important;">CONTACT PERSON</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="emergency_contact_name" class="block text-xs font-medium text-gray-700 mb-2">*IN CASE OF EMERGENCY:</label>
                            <input type="text" id="emergency_contact_name" name="emergency_contact_name" required
                                class="w-full px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-xs">
                        </div>
                        <div>
                            <label for="emergency_contact_number" class="block text-xs font-medium text-gray-700 mb-2">*CONTACT NUMBER:</label>
                            <input type="tel" id="emergency_contact_number" name="emergency_contact_number" required
                                class="w-full px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-xs">
                        </div>
                    </div>
                </div>



                <!-- Form Actions -->
                <div class="flex justify-end space-x-3">
                    <a href="index.php" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors duration-200">
                        Cancel
                    </a>
                    <button type="button" id="next-page-btn" class="bg-primary hover:bg-secondary text-white px-6 py-2 rounded-md font-medium transition-colors duration-200" onclick="handleNextPage()">
                        Next Page
                    </button>
                </div>
            </form>
            
            <!-- Document Upload Page (Hidden by default) -->
            <div id="document-upload-page" class="hidden">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-green-600 mb-2">Document Upload</h2>
                    <p class="text-gray-600">Please upload the required documents based on your application type.</p>
                </div>
                
                <!-- Application Type Display -->
                <div class="border-2 border-gray-300 rounded-lg p-4 bg-gray-50 mb-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-3">Application Type: <span id="selected-application-type" class="text-green-600"></span></h3>
                </div>
                
                <!-- Requirements Display -->
                <div class="border-2 border-gray-300 rounded-lg p-4 bg-gray-50 mb-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-3">Required Documents:</h3>
                    <div id="requirements-list" class="space-y-3">
                        <!-- Requirements will be populated by JavaScript -->
                    </div>
                </div>
                
                <!-- Document Upload Form -->
                <form method="POST" enctype="multipart/form-data" class="space-y-4">
                    <input type="hidden" name="form_data" id="form-data-input">
                    
                    <!-- Common Documents -->
                    <div class="border-2 border-gray-300 rounded-lg p-4 bg-gray-50">
                        <h3 class="text-lg font-bold text-gray-800 mb-3">Common Documents (All Application Types)</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">1. 2x2 Picture (2pc)</label>
                                <input type="file" name="picture_2x2[]" multiple accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                <p class="text-xs text-gray-500 mt-1">Upload 2 pieces of 2x2 photos</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">2. 1x1 Picture (1pc)</label>
                                <input type="file" name="picture_1x1" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                <p class="text-xs text-gray-500 mt-1">Upload 1 piece of 1x1 photo</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">3. Barangay Certificate Proof of Residency</label>
                                <input type="file" name="barangay_certificate" accept=".pdf,.jpg,.jpeg,.png" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                <p class="text-xs text-gray-500 mt-1">Original copy with Barangay Contact No.</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">4. Certificate of Disability</label>
                                <input type="file" name="disability_certificate[]" multiple accept=".pdf,.jpg,.jpeg,.png" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                <p class="text-xs text-gray-500 mt-1">Two copies (1 Original & 1 Xerox)</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">5. Valid ID / Birth Certificate</label>
                                <input type="file" name="valid_id" accept=".pdf,.jpg,.jpeg,.png" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                <p class="text-xs text-gray-500 mt-1">Xerox copy of any valid ID or birth certificate</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Conditional Documents -->
                    <div id="conditional-documents" class="border-2 border-gray-300 rounded-lg p-4 bg-gray-50">
                        <h3 class="text-lg font-bold text-gray-800 mb-3">Additional Documents</h3>
                        <div id="additional-docs-list" class="space-y-4">
                            <!-- Additional documents will be populated by JavaScript -->
                        </div>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="flex justify-between space-x-3">
                        <button type="button" id="back-btn" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors duration-200" onclick="handleBackToForm()">
                            Back to Form
                        </button>
                        <button type="submit" class="bg-primary hover:bg-secondary text-white px-6 py-2 rounded-md font-medium transition-colors duration-200">
                            Submit Application
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Column: Tabbed Interface -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-md p-6">
            <!-- Tab Buttons -->
            <div class="flex space-x-2 mb-4">
                <button id="tab-requirements" class="tab-button bg-green-200 text-gray-800 px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200 cursor-pointer hover:bg-green-300" onclick="document.getElementById('requirements-content').classList.remove('hidden'); document.getElementById('disability-types-content').classList.add('hidden'); document.getElementById('medical-cert-content').classList.add('hidden'); this.classList.remove('bg-gray-200', 'text-gray-700'); this.classList.add('bg-green-200', 'text-gray-800'); document.getElementById('tab-disability-types').classList.remove('bg-green-200', 'text-gray-800'); document.getElementById('tab-disability-types').classList.add('bg-gray-200', 'text-gray-700'); document.getElementById('tab-medical-cert').classList.remove('bg-green-200', 'text-gray-800'); document.getElementById('tab-medical-cert').classList.add('bg-gray-200', 'text-gray-700');">
                    REQUIRED DOCUMENTS
                </button>
                <button id="tab-disability-types" class="tab-button bg-gray-200 text-gray-700 px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200 cursor-pointer hover:bg-gray-300" onclick="document.getElementById('disability-types-content').classList.remove('hidden'); document.getElementById('requirements-content').classList.add('hidden'); document.getElementById('medical-cert-content').classList.add('hidden'); this.classList.remove('bg-gray-200', 'text-gray-700'); this.classList.add('bg-green-200', 'text-gray-800'); document.getElementById('tab-requirements').classList.remove('bg-green-200', 'text-gray-800'); document.getElementById('tab-requirements').classList.add('bg-gray-200', 'text-gray-700'); document.getElementById('tab-medical-cert').classList.remove('bg-green-200', 'text-gray-800'); document.getElementById('tab-medical-cert').classList.add('bg-gray-200', 'text-gray-700');">
                    TYPE OF DISABILITY
                </button>
                <button id="tab-medical-cert" class="tab-button bg-gray-200 text-gray-700 px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200 cursor-pointer hover:bg-gray-300" onclick="document.getElementById('medical-cert-content').classList.remove('hidden'); document.getElementById('requirements-content').classList.add('hidden'); document.getElementById('disability-types-content').classList.add('hidden'); this.classList.remove('bg-gray-200', 'text-gray-700'); this.classList.add('bg-green-200', 'text-gray-800'); document.getElementById('tab-requirements').classList.remove('bg-green-200', 'text-gray-800'); document.getElementById('tab-requirements').classList.add('bg-gray-200', 'text-gray-700'); document.getElementById('tab-disability-types').classList.remove('bg-green-200', 'text-gray-800'); document.getElementById('tab-disability-types').classList.add('bg-gray-200', 'text-gray-700');">
                    Medical Certificate Sample
                </button>
            </div>

            <!-- Tab Content -->
            <div id="tab-content">
                <!-- Requirements Documents Tab -->
                <div id="requirements-content" class="tab-content active">
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse border border-gray-300 text-xs">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border border-gray-300 px-2 py-2 text-center font-bold">NEW</th>
                                    <th class="border border-gray-300 px-2 py-2 text-center font-bold">RENEWAL</th>
                                    <th class="border border-gray-300 px-2 py-2 text-center font-bold">IF REPRESENTATIVE</th>
                                    <th class="border border-gray-300 px-2 py-2 text-center font-bold">IF LOST</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="border border-gray-300 px-2 py-2 align-top">1. 2x2 Picture (2pc)</td>
                                    <td class="border border-gray-300 px-2 py-2 align-top">1. 2X2 Picture (2pc)</td>
                                    <td class="border border-gray-300 px-2 py-2 align-top">1. 2X2 Picture (2pc)</td>
                                    <td class="border border-gray-300 px-2 py-2 align-top">1. 2X2 Picture (2pc)</td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-300 px-2 py-2 align-top">2. 1x1 Picture (1pc)</td>
                                    <td class="border border-gray-300 px-2 py-2 align-top">2. 1X1 Picture (1pc)</td>
                                    <td class="border border-gray-300 px-2 py-2 align-top">2. 1X1 Picture (1pc)</td>
                                    <td class="border border-gray-300 px-2 py-2 align-top">2. 1X1 Picture (1pc)</td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-300 px-2 py-2 align-top">3. Barangay Certificate Proof of Residency: FOR PWD PURPOSES with Barangay Contact No. (Original Copy)</td>
                                    <td class="border border-gray-300 px-2 py-2 align-top">3. Barangay Certificate Proof of Residency: FOR PWD PURPOSES with Barangay Contact No. (Original Copy)</td>
                                    <td class="border border-gray-300 px-2 py-2 align-top">3. Barangay Certificate Proof of Residency: FOR PWD PURPOSES With Barangay Contact No. INDICATE REPRESENTATIVE'S NAME (Original Copy)</td>
                                    <td class="border border-gray-300 px-2 py-2 align-top">3. Barangay Certificate Proof of Residency: FOR PWD PURPOSES with Barangay Contact No. (Original Copy)</td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-300 px-2 py-2 align-top">4. Two copies of Latest Certificate of Disability (1 Original Copy & 1 Xerox Copy)</td>
                                    <td class="border border-gray-300 px-2 py-2 align-top">4. Two copies of Latest Certificate of Disability (1 Original Copy & 1 Xerox Copy)</td>
                                    <td class="border border-gray-300 px-2 py-2 align-top">4. Two copies of Latest Certificate of Disability (1 Original Copy & 1 Xerox Copy)</td>
                                    <td class="border border-gray-300 px-2 py-2 align-top">4. Two copies of Latest Certificate of Disability (1 Original Copy & 1 Xerox Copy)</td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-300 px-2 py-2 align-top">5. Xerox of Any Valid ID / Birth Certificate</td>
                                    <td class="border border-gray-300 px-2 py-2 align-top">5. Xerox of Any Valid ID / Birth Certificate</td>
                                    <td class="border border-gray-300 px-2 py-2 align-top">5. Xerox of Any Valid ID / Birth Certificate</td>
                                    <td class="border border-gray-300 px-2 py-2 align-top">5. Xerox of Any Valid ID / Birth Certificate</td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-300 px-2 py-2 align-top"></td>
                                    <td class="border border-gray-300 px-2 py-2 align-top">6. Surrender OLD PWD ID and BOOKLET</td>
                                    <td class="border border-gray-300 px-2 py-2 align-top">6. Letter of Authorization</td>
                                    <td class="border border-gray-300 px-2 py-2 align-top">6. Affidavit of Loss</td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-300 px-2 py-2 align-top"></td>
                                    <td class="border border-gray-300 px-2 py-2 align-top"></td>
                                    <td class="border border-gray-300 px-2 py-2 align-top">7. ID of Representative</td>
                                    <td class="border border-gray-300 px-2 py-2 align-top"></td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-300 px-2 py-2 align-top"></td>
                                    <td class="border border-gray-300 px-2 py-2 align-top"></td>
                                    <td class="border border-gray-300 px-2 py-2 align-top">8. Picture of Applicant holding dated Newspaper or Calendar together with Representative</td>
                                    <td class="border border-gray-300 px-2 py-2 align-top"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Type of Disability Tab -->
                <div id="disability-types-content" class="tab-content hidden">
                    <div class="space-y-4">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                            <p class="text-sm text-blue-800 font-medium">
                                <strong>Instructions:</strong> Check the appropriate box/es for the Type/s of Disability sustained by the Person with Disability. One or more items can be checked for this field.
                            </p>
                        </div>
                        
                        <div class="space-y-4 text-sm">
                            <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                <h4 class="font-semibold text-gray-800 mb-3">1. Deaf or Hard of Hearing</h4>
                                <p class="text-gray-700 leading-relaxed">
                                    Refers to people with hearing loss, implies little or no hearing/ranging from mild to severe. Hearing loss, also known as hearing impairment means the complete or partial loss of the ability to hear from one or both ears with 26 dB or greater hearing threshold, averaged at frequencies' 0.5, 1, 2, 4 kilohertz.
                                </p>
                            </div>
                            
                            <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                <h4 class="font-semibold text-gray-800 mb-3">2. Intellectual Disability</h4>
                                <p class="text-gray-700 leading-relaxed">
                                    A significantly reduced ability to understand new or complex information and to learn and apply new skills.
                                </p>
                            </div>
                            
                            <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                <h4 class="font-semibold text-gray-800 mb-3">3. Learning Disability</h4>
                                <p class="text-gray-700 leading-relaxed">
                                    Persons who, although normal in sensory, emotional and intellectual abilities, exhibit disorders in perception, listening, thinking, reading, writing, spelling, and arithmetic.
                                </p>
                            </div>
                            
                            <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                <h4 class="font-semibold text-gray-800 mb-3">4. Mental Disability</h4>
                                <p class="text-gray-700 leading-relaxed">
                                    Disability resulting from organic brain syndrome and or mental illness (psychotic or non-psychotic disorder).
                                </p>
                            </div>
                            
                            <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                <h4 class="font-semibold text-gray-800 mb-3">5. Physical Disability</h4>
                                <p class="text-gray-700 leading-relaxed">
                                    Is a restriction of ability due to any physical impairment that affects a person's mobility, function, endurance or stamina to sustain prolonged physical ability, dexterity to perform tasks skillfully and quality of life. Causes may be hereditary or acquired from trauma, infection, surgical or medical condition and include the following disorders, namely: (1) Musculoskeletal or orthopedic disorders (2) Neurological disorders (3) Cardiopulmonary disorders (4) Pediatric and congenital disorders.
                                </p>
                            </div>
                            
                            <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                <h4 class="font-semibold text-gray-800 mb-3">6. Psychosocial Disability</h4>
                                <p class="text-gray-700 leading-relaxed">
                                    Any acquired behavioral, cognitive, emotional or social impairment that limits one or more activities necessary to effective interpersonal transactions and other civilizing process or activities to daily living such as but not limited to deviancy or anti-social behavior.
                                </p>
                            </div>
                            
                            <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                <h4 class="font-semibold text-gray-800 mb-3">7. Speech and Language Impairment</h4>
                                <p class="text-gray-700 leading-relaxed">
                                    One or more speech/language disorders of voice, articulation, rhythm and/or the receptive and expressive processes of language.
                                </p>
                            </div>
                            
                            <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                <h4 class="font-semibold text-gray-800 mb-3">8. Visual Disability</h4>
                                <p class="text-gray-700 leading-relaxed">
                                    A person with visual disability (Impairment) is one who has impairment of visual functioning even after treatment and/or standard refractive correction, and has visual acuity in the better eye of less than (6/18 for low vision and 3/60 for blind), or a visual field of less than10 degrees from the point of fixation. A certain level of visual impairment is defined as legal blindness. One is legally blind when your best corrected central visual acuity in your better eye is 6/60 on worse or your side vision is 20 degrees or less in the better eye.
                                </p>
                            </div>
                            
                            <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                <h4 class="font-semibold text-gray-800 mb-3">9. Cancer (RA 11215)</h4>
                                <p class="text-gray-700 leading-relaxed">
                                    Cancer refers to a genetic term for a large group of diseases that can affect any part of the body. Other terms used are malignant tumors and neoplasms. One defining feature of cancer is the rapid creation of abnormal cells that grow beyond their usual boundaries, and which can then invade adjoining parts of the body and spread to other organs.
                                </p>
                            </div>
                            
                            <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                <h4 class="font-semibold text-gray-800 mb-3">10. Rare Disease (RA 10747)</h4>
                                <p class="text-gray-700 leading-relaxed">
                                    Refers to disorders such as inherited metabolic disorders and other diseases with similar rare occurrence as recognized by the DOH upon recommendation of the NIH but excluding catastrophic (i.e., life threatening, seriously debilitating, or serious and chronic) forms of more frequently occurring diseases.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Medical Certificate Sample Tab -->
                <div id="medical-cert-content" class="tab-content hidden">
                    <div class="space-y-4">
                        <h3 class="text-lg font-bold text-gray-800 mb-3">Medical Certificate Sample</h3>
                        <div class="border-2 border-gray-300 rounded-lg p-4 bg-gray-50">
                            <div class="text-center mb-4">
                                <p class="text-xs text-gray-600 mb-2">THIS IS ONLY A GUIDE / SAMPLE</p>
                                <h4 class="text-lg font-bold text-gray-800">CERTIFICATION ON DISABILITY</h4>
                                <p class="text-sm text-gray-600">(In Physician's Prescription Pad)</p>
                                <p class="text-sm text-gray-600 mt-2">PLEASE PUT IN LOGO OF MEDICAL HOSPITAL / CLINIC / OFFICE</p>
                            </div>
                            
                            <div class="space-y-3 text-sm leading-relaxed">
                                <p>This is to certify that <strong>_____(Patient's Name)_____</strong> resident of <strong>_____(Caloocan Residence)_____</strong>, had voluntarily submitted herself/himself to this facility/clinic/office to with regard to the nature of disability.</p>
                                
                                <p>Based on the personal interview and medical assessment conducted by herein physician, the patient has <strong>_____(Diagnose)_____</strong> that resulted to:</p>
                                
                                <div class="grid grid-cols-2 gap-2 ml-4 text-sm">
                                    <div class="flex items-center">
                                        <span class="mr-2">☐</span>
                                        <span>Deaf / Hard of Hearing</span>
                                    </div>
                                    <div class="flex items-center">
                                        <span class="mr-2">☐</span>
                                        <span>Psychosocial Disability</span>
                                    </div>
                                    <div class="flex items-center">
                                        <span class="mr-2">☐</span>
                                        <span>Intellectual Disability</span>
                                    </div>
                                    <div class="flex items-center">
                                        <span class="mr-2">☐</span>
                                        <span>Speech and Language Impairment</span>
                                    </div>
                                    <div class="flex items-center">
                                        <span class="mr-2">☐</span>
                                        <span>Mental Disability</span>
                                    </div>
                                    <div class="flex items-center">
                                        <span class="mr-2">☐</span>
                                        <span>Visual Disability</span>
                                    </div>
                                    <div class="flex items-center">
                                        <span class="mr-2">☐</span>
                                        <span>Physical Disability (Orthopedic)</span>
                                    </div>
                                    <div class="flex items-center">
                                        <span class="mr-2">☐</span>
                                        <span>Cancer (RA 11215)</span>
                                    </div>
                                    <div class="flex items-center">
                                        <span class="mr-2">☐</span>
                                        <span>Learning Disability</span>
                                    </div>
                                    <div class="flex items-center">
                                        <span class="mr-2">☐</span>
                                        <span>Rare Disease (RA 10747)</span>
                                    </div>
                                </div>
                                
                                <p>As classified by the Department of Health Administrative Order No. 2009-011.</p>
                                
                                <p>This Certification is issued on <strong>__________</strong> at <strong>___________</strong> in Compliance with the requirement in the issuance of ID for the twenty percent (20%) discount for Person with Disabilities mandated by Republic Act. No 9442 or Magna Carta for Person with Disabilities.</p>
                                
                                <div class="mt-6 grid grid-cols-2 gap-8">
                                    <div>
                                        <p><strong>Signed By:</strong></p>
                                        <p class="mt-2"><strong>Name of Physician:</strong></p>
                                    </div>
                                    <div>
                                        <p><strong>Contact No.:</strong></p>
                                        <p class="mt-2"><strong>License No.:</strong></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                            <h4 class="font-semibold text-blue-800 mb-2">Important Notes:</h4>
                            <ul class="text-sm text-blue-700 space-y-1">
                                <li>• This certificate must be signed by a licensed physician</li>
                                <li>• The physician must indicate their PRC license number</li>
                                <li>• The certificate should be notarized</li>
                                <li>• Submit both original and photocopy</li>
                                <li>• Must be on physician's prescription pad</li>
                                <li>• Hospital/clinic logo should be included</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <!-- Important Notes -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-6">
            <h3 class="text-sm font-medium text-blue-800 mb-2">Important Notes:</h3>
            <ul class="text-sm text-blue-700 space-y-1">
                <li>• All fields marked with * are required</li>
                <li>• Please ensure all information is accurate and complete</li>
                <li>• You will be notified once your application is processed</li>
                <li>• You may be required to submit additional documents during processing</li>
            </ul>
        </div>
    </div>

    <script>
        // Simple Next Page function that will definitely work
        function handleNextPage() {
            console.log('handleNextPage function called!');
            alert('Next Page button is working!');
            
            // Get the form and document upload page
            const formPage = document.querySelector('#pwd-form').parentElement;
            const documentUploadPage = document.getElementById('document-upload-page');
            
            if (!formPage || !documentUploadPage) {
                alert('Error: Could not find form elements');
                return;
            }
            
            // Check if application type is selected
            const applicationTypeRadio = document.querySelector('input[name="application_type"]:checked');
            if (!applicationTypeRadio) {
                alert('Please select an application type first!');
                return;
            }
            
            // Simple validation - just check a few key fields
            const requiredFields = ['last_name', 'first_name', 'birth_date', 'mobile'];
            let missingFields = [];
            
            requiredFields.forEach(fieldName => {
                const field = document.getElementById(fieldName);
                if (field && !field.value.trim()) {
                    missingFields.push(fieldName);
                    field.style.borderColor = 'red';
                }
            });
            
            if (missingFields.length > 0) {
                alert('Please fill in these required fields: ' + missingFields.join(', '));
                return;
            }
            
            // If validation passes, show the document upload page
            formPage.style.display = 'none';
            documentUploadPage.style.display = 'block';
            
            // Update the application type display
            const selectedApplicationType = document.getElementById('selected-application-type');
            if (selectedApplicationType) {
                selectedApplicationType.textContent = applicationTypeRadio.nextElementSibling.textContent;
            }
            
            console.log('Successfully moved to document upload page!');
        }
        
        // Simple Back to Form function
        function handleBackToForm() {
            console.log('handleBackToForm function called!');
            
            const formPage = document.querySelector('#pwd-form').parentElement;
            const documentUploadPage = document.getElementById('document-upload-page');
            
            if (formPage && documentUploadPage) {
                documentUploadPage.style.display = 'none';
                formPage.style.display = 'block';
                console.log('Successfully moved back to form!');
            }
        }
        
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



                                                                                                                                                                                                                                                                                               // Form functionality
          document.addEventListener('DOMContentLoaded', function() {
              console.log('DOM Content Loaded - JavaScript is running!');
               // Handle disability cause dropdown
               const disabilityCauseSelect = document.getElementById('disability_cause');
               const congenitalOptions = document.getElementById('congenital_options');
               const acquiredOptions = document.getElementById('acquired_options');
               
               console.log('Elements found:', {
                   disabilityCauseSelect: !!disabilityCauseSelect,
                   congenitalOptions: !!congenitalOptions,
                   acquiredOptions: !!acquiredOptions
               });
               
               if (disabilityCauseSelect) {
                   disabilityCauseSelect.addEventListener('change', function() {
                       console.log('Dropdown changed to:', this.value);
                       
                       // Hide both option sections first
                       if (congenitalOptions) {
                           congenitalOptions.classList.add('hidden');
                           congenitalOptions.style.display = 'none';
                       }
                       if (acquiredOptions) {
                           acquiredOptions.classList.add('hidden');
                           acquiredOptions.style.display = 'none';
                       }
                       
                       // Show appropriate options based on selection
                       if (this.value === 'congenital') {
                           console.log('Showing congenital options');
                           if (congenitalOptions) {
                               congenitalOptions.classList.remove('hidden');
                               congenitalOptions.style.display = 'block';
                           }
                       } else if (this.value === 'acquired') {
                           console.log('Showing acquired options');
                           if (acquiredOptions) {
                               acquiredOptions.classList.remove('hidden');
                               acquiredOptions.style.display = 'block';
                           }
                       }
                   });
               }
               
               // Handle "Other: Specify" options
               const otherCongenitalRadio = document.querySelector('input[name="disability_cause_specific"][value="other_congenital"]');
               const otherAcquiredRadio = document.querySelector('input[name="disability_cause_specific"][value="other_acquired"]');
               const otherCongenitalDiv = document.getElementById('other_congenital_div');
               const otherAcquiredDiv = document.getElementById('other_acquired_div');
               
               if (otherCongenitalRadio && otherCongenitalDiv) {
                   otherCongenitalRadio.addEventListener('change', function() {
                       if (this.checked) {
                           otherCongenitalDiv.classList.remove('hidden');
                       } else {
                           otherCongenitalDiv.classList.add('hidden');
                       }
                   });
               }
               
               if (otherAcquiredRadio && otherAcquiredDiv) {
                   otherAcquiredRadio.addEventListener('change', function() {
                       if (this.checked) {
                           otherAcquiredDiv.classList.remove('hidden');
                       } else {
                           otherAcquiredDiv.classList.add('hidden');
                       }
                   });
               }

                          // Auto-calculate age from birth date
             const birthDateInput = document.getElementById('birth_date');
             const ageInput = document.getElementById('age');

             if (birthDateInput && ageInput) {
                 birthDateInput.addEventListener('change', function() {
                     const birthDate = new Date(this.value);
                     const today = new Date();
                     const age = today.getFullYear() - birthDate.getFullYear();
                     const monthDiff = today.getMonth() - birthDate.getMonth();
                     
                     if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                         age--;
                     }
                     
                     ageInput.value = age;
                 });
             }

                           // Handle "No Middle Name" checkbox
              const noMiddleNameCheckbox = document.getElementById('no_middle_name');
              const middleNameInput = document.getElementById('middle_name');

              if (noMiddleNameCheckbox && middleNameInput) {
                  noMiddleNameCheckbox.addEventListener('change', function() {
                      if (this.checked) {
                          middleNameInput.value = '';
                          middleNameInput.disabled = true;
                          middleNameInput.classList.add('bg-gray-100');
                      } else {
                          middleNameInput.disabled = false;
                          middleNameInput.classList.remove('bg-gray-100');
                      }
                  });
              }

              // Validate birth date to ensure it's not in the future
              const birthDateInput = document.getElementById('birth_date');
              if (birthDateInput) {
                  birthDateInput.addEventListener('change', function() {
                      const selectedDate = new Date(this.value);
                      const today = new Date();
                      today.setHours(23, 59, 59, 999); // Set to end of today
                      
                      if (selectedDate > today) {
                          alert('Birth date cannot be in the future. Please select a valid date.');
                          this.value = '';
                          return false;
                      }
                  });
              }
              
              // Multi-page form functionality
              console.log('Setting up multi-page form functionality...');
              const backBtn = document.getElementById('back-btn');
              const formPage = document.querySelector('#pwd-form').parentElement;
              const documentUploadPage = document.getElementById('document-upload-page');
              const selectedApplicationType = document.getElementById('selected-application-type');
              const requirementsList = document.getElementById('requirements-list');
              const additionalDocsList = document.getElementById('additional-docs-list');
              
              console.log('Elements found:', {
                  nextPageBtn: !!nextPageBtn,
                  backBtn: !!backBtn,
                  formPage: !!formPage,
                  documentUploadPage: !!documentUploadPage,
                  selectedApplicationType: !!selectedApplicationType,
                  requirementsList: !!requirementsList,
                  additionalDocsList: !!additionalDocsList
              });
              
              // Requirements data based on application type
              const requirementsData = {
                  new: [
                      "1. 2x2 Picture (2pc)",
                      "2. 1x1 Picture (1pc)",
                      "3. Barangay Certificate Proof of Residency: FOR PWD PURPOSES with Barangay Contact No. (Original Copy)",
                      "4. Two copies of Latest Certificate of Disability (1 Original Copy & 1 Xerox Copy)",
                      "5. Xerox of Any Valid ID / Birth Certificate"
                  ],
                  renewal: [
                      "1. 2X2 Picture (2pc)",
                      "2. 1X1 Picture (1pc)",
                      "3. Barangay Certificate Proof of Residency: FOR PWD PURPOSES with Barangay Contact No. (Original Copy)",
                      "4. Two copies of Latest Certificate of Disability (1 Original Copy & 1 Xerox Copy)",
                      "5. Xerox of Any Valid ID / Birth Certificate",
                      "6. Surrender OLD PWD ID and BOOKLET"
                  ],
                  representative: [
                      "1. 2X2 Picture (2pc)",
                      "2. 1X1 Picture (1pc)",
                      "3. Barangay Certificate Proof of Residency: FOR PWD PURPOSES With Barangay Contact No. INDICATE REPRESENTATIVE'S NAME (Original Copy)",
                      "4. Two copies of Latest Certificate of Disability (1 Original Copy & 1 Xerox Copy)",
                      "5. Xerox of Any Valid ID / Birth Certificate",
                      "6. Letter of Authorization",
                      "7. ID of Representative",
                      "8. Picture of Applicant holding dated Newspaper or Calendar together with Representative"
                  ],
                  lost_replacement: [
                      "1. 2X2 Picture (2pc)",
                      "2. 1X1 Picture (1pc)",
                      "3. Barangay Certificate Proof of Residency: FOR PWD PURPOSES with Barangay Contact No. (Original Copy)",
                      "4. Two copies of Latest Certificate of Disability (1 Original Copy & 1 Xerox Copy)",
                      "5. Xerox of Any Valid ID / Birth Certificate",
                      "6. Affidavit of Loss"
                  ]
              };
              
              // Additional documents data
              const additionalDocsData = {
                  new: [],
                  renewal: [
                      {
                          name: "old_pwd_id",
                          label: "6. Surrender OLD PWD ID and BOOKLET",
                          description: "Original PWD ID and booklet to be surrendered"
                      }
                  ],
                  representative: [
                      {
                          name: "letter_authorization",
                          label: "6. Letter of Authorization",
                          description: "Notarized letter authorizing representative"
                      },
                      {
                          name: "representative_id",
                          label: "7. ID of Representative",
                          description: "Valid ID of the representative"
                      },
                      {
                          name: "picture_with_representative",
                          label: "8. Picture of Applicant holding dated Newspaper or Calendar together with Representative",
                          description: "Photo showing applicant and representative with current date"
                      }
                  ],
                  lost_replacement: [
                      {
                          name: "affidavit_loss",
                          label: "6. Affidavit of Loss",
                          description: "Notarized affidavit stating the loss of PWD ID"
                      }
                  ]
              };
              
              // Next page button functionality
              console.log('Looking for next page button...');
              const nextPageBtn = document.getElementById('next-page-btn');
              console.log('Next page button element:', nextPageBtn);
              
              if (nextPageBtn) {
                  console.log('Next page button found, adding click listener...');
                  nextPageBtn.addEventListener('click', function(e) {
                      e.preventDefault(); // Prevent any default form submission
                      console.log('Next page button clicked!');
                      // Validate required fields
                      console.log('Validating required fields...');
                      
                      // Clear previous error styling
                      formPage.querySelectorAll('.border-red-500').forEach(field => {
                          field.classList.remove('border-red-500');
                      });
                      
                      // Get all required fields including radio buttons and selects
                      const requiredInputs = formPage.querySelectorAll('input[required]');
                      const requiredSelects = formPage.querySelectorAll('select[required]');
                      const requiredFields = [...requiredInputs, ...requiredSelects];
                      
                      console.log('Found required fields:', requiredFields.length);
                      let isValid = true;
                      let firstEmptyField = null;
                      
                      requiredFields.forEach(field => {
                          let fieldValue = '';
                          
                          if (field.type === 'radio') {
                              // For radio buttons, check if any in the group is selected
                              const radioGroup = formPage.querySelectorAll(`input[name="${field.name}"]`);
                              const isSelected = Array.from(radioGroup).some(radio => radio.checked);
                              fieldValue = isSelected ? 'selected' : '';
                              console.log('Radio group:', field.name, 'Selected:', isSelected);
                          } else {
                              fieldValue = field.value.trim();
                              console.log('Field:', field.name, 'Value:', fieldValue);
                          }
                          
                          if (!fieldValue) {
                              isValid = false;
                              field.classList.add('border-red-500');
                              
                              // Store the first empty field for scrolling
                              if (!firstEmptyField) {
                                  firstEmptyField = field;
                              }
                          } else {
                              field.classList.remove('border-red-500');
                          }
                      });
                      
                      if (!isValid) {
                          console.log('Validation failed');
                          console.log('First empty field:', firstEmptyField);
                          
                          // Scroll to the first empty field
                          if (firstEmptyField) {
                              // Scroll to the field with offset for header
                              const headerHeight = 80; // Approximate header height
                              const fieldPosition = firstEmptyField.getBoundingClientRect().top + window.pageYOffset - headerHeight;
                              
                              window.scrollTo({
                                  top: fieldPosition,
                                  behavior: 'smooth'
                              });
                              
                              // Add focus to the field
                              setTimeout(() => {
                                  firstEmptyField.focus();
                                  // Add a visual highlight effect
                                  firstEmptyField.classList.add('ring-2', 'ring-red-500', 'ring-opacity-50');
                                  
                                  // Remove highlight after 3 seconds
                                  setTimeout(() => {
                                      firstEmptyField.classList.remove('ring-2', 'ring-red-500', 'ring-opacity-50');
                                  }, 3000);
                              }, 500);
                          }
                          
                          alert('Please fill in all required fields marked with *. The form has been scrolled to the first missing field.');
                          return;
                      }
                      console.log('Validation passed');
                      
                      // Get selected application type
                      console.log('Checking application type...');
                      const applicationTypeRadio = document.querySelector('input[name="application_type"]:checked');
                      console.log('Application type radio found:', !!applicationTypeRadio);
                      if (!applicationTypeRadio) {
                          console.log('No application type selected');
                          alert('Please select an application type');
                          return;
                      }
                      
                      const applicationType = applicationTypeRadio.value;
                      const applicationTypeText = applicationTypeRadio.nextElementSibling.textContent;
                      console.log('Application type:', applicationType, 'Text:', applicationTypeText);
                      
                      // Update display
                      selectedApplicationType.textContent = applicationTypeText;
                      
                      // Populate requirements list
                      requirementsList.innerHTML = '';
                      requirementsData[applicationType].forEach(req => {
                          const reqItem = document.createElement('div');
                          reqItem.className = 'flex items-start space-x-2';
                          reqItem.innerHTML = `
                              <span class="text-green-600 font-bold">✓</span>
                              <span class="text-sm text-gray-700">${req}</span>
                          `;
                          requirementsList.appendChild(reqItem);
                      });
                      
                      // Populate additional documents
                      additionalDocsList.innerHTML = '';
                      additionalDocsData[applicationType].forEach(doc => {
                          const docItem = document.createElement('div');
                          docItem.innerHTML = `
                              <label class="block text-sm font-medium text-gray-700 mb-2">${doc.label}</label>
                              <input type="file" name="${doc.name}" accept=".pdf,.jpg,.jpeg,.png" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                              <p class="text-xs text-gray-500 mt-1">${doc.description}</p>
                          `;
                          additionalDocsList.appendChild(docItem);
                      });
                      
                      // Store form data
                      const formData = new FormData(document.getElementById('pwd-form'));
                      const formDataString = JSON.stringify(Object.fromEntries(formData));
                      document.getElementById('form-data-input').value = formDataString;
                      
                      // Show document upload page
                      console.log('Transitioning to document upload page...');
                      console.log('Form page element:', formPage);
                      console.log('Document upload page element:', documentUploadPage);
                      formPage.classList.add('hidden');
                      documentUploadPage.classList.remove('hidden');
                      console.log('Page transition completed');
                  });
              } else {
                  console.error('Next page button not found!');
                  // Try to find the button by different selectors
                  const buttonByClass = document.querySelector('.bg-primary');
                  const buttonByText = Array.from(document.querySelectorAll('button')).find(btn => btn.textContent.includes('Next Page'));
                  console.log('Alternative button search:', {
                      byClass: !!buttonByClass,
                      byText: !!buttonByText,
                      buttonText: buttonByText ? buttonByText.textContent : 'not found'
                  });
              }
              
              // Back button functionality
              if (backBtn) {
                  console.log('Back button found, adding click listener...');
                  backBtn.addEventListener('click', function() {
                      console.log('Back button clicked!');
                      documentUploadPage.classList.add('hidden');
                      formPage.classList.remove('hidden');
                      console.log('Back to form page');
                  });
              } else {
                  console.error('Back button not found!');
              }
        });
    </script>
</body>
</html>
