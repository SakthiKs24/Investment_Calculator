<!DOCTYPE html>
<html>
<head>
    <title>Investment Calculator</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        /* Your existing styles */
        .header {
            align-items: center; 
            margin-bottom: 20px; 
        }
        .logo { 
            max-height: 50px; 
            margin-right: 10px; 
        }
        .slider { 
            width: 100%; 
        }
        .chart-container { 
            width: 100%; 
            max-width: 300px; 
            margin: 0 auto; 
        }
        .slider-container { 
            display: flex; 
            align-items: center; 
        }
        .slider-container span { 
            width: 85px; 
        }
        .header h2 { 
            font-size: 50px; 
            font-weight: 400; 
            text-align: center; 
            color: #0c4327; 
            font-family: Roboto; 
        }
        .form-group { 
            color: #053960; 
            font-size: 20px; 
            font-weight: 400; 
            font-family: Roboto;  
        }
        .form-group span { 
            color: #e18a12; 
            font-size: 15px; 
            font-weight: 400; 
        }
        .results h4 { 
            color: #880868; 
        }
        .results { 
            color: #554e53; 
            font-size: 20px; 
            font-weight: 400; 
            font-family: Roboto; 
        }
        .user-inputs h4 { 
            color: #880868; 
        }
        .user-inputs { 
            color: #554e53; 
            font-weight: 300; 
            font-family: Roboto; 
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="container mt-5">
    <div class="header">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo">
        <h2>Rate of Returns Calculator</h2>
    </div>

    <!-- Display results and charts at the top if available -->
    @isset($propertyPrice)
    <div class="results">
        <div class="row">
            <div class="col-md-6 user-inputs">
                <h4><b>USER INPUTS</b></h4>
                <p>Property Price: ${{ $propertyPrice }}</p>
                <p>Initial Investment: ${{ $initialInvestment }}</p>
                <p>Loan Duration: {{ $years }} years</p>
                <p>Approx Rental per Month: ${{ $approxRental }}</p>
                <p>Rate of Interest: {{ $annualRate }}%</p>
            </div>
            <div class="col-md-6">
                <div class="chart-container">
                    <canvas id="investmentDoughnutChart" style="height:400px;"></canvas>
                </div>
            </div>
        </div>
    </div>
    @endisset

    @isset($monthlyEMI)
    <div class="results mt-5">
        <div class="row">
            <div class="col-md-6">
                <h4><b>RESULTS</b></h4>
                <p>Monthly EMI: ${{ $monthlyEMI }}</p>
                <p>Annual Interest Rate: {{ $annualRate }}%</p>
                <p>Cash Back in Hand after Letting Property: ${{ $cashBackInHand }}</p>
                <p>Total Amount Paid for Property in {{$years}} Years: ${{ $totalAmountPaidForPropertyIn15Years }}</p>
                <p>Net Gain in Cash (COPR) - 12 Months: ${{ $netGainInCash12Months }}</p>
                <p>Net Gain in Cash (COPR) - {{$years}} Years: ${{ $netGainInCash15Years }}</p>
            </div>
            <div class="col-md-6">
                <div class="chart-container">
                    <canvas id="emiDoughnutChart" style="height:400px;"></canvas>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Charts rendering script
        var ctxInvestment = document.getElementById('investmentDoughnutChart').getContext('2d');
        var investmentDoughnutChart = new Chart(ctxInvestment, {
            type: 'doughnut',
            data: {
                labels: ['Property Price ($)', 'Initial Investment ($)', 'Rate of Interest (%)', 'Loan Duration (Years)', 'Rental Income (Monthly $)'],
                datasets: [{
                    label: 'Investment Breakdown',
                    data: [{{ $propertyPrice }}, {{ $initialInvestment }}, {{ $annualRate }}, {{ $years }}, {{ $approxRental }}],
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutoutPercentage: 50,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom', // Positioning the legend at the bottom
                        labels: {
                            font: {
                                size: 12 // Adjust font size if needed
                            },
                            padding: 10 // Add padding between labels
                        }
                    }
                }
            }
        });

        var ctxEMI = document.getElementById('emiDoughnutChart').getContext('2d');
        var emiDoughnutChart = new Chart(ctxEMI, {
            type: 'doughnut',
            data: {
                labels: ['Monthly EMI', 'Cash Back in Hand', 'Total Amount Paid in {{$years}} Years', 'Net Gain in 12 Months', 'Net Gain in {{$years}} Years'],
                datasets: [{
                    label: 'Calculation Results',
                    data: [{{ $monthlyEMI }}, {{ $cashBackInHand }}, {{ $totalAmountPaidForPropertyIn15Years }}, {{ $netGainInCash12Months }}, {{ $netGainInCash15Years }}],
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutoutPercentage: 50,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom', // Positioning the legend at the bottom
                        labels: {
                            font: {
                                size: 12 // Adjust font size if needed
                            },
                            padding: 10 // Add padding between labels
                        }
                    }
                }
            }
        });
    </script>
    @endisset

    <!-- Display the form below the results -->
    <div class="row mt-5">
        <div class="col-md-6">
            <form method="POST" action="/calculate">
                @csrf
                <!-- Form Fields -->
                <div class="form-group">
                    <label for="propertyPrice">Property Price ($):</label>
                    <div class="slider-container">
                        <span>$1,00,000&nbsp;&nbsp;</span>
                        <input type="range" class="slider" id="propertyPrice" name="propertyPrice" min="100000" max="10000000" step="10000" value="{{ old('propertyPrice', 100000) }}" oninput="this.nextElementSibling.value = this.value; document.getElementById('propertyPriceText').value = this.value;">
                        <span>&nbsp;&nbsp;$10,000,000</span>
                    </div>
                    <input type="text" class="form-control mt-2" id="propertyPriceText" value="{{ old('propertyPrice', 100000) }}" oninput="document.getElementById('propertyPrice').value = this.value;">
                </div>
                <div class="form-group">
                    <label for="initialInvestment">Initial Investment ($):</label>
                    <div class="slider-container">
                        <span>$10,000</span>
                        <input type="range" class="slider" id="initialInvestment" name="initialInvestment" min="10000" max="5000000" step="10000" value="{{ old('initialInvestment', 10000) }}" oninput="this.nextElementSibling.value = this.value; document.getElementById('initialInvestmentText').value = this.value;">
                        <span>&nbsp;&nbsp;$5,000,000</span>
                    </div>
                    <input type="text" class="form-control mt-2" id="initialInvestmentText" value="{{ old('initialInvestment', 10000) }}" oninput="document.getElementById('initialInvestment').value = this.value;">
                </div>
                <div class="form-group">
                    <label for="approxRental">Approx Rental per Month ($):</label>
                    <div class="slider-container">
                        <span>$1,000 &nbsp;</span>
                        <input type="range" class="slider" id="approxRental" name="approxRental" min="1000" max="100000" step="500" value="{{ old('approxRental', 1000) }}" oninput="this.nextElementSibling.value = this.value; document.getElementById('approxRentalText').value = this.value;">
                        <span>&nbsp;&nbsp;$1,00,000</span>
                    </div>
                    <input type="text" class="form-control mt-2" id="approxRentalText" value="{{ old('approxRental', 1000) }}" oninput="document.getElementById('approxRental').value = this.value;">
                </div>
                <div class="form-group">
                    <label for="annualRate">Rate of Interest on loan (%):</label>
                    <div class="slider-container">
                        <span style="width: 35px;">1%</span>
                        <input type="range" class="slider" id="annualRate" name="annualRate" min="1" max="20" step="0.1" value="{{ old('annualRate', 1) }}" oninput="this.nextElementSibling.value = this.value; document.getElementById('annualRateText').value = this.value;">
                        <span style="width: 50px;">&nbsp;&nbsp;20%</span>
                    </div>
                    <input type="text" class="form-control mt-2" id="annualRateText" value="{{ old('annualRate', 1) }}" oninput="document.getElementById('annualRate').value = this.value;">
                </div>
                <div class="form-group">
                    <label for="years">Duration of Loan (Years):</label>
                    <div class="slider-container">
                        <span style="width: 35px;">1 yr</span>
                        <input type="range" class="slider" id="years" name="years" min="1" max="30" step="1" value="{{ old('years', 1) }}" oninput="this.nextElementSibling.value = this.value; document.getElementById('yearsText').value = this.value;">
                        <span style="width: 50px;">30 yrs</span>
                    </div>
                    <input type="text" class="form-control mt-2" id="yearsText" value="{{ old('years', 1) }}" oninput="document.getElementById('years').value = this.value;">
                </div>
                <button type="submit" class="btn btn-primary">Calculate</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
