<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - SalesViz SaaS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-500 via-purple-600 to-pink-500 min-h-screen">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-4xl w-full bg-white rounded-2xl shadow-2xl overflow-hidden">
            <div class="grid md:grid-cols-2">
                <div class="bg-gradient-to-br from-blue-600 to-blue-800 p-12 text-white">
                    <div class="flex items-center mb-8">
                        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-chart-pie text-blue-600 text-2xl"></i>
                        </div>
                        <span class="text-2xl font-bold">SalesViz</span>
                    </div>
                    
                    <h1 class="text-4xl font-bold mb-4">Sales Analytics Dashboard</h1>
                    <p class="text-blue-100 mb-8">Transform your sales data into actionable insights with our powerful visualization tools.</p>
                    
                    <div class="space-y-4">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-check-circle text-green-400"></i>
                            <span>Real-time analytics</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-check-circle text-green-400"></i>
                            <span>Interactive dashboards</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-check-circle text-green-400"></i>
                            <span>Export to multiple formats</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-check-circle text-green-400"></i>
                            <span>Multi-tenant support</span>
                        </div>
                    </div>
                </div>
                
                <div class="p-12">
                    <h2 class="text-2xl font-bold text-gray-800 mb-8">Get Started</h2>
                    
                    <div class="space-y-4">
                        <a href="{{ route('login') }}" class="block w-full bg-blue-600 text-white text-center py-4 rounded-lg font-semibold hover:bg-blue-700 transition">
                            Sign In
                        </a>
                        
                        <a href="{{ route('register') }}" class="block w-full border-2 border-blue-600 text-blue-600 text-center py-4 rounded-lg font-semibold hover:bg-blue-50 transition">
                            Create Account
                        </a>
                    </div>
                    
                    <div class="mt-8 pt-8 border-t border-gray-200">
                        <p class="text-sm text-gray-500 mb-4">Demo Credentials:</p>
                        <div class="bg-gray-50 rounded-lg p-4 text-sm">
                            <p><strong>Email:</strong> admin@saleviz.com</p>
                            <p><strong>Password:</strong> password</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</body>
</html>
