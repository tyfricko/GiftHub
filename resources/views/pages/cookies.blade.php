@extends('components.layout')

@section('content')
<div class="container mx-auto px-6 py-12">
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <h1 class="text-display font-bold text-neutral-900 mb-4">Cookie Policy</h1>
            <p class="text-body text-neutral-600">
                Last updated: {{ date('F j, Y') }}
            </p>
        </div>

        <div class="prose prose-lg max-w-none text-neutral-700">
            <h2>What Are Cookies</h2>
            <p>
                Cookies are small text files that are stored on your computer or mobile device when you visit our website. They allow us to remember your preferences and improve your browsing experience.
            </p>

            <h2>How We Use Cookies</h2>
            <p>We use cookies for the following purposes:</p>

            <h3>Essential Cookies</h3>
            <p>These cookies are necessary for the website to function properly:</p>
            <ul>
                <li><strong>Session Management:</strong> To keep you logged in during your session</li>
                <li><strong>Security:</strong> To protect against cross-site request forgery and other security threats</li>
                <li><strong>CSRF Protection:</strong> To prevent unauthorized form submissions</li>
            </ul>

            <h3>Functional Cookies</h3>
            <p>These cookies enhance your experience on our site:</p>
            <ul>
                <li><strong>Language Preferences:</strong> To remember your language settings</li>
                <li><strong>Form Data:</strong> To save information you've entered in forms</li>
                <li><strong>User Preferences:</strong> To remember your display preferences</li>
            </ul>

            <h3>Analytics Cookies</h3>
            <p>These cookies help us understand how visitors use our website:</p>
            <ul>
                <li><strong>Usage Statistics:</strong> To track page views and user interactions</li>
                <li><strong>Performance Monitoring:</strong> To identify areas for improvement</li>
                <li><strong>Error Tracking:</strong> To detect and fix technical issues</li>
            </ul>

            <h2>Third-Party Cookies</h2>
            <p>We may use third-party services that set their own cookies:</p>

            <h3>Font Awesome</h3>
            <p>
                We use Font Awesome for icons. They may set cookies to improve font loading performance.
            </p>

            <h3>Google Fonts</h3>
            <p>
                We use Google Fonts to display text. Google may collect information about your use of fonts.
            </p>

            <h3>Alpine.js</h3>
            <p>
                We use Alpine.js for interactive elements. This lightweight JavaScript framework may use local storage for functionality.
            </p>

            <h2>Managing Cookies</h2>

            <h3>Browser Settings</h3>
            <p>You can control cookies through your browser settings:</p>

            <h4>Chrome</h4>
            <ol>
                <li>Click the menu button (three dots) in the top right</li>
                <li>Select "Settings"</li>
                <li>Click "Privacy and security"</li>
                <li>Click "Cookies and other site data"</li>
                <li>Choose your preferred cookie settings</li>
            </ol>

            <h4>Firefox</h4>
            <ol>
                <li>Click the menu button (three lines) in the top right</li>
                <li>Select "Preferences"</li>
                <li>Click "Privacy & Security" in the left menu</li>
                <li>Scroll to the "Cookies and Site Data" section</li>
                <li>Choose your preferred cookie settings</li>
            </ol>

            <h4>Safari</h4>
            <ol>
                <li>Click "Safari" in the menu bar</li>
                <li>Select "Preferences"</li>
                <li>Click the "Privacy" tab</li>
                <li>Choose your preferred cookie settings</li>
            </ol>

            <h3>Opting Out</h3>
            <p>
                Please note that disabling certain cookies may affect the functionality of our website. Essential cookies cannot be disabled as they are necessary for the site to work properly.
            </p>

            <h2>Cookie Retention</h2>
            <p>
                Different types of cookies have different lifespans:
            </p>
            <ul>
                <li><strong>Session Cookies:</strong> Deleted when you close your browser</li>
                <li><strong>Persistent Cookies:</strong> Remain until deleted or expired (typically 30 days to 2 years)</li>
                <li><strong>Essential Cookies:</strong> May have longer retention periods for security purposes</li>
            </ul>

            <h2>Updates to This Policy</h2>
            <p>
                We may update this Cookie Policy from time to time to reflect changes in our practices or for other operational, legal, or regulatory reasons. We will notify you of any material changes by updating the "Last updated" date at the top of this policy.
            </p>

            <h2>Contact Us</h2>
            <p>
                If you have any questions about our use of cookies or this Cookie Policy, please contact us:
            </p>
            <div class="bg-neutral-50 p-4 rounded-lg">
                <p class="font-medium">Email: <a href="mailto:privacy@gifthub.test" class="text-primary-600 hover:text-primary-700">privacy@gifthub.test</a></p>
                <p class="mt-2">Or use our <a href="{{ route('pages.contact') }}" class="text-primary-600 hover:text-primary-700">contact form</a></p>
            </div>

            <h2>More Information</h2>
            <p>
                For more information about cookies and online privacy, you can visit:
            </p>
            <ul>
                <li><a href="https://www.allaboutcookies.org/" target="_blank" rel="noopener" class="text-primary-600 hover:text-primary-700">All About Cookies</a></li>
                <li><a href="https://ico.org.uk/for-the-public/online/cookies/" target="_blank" rel="noopener" class="text-primary-600 hover:text-primary-700">ICO Cookies Guide</a></li>
                <li><a href="https://www.youronlinechoices.com/" target="_blank" rel="noopener" class="text-primary-600 hover:text-primary-700">Your Online Choices</a></li>
            </ul>
        </div>
    </div>
</div>
@endsection