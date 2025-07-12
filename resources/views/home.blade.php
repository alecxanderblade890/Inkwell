<x-layout>
    <!-- Page Content -->
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Page Header --}}
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-blue-600 mb-4">Welcome to Inkwell!</h1>
                <p class="text-lg text-gray-600">Where you can generate a cover letter on the instantly for free!</p>
                <p class="text-lg text-gray-600">Upload your resume in PDF form!</p>    
            </div>

            {{-- Main Content Grid --}}
            <div class="grid grid-cols-1 gap-8">
                
                {{-- Upload Section --}}
                <div class="bg-white rounded-xl shadow-lg p-8">
                    {{-- <h2 class="text-2xl font-semibold text-gray-800 mb-6">Upload Resume</h2> --}}
                    
                    {{-- Upload Form --}}
                    <form id="resumeUploadForm" enctype="multipart/form-data" class="space-y-6">
                        @csrf {{-- Laravel CSRF protection token --}}
                        
                        {{-- File Upload Area --}}
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-500 transition-colors duration-200" 
                            id="dropZone">
                            <div class="space-y-4">
                                {{-- Upload Icon --}}
                                <div class="mx-auto w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                </div>
                                
                                {{-- Upload Instructions --}}
                                <div>
                                    <p class="text-lg font-medium text-gray-700">Drop your PDF here or click to browse</p>
                                    <p class="text-sm text-gray-500 mt-2">PDF files only, maximum 5MB</p>
                                </div>
                                
                                {{-- Hidden File Input --}}
                                <input type="file" id="resumeFile" name="resume" accept=".pdf" class="hidden">
                                
                                {{-- Browse Button --}}
                                <button type="button" id="browseButton" 
                                        class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-6 rounded-lg transition-colors duration-200">
                                    Browse Files
                                </button>
                            </div>
                        </div>
                        
                        {{-- Selected File Display --}}
                        <div id="selectedFile" class="hidden bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center space-x-3">
                                <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <div class="flex-1">
                                    <p id="fileName" class="font-medium text-gray-700"></p>
                                    <p id="fileSize" class="text-sm text-gray-500"></p>
                                </div>
                                <button type="button" id="removeFile" class="text-red-500 hover:text-red-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                            <!-- Job Description Input Area -->
                            <div class="mt-4">
                                <label for="jobInfo" class="block text-sm font-medium text-gray-700 mb-1">Job description</label>
                                <textarea required id="jobInfo" name="jobInfo" rows="4" class="w-full border border-gray-300 rounded-lg py-2 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-vertical whitespace-pre-wrap" placeholder="Paste the full job description or requirements here."></textarea>
                            </div>
                            <!-- Additional Info Input Area -->
                            <div class="mt-4">
                                <label for="addInfo" class="block text-sm font-medium text-gray-700 mb-1">Additional Info (Optional)</label>
                                <textarea id="addInfo" name="addInfo" rows="4" class="w-full border border-gray-300 rounded-lg py-2 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-vertical whitespace-pre-wrap" placeholder="Paste additonal info you wish to add here."></textarea>
                            </div>
                        </div>
                        
                        {{-- Error Messages --}}
                        <div id="errorMessages" class="hidden bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <ul id="errorList" class="text-sm text-red-700"></ul>
                            </div>
                        </div>
                        
                        {{-- Submit Button --}}
                        <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-4 space-y-4 sm:space-y-0 justify-center mx-auto w-full sm:w-max">
                            <button type="submit" id="submitButton" 
                                    class="w-full sm:w-auto bg-blue-500 hover:bg-blue-600 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                                    disabled>
                                <span id="buttonText">Generate Letter</span>
                                <svg id="loadingSpinner" class="hidden w-5 h-5 ml-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                            </button>
                            <!-- Dropdown Menu -->
                            <div class="relative w-full sm:w-auto">
                                <div class="relative">
                                    <select id="dropdownMenu" name="dropdownMenu" class="block w-full sm:w-auto appearance-none bg-white border-2 border-blue-200 rounded-lg py-3 px-4 pr-10 text-gray-700 font-semibold focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-500 transition-colors duration-200 shadow-sm hover:border-blue-400">
                                        <option value="Casual">Casual</option>
                                        <option value="Professional" selected>Professional</option>
                                        <option value="Excited">Excited</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-blue-500">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    
                    {{-- Success Message --}}
                    <div id="successMessage" class="hidden bg-green-50 border border-green-200 rounded-lg p-4 mt-4">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <p class="text-sm text-green-700">Cover Letter Generated successfully!</p>
                        </div>
                    </div>
                </div>
                
                {{-- Text Display Section --}}
                <div class="bg-white rounded-xl shadow-xl p-8">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Generated Cover Letter</h2>
                    
                    {{-- Text Container --}}
                    <div id="extractedTextContainer" class="bg-gray-50 rounded-lg p-6 min-h-[400px] border">
                        <div id="placeholderText" class="text-center text-gray-500 py-20">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-lg">Upload a PDF to see generated cover letter here</p>
                        </div>
                        
                        {{-- Extracted Text Display --}}
                        <div id="extractedText" class="hidden">
                            <div class="flex justify-between items-center mb-4">
                                <button id="copyTextButton" class="text-blue-500 hover:text-blue-600 text-sm font-medium">
                                    Copy Text
                                </button>
                            </div>
                            <textarea id="textContent" name="textContent" class="text-gray-700 leading-relaxed whitespace-pre-wrap max-h-96 overflow-y-auto w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-vertical" rows="12" placeholder="The generated cover letter will appear here and can be edited."></textarea>
                            <div class="flex justify-end mt-4">
                                <button type="button" id="downloadPdfButton" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg">Download as PDF</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout> 
<script>
    window.generateDownloadPdfUrl = "{{ route('download.pdf') }}";
</script>