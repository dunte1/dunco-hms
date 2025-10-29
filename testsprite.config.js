module.exports = {
  // TestSprite Configuration for DuncoHMS
  projectName: "DuncoHMS",
  projectType: "Laravel",
  framework: "PHP",
  
  // Test Configuration
  testSuites: [
    {
      name: "Authentication Tests",
      path: "tests/Feature/Auth",
      description: "Test user authentication, registration, and password management"
    },
    {
      name: "Patient Management Tests", 
      path: "tests/Feature/Patients",
      description: "Test patient CRUD operations, medical history, and case management"
    },
    {
      name: "Doctor Management Tests",
      path: "tests/Feature/Doctors", 
      description: "Test doctor profiles, departments, and scheduling"
    },
    {
      name: "Appointment Tests",
      path: "tests/Feature/Appointments",
      description: "Test appointment booking, scheduling, and management"
    },
    {
      name: "Inventory Tests",
      path: "tests/Feature/Inventory",
      description: "Test inventory management, suppliers, and stock movements"
    },
    {
      name: "Finance Tests", 
      path: "tests/Feature/Finance",
      description: "Test financial operations, accounts, income, and expenses"
    },
    {
      name: "Laboratory Tests",
      path: "tests/Feature/Laboratory",
      description: "Test lab tests, requests, and result management"
    },
    {
      name: "Pharmacy Tests",
      path: "tests/Feature/Pharmacy", 
      description: "Test medicine management, prescriptions, and dispensing"
    },
    {
      name: "Blood Bank Tests",
      path: "tests/Feature/BloodBank",
      description: "Test blood inventory, donors, and requests"
    },
    {
      name: "Ambulance Tests",
      path: "tests/Feature/Ambulance",
      description: "Test ambulance calls, emergency admissions, and dispatch"
    }
  ],
  
  // Database Configuration
  database: {
    connection: "sqlite",
    database: ":memory:",
    testing: true
  },
  
  // Test Environment
  environment: {
    APP_ENV: "testing",
    DB_CONNECTION: "sqlite", 
    DB_DATABASE: ":memory:",
    CACHE_STORE: "array",
    SESSION_DRIVER: "array",
    MAIL_MAILER: "array",
    QUEUE_CONNECTION: "sync"
  },
  
  // Coverage Settings
  coverage: {
    enabled: true,
    threshold: 80,
    exclude: [
      "vendor/**",
      "node_modules/**", 
      "storage/**",
      "bootstrap/cache/**"
    ]
  },
  
  // Test Data
  testData: {
    seeders: [
      "Database\\Seeders\\TestDataSeeder"
    ],
    factories: [
      "UserFactory",
      "PatientFactory", 
      "DoctorFactory",
      "AppointmentFactory"
    ]
  }
};





