<?php
namespace App\Http\Controllers;

/*
Made by :
Fauzan Reza Arnanda
fauzan.rez@gmail.com
https://www.linkedin.com/in/fauzan-reza/
https://fauzanreza.site/
*/

use App\Models\Document;
use App\Models\Signer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class DocumentController extends Controller {
    
    public function dashboard(Request $request) {
        $query = Auth::user()->documents();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('year')) {
            $query->whereYear('created_at', $request->year);
        }

        if ($request->filled('search')) {
            $query->where('file_name', 'like', '%' . $request->search . '%')
                  ->orWhere('document_number', 'like', '%' . $request->search . '%');
        }

        $documents = $query->latest()->paginate(10);
        
        $roles = Auth::user()->documents()->distinct()->pluck('role');
        $years = Auth::user()->documents()->selectRaw('YEAR(created_at) as year')->distinct()->pluck('year')->sortDesc();

        return view('dashboard', compact('documents', 'roles', 'years'));
    }
    
    public function create() {
        $signers = Signer::all();
        return view('document.create', compact('signers'));
    }
    
    public function position(Document $document) {
        $this->authorize('view', $document);
        return view('document.position', compact('document'));
    }
    
    public function upload(Request $request) {
        $validated = $request->validate([
            'pdf_file' => 'required|mimes:pdf|max:10000',
            'signer_id' => 'required|exists:signers,id',
            'document_number' => 'required|string|max:255',
        ]);
        
        $file = $request->file('pdf_file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('pdfs', $filename, 'public');
        
        $signer = Signer::find($validated['signer_id']);
        
        $document = Document::create([
            'user_id' => Auth::id(),
            'signer_id' => $signer->id,
            'file_name' => $file->getClientOriginalName(),
            'document_number' => $validated['document_number'],
            'file_path' => $path,
            'role' => $signer->role,
            'status' => 'pending',
        ]);
        
        return redirect()->route('document.position', $document->id)
                        ->with('success', 'PDF uploaded. Now position your QR code.');
    }
    
    public function savePosition(Request $request, Document $document) {
        $this->authorize('update', $document);
        
        $validated = $request->validate([
            'page_number' => 'required|integer|min:1',
            'qr_x' => 'required|numeric',
            'qr_y' => 'required|numeric',
            'qr_width' => 'required|numeric',
            'qr_height' => 'required|numeric',
        ]);
        
        $document->update($validated);
        
        return response()->json(['success' => true, 'message' => 'Position saved']);
    }
    
    public function stamp(Request $request, Document $document) {
        $this->authorize('update', $document);
        
        try {
            // Generate QR code data
            $qrData = route('verify.signature', ['doc_id' => $document->id, 'uuid' => uniqid()]);
            $qrImagePath = storage_path('app/qrcodes/' . $document->id . '.png');
            
            if (!is_dir(storage_path('app/qrcodes'))) {
                mkdir(storage_path('app/qrcodes'), 0755, true);
            }
            
            // Generate QR code image using chillerlan/php-qrcode (supports GD)
            $options = new \chillerlan\QRCode\QROptions([
                'version'      => -1, // Set to -1 for automatic version selection
                'outputType'   => \chillerlan\QRCode\QRCode::OUTPUT_IMAGE_PNG,
                'eccLevel'     => \chillerlan\QRCode\Common\EccLevel::H, // High error correction for logo
                'scale'        => 10,
                'imageBase64'  => false,
            ]);
            
            $qrcode = new \chillerlan\QRCode\QRCode($options);
            $qrcode->render($qrData, $qrImagePath);

            // --- Logo Overlay Logic ---
            // Path to your logo image (e.g., public_path('images/logo-qr.png'))
            $logoPath = public_path('images/logo-qr.png'); 
            
            if (file_exists($logoPath)) {
                $qrImage = imagecreatefrompng($qrImagePath);
                $logo = imagecreatefromstring(file_get_contents($logoPath));
                
                $qrWidth = imagesx($qrImage);
                $qrHeight = imagesy($qrImage);
                $logoWidth = imagesx($logo);
                $logoHeight = imagesy($logo);
                
                // Scale logo to fit in the middle (e.g., 35% of QR size for better visibility)
                $targetLogoWidth = $qrWidth * 0.35; 
                $targetLogoHeight = $logoHeight * ($targetLogoWidth / $logoWidth);
                
                // Calculate background size (with padding)
                $padding = 20;
                $bgWidth = $targetLogoWidth + $padding;
                $bgHeight = $targetLogoHeight + $padding;
                
                $centerX = ($qrWidth - $bgWidth) / 2;
                $centerY = ($qrHeight - $bgHeight) / 2;
                
                // Create white background with rounded corners
                $white = imagecolorallocate($qrImage, 255, 255, 255);
                
                // Draw rounded rectangle background
                $radius = 15;
                // Fill the main rectangle
                imagefilledrectangle($qrImage, $centerX + $radius, $centerY, $centerX + $bgWidth - $radius, $centerY + $bgHeight, $white);
                imagefilledrectangle($qrImage, $centerX, $centerY + $radius, $centerX + $bgWidth, $centerY + $bgHeight - $radius, $white);
                // Fill the four corners
                imagefilledellipse($qrImage, $centerX + $radius, $centerY + $radius, $radius * 2, $radius * 2, $white);
                imagefilledellipse($qrImage, $centerX + $bgWidth - $radius, $centerY + $radius, $radius * 2, $radius * 2, $white);
                imagefilledellipse($qrImage, $centerX + $radius, $centerY + $bgHeight - $radius, $radius * 2, $radius * 2, $white);
                imagefilledellipse($qrImage, $centerX + $bgWidth - $radius, $centerY + $bgHeight - $radius, $radius * 2, $radius * 2, $white);
                
                // Draw the logo on top of the white background
                imagecopyresampled(
                    $qrImage, $logo, 
                    $centerX + ($padding / 2), $centerY + ($padding / 2), 
                    0, 0, 
                    $targetLogoWidth, $targetLogoHeight, 
                    $logoWidth, $logoHeight
                );
                
                imagepng($qrImage, $qrImagePath);
                imagedestroy($qrImage);
                imagedestroy($logo);
            }
            // ---------------------------
            
            $document->update(['qr_data' => $qrData]);
            
            // Call Node.js script to stamp PDF
            $stamped = $this->stampPdfWithNode($document, $qrImagePath);
            
            if ($stamped) {
                $document->update(['status' => 'signed']);
                return response()->json(['success' => true, 'document_id' => $document->id]);
            }
            
            return response()->json(['success' => false, 'message' => 'Stamping failed'], 500);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    private function stampPdfWithNode(Document $document, $qrImagePath) {
        $pdfPath = storage_path('app/public/' . $document->file_path);
        $outputPath = storage_path('app/public/signed/' . $document->id . '_signed.pdf');
        
        if (!is_dir(storage_path('app/public/signed'))) {
            mkdir(storage_path('app/public/signed'), 0755, true);
        }
        
        // Node.js script path
        $scriptPath = base_path('scripts/stamp-pdf.js');
        
        $process = new Process([
            'node',
            $scriptPath,
            $pdfPath,
            $qrImagePath,
            $outputPath,
            (string)$document->page_number,
            (string)$document->qr_x,
            (string)$document->qr_y,
            (string)$document->qr_width,
            (string)$document->qr_height,
        ]);
        
        $process->run();
        
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        
        // Save signed PDF path to document
        $document->update(['file_path' => 'signed/' . $document->id . '_signed.pdf']);
        
        return true;
    }
    
    public function download(Document $document) {
        $this->authorize('view', $document);
        
        if (!Storage::disk('public')->exists($document->file_path)) {
            return back()->withErrors('File not found');
        }
        
        return Storage::disk('public')->download($document->file_path, 'signed_' . $document->file_name);
    }

    public function verify(Request $request) {
        $document = Document::with('signer')->findOrFail($request->doc_id);
        return view('document.verify', compact('document'));
    }

    public function destroy(Document $document) {
        $this->authorize('delete', $document);
        
        // Delete the file from storage
        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }
        
        $document->delete();
        
        return back()->with('success', 'Document deleted successfully.');
    }
}