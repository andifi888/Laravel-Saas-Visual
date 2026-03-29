@extends('layouts.app')
@section('title', 'Dashboard')

@section('page-title', 'Dashboard')
@section('page-subtitle', 'Sales Analytics Overview')

@push('styles')
<style>
.stat-card {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    color: white;
    border-radius: 16px;
    padding: 24px;
    position: relative;
    overflow: hidden;
}
.stat-card::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 100%;
    height: 100%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
}
.stat-card .stat-icon {
    font-size: 2.5rem;
    opacity: 0.9;
}
.chart-container {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    height: 400px;
}
[data-theme="dark"] .chart-container {
    background: #1f2937;
}
</style>
@endpush

@section('content')
<div class="space-y-6 fade-in">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-80">Total Revenue</p>
                    <h3 class="text-3xl font-bold mt-2">${{ number_format($overview['total_revenue'], 0) }}</h3>
                    <p class="text-sm opacity-80 mt-2">
                        @if($trends['revenue']['trend'] === 'up')
                        <i class="fas fa-arrow-up"></i>
                        @else
                        <i class="fas fa-arrow-down"></i>
                        @endif
                        {{ $trends['revenue']['change'] }}% vs last period
                    </p>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-80">Total Orders</p>
                    <h3 class="text-3xl font-bold mt-2">{{ number_format($overview['total_orders']) }}</h3>
                    <p class="text-sm opacity-80 mt-2">
                        @if($trends['orders']['trend'] === 'up')
                        <i class="fas fa-arrow-up"></i>
                        @else
                        <i class="fas fa-arrow-down"></i>
                        @endif
                        {{ $trends['orders']['change'] }}% vs last period
                    </p>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-80">Total Profit</p>
                    <h3 class="text-3xl font-bold mt-2">${{ number_format($overview['total_profit'], 0) }}</h3>
                    <p class="text-sm opacity-80 mt-2">
                        @if($trends['profit']['trend'] === 'up')
                        <i class="fas fa-arrow-up"></i>
                        @else
                        <i class="fas fa-arrow-down"></i>
                        @endif
                        {{ $trends['profit']['change'] }}% vs last period
                    </p>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-80">Avg Order Value</p>
                    <h3 class="text-3xl font-bold mt-2">${{ number_format($overview['average_order_value'], 0) }}</h3>
                    <p class="text-sm opacity-80 mt-2">
                        <i class="fas fa-users"></i>
                        {{ number_format($overview['total_customers']) }} customers
                    </p>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-receipt"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 md:mb-0">Analytics Filters</h3>
            <form method="GET" action="{{ route('dashboard') }}" class="flex flex-wrap gap-4">
                <input type="date" name="start_date" value="{{ $filters['start_date'] }}" 
                       class="input-custom text-sm">
                <input type="date" name="end_date" value="{{ $filters['end_date'] }}" 
                       class="input-custom text-sm">
                <select name="group_by" class="input-custom text-sm">
                    <option value="day" {{ $filters['group_by'] === 'day' ? 'selected' : '' }}>Daily</option>
                    <option value="week" {{ $filters['group_by'] === 'week' ? 'selected' : '' }}>Weekly</option>
                    <option value="month" {{ $filters['group_by'] === 'month' ? 'selected' : '' }}>Monthly</option>
                    <option value="year" {{ $filters['group_by'] === 'year' ? 'selected' : '' }}>Yearly</option>
                </select>
                <button type="submit" class="btn-primary text-sm px-4 py-2">
                    <i class="fas fa-filter mr-2"></i>Apply
                </button>
                <a href="{{ route('dashboard') }}" class="btn-secondary text-sm px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-lg">
                    Reset
                </a>
            </form>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="chart-container">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Sales Over Time</h3>
            <div id="sales-chart" class="w-full h-80"></div>
        </div>
        
        <div class="chart-container">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Revenue by Category</h3>
            <div id="category-chart" class="w-full h-80"></div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="chart-container">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Sales Distribution</h3>
            <div id="pie-chart" class="w-full h-80"></div>
        </div>
        
        <div class="chart-container">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Top Products</h3>
            <div id="product-chart" class="w-full h-80"></div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="chart-container">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Daily Sales Activity</h3>
            <div id="heatmap-chart" class="w-full h-80"></div>
        </div>
        
        <div class="chart-container">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Top Customers</h3>
            <div id="customer-chart" class="w-full h-80"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
window.echartsInstances = [];

document.addEventListener('DOMContentLoaded', function() {
    loadCharts();
});

async function loadCharts() {
    try {
        const response = await fetch('{{ route('dashboard.charts') }}?' + new URLSearchParams({
            start_date: '{{ $filters['start_date'] }}',
            end_date: '{{ $filters['end_date'] }}',
            group_by: '{{ $filters['group_by'] }}'
        }));
        const data = await response.json();
        
        if (data.success) {
            initSalesChart(data.data.sales_over_time);
            initCategoryChart(data.data.revenue_by_category);
            initPieChart(data.data.sales_distribution);
            initProductChart(data.data.revenue_by_product);
            initHeatmapChart(data.data.daily_heatmap);
            initCustomerChart(data.data.top_customers);
        }
    } catch (error) {
        console.error('Error loading charts:', error);
    }
}

