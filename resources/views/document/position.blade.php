@extends('layouts.app')

@section('content')
<style>
.pdf-preview-container {
    position: relative;
    width: 100%;
    height: 600px;
    border: 1px solid #ddd;
    background: white;
    overflow: auto;
}

.pdf-canvas {
    display: block;
    margin: 0 auto;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.pdf-content {
    position: relative;
    margin: 20px auto;
}

.qr-position-indicator {
    position: absolute;
    z-index: 10;
    cursor: move;
}

.qr-box {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
    border: 2px solid #fff;
    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    background: var(--primary-color);
}

.document-placeholder {
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.pdf-loading {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    background: #f8f9fa;
}

/* Hide number input spinners */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
input[type=number] {
    -moz-appearance: textfield;
}
</style>

<div class="container-fluid">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
        <h2 class="font-weight-bold text-dark mb-3 mb-md-0">Position QR Code</h2>
        <a href="{{ route('dashboard') }}" class="btn btn-light text-muted font-weight-bold shadow-sm w-100 w-md-auto">
            <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
        </a>
    </div>
    
    <div class="row">
        <div class="col-md-9">
            <div class="card shadow border-0 mb-4">
                <!-- PDF Controls -->
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                        <!-- Page Navigation -->
                        <div class="d-flex align-items-center mb-3 mb-md-0">
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="prevPage">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <div class="d-flex align-items-center mx-2">
                                <span class="small mr-2 text-muted">Page</span>
                                <input type="number" id="pageInput" class="form-control form-control-sm text-center font-weight-bold px-1" value="1" min="1" style="width: 50px; border-radius: 6px;">
                                <span class="small ml-2 text-muted" id="totalPagesInfo">of 1</span>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="nextPage">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                        
                        <!-- Zoom Controls -->
                        <div class="d-flex align-items-center">
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="zoomOut">
                                <i class="fas fa-search-minus"></i>
                            </button>
                            <div class="input-group input-group-sm mx-2" style="width: 90px;">
                                <input type="number" id="zoomInput" class="form-control text-center font-weight-bold px-1" value="100" min="50" max="200" style="border-radius: 6px 0 0 6px;">
                                <div class="input-group-append">
                                    <span class="input-group-text bg-white small px-2" style="border-radius: 0 6px 6px 0;">%</span>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="zoomIn">
                                <i class="fas fa-search-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- PDF Preview -->
                <div class="card-body p-0 bg-light">
                    <div class="pdf-preview-container" id="pdfPreview">
                        <div class="document-placeholder">
                            <div class="text-center">
                                <div class="spinner-border text-primary mb-3" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <p class="text-muted">Loading PDF...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card shadow border-0 sticky-top" style="top: 20px;">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 font-weight-bold text-primary"><i class="fas fa-sliders-h mr-2"></i> QR Position</h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="font-weight-bold text-dark mb-3">
                            <i class="fas fa-arrows-h mr-2"></i>Horizontal (X)
                        </label>
                        <input type="range" class="custom-range" min="0" max="100" value="80" id="qrXPosition">
                        <div class="text-center mt-2">
                            <span class="font-weight-bold text-primary" id="xPositionValue">80%</span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="font-weight-bold text-dark mb-3">
                            <i class="fas fa-arrows-v mr-2"></i>Vertical (Y)
                        </label>
                        <input type="range" class="custom-range" min="0" max="100" value="80" id="qrYPosition">
                        <div class="text-center mt-2">
                            <span class="font-weight-bold text-primary" id="yPositionValue">80%</span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="font-weight-bold text-dark mb-3">
                            <i class="fas fa-expand mr-2"></i>QR Size
                        </label>
                        <div class="d-flex align-items-center">
                            <input type="range" class="custom-range mr-3" min="30" max="200" value="50" id="qrSize">
                            <div class="input-group input-group-sm" style="width: 95px;">
                                <input type="number" id="qrSizeInput" class="form-control text-center font-weight-bold text-primary border-primary px-1" value="50" min="30" max="200" style="border-radius: 8px 0 0 8px; height: 31px;">
                                <div class="input-group-append">
                                    <span class="input-group-text bg-white border-primary text-primary small px-2" style="border-radius: 0 8px 8px 0; font-size: 0.7rem; height: 31px;">px</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    

                    <div class="text-center">
                        <button class="btn btn-sm btn-primary shadow-sm px-4 py-2 font-weight-bold" id="generateQr" style="border-radius: 8px;">
                            <i class="fas fa-stamp mr-2"></i> Stamp Document
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>
<script>
    // Initialize PDF.js
    if (typeof pdfjsLib !== 'undefined') {
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.worker.min.js';
        console.log('PDF.js initialized successfully');
    } else {
        console.error('PDF.js library not loaded');
    }

    const documentId = {{ $document->id }};
    const pdfPath = "{{ asset('storage/' . $document->file_path) }}";
    
    let pdfDoc = null;
    let currentPage = {{ $document->page_number ?? 1 }};
    let totalPages = 1;
    let zoom = 1.0;
    let qrIndicator = null;
    
    console.log('Loading PDF from:', pdfPath);
    
    // Load and render PDF
    async function loadPDF() {
        try {
            const loadingTask = pdfjsLib.getDocument(pdfPath);
            pdfDoc = await loadingTask.promise;
            totalPages = pdfDoc.numPages;
            console.log('PDF loaded successfully, pages:', totalPages);
            await renderPage(currentPage);
        } catch (error) {
            console.error('Error loading PDF:', error);
            document.getElementById('pdfPreview').innerHTML = 
                '<div class="pdf-loading"><div class="text-center text-danger">' +
                '<i class="fas fa-exclamation-triangle fa-3x mb-3"></i>' +
                '<p>Error loading PDF</p>' +
                '<small>' + error.message + '</small>' +
                '</div></div>';
        }
    }
    
    async function renderPage(pageNum) {
        if (!pdfDoc) return;
        
        console.log('Rendering page', pageNum);
        
        try {
            const page = await pdfDoc.getPage(pageNum);
            const viewport = page.getViewport({ scale: zoom });
            
            const canvas = document.createElement('canvas');
            const context = canvas.getContext('2d');
            canvas.width = viewport.width;
            canvas.height = viewport.height;
            canvas.className = 'pdf-canvas';
            
            await page.render({
                canvasContext: context,
                viewport: viewport
            }).promise;
            
            const pdfContent = document.createElement('div');
            pdfContent.className = 'pdf-content';
            pdfContent.style.width = viewport.width + 'px';
            pdfContent.style.height = viewport.height + 'px';
            pdfContent.appendChild(canvas);
            
            // Add QR indicator
            qrIndicator = document.createElement('div');
            qrIndicator.className = 'qr-position-indicator';
            qrIndicator.id = 'qrIndicator';
            
            const xPercent = document.getElementById('qrXPosition').value;
            const yPercent = document.getElementById('qrYPosition').value;
            const size = document.getElementById('qrSize').value;
            
            const initialX = (xPercent / 100) * viewport.width;
            const initialY = (yPercent / 100) * viewport.height;
            
            qrIndicator.style.left = initialX + 'px';
            qrIndicator.style.top = initialY + 'px';
            qrIndicator.style.width = size + 'px';
            qrIndicator.style.height = size + 'px';
            qrIndicator.style.transform = 'translate(-50%, -50%)';
            qrIndicator.innerHTML = `<div class="qr-box" style="width: ${size}px; height: ${size}px;"><i class="fas fa-qrcode text-white fa-2x"></i></div>`;
            
            pdfContent.appendChild(qrIndicator);
            makeDraggable(qrIndicator, pdfContent);
            
            const container = document.getElementById('pdfPreview');
            container.innerHTML = '';
            container.appendChild(pdfContent);
            
            updateControls();
            
        } catch (error) {
            console.error('Error rendering page:', error);
        }
    }
    
    function updateControls() {
        document.getElementById('pageInput').value = currentPage;
        document.getElementById('totalPagesInfo').textContent = `of ${totalPages}`;
        document.getElementById('prevPage').disabled = currentPage === 1;
        document.getElementById('nextPage').disabled = currentPage === totalPages;
        document.getElementById('zoomInput').value = Math.round(zoom * 100);
    }
    
    function makeDraggable(element, container) {
        let isDragging = false;
        let currentX, currentY, initialX, initialY;
        let xOffset = 0, yOffset = 0;

        element.addEventListener('mousedown', dragStart);
        document.addEventListener('mousemove', drag);
        document.addEventListener('mouseup', dragEnd);

        function dragStart(e) {
            initialX = e.clientX - xOffset;
            initialY = e.clientY - yOffset;
            if (e.target === element || element.contains(e.target)) {
                isDragging = true;
                element.style.cursor = 'grabbing';
            }
        }

        function drag(e) {
            if (isDragging) {
                e.preventDefault();
                currentX = e.clientX - initialX;
                currentY = e.clientY - initialY;
                xOffset = currentX;
                yOffset = currentY;

                const containerRect = container.getBoundingClientRect();
                let newLeft = e.clientX - containerRect.left;
                let newTop = e.clientY - containerRect.top;
                
                const qrSize = document.getElementById('qrSize').value;
                newLeft = Math.max(qrSize/2, Math.min(newLeft, containerRect.width - qrSize/2));
                newTop = Math.max(qrSize/2, Math.min(newTop, containerRect.height - qrSize/2));
                
                element.style.left = newLeft + 'px';
                element.style.top = newTop + 'px';
                
                const xPercent = Math.round((newLeft / containerRect.width) * 100);
                const yPercent = Math.round((newTop / containerRect.height) * 100);
                
                document.getElementById('qrXPosition').value = xPercent;
                document.getElementById('qrYPosition').value = yPercent;
                document.getElementById('xPositionValue').textContent = xPercent + '%';
                document.getElementById('yPositionValue').textContent = yPercent + '%';
            }
        }

        function dragEnd() {
            if (isDragging) {
                initialX = currentX;
                initialY = currentY;
                isDragging = false;
                element.style.cursor = 'move';
            }
        }
    }
    
    // Controls
    document.getElementById('prevPage').addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            renderPage(currentPage);
        }
    });
    
    document.getElementById('nextPage').addEventListener('click', () => {
        if (currentPage < totalPages) {
            currentPage++;
            renderPage(currentPage);
        }
    });
    
    document.getElementById('zoomOut').addEventListener('click', () => {
        if (zoom > 0.5) {
            zoom = Math.max(0.5, zoom - 0.1);
            renderPage(currentPage);
        }
    });
    
    document.getElementById('zoomIn').addEventListener('click', () => {
        if (zoom < 2) {
            zoom = Math.min(2, zoom + 0.1);
            renderPage(currentPage);
        }
    });

    // Page Input Manual Entry
    document.getElementById('pageInput').addEventListener('change', function() {
        let val = parseInt(this.value);
        if (isNaN(val) || val < 1) val = 1;
        if (val > totalPages) val = totalPages;
        currentPage = val;
        renderPage(currentPage);
    });

    // Zoom Input Manual Entry
    document.getElementById('zoomInput').addEventListener('change', function() {
        let val = parseInt(this.value);
        if (isNaN(val) || val < 50) val = 50;
        if (val > 200) val = 200;
        zoom = val / 100;
        renderPage(currentPage);
    });
    
    // Slider controls
    document.getElementById('qrXPosition').addEventListener('input', function() {
        const xValue = this.value;
        document.getElementById('xPositionValue').textContent = xValue + '%';
        
        if (qrIndicator) {
            const pdfContent = qrIndicator.parentElement;
            const contentWidth = pdfContent.clientWidth;
            qrIndicator.style.left = ((xValue / 100) * contentWidth) + 'px';
        }
    });
    
    document.getElementById('qrYPosition').addEventListener('input', function() {
        const yValue = this.value;
        document.getElementById('yPositionValue').textContent = yValue + '%';
        
        if (qrIndicator) {
            const pdfContent = qrIndicator.parentElement;
            const contentHeight = pdfContent.clientHeight;
            qrIndicator.style.top = ((yValue / 100) * contentHeight) + 'px';
        }
    });

    // QR Size sync (Slider & Input)
    const qrSizeSlider = document.getElementById('qrSize');
    const qrSizeInput = document.getElementById('qrSizeInput');

    function updateQrSize(size) {
        qrSizeSlider.value = size;
        qrSizeInput.value = size;
        
        if (qrIndicator) {
            qrIndicator.style.width = size + 'px';
            qrIndicator.style.height = size + 'px';
            const qrBox = qrIndicator.querySelector('.qr-box');
            if (qrBox) {
                qrBox.style.width = size + 'px';
                qrBox.style.height = size + 'px';
            }
        }
    }

    qrSizeSlider.addEventListener('input', function() {
        updateQrSize(this.value);
    });

    qrSizeInput.addEventListener('input', function() {
        let val = parseInt(this.value);
        if (isNaN(val)) return;
        // Allow typing but clamp on blur or if it exceeds max significantly
        if (val > 500) val = 500; 
        updateQrSize(val);
    });

    qrSizeInput.addEventListener('blur', function() {
        let val = parseInt(this.value);
        if (isNaN(val) || val < 30) val = 30;
        if (val > 500) val = 500;
        updateQrSize(val);
    });
    

    
    // Generate QR
    document.getElementById('generateQr').addEventListener('click', () => {
        confirmAction({
            title: 'Stamp Document',
            message: 'Are you sure you want to stamp the document? This action will permanently embed the QR code and cannot be undone.',
            confirmText: 'Yes, Stamp Now',
            confirmClass: 'btn-success',
            onConfirm: async function() {
                const btn = document.getElementById('generateQr');
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';
                btn.disabled = true;
                
                try {
                    // First, save the current position
                    const positionData = {
                        page_number: currentPage,
                        qr_x: parseFloat(document.getElementById('qrXPosition').value),
                        qr_y: parseFloat(document.getElementById('qrYPosition').value),
                        qr_width: parseFloat(document.getElementById('qrSize').value),
                        qr_height: parseFloat(document.getElementById('qrSize').value),
                    };
                    
                    await fetch('{{ route("document.save-position", $document) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(positionData)
                    });

                    // Then, proceed to stamp
                    const response = await fetch('{{ route("document.stamp", $document) }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    
                    const result = await response.json();
                    if (result.success) {
                        // Trigger download first
                        const downloadUrl = '{{ route("document.download", $document) }}';
                        const link = document.createElement('a');
                        link.href = downloadUrl;
                        link.download = ''; // Browser will use the filename from Content-Disposition
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);

                        // Then redirect to dashboard after a short delay to ensure download starts
                        setTimeout(() => {
                            window.location.href = '{{ route("dashboard") }}?status=Document+stamped+and+downloaded+successfully';
                        }, 1000);
                    } else {
                        confirmAction({
                            title: 'Error',
                            message: 'Error: ' + result.message,
                            confirmText: 'Close',
                            confirmClass: 'btn-secondary',
                            onConfirm: () => {}
                        });
                    }
                } catch (e) {
                    confirmAction({
                        title: 'Error',
                        message: 'An unexpected error occurred while processing the document.',
                        confirmText: 'Close',
                        confirmClass: 'btn-secondary',
                        onConfirm: () => {}
                    });
                } finally {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }
            }
        });
    });
    
    // Load PDF on page load
    loadPDF();
</script>
@endsection