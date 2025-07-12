<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Smalot\PdfParser\Parser; // You'll need to install this package
use Illuminate\Support\Facades\Log;
use Gemini\Laravel\Facades\Gemini;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Inkwell;

class ResumeController extends Controller
{
    public function generateCoverLetter(Request $request)
    {
        try {
            // Validate the uploaded file
            // Laravel's validation rules ensure file meets our requirements
            $request->validate([
                'resume' => [
                    'required',           // File must be present
                    'file',              // Must be a file
                    'mimes:pdf',         // Only PDF files allowed
                    'max:5120',          // Maximum 5MB (5120 KB)
                ],
            ], [
                // Custom error messages for better user experience
                'resume.required' => 'Please select a resume file to upload.',
                'resume.mimes' => 'Only PDF files are allowed.',
                'resume.max' => 'File size must not exceed 5MB.',
            ]);

            $file = $request->file('resume');
            $writingStyle = $request->input('dropdownMenu'); // Get selected writing style
            $jobInfo = $request->input('jobInfo'); // Get selected writing style
            $additionalInstructions = $request->input('addInfo'); // Get selected writing style
            
            // Extract text from PDF
            $extractedText = $this->extractPdfText($file);

            $prompt = "Generate a single cover letter in a ".$writingStyle." 
            style based on the follwing resume and job description information: \n\nRESUME: " . $extractedText . "\n\n JOB DESCRIPTION: " . $jobInfo . "\n\n Format the letter as follows (Write default for values you can't find on the resume): \n\n
            [Name]\n[Address]\n[City, State, Zip]\n[Email]\n[Phone Number]\n\nDear Hiring Manager,\n\n[Body of the cover letter]\n\nSincerely,\n[Name]\n\n" . "ADDITIONAL INSTRCUTIONS:\n" . $additionalInstructions;


            $result = Gemini::generativeModel(model: 'gemini-2.0-flash')->generateContent($prompt);
            $coverLetterText = $result->text();

            return response()->json([
                'success' => true,
                'message' => 'Resume uploaded successfully!',
                'coverLetterText' => $coverLetterText, // Plain text for display
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors (file type, size, etc.)
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            // Handle any other errors that might occur
            Log::error('Resume upload error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while uploading your resume. Please try again.',
            ], 500);
        }
    }

    /**
     * Extract text content from PDF file
     * This method uses the PDF parser to get readable text
     */
    private function extractPdfText($file)
    {
        try {
            // Create new PDF parser instance
            $parser = new Parser();
            
            // Parse the PDF file
            $pdf = $parser->parseFile($file->getPathname());
            
            // Extract text from all pages
            $text = $pdf->getText();
            
            // Clean up the text (remove extra whitespace, etc.)
            $cleanText = trim($text);
            
            return $cleanText ?: 'No text could be extracted from this PDF.';
            
        } catch (\Exception $e) {
            Log::error('PDF text extraction error: ' . $e->getMessage());
            return 'Error extracting text from PDF.';
        }
    }
    /**
     * Download the cover letter as a PDF.
     * Accepts a POST request with 'textContent' (plain text) in the body.
     * Converts the text to simple HTML and returns a PDF download.
     */
    public function downloadPDF(Request $request)
    {
        try {
            // Validate input
            $request->validate([
                'textContent' => 'required|string',
                'filename' => 'nullable|string',
            ], [
                'textContent.required' => 'No cover letter text provided.',
            ]);

            $textContent = $request->input('textContent');
            $filename = $request->input('filename', 'cover_letter.pdf');

            // Convert plain text to HTML (preserve line breaks, avoid double spacing)
            $coverLetterHtml = '<html><body style="font-family: Arial, sans-serif; white-space: pre-line;">'
                . e($textContent) . '</body></html>';

            $pdf = Pdf::loadHTML($coverLetterHtml);
            return $pdf->download($filename);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            // Handle any other errors
            Log::error('PDF download error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while generating the PDF. Please try again.',
            ], 500);
        }
    }
}