<?php
/**
 * Plugin Name: Funnel Calculator Pro
 * Plugin URI: https://example.com
 * Description: Professional funnel analysis calculator with real-time conversion metrics
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://example.com
 * License: GPL v2 or later
 * Text Domain: funnel-calculator
 */

if (!defined('ABSPATH')) exit;

class Funnel_Calculator_Pro {
    
    public function __construct() {
        add_shortcode('funnel_calculator', array($this, 'render_calculator'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
    }
    
    public function enqueue_assets() {
        // Inline CSS
        add_action('wp_footer', array($this, 'inline_styles'));
        add_action('wp_footer', array($this, 'inline_scripts'));
    }
    
    public function inline_styles() {
        ?>
        <style>
        .funnel-calculator-wrapper {
            max-width: 1200px;
            margin: 30px auto;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;
        }
        .funnel-tabs {
            display: flex;
            gap: 8px;
            margin-bottom: 20px;
            background: #f1f5f9;
            padding: 12px;
            border-radius: 10px;
        }
        .funnel-tab {
            padding: 14px 28px;
            border: none;
            background: transparent;
            cursor: pointer;
            font-size: 15px;
            font-weight: 600;
            color: #64748b;
            border-radius: 8px;
            transition: all 0.2s ease;
        }
        .funnel-tab.active {
            background: white;
            color: #5B8DEF;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .funnel-tab:hover:not(.active) {
            background: rgba(255,255,255,0.6);
        }
        .funnel-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 35px;
            background: white;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.06);
        }
        .funnel-input-section {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .input-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .input-group label {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
            letter-spacing: -0.01em;
        }
        .input-group input {
            padding: 14px 18px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.2s;
            background: #fafbfc;
        }
        .input-group input:focus {
            outline: none;
            border-color: #5B8DEF;
            background: white;
            box-shadow: 0 0 0 3px rgba(91,141,239,0.1);
        }
        .input-group input:hover {
            border-color: #cbd5e1;
        }
        .reset-btn {
            padding: 14px 24px;
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 600;
            color: #64748b;
            transition: all 0.2s;
            margin-top: 10px;
        }
        .reset-btn:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            transform: translateY(-1px);
        }
        .funnel-results-section {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            padding: 35px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }
        .funnel-results-section h2 {
            font-size: 22px;
            font-weight: 700;
            margin: 0 0 28px 0;
            color: #0f172a;
            letter-spacing: -0.02em;
        }
        .result-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 20px;
            background: white;
            border-radius: 8px;
            margin-bottom: 12px;
            transition: all 0.2s;
        }
        .result-item:hover {
            transform: translateX(4px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        }
        .result-label {
            font-size: 15px;
            color: #5B8DEF;
            font-weight: 600;
        }
        .result-value {
            font-size: 22px;
            font-weight: 700;
            color: #0f172a;
        }
        .result-value.blue {
            color: #5B8DEF;
        }
        .result-value.red {
            color: #ef4444;
        }
        .copy-btn {
            width: 100%;
            padding: 16px;
            background: #5B8DEF;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 20px;
            transition: all 0.2s;
        }
        .copy-btn:hover {
            background: #4a7dd9;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(91,141,239,0.3);
        }
        .copy-btn:active {
            transform: translateY(0);
        }
        @media (max-width: 768px) {
            .funnel-container {
                grid-template-columns: 1fr;
                padding: 25px;
                gap: 25px;
            }
            .funnel-tabs {
                flex-direction: column;
            }
        }
        </style>
        <?php
    }
    
