const fs = require('fs');
const path = require('path');
const { PDFDocument } = require('pdf-lib');

async function stampPdf(inputPath, qrImagePath, outputPath, pageNum, qrX, qrY, qrW, qrH) {
    try {
        // Read existing PDF
        const pdfBytes = fs.readFileSync(inputPath);
        const pdfDoc = await PDFDocument.load(pdfBytes);
        
        // Get the specified page (0-indexed)
        const pageIndex = parseInt(pageNum) - 1;
        const page = pdfDoc.getPage(pageIndex);
        const { width, height } = page.getSize();
        
        // Read QR code image
        const qrImageBytes = fs.readFileSync(qrImagePath);
        const qrImage = await pdfDoc.embedPng(qrImageBytes);
        
        // Draw QR code at specified position
        // qrX and qrY are percentages (0-100) from the UI (top-left origin)
        // qrW and qrH are the size in PDF points
        
        const absW = parseFloat(qrW);
        const absH = parseFloat(qrH);
        
        // Calculate absolute center position in PDF points
        const centerX = (parseFloat(qrX) / 100) * width;
        const centerY = height - ((parseFloat(qrY) / 100) * height);
        
        // Calculate bottom-left corner for drawImage
        const drawX = centerX - (absW / 2);
        const drawY = centerY - (absH / 2);
        
        page.drawImage(qrImage, {
            x: drawX,
            y: drawY,
            width: absW,
            height: absH,
        });
        
        // Save PDF
        const stampedPdfBytes = await pdfDoc.save();
        fs.writeFileSync(outputPath, stampedPdfBytes);
        
        console.log('PDF stamped successfully');
        process.exit(0);
    } catch (error) {
        console.error('Error stamping PDF:', error);
        process.exit(1);
    }
}

// Get arguments
const args = process.argv.slice(2);
const [inputPath, qrImagePath, outputPath, pageNum, qrX, qrY, qrW, qrH] = args;

if (!inputPath || !qrImagePath || !outputPath) {
    console.error('Missing required arguments');
    process.exit(1);
}

stampPdf(inputPath, qrImagePath, outputPath, pageNum, qrX, qrY, qrW, qrH);