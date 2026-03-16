"""
Script alternatif de génération de certificats en Python avec ReportLab
Peut être utilisé comme worker asynchrone ou intégré avec Django/FastAPI

pip install reportlab pillow qrcode python-dotenv
"""

from reportlab.lib.pagesizes import landscape, A4
from reportlab.lib import colors
from reportlab.lib.styles import getSampleStyleSheet, ParagraphStyle
from reportlab.lib.units import cm, inch
from reportlab.platypus import SimpleDocTemplate, Paragraph, Spacer, Image, Table, TableStyle
from reportlab.platypus import PageBreak, Flowable
from reportlab.lib.enums import TA_CENTER, TA_LEFT, TA_RIGHT
from reportlab.pdfgen import canvas
from datetime import datetime
from pathlib import Path
import qrcode
from io import BytesIO
import hashlib
import uuid


class CertificateGenerator:
    """
    Génère des certificats PDF professionnels avec QR code
    """
    
    def __init__(self, output_dir='./certificates'):
        self.output_dir = Path(output_dir)
        self.output_dir.mkdir(exist_ok=True)
        
    def generate(self,
                 user_name: str,
                 formation_title: str,
                 formation_level: str,
                 certificate_number: str,
                 verification_token: str,
                 completion_percentage: int = 100,
                 issued_date: datetime = None,
                 output_filename: str = None) -> str:
        """
        Génère le certificat et retourne le chemin du fichier
        
        Args:
            user_name: Nom du bénéficiaire
            formation_title: Titre de la formation
            formation_level: Niveau (débutant/intermédiaire/avancé)
            certificate_number: Numéro unique du certificat
            verification_token: Token de vérification
            completion_percentage: % de complétion (défaut 100)
            issued_date: Date d'émission (défaut = aujourd'hui)
            output_filename: Nom du fichier (défaut = auto-généré)
            
        Returns:
            str: Chemin complet du fichier PDF généré
        """
        
        if issued_date is None:
            issued_date = datetime.now()
            
        if output_filename is None:
            output_filename = f"certificate_{certificate_number}.pdf"
            
        output_path = self.output_dir / output_filename
        
        # Créer le document
        doc = SimpleDocTemplate(
            str(output_path),
            pagesize=landscape(A4),
            rightMargin=1*cm,
            leftMargin=1*cm,
            topMargin=1.5*cm,
            bottomMargin=1.5*cm
        )
        
        # Styles
        styles = getSampleStyleSheet()
        
        title_style = ParagraphStyle(
            'CustomTitle',
            parent=styles['Heading1'],
            fontSize=48,
            textColor=colors.HexColor('#1a1a1a'),
            spaceAfter=30,
            alignment=TA_CENTER,
            fontName='Helvetica-Bold',
            letterSpacing=3,
        )
        
        subtitle_style = ParagraphStyle(
            'CustomSubtitle',
            parent=styles['Normal'],
            fontSize=18,
            textColor=colors.HexColor('#667eea'),
            spaceAfter=20,
            alignment=TA_CENTER,
            fontName='Helvetica-Bold',
        )
        
        body_style = ParagraphStyle(
            'CustomBody',
            parent=styles['Normal'],
            fontSize=13,
            textColor=colors.HexColor('#333333'),
            alignment=TA_CENTER,
            spaceAfter=15,
            leading=20,
        )
        
        formation_style = ParagraphStyle(
            'Formation',
            parent=styles['Normal'],
            fontSize=22,
            textColor=colors.HexColor('#667eea'),
            alignment=TA_CENTER,
            spaceAfter=10,
            fontName='Helvetica-Bold',
        )
        
        name_style = ParagraphStyle(
            'Name',
            parent=styles['Normal'],
            fontSize=36,
            textColor=colors.HexColor('#764ba2'),
            alignment=TA_CENTER,
            spaceAfter=20,
            fontName='Helvetica-Bold',
            underlineColor=colors.HexColor('#d4af37'),
            underlineWidth=2,
        )
        
        # Contenu
        elements = []
        
        # Espaceur du haut
        elements.append(Spacer(1, 0.5*cm))
        
        # Logo / Issuer
        issuer_para = Paragraph(
            "<b>CODELEARN ACADEMY</b>",
            ParagraphStyle(
                'Issuer',
                parent=styles['Normal'],
                fontSize=12,
                textColor=colors.HexColor('#666666'),
                alignment=TA_CENTER,
                letterSpacing=2,
            )
        )
        elements.append(issuer_para)
        elements.append(Spacer(1, 0.5*cm))
        
        # Titre
        elements.append(Paragraph("CERTIFICATE", title_style))
        elements.append(Paragraph("of Completion", subtitle_style))
        
        elements.append(Spacer(1, 0.5*cm))
        
        # "This certifies that"
        elements.append(Paragraph(
            "This is to certify that",
            body_style
        ))
        
        # Nom du bénéficiaire
        elements.append(Paragraph(user_name, name_style))
        
        # Description
        elements.append(Paragraph(
            "has successfully completed the course",
            body_style
        ))
        
        elements.append(Spacer(1, 0.3*cm))
        
        # Titre de la formation
        elements.append(Paragraph(formation_title, formation_style))
        
        # Niveau et complétion
        level_text = f"<i>{formation_level.capitalize()} Level</i><br/>"
        level_text += f"with a completion rate of <b>{completion_percentage}%</b>"
        elements.append(Paragraph(level_text, body_style))
        
        elements.append(Paragraph(
            "demonstrating exceptional proficiency in the subject matter",
            body_style
        ))
        
        elements.append(Spacer(1, 1*cm))
        
        # Section de signature
        # Générer QR code
        qr_img = self._generate_qr_code(verification_token)
        
        # Table avec signature, QR, date
        signature_table_data = [
            [
                Paragraph("<b>Authorized Signature</b>", body_style),
                Paragraph("<b>Verify Online</b><br/><i>(Scan QR Code)</i>", body_style),
                Paragraph("<b>Date Issued</b>", body_style),
            ],
            [
                Spacer(1, 2*cm),  # Espace pour signature
                Image(qr_img, width=1.2*inch, height=1.2*inch),
                Spacer(1, 2*cm),  # Espace pour date
            ],
        ]
        
        sig_table = Table(signature_table_data, colWidths=[3*cm, 3*cm, 3*cm])
        sig_table.setStyle(TableStyle([
            ('ALIGN', (0, 0), (-1, -1), 'CENTER'),
            ('VALIGN', (0, 0), (-1, -1), 'MIDDLE'),
            ('LEFTPADDING', (0, 0), (-1, -1), 10),
            ('RIGHTPADDING', (0, 0), (-1, -1), 10),
            ('TOPPADDING', (0, 1), (-1, 1), 0),
        ]))
        
        elements.append(sig_table)
        
        elements.append(Spacer(1, 0.5*cm))
        
        # Numéro et détails
        cert_details = (
            f"Certificate No: <b>{certificate_number}</b><br/>"
            f"Issued: <b>{issued_date.strftime('%d %B %Y')}</b><br/>"
            f"Verification Token: {verification_token[:16]}..."
        )
        
        cert_style = ParagraphStyle(
            'CertDetails',
            parent=styles['Normal'],
            fontSize=10,
            textColor=colors.HexColor('#999999'),
            alignment=TA_CENTER,
            spaceAfter=0,
        )
        
        elements.append(Paragraph(cert_details, cert_style))
        
        # Construire le PDF
        doc.build(elements)
        
        return str(output_path)
    
    def _generate_qr_code(self, verification_token: str) -> BytesIO:
        """
        Génère un QR code pour la vérification
        
        Returns:
            BytesIO: Image du QR code
        """
        qr = qrcode.QRCode(
            version=1,
            error_correction=qrcode.constants.ERROR_CORRECT_H,
            box_size=10,
            border=2,
        )
        
        qr.add_data(verification_token)
        qr.make(fit=True)
        
        img = qr.make_image(fill_color="black", back_color="white")
        
        # Convertir en BytesIO
        img_io = BytesIO()
        img.save(img_io, format='PNG')
        img_io.seek(0)
        
        return img_io
    
    @staticmethod
    def generate_certificate_number() -> str:
        """Génère un numéro unique de certificat"""
        timestamp = datetime.now().strftime("%Y%m%d")
        random_part = str(uuid.uuid4())[:8].upper()
        return f"CERT-{random_part}-{timestamp}"
    
    @staticmethod
    def generate_verification_token() -> str:
        """Génère un token de vérification unique"""
        return hashlib.sha256(
            (str(uuid.uuid4()) + str(datetime.now().timestamp())).encode()
        ).hexdigest()


# Exemple d'utilisation
if __name__ == "__main__":
    generator = CertificateGenerator(output_dir='./storage/certificates')
    
    cert_number = generator.generate_certificate_number()
    token = generator.generate_verification_token()
    
    filepath = generator.generate(
        user_name="Marie Dupont",
        formation_title="Maîtrise du Python Avancé",
        formation_level="avancé",
        certificate_number=cert_number,
        verification_token=token,
        completion_percentage=98,
    )
    
    print(f"✅ Certificat généré: {filepath}")
    print(f"📋 Numéro: {cert_number}")
    print(f"🔐 Token: {token}")
