<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificat - {{ $formation_title }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Georgia', serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .certificate-container {
            width: 100%;
            height: 100vh;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            position: relative;
            overflow: hidden;
            page-break-after: always;
        }
        
        /* Décoration de bordure élégante */
        .certificate-container::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 20px;
            right: 20px;
            bottom: 20px;
            border: 3px solid #d4af37;
            border-radius: 10px;
            pointer-events: none;
        }
        
        .certificate-container::after {
            content: '';
            position: absolute;
            top: 30px;
            left: 30px;
            right: 30px;
            bottom: 30px;
            border: 1px solid #d4af37;
            border-radius: 5px;
            pointer-events: none;
        }
        
        /* Contenu principal */
        .certificate-content {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px 80px;
            text-align: center;
            z-index: 1;
        }
        
        /* Logos et en-tête */
        .certificate-header {
            margin-bottom: 40px;
        }
        
        .logo {
            width: 60px;
            height: 60px;
            margin-bottom: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 32px;
            font-weight: bold;
        }
        
        .issuer {
            font-size: 12px;
            color: #666;
            letter-spacing: 2px;
            text-transform: uppercase;
            font-weight: 500;
        }
        
        /* Titre "Certificate" */
        .certificate-title {
            font-size: 48px;
            font-weight: bold;
            color: #1a1a1a;
            margin: 20px 0 30px 0;
            letter-spacing: 3px;
        }
        
        .certificate-subtitle {
            font-size: 18px;
            color: #667eea;
            margin-bottom: 40px;
            font-weight: 500;
        }
        
        /* Section "This certifies that" */
        .certifies-section {
            margin: 40px 0;
        }
        
        .certifies-text {
            font-size: 14px;
            color: #666;
            margin-bottom: 15px;
            font-style: italic;
        }
        
        /* Nom du bénéficiaire */
        .recipient-name {
            font-size: 36px;
            font-weight: bold;
            color: #764ba2;
            margin: 20px 0;
            letter-spacing: 1px;
            text-decoration: underline;
            text-decoration-color: #d4af37;
            text-decoration-thickness: 2px;
            text-underline-offset: 8px;
        }
        
        /* Description de l'accomplissement */
        .achievement {
            margin: 30px 0;
            color: #333;
            line-height: 1.6;
        }
        
        .achievement-text {
            font-size: 13px;
            margin: 10px 0;
        }
        
        .formation-title {
            font-size: 22px;
            font-weight: bold;
            color: #667eea;
            margin: 15px 0;
        }
        
        .formation-level {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        /* Section de signature et détails */
        .signature-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 60px;
            padding-top: 40px;
            border-top: 2px solid #d4af37;
        }
        
        .signature-box {
            flex: 0 0 auto;
            text-align: center;
            width: 150px;
        }
        
        .signature-line {
            border-top: 2px solid #333;
            margin-bottom: 5px;
            min-height: 80px;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            margin-bottom: 0;
        }
        
        .signature-label {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
            font-weight: 500;
        }
        
        /* QR Code */
        .qr-code-box {
            flex: 0 0 auto;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .qr-code {
            width: 120px;
            height: 120px;
            border: 2px solid #d4af37;
            padding: 5px;
            background: white;
            margin-bottom: 8px;
        }
        
        .qr-label {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        /* Numéro de certificat */
        .certificate-number {
            font-size: 11px;
            color: #999;
            margin-top: 30px;
            letter-spacing: 1px;
        }
        
        /* Date */
        .issue-date {
            font-size: 13px;
            color: #333;
            margin-top: 15px;
        }
        
        /* Éléments décoratifs */
        .decoration {
            position: absolute;
            opacity: 0.05;
            pointer-events: none;
        }
        
        .decoration-top-left {
            top: 40px;
            left: 40px;
            font-size: 200px;
            color: #667eea;
        }
        
        .decoration-bottom-right {
            bottom: 40px;
            right: 40px;
            font-size: 150px;
            color: #764ba2;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <!-- Décorations -->
        <div class="decoration decoration-top-left">✓</div>
        <div class="decoration decoration-bottom-right">★</div>
        
        <!-- Contenu principal -->
        <div class="certificate-content">
            <!-- En-tête -->
            <div class="certificate-header">
                <div class="logo">CA</div>
                <div class="issuer">CodeLearn Academy</div>
            </div>
            
            <!-- Titre -->
            <h1 class="certificate-title">CERTIFICATE</h1>
            <p class="certificate-subtitle">of Completion</p>
            
            <!-- Section "This certifies that" -->
            <div class="certifies-section">
                <p class="certifies-text">This is to certify that</p>
                <h2 class="recipient-name">{{ $user_name }}</h2>
                <p class="certifies-text">has successfully completed the course</p>
            </div>
            
            <!-- Description de l'accomplissement -->
            <div class="achievement">
                <h3 class="formation-title">{{ $formation_title }}</h3>
                <p class="formation-level">{{ $formation_level }} Level</p>
                <p class="achievement-text">
                    with a completion rate of <strong>{{ $completion_percentage }}%</strong>
                </p>
                <p class="achievement-text">
                    demonstrating exceptional proficiency in the subject matter
                </p>
            </div>
            
            <!-- Section de signature -->
            <div class="signature-section">
                <!-- Signature -->
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <div class="signature-label">Authorized Signature</div>
                </div>
                
                <!-- QR Code -->
                <div class="qr-code-box">
                    <img src="{{ $qr_code_base64 }}" alt="Certificate QR Code" class="qr-code">
                    <div class="qr-label">Verify Online</div>
                </div>
                
                <!-- Date -->
                <div class="signature-box">
                    <div style="height: 80px; display: flex; align-items: flex-end;">
                        <div style="text-align: center; width: 100%;">
                            {{ $issued_date }}
                        </div>
                    </div>
                    <div class="signature-label">Date Issued</div>
                </div>
            </div>
            
            <!-- Numéro et détails -->
            <div class="certificate-number">
                Certificate No: {{ $certificate_number }}
            </div>
            <div class="certificate-number" style="font-size: 10px;">
                Verification: {{ $verification_url }}
            </div>
        </div>
    </div>
</body>
</html>