    public function inline_scripts() {
        ?>
        <script>
        (function() {
            if (typeof window.funnelCalcInit !== 'undefined') return;
            window.funnelCalcInit = true;
            
            document.addEventListener('DOMContentLoaded', function() {
                const calculator = document.querySelector('.funnel-calculator-wrapper');
                if (!calculator) return;
                
                const inputs = {
                    impressions: calculator.querySelector('#impressions'),
                    clicks: calculator.querySelector('#clicks'),
                    atc: calculator.querySelector('#atc'),
                    ic: calculator.querySelector('#ic'),
                    purchases: calculator.querySelector('#purchases')
                };
                
                const results = {
                    overallConversion: calculator.querySelector('#overallConversion'),
                    impressionToPurchase: calculator.querySelector('#impressionToPurchase'),
                    ctr: calculator.querySelector('#ctr'),
                    atcRate: calculator.querySelector('#atcRate'),
                    icRate: calculator.querySelector('#icRate'),
                    purchaseRate: calculator.querySelector('#purchaseRate')
                };
                
                function calculate() {
                    const impressions = parseFloat(inputs.impressions.value) || 0;
                    const clicks = parseFloat(inputs.clicks.value) || 0;
                    const atc = parseFloat(inputs.atc.value) || 0;
                    const ic = parseFloat(inputs.ic.value) || 0;
                    const purchases = parseFloat(inputs.purchases.value) || 0;
                    
                    const overallConv = impressions > 0 ? (purchases / impressions * 100) : 0;
                    const impToPurch = impressions > 0 ? (purchases / impressions * 100) : 0;
                    const ctrValue = impressions > 0 ? (clicks / impressions * 100) : 0;
                    const atcRateValue = clicks > 0 ? (atc / clicks * 100) : 0;
                    const icRateValue = atc > 0 ? (ic / atc * 100) : 0;
                    const purchaseRateValue = ic > 0 ? (purchases / ic * 100) : 0;
                    
                    results.overallConversion.textContent = overallConv.toFixed(2) + '%';
                    results.impressionToPurchase.textContent = impToPurch.toFixed(2) + '%';
                    results.ctr.textContent = ctrValue.toFixed(2) + '%';
                    results.atcRate.textContent = atcRateValue.toFixed(2) + '%';
                    results.icRate.textContent = icRateValue.toFixed(2) + '%';
                    results.purchaseRate.textContent = purchaseRateValue.toFixed(2) + '%';
                }
                
                Object.values(inputs).forEach(function(input) {
                    if (input) input.addEventListener('input', calculate);
                });
                
                const resetBtn = calculator.querySelector('#resetBtn');
                if (resetBtn) {
                    resetBtn.addEventListener('click', function() {
                        inputs.impressions.value = 0;
                        inputs.clicks.value = 0;
                        inputs.atc.value = 0;
                        inputs.ic.value = 0;
                        inputs.purchases.value = 0;
                        calculate();
                    });
                }
                
                const copyBtn = calculator.querySelector('#copyBtn');
                if (copyBtn) {
                    copyBtn.addEventListener('click', function() {
                        const text = 'Funnel Analysis Results:\n' +
                            'Overall Conversion: ' + results.overallConversion.textContent + '\n' +
                            'Impression → Purchase: ' + results.impressionToPurchase.textContent + '\n' +
                            'CTR: ' + results.ctr.textContent + '\n' +
                            'ATC Rate: ' + results.atcRate.textContent + '\n' +
                            'IC Rate: ' + results.icRate.textContent + '\n' +
                            'Purchase Rate: ' + results.purchaseRate.textContent;
                        
                        if (navigator.clipboard) {
                            navigator.clipboard.writeText(text).then(function() {
                                const originalText = copyBtn.textContent;
                                copyBtn.textContent = '✓ Copied!';
                                setTimeout(function() {
                                    copyBtn.textContent = originalText;
                                }, 2000);
                            });
                        }
                    });
                }
                
                calculate();
            });
        })();
        </script>
        <?php
    }
    
    public function render_calculator() {
        ob_start();
        ?>
        <div class="funnel-calculator-wrapper">
            <div class="funnel-tabs">
                <button class="funnel-tab">ROI Calculator</button>
                <button class="funnel-tab">Budget Planner</button>
                <button class="funnel-tab active">Funnel Calculator</button>
            </div>
            
            <div class="funnel-container">
                <div class="funnel-input-section">
                    <div class="input-group">
                        <label>Impressions</label>
                        <input type="number" id="impressions" value="342" min="0">
                    </div>
                    
                    <div class="input-group">
                        <label>Clicks</label>
                        <input type="number" id="clicks" value="2000" min="0">
                    </div>
                    
                    <div class="input-group">
                        <label>Add to Carts (ATC)</label>
                        <input type="number" id="atc" value="200" min="0">
                    </div>
                    
                    <div class="input-group">
                        <label>Initiate Checkouts (IC)</label>
                        <input type="number" id="ic" value="80" min="0">
                    </div>
                    
                    <div class="input-group">
                        <label>Purchases</label>
                        <input type="number" id="purchases" value="20" min="0">
                    </div>
                    
                    <button class="reset-btn" id="resetBtn">↻ Reset</button>
                </div>
                
                <div class="funnel-results-section">
                    <h2>Funnel Analysis Results</h2>
                    
                    <div class="result-item">
                        <span class="result-label">Overall Conversion</span>
                        <span class="result-value" id="overallConversion">5.85%</span>
                    </div>
                    
                    <div class="result-item">
                        <span class="result-label">Impression → Purchase</span>
                        <span class="result-value" id="impressionToPurchase">5.85%</span>
                    </div>
                    
                    <div class="result-item">
                        <span class="result-label">CTR</span>
                        <span class="result-value blue" id="ctr">584.80%</span>
                    </div>
                    
                    <div class="result-item">
                        <span class="result-label">ATC Rate</span>
                        <span class="result-value red" id="atcRate">10.00%</span>
                    </div>
                    
                    <div class="result-item">
                        <span class="result-label">IC Rate</span>
                        <span class="result-value red" id="icRate">40.00%</span>
                    </div>
                    
                    <div class="result-item">
                        <span class="result-label">Purchase Rate</span>
                        <span class="result-value red" id="purchaseRate">25.00%</span>
                    </div>
                    
                    <button class="copy-btn" id="copyBtn">📋 Copy Results</button>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}

new Funnel_Calculator_Pro();