function initSalesChart(data) {
    const chart = echarts.init(document.getElementById('sales-chart'));
    const option = {
        tooltip: { trigger: 'axis' },
        legend: { data: ['Revenue', 'Profit'], bottom: 0 },
        grid: { left: '3%', right: '4%', bottom: '15%', containLabel: true },
        xAxis: { type: 'category', data: data.labels, axisLabel: { rotate: 45 } },
        yAxis: { type: 'value' },
        series: [
            { name: 'Revenue', type: 'line', smooth: true, data: data.revenue, itemStyle: { color: '#3b82f6' } },
            { name: 'Profit', type: 'line', smooth: true, data: data.profit, itemStyle: { color: '#10b981' } }
        ]
    };
    chart.setOption(option);
    window.echartsInstances.push(chart);
}

function initCategoryChart(data) {
    const chart = echarts.init(document.getElementById('category-chart'));
    const option = {
        tooltip: { trigger: 'axis' },
        grid: { left: '3%', right: '4%', bottom: '3%', containLabel: true },
        xAxis: { type: 'value' },
        yAxis: { type: 'category', data: data.categories.reverse() },
        series: [{
            type: 'bar',
            data: data.revenue.reverse(),
            itemStyle: { color: '#8b5cf6', borderRadius: [0, 4, 4, 0] }
        }]
    };
    chart.setOption(option);
    window.echartsInstances.push(chart);
}

function initPieChart(data) {
    const chart = echarts.init(document.getElementById('pie-chart'));
    const option = {
        tooltip: { trigger: 'item', formatter: '{b}: ${c} ({d}%)' },
        legend: { orient: 'vertical', right: 10, top: 'center' },
        series: [{
            type: 'pie',
            radius: ['40%', '70%'],
            avoidLabelOverlap: false,
            itemStyle: { borderRadius: 10, borderColor: '#fff', borderWidth: 2 },
            label: { show: false },
            emphasis: { label: { show: true, fontSize: 14, fontWeight: 'bold' } },
            data: data.names.map((name, i) => ({ name, value: data.values[i] }))
        }]
    };
    chart.setOption(option);
    window.echartsInstances.push(chart);
}

function initProductChart(data) {
    const chart = echarts.init(document.getElementById('product-chart'));
    const option = {
        tooltip: { trigger: 'axis' },
        grid: { left: '3%', right: '4%', bottom: '3%', containLabel: true },
        xAxis: { type: 'value' },
        yAxis: { type: 'category', data: data.products.reverse().slice(0, 5) },
        series: [{
            type: 'bar',
            data: data.revenue.reverse().slice(0, 5),
            itemStyle: { color: '#f59e0b', borderRadius: [0, 4, 4, 0] }
        }]
    };
    chart.setOption(option);
    window.echartsInstances.push(chart);
}

function initHeatmapChart(data) {
    const chart = echarts.init(document.getElementById('heatmap-chart'));
    const hours = ['12a', '1a', '2a', '3a', '4a', '5a', '6a', '7a', '8a', '9a', '10a', '11a',
                   '12p', '1p', '2p', '3p', '4p', '5p', '6p', '7p', '8p', '9p', '10p', '11p'];
    const days = ['Sat', 'Fri', 'Thu', 'Wed', 'Tue', 'Mon', 'Sun'];
    
    const option = {
        tooltip: { position: 'top', formatter: (p) => `${days[p.data[1]]} ${hours[p.data[0]]}: $${p.data[2].toFixed(0)}` },
        grid: { left: '2%', right: '5%', top: '5%', bottom: '15%', containLabel: true },
        xAxis: { type: 'category', data: hours, splitArea: { show: true } },
        yAxis: { type: 'category', data: days },
        visualMap: { min: 0, max: Math.max(...data.data.map(d => d[2])), calculable: true, orient: 'horizontal', left: 'center', bottom: '0%' },
        series: [{ type: 'heatmap', data: data.data, label: { show: false }, emphasis: { itemStyle: { shadowBlur: 10, shadowColor: 'rgba(0, 0, 0, 0.5)' } } }]
    };
    chart.setOption(option);
    window.echartsInstances.push(chart);
}

function initCustomerChart(data) {
    const chart = echarts.init(document.getElementById('customer-chart'));
    const option = {
        tooltip: { trigger: 'axis', axisPointer: { type: 'shadow' } },
        grid: { left: '3%', right: '4%', bottom: '3%', containLabel: true },
        xAxis: { type: 'value' },
        yAxis: { type: 'category', data: data.customers.reverse().slice(0, 5) },
        series: [{
            type: 'bar',
            data: data.total_spent.reverse().slice(0, 5),
            itemStyle: { color: '#10b981', borderRadius: [0, 4, 4, 0] },
            label: { show: true, position: 'right', formatter: '${c}' }
        }]
    };
    chart.setOption(option);
    window.echartsInstances.push(chart);
}

$(window).on('resize', function() {
    window.echartsInstances.forEach(chart => chart.resize());
});
</script>
@endpush
