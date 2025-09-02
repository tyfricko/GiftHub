@extends('components.layout')

@section('content')
<div class="container mx-auto px-6 py-12">
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <h1 class="text-display font-bold text-neutral-900 mb-4">Privacy Policy</h1>
            <p class="text-body text-neutral-600">
                Last updated: {{ date('F j, Y') }}
            </p>
        </div>

        <div class="prose prose-lg max-w-none text-neutral-700">
            <h2>Introduction</h2>
            <p>
                At GiftHub, we are committed to protecting your privacy and ensuring the security of your personal information. This Privacy Policy explains how we collect, use, and safeguard your data when you use our platform.
            </p>

            <h2>Information We Collect</h2>

            <h3>Personal Information</h3>
            <p>We collect the following personal information when you register for an account:</p>
            <ul>
                <li>Email address</li>
                <li>Username</li>
                <li>Profile information (optional)</li>
                <li>Avatar image (optional)</li>
            </ul>

            <h3>Usage Data</h3>
            <p>We automatically collect certain information about your use of our service:</p>
            <ul>
                <li>IP address and location information</li>
                <li>Browser type and version</li>
                <li>Pages visited and time spent on our site</li>
                <li>Device information</li>
                <li>Cookies and similar tracking technologies</li>
            </ul>

            <h3>Wishlist Data</h3>
            <p>When you create wishlists and add items:</p>
            <ul>
                <li>Product URLs and metadata (automatically scraped)</li>
                <li>Item descriptions and notes</li>
                <li>Price information</li>
                <li>Images associated with wishlist items</li>
            </ul>

            <h2>How We Use Your Information</h2>
            <p>We use the information we collect to:</p>
            <ul>
                <li>Provide and maintain our service</li>
                <li>Process and manage your account</li>
                <li>Send you important updates and notifications</li>
                <li>Improve our platform and develop new features</li>
                <li>Ensure security and prevent fraud</li>
                <li>Comply with legal obligations</li>
            </ul>

            <h2>Information Sharing</h2>
            <p>We do not sell, trade, or rent your personal information to third parties. We may share your information only in the following circumstances:</p>

            <h3>Public Profile Information</h3>
            <p>
                Your username and wishlist information may be visible to other users when you share your profile link or participate in gift exchanges.
            </p>

            <h3>Service Providers</h3>
            <p>
                We may share information with trusted third-party service providers who help us operate our platform, such as hosting providers and email services.
            </p>

            <h3>Legal Requirements</h3>
            <p>
                We may disclose information if required by law or to protect our rights and the safety of our users.
            </p>

            <h2>Data Security</h2>
            <p>
                We implement appropriate technical and organizational measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction. However, no method of transmission over the internet is 100% secure.
            </p>

            <h2>Your Rights</h2>
            <p>You have the following rights regarding your personal information:</p>
            <ul>
                <li><strong>Access:</strong> Request a copy of your personal data</li>
                <li><strong>Correction:</strong> Request correction of inaccurate data</li>
                <li><strong>Deletion:</strong> Request deletion of your personal data</li>
                <li><strong>Portability:</strong> Request transfer of your data</li>
                <li><strong>Objection:</strong> Object to processing of your data</li>
            </ul>

            <h2>Cookies</h2>
            <p>
                We use cookies and similar technologies to enhance your experience on our platform. For more detailed information about our use of cookies, please see our <a href="{{ route('pages.cookies') }}" class="text-primary-600 hover:text-primary-700">Cookie Policy</a>.
            </p>

            <h2>Children's Privacy</h2>
            <p>
                Our service is not intended for children under 13 years of age. We do not knowingly collect personal information from children under 13. If we become aware that we have collected personal information from a child under 13, we will take steps to delete such information.
            </p>

            <h2>Changes to This Policy</h2>
            <p>
                We may update this Privacy Policy from time to time. We will notify you of any changes by posting the new policy on this page and updating the "Last updated" date.
            </p>

            <h2>Contact Us</h2>
            <p>
                If you have any questions about this Privacy Policy or our data practices, please contact us at:
            </p>
            <div class="bg-neutral-50 p-4 rounded-lg">
                <p class="font-medium">Email: <a href="mailto:privacy@gifthub.test" class="text-primary-600 hover:text-primary-700">privacy@gifthub.test</a></p>
                <p class="mt-2">Or use our <a href="{{ route('pages.contact') }}" class="text-primary-600 hover:text-primary-700">contact form</a></p>
            </div>
        </div>
    </div>
</div>
@endsection