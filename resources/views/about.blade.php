<x-layout>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-xl p-8">
                <div class="text-center mb-8">
                    <button type="button" id="aboutAccordionBtn" class="w-full flex justify-between items-center px-4 py-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200 bg-white border border-gray-200">
                        <span class="text-4xl font-bold text-blue-600">About Inkwell</span>
                        <svg id="aboutAccordionIcon" class="w-6 h-6 text-blue-600 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                </div>
                <div id="aboutAccordionContent" class="prose max-w-none text-gray-700 mx-auto overflow-hidden transition-all duration-300" style="max-height: 0px; opacity: 0;">
                    <p class="text-lg text-gray-600">Your AI-powered cover letter generator</p>
                    <br>
                    <p>
                        <strong>Inkwell</strong> is designed to help you create professional, personalized cover letters in seconds. Simply upload your PDF resume, paste the job description, and let our AI do the rest. You can edit, copy, or download your generated cover letter as DOCX or PDF.
                    </p>
                    <ul class="list-disc pl-6 my-4">
                        <li>Modern, clean, and responsive design</li>
                        <li>Easy drag-and-drop PDF upload</li>
                        <li>Customizable writing style and additional info fields</li>
                        <li>Instant AI-generated cover letter with editable output</li>
                        <li>Download as DOCX or PDF (Coming Soon!)</li>
                    </ul>
                    <p>
                        <span class="font-semibold">Why Inkwell?</span><br>
                        Writing cover letters can be time-consuming and stressful. Inkwell streamlines the process, ensuring your application stands out with a tailored, well-structured letter every time.
                    </p>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Simple accordion toggle
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('aboutAccordionBtn');
            const content = document.getElementById('aboutAccordionContent');
            const icon = document.getElementById('aboutAccordionIcon');
            let expanded = false;
            btn.addEventListener('click', function() {
                expanded = !expanded;
                if (expanded) {
                    content.style.maxHeight = '1000px';
                    content.style.opacity = '1';
                    icon.style.transform = 'rotate(0deg)';
                } else {
                    content.style.maxHeight = '0px';
                    content.style.opacity = '0';
                    icon.style.transform = 'rotate(-180deg)';
                }
            });
        });
    </script>
</x-layout>