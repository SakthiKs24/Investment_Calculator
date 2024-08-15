<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InvestmentCalculatorController extends Controller
{
    public function index()
    {
        return view('investment-calculator');
    }

    public function calculate(Request $request)
{
    $request->validate([
        'propertyPrice' => 'required|numeric',
        'initialInvestment' => 'required|numeric',
        'annualRate' => 'required|numeric',
        'years' => 'required|numeric',
        'approxRental' => 'required|numeric',
    ]);

    $propertyPrice = $request->input('propertyPrice');
    $initialInvestment = $request->input('initialInvestment');
    $annualRate = $request->input('annualRate');
    $years = $request->input('years');
    $approxRental = $request->input('approxRental');

    $loanAmount = $propertyPrice - $initialInvestment;
    $monthlyRate = $annualRate / 12 / 100;
    $totalMonths = $years * 12;

    $monthlyEMI = $loanAmount * $monthlyRate * pow(1 + $monthlyRate, $totalMonths) / (pow(1 + $monthlyRate, $totalMonths) - 1);
    $monthlyEMI = round($monthlyEMI, 2);

    $rentalIncome = $approxRental * 12;
    $cashBackInHand = $approxRental - $monthlyEMI;

    // Calculate additional metrics
    $totalAmountPaidForPropertyIn15Years = $monthlyEMI * 12 * $years;
    $netGainInCash12Months = $cashBackInHand * 12;
    $netGainInCash15Years = $netGainInCash12Months * $years;

    return view('investment-calculator', [
        'monthlyEMI' => $monthlyEMI,
        'propertyPrice' => $propertyPrice,
        'initialInvestment' => $initialInvestment,
        'annualRate' => $annualRate,
        'years' => $years,
        'approxRental' => $approxRental,
        'rentalIncome' => round($rentalIncome, 2),
        'cashBackInHand' => round($cashBackInHand, 2),
        'totalAmountPaidForPropertyIn15Years' => round($totalAmountPaidForPropertyIn15Years, 2),
        'netGainInCash12Months' => round($netGainInCash12Months, 2),
        'netGainInCash15Years' => round($netGainInCash15Years, 2),
    ]);
}

}
