# TDMS Payment Gateway Extension

## Overview
This extension integrates TD Merchant Solutions (Bambora/Worldline) payment gateway with miniCal. It provides secure payment processing, tokenization, refunds, and void capabilities.

## Features
- **Secure Payment Processing** - Process credit card payments through TD Merchant Solutions
- **Payment Profile Tokenization** - Store customer payment information securely for future transactions
- **Capture Pre-Authorizations** - Complete previously authorized payments
- **Refund Support** - Issue full or partial refunds
- **Void Transactions** - Cancel transactions before settlement
- **Test Mode** - Sandbox environment for testing
- **Multi-Currency Support** - Process payments in different currencies

## Installation

1. Download or clone this extension
2. Upload the `tdms_payment_gateway` folder to `/public/application/extensions/` directory
3. Log in to your miniCal admin panel
4. Navigate to **Extensions** and activate "TDMS Payment Gateway"
5. Click the settings icon to configure your credentials

## Configuration

### Required Credentials

You'll need the following from your TD Merchant Services dashboard:

1. **Merchant ID**
   - Location: Administration > Company Info
   
2. **API Access Passcode**
   - Location: Administration > Order Settings > Payment Gateway > Security/Authentication
   - Generate if empty by clicking "Generate New Code"
   
3. **Profile API Passcode** (Optional - for tokenization)
   - Location: Configuration > Payment Profile Configuration > Security Settings
   - Required only if you want to store customer payment profiles

### Setup Steps

1. Log in to your TD Merchant Services dashboard at [tdmerchantsolutions.com/TDMI](https://www.tdmerchantsolutions.com/TDMI)
2. Enable hash validation under Administration > Order Settings
3. Generate API passcodes if fields are empty
4. Enable API access passcode under Security Settings
5. Copy your Merchant ID and passcodes
6. In miniCal, go to TDMS Payment Gateway settings
7. Enter your credentials
8. Click "Test Connection" to verify
9. Click "Save Settings" to activate

## Usage

### Processing Payments

Once configured, TDMS will appear as a payment option when processing bookings:

1. Select "TDMS" as the payment gateway
2. Enter customer card details or use saved profile
3. Process payment (capture immediately or authorize for later)

### Tokenization

Enable tokenization to store customer payment profiles:

1. In TDMS settings, check "Enable Payment Profile Tokenization"
2. Enter your Profile API Passcode
3. Save settings
4. Customer cards will now be securely stored as tokens

### Refunds

To refund a payment:

1. Navigate to the booking/payment
2. Click "Refund"
3. Enter refund amount (full or partial)
4. Confirm refund

### Void Transactions

To void a transaction (before settlement):

1. Navigate to the payment
2. Click "Void"
3. Confirm void action
4. Note: Voids must be processed within 24 hours

## API Integration

The extension uses TD Merchant Solutions (Bambora/Worldline) North America API:

- **Base URL**: `https://api.na.bambora.com/v1`
- **Authentication**: Passcode (Base64 encoded merchant_id:api_passcode)
- **Endpoints**:
  - `/payments` - Create payment
  - `/payments/{id}/completions` - Capture authorization
  - `/payments/{id}/returns` - Create refund
  - `/payments/{id}/void` - Void transaction
  - `/profiles` - Manage payment profiles

## Security

- All API communications use HTTPS
- Card data is tokenized and never stored directly
- PCI DSS compliant when using tokenization
- Credentials are encrypted in database

## Troubleshooting

### Connection Test Fails
- Verify Merchant ID and API Passcode are correct
- Ensure API access is enabled in TD Merchant Services dashboard
- Check that hash validation is enabled

### Payment Fails
- Verify customer has valid payment profile/card
- Check that amount is positive
- Ensure booking balance allows payment
- Review error message for specific issue

### Tokenization Not Working
- Verify Profile API Passcode is entered
- Ensure tokenization is enabled in settings
- Check that Profile API access is enabled in dashboard

## Support

For technical support:
- **TD Merchant Solutions**: 1-800-363-1163
- **miniCal Support**: support@minical.io
- **Documentation**: [TD Merchant Solutions Documentation](https://www.tdmerchantsolutions.com)

## Version History

### 1.0.0 (2026-01-19)
- Initial release
- Payment processing
- Tokenization support
- Refund and void capabilities
- Test mode
- Multi-currency support

## License

This extension is licensed under the Open Software License 3.0 (OSL-3.0)

## Credits

Developed for miniCal integration with TD Merchant Solutions (Bambora/Worldline) payment gateway